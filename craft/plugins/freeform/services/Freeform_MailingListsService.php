<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2017, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Craft;

use Solspace\Freeform\Library\Configuration\CraftPluginConfiguration;
use Solspace\Freeform\Library\Database\MailingListHandlerInterface;
use Solspace\Freeform\Library\Exceptions\Integrations\IntegrationException;
use Solspace\Freeform\Library\Exceptions\Integrations\ListNotFoundException;
use Solspace\Freeform\Library\Exceptions\Integrations\MailingListIntegrationNotFoundException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;
use Solspace\Freeform\Library\Integrations\AbstractIntegration;
use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;
use Solspace\Freeform\Library\Integrations\MailingLists\AbstractMailingListIntegration;
use Solspace\Freeform\Library\Integrations\MailingLists\DataObjects\ListObject;
use Solspace\Freeform\Library\Integrations\SettingBlueprint;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Freeform_MailingListsService extends BaseApplicationComponent implements MailingListHandlerInterface
{
    /** @var array */
    private static $integrations;

    /**
     * @return Freeform_IntegrationModel[]
     */
    public function getAllModels()
    {
        $records = Freeform_IntegrationRecord::model()->findAllByAttributes(
            ["type" => Freeform_IntegrationRecord::TYPE_MAILING_LIST]
        );

        /** @var Freeform_IntegrationModel[] $models */
        $models = Freeform_IntegrationModel::populateModels($records);

        return $models;
    }

    /**
     * @return AbstractMailingListIntegration[]
     */
    public function getAllIntegrations()
    {
        $mailingListIntegrationModels = $this->getAllModels();

        $integrations = [];
        foreach ($mailingListIntegrationModels as $model) {
            $integrations[] = $model->getIntegrationObject();
        }

        return $integrations;
    }

    /**
     * @param int $id
     *
     * @return AbstractMailingListIntegration
     * @throws MailingListIntegrationNotFoundException
     */
    public function getIntegrationById($id)
    {
        /** @var Freeform_IntegrationRecord $record */
        $record = Freeform_IntegrationRecord::model()->findById($id);

        if ($record && $record->type === Freeform_IntegrationRecord::TYPE_MAILING_LIST) {
            /** @var Freeform_IntegrationModel $model */
            $model = Freeform_IntegrationModel::populateModel($record);

            return $model->getIntegrationObject();
        }

        throw new MailingListIntegrationNotFoundException(
            Craft::t("Mailing List integration with ID {id} not found", ["id" => $id])
        );
    }

    /**
     * @param int $id
     *
     * @return Freeform_IntegrationModel|null
     */
    public function getModelById($id)
    {
        $record = Freeform_IntegrationRecord::model()->findById($id);

        if ($record) {
            return Freeform_IntegrationModel::populateModel($record);
        }

        return null;
    }

    /**
     * @param string $handle
     *
     * @return Freeform_IntegrationModel|null
     */
    public function getModelByHandle($handle)
    {
        $record = Freeform_IntegrationRecord::model()->findByAttributes(["handle" => $handle]);

        if ($record) {
            return Freeform_IntegrationModel::populateModel($record);
        }

        return null;
    }

    /**
     * @param Freeform_IntegrationModel $model
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_IntegrationModel $model)
    {
        $isNewIntegration = !$model->id;

        if (!$isNewIntegration) {
            $record = Freeform_IntegrationRecord::model()->findById($model->id);

            if (!$record) {
                throw new Exception(
                    Craft::t("Mailing List integration with ID {id} not found", ["id" => $model->id])
                );
            }
        } else {
            $record = new Freeform_IntegrationRecord();
        }

        $beforeSaveEvent = $this->onBeforeSave($model, $isNewIntegration);

        $record->name        = $model->name;
        $record->type        = $model->type;
        $record->handle      = $model->handle;
        $record->type        = $model->type;
        $record->class       = $model->class;
        $record->accessToken = $model->accessToken;
        $record->settings    = $model->settings;
        $record->forceUpdate = $model->forceUpdate;
        $record->lastUpdate  = new DateTime();

        $record->validate();
        $model->addErrors($record->getErrors());

        $configuration = new CraftPluginConfiguration();

        /** @var AbstractIntegration $integrationClass */
        $integrationClass = $model->class;
        foreach ($integrationClass::getSettingBlueprints() as $blueprint) {
            if ($blueprint->getType() === SettingBlueprint::TYPE_CONFIG) {
                $value = $configuration->get($blueprint->getHandle());

                if (!$value && $blueprint->isRequired()) {
                    $model->addError(
                        "class",
                        Craft::t(
                            "'{key}' key missing in Freeform's plugin configuration",
                            [
                                "key" => $blueprint->getHandle(),
                            ]
                        )
                    );
                }
            }
        }

        if ($beforeSaveEvent->performAction && !$model->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $record->save(false);

                if (!$model->id) {
                    $model->id = $record->id;
                }

                if ($transaction !== null) {
                    $transaction->commit();
                }

                if ($model->getIntegrationObject()->checkConnection()) {
                    try {
                        $mailingList = $model->getIntegrationObject();
                        $mailingList->setForceUpdate(true);
                        $mailingList->getLists();
                    } catch (IntegrationException $e) {
                        craft()->userSession->setError($e->getMessage());
                    }
                }

                $this->onAfterSave($model, $isNewIntegration);

                return true;
            } catch (\Exception $e) {
                if ($transaction !== null) {
                    $transaction->rollback();
                }

                throw $e;
            }
        }

        return false;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Exception
     */
    public function delete($id)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $model = $this->getModelById($id);

        if (!$model || !$this->onBeforeDelete($model)->performAction) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {
            $affectedRows = craft()->db
                ->createCommand()
                ->delete('freeform_integrations', ['id' => $model->id]);

            if ($transaction !== null) {
                $transaction->commit();
            }

            $this->onAfterDelete($model);

            return (bool)$affectedRows;
        } catch (\Exception $exception) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $exception;
        }
    }

    /**
     * Updates the mailing lists of a given mailing list integration
     *
     * @param AbstractMailingListIntegration $integration
     * @param ListObject[]                   $mailingLists
     *
     * @return bool
     */
    public function updateLists(AbstractMailingListIntegration $integration, array $mailingLists)
    {
        $resourceIds = [];
        foreach ($mailingLists as $mailingList) {
            $resourceIds[] = $mailingList->getId();
        }

        $id                  = $integration->getId();
        $existingResourceIds = craft()
            ->db
            ->createCommand()
            ->select("resourceId")
            ->from("freeform_mailing_lists")
            ->where("integrationId = :integrationId", ["integrationId" => $id])
            ->queryColumn();

        $removableResourceIds = array_diff($existingResourceIds, $resourceIds);
        $addableIds           = array_diff($resourceIds, $existingResourceIds);
        $updatableIds         = array_intersect($resourceIds, $existingResourceIds);

        foreach ($removableResourceIds as $resourceId) {
            // PERFORM DELETE
            craft()
                ->db
                ->createCommand()
                ->delete(
                    "freeform_mailing_lists",
                    "integrationId = :integrationId AND resourceId = :resourceId",
                    [
                        "integrationId" => $id,
                        "resourceId"    => $resourceId,
                    ]
                );
        }

        foreach ($mailingLists as $mailingList) {
            // PERFORM INSERT
            if (in_array($mailingList->getId(), $addableIds)) {
                $record                = new Freeform_MailingListRecord();
                $record->integrationId = $id;
                $record->resourceId    = $mailingList->getId();
                $record->name          = $mailingList->getName();
                $record->memberCount   = $mailingList->getMemberCount();
                $record->save();
            }

            // PERFORM UPDATE
            if (in_array($mailingList->getId(), $updatableIds)) {
                craft()
                    ->db
                    ->createCommand()
                    ->update(
                        "freeform_mailing_lists",
                        [
                            "name"        => $mailingList->getName(),
                            "memberCount" => $mailingList->getMemberCount(),
                        ],
                        "integrationId = :integrationId AND resourceId = :resourceId",
                        [
                            "integrationId" => $id,
                            "resourceId"    => $mailingList->getId(),
                        ]
                    );
            }
        }

        $this->updateListFields($mailingLists);

        // Remove ForceUpdate flag
        craft()
            ->db
            ->createCommand()
            ->update(
                "freeform_integrations",
                ["forceUpdate" => 0],
                "id = :id",
                ["id" => $id]
            );

        return true;
    }

    /**
     * Returns all ListObjects of a particular mailing list integration
     *
     * @param AbstractMailingListIntegration $integration
     *
     * @return ListObject[]
     */
    public function getLists(AbstractMailingListIntegration $integration)
    {
        $data = craft()
            ->db
            ->createCommand()
            ->select("id, resourceId, name, memberCount")
            ->from(Freeform_MailingListRecord::TABLE)
            ->where(
                "integrationId = :integrationId",
                ["integrationId" => $integration->getId()]
            )
            ->order("dateCreated ASC")
            ->queryAll();

        $lists = [];
        foreach ($data as $item) {
            $fieldData = craft()
                ->db
                ->createCommand()
                ->select("handle, label, type, required")
                ->from(Freeform_MailingListFieldRecord::TABLE)
                ->where(
                    "mailingListId = :mailingListId",
                    ["mailingListId" => $item["id"]]
                )
                ->order("dateCreated ASC")
                ->queryAll();

            $fields = [];
            foreach ($fieldData as $fieldItem) {
                $fields[] = new FieldObject(
                    $fieldItem["handle"],
                    $fieldItem["label"],
                    $fieldItem["type"],
                    $fieldItem["required"]
                );
            }

            $lists[] = new ListObject(
                $integration,
                $item["resourceId"],
                $item["name"],
                $fields,
                $item["memberCount"]
            );
        }

        return $lists;
    }

    /**
     * @param AbstractMailingListIntegration $integration
     * @param int                            $id
     *
     * @return ListObject
     * @throws ListNotFoundException
     */
    public function getListById(AbstractMailingListIntegration $integration, $id)
    {
        $record = Freeform_MailingListRecord::model()->findByAttributes(
            [
                "resourceId"    => $id,
                "integrationId" => $integration->getId(),
            ]
        );

        if ($record) {
            /** @var Freeform_MailingListModel $model */
            $model = Freeform_MailingListModel::populateModel($record);

            $listObject = new ListObject(
                $integration,
                $model->resourceId,
                $model->name,
                $model->getFieldObjects(),
                $model->memberCount
            );

            return $listObject;
        }

        throw new ListNotFoundException(
            sprintf(
                "Could not find a list by ID \"%s\" in %s",
                $id,
                $integration->getServiceProvider()
            )
        );
    }

    /**
     * Flag the given mailing list integration so that it's updated the next time it's accessed
     *
     * @param AbstractMailingListIntegration $integration
     */
    public function flagMailingListIntegrationForUpdating(AbstractMailingListIntegration $integration)
    {
        craft()
            ->db
            ->createCommand()
            ->update(
                "freeform_integrations",
                ["forceUpdate" => true],
                "id = :id",
                ["id" => $integration->getId()]
            );
    }

    /**
     * @return array
     */
    public function getAllMailingListServiceProviders()
    {
        if (null === self::$integrations) {
            $interface = 'Solspace\Freeform\Library\Integrations\MailingLists\MailingListIntegrationInterface';
            $validIntegrations = [];

            $integrations = [
                'Solspace\Freeform\Library\Integrations\MailingLists\Implementations\MailChimp' => 'MailChimp',
            ];

            $addonIntegrations = craft()->plugins->call('registerFreeformMailingListIntegrations');
            foreach ($addonIntegrations as $integrationList) {
                $integrations = array_merge($integrations, $integrationList);
            }

            $finder          = new Finder();
            $mailingListPath = __DIR__ . '/../Library/Pro/Integrations/MailingLists';
            if (file_exists($mailingListPath) && is_dir($mailingListPath)) {
                /** @var SplFileInfo[] $files */
                $files         = $finder->files()->in($mailingListPath)->name('*.php');
                $baseNamespace = 'Solspace\Freeform\Library\Pro\Integrations\MailingLists';

                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                    $baseName = substr(
                        $fileName,
                        0,
                        strpos($fileName, '.')
                    );

                    $className = $baseNamespace . "\\" . $baseName;

                    $integrations[$className] = $baseName;
                }
            }


            foreach ($integrations as $class => $name) {
                $reflectionClass = new \ReflectionClass($class);

                if ($reflectionClass->implementsInterface($interface)) {
                    $title = $reflectionClass->getConstant('TITLE') ?: ($name . ' (Legacy)');

                    $validIntegrations[$class] = $title;
                }
            }

            self::$integrations = $validIntegrations;
        }

        return self::$integrations;
    }

    /**
     * @return array
     */
    public function getAllMailingListSettingBlueprints()
    {
        $serviceProviderTypes = $this->getAllMailingListServiceProviders();

        // Get all blueprints per class
        $settingBlueprints = [];

        /**
         * @var AbstractIntegration $providerClass
         * @var string              $name
         */
        foreach ($serviceProviderTypes as $providerClass => $name) {
            $settingBlueprints[$providerClass] = $providerClass::getSettingBlueprints();
        }

        return $settingBlueprints;
    }

    /**
     * Get all setting blueprints for a specific mailing list integration
     *
     * @param string $class
     *
     * @return SettingBlueprint[]
     * @throws Exception
     */
    public function getMailingListSettingBlueprints($class)
    {
        $serviceProviderTypes = $this->getAllMailingListServiceProviders();

        /**
         * @var AbstractIntegration $providerClass
         * @var string              $name
         */
        foreach ($serviceProviderTypes as $providerClass => $name) {
            if ($providerClass === $class) {
                return $providerClass::getSettingBlueprints();
            }
        }

        throw new Exception("Could not get Mailing List settings");
    }

    /**
     * @param ListObject[] $mailingLists
     */
    private function updateListFields(array $mailingLists)
    {
        $metadata = craft()
            ->db
            ->createCommand()
            ->select("id, resourceId")
            ->from(Freeform_MailingListRecord::TABLE)
            ->queryAll();

        $mailingListIds = [];
        foreach ($metadata as $item) {
            $mailingListIds[$item["resourceId"]] = $item["id"];
        }

        foreach ($mailingLists as $mailingList) {
            // Getting the database ID based on mailing list resource ID
            $mailingListId = $mailingListIds[$mailingList->getId()];

            $fields       = $mailingList->getFields();
            $fieldHandles = [];
            foreach ($fields as $field) {
                $fieldHandles[] = $field->getHandle();
            }

            $existingFieldHandles = craft()
                ->db
                ->createCommand()
                ->select("handle")
                ->from(Freeform_MailingListFieldRecord::TABLE)
                ->where("mailingListId = :mailingListId", ["mailingListId" => $mailingListId])
                ->queryColumn();

            $removableFieldHandles = array_diff($existingFieldHandles, $fieldHandles);
            $addableFieldHandles   = array_diff($fieldHandles, $existingFieldHandles);
            $updatableFieldHandles = array_intersect($fieldHandles, $existingFieldHandles);

            foreach ($removableFieldHandles as $handle) {
                // PERFORM DELETE
                craft()
                    ->db
                    ->createCommand()
                    ->delete(
                        Freeform_MailingListFieldRecord::TABLE,
                        "mailingListId = :mailingListId AND handle = :handle",
                        [
                            "mailingListId" => $mailingListId,
                            "handle"        => $handle,
                        ]
                    );
            }

            foreach ($fields as $field) {
                // PERFORM INSERT
                if (in_array($field->getHandle(), $addableFieldHandles)) {
                    $record                = new Freeform_MailingListFieldRecord();
                    $record->mailingListId = $mailingListId;
                    $record->handle        = $field->getHandle();
                    $record->label         = $field->getLabel();
                    $record->type          = $field->getType();
                    $record->required      = $field->isRequired();
                    $record->save();
                }

                // PERFORM UPDATE
                if (in_array($field->getHandle(), $updatableFieldHandles)) {
                    craft()
                        ->db
                        ->createCommand()
                        ->update(
                            Freeform_MailingListFieldRecord::TABLE,
                            [
                                "handle"   => $field->getHandle(),
                                "label"    => $field->getLabel(),
                                "type"     => $field->getType(),
                                "required" => $field->isRequired() ? 1 : 0,
                            ],
                            "mailingListId = :mailingListId AND handle = :handle",
                            [
                                "mailingListId" => $mailingListId,
                                "handle"        => $field->getHandle(),
                            ]
                        );
                }
            }
        }
    }

    /**
     * @param Freeform_IntegrationModel $model
     * @param bool                      $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_IntegrationModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew,]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_IntegrationModel $model
     * @param bool                      $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_IntegrationModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew,]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_IntegrationModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_IntegrationModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_IntegrationModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_IntegrationModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}

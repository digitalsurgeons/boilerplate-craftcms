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

use Guzzle\Http\Exception\BadResponseException;
use Solspace\Freeform\Library\Composer\Components\Layout;
use Solspace\Freeform\Library\Composer\Components\Properties\IntegrationProperties;
use Solspace\Freeform\Library\Configuration\CraftPluginConfiguration;
use Solspace\Freeform\Library\Database\CRMHandlerInterface;
use Solspace\Freeform\Library\Exceptions\FreeformException;
use Solspace\Freeform\Library\Exceptions\Integrations\CRMIntegrationNotFoundException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;
use Solspace\Freeform\Library\Integrations\AbstractIntegration;
use Solspace\Freeform\Library\Integrations\CRM\AbstractCRMIntegration;
use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;
use Solspace\Freeform\Library\Integrations\SettingBlueprint;
use Solspace\Freeform\Library\Integrations\TokenRefreshInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Freeform_CrmService extends BaseApplicationComponent implements CRMHandlerInterface
{
    /** @var array */
    private static $integrations;

    /**
     * @return Freeform_IntegrationModel[]
     */
    public function getAllModels()
    {
        $records = Freeform_IntegrationRecord::model()->findAllByAttributes(
            ["type" => Freeform_IntegrationRecord::TYPE_CRM]
        );

        /** @var Freeform_IntegrationModel[] $models */
        $models = Freeform_IntegrationModel::populateModels(
            $records
        );

        return $models;
    }

    /**
     * @return AbstractCRMIntegration[]
     */
    public function getAllIntegrations()
    {
        $models = $this->getAllModels();

        $integrations = [];
        foreach ($models as $model) {
            $integrations[] = $model->getIntegrationObject();
        }

        return $integrations;
    }

    /**
     * @param int $id
     *
     * @return AbstractCRMIntegration
     * @throws CRMIntegrationNotFoundException
     */
    public function getIntegrationById($id)
    {
        /** @var Freeform_IntegrationRecord $record */
        $record = Freeform_IntegrationRecord::model()->findById($id);

        if ($record && $record->type === Freeform_IntegrationRecord::TYPE_CRM) {
            /** @var Freeform_IntegrationModel $model */
            $model = Freeform_IntegrationModel::populateModel($record);

            return $model->getIntegrationObject();
        }

        throw new CRMIntegrationNotFoundException(
            Craft::t("CRM Integration with ID {id} not found", ["id" => $id])
        );
    }

    /**
     * @param int $id
     *
     * @return Freeform_IntegrationModel|null
     */
    public function getModelById($id)
    {
        /** @var Freeform_IntegrationRecord $record */
        $record = Freeform_IntegrationRecord::model()->findById($id);

        if ($record && $record->type === Freeform_IntegrationRecord::TYPE_CRM) {
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
        /** @var Freeform_IntegrationRecord $record */
        $record = Freeform_IntegrationRecord::model()->findByAttributes(["handle" => $handle]);

        if ($record && $record->type === Freeform_IntegrationRecord::TYPE_CRM) {
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
            /** @var Freeform_IntegrationRecord $record */
            $record = Freeform_IntegrationRecord::model()->findById($model->id);

            if (!$record || $record->type !== Freeform_IntegrationRecord::TYPE_CRM) {
                throw new Exception(
                    Craft::t(
                        "CRM Integration with ID {id} not found",
                        ["id" => $model->id]
                    )
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
     * Update the access token of an integration
     *
     * @param AbstractCRMIntegration $integration
     */
    public function updateAccessToken(AbstractCRMIntegration $integration)
    {
        $model              = $this->getModelById($integration->getId());
        $model->accessToken = $integration->getAccessToken();
        $model->settings    = $integration->getSettings();

        $this->save($model);
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
     * @return array
     */
    public function getAllCRMServiceProviders()
    {
        if (null === self::$integrations) {
            $interface = 'Solspace\Freeform\Library\Integrations\CRM\CRMIntegrationInterface';
            $integrations = $validIntegrations = [];

            $addonIntegrations = craft()->plugins->call('registerFreeformCRMIntegrations');
            foreach ($addonIntegrations as $integrationList) {
                $integrations = array_merge($integrations, $integrationList);
            }

            $finder      = new Finder();
            $crmListPath = __DIR__ . '/../Library/Pro/Integrations/CRM';
            if (file_exists($crmListPath) && is_dir($crmListPath)) {
                /** @var SplFileInfo[] $files */
                $files         = $finder->files()->in($crmListPath)->name('*.php');
                $baseNamespace = 'Solspace\Freeform\Library\Pro\Integrations\CRM';

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


            $validIntegrations = [];
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
    public function getAllCRMSettingBlueprints()
    {
        $serviceProviderTypes = $this->getAllCRMServiceProviders();

        // Get all blueprints per class
        $settingBlueprints = [];

        /**
         * @var AbstractCRMIntegration $providerClass
         * @var string                 $name
         */
        foreach ($serviceProviderTypes as $providerClass => $name) {
            $settingBlueprints[$providerClass] = $providerClass::getSettingBlueprints();
        }

        return $settingBlueprints;
    }

    /**
     * Get all setting blueprints for a specific CRM integration
     *
     * @param string $class
     *
     * @return SettingBlueprint[]
     * @throws Exception
     */
    public function getCRMSettingBlueprints($class)
    {
        $serviceProviderTypes = $this->getAllCRMServiceProviders();

        /**
         * @var AbstractCRMIntegration $providerClass
         */
        foreach ($serviceProviderTypes as $providerClass => $name) {
            if ($providerClass === $class) {
                return $providerClass::getSettingBlueprints();
            }
        }

        throw new Exception("Could not get CRM settings");
    }

    /**
     * Updates the fields of a given CRM integration
     *
     * @param AbstractCRMIntegration $integration
     * @param FieldObject[]          $fields
     *
     * @return bool
     */
    public function updateFields(AbstractCRMIntegration $integration, array $fields)
    {
        $handles = [];
        foreach ($fields as $field) {
            $handles[] = $field->getHandle();
        }

        $id             = $integration->getId();
        $existingFields = craft()
            ->db
            ->createCommand()
            ->select("handle")
            ->from(Freeform_CrmFieldRecord::TABLE)
            ->where("integrationId = :integrationId", ["integrationId" => $id])
            ->queryColumn();

        $removableHandles = array_diff($existingFields, $handles);
        $addableHandles   = array_diff($handles, $existingFields);
        $updatableHandles = array_intersect($handles, $existingFields);

        foreach ($removableHandles as $handle) {
            // PERFORM DELETE
            craft()
                ->db
                ->createCommand()
                ->delete(
                    Freeform_CrmFieldRecord::TABLE,
                    "integrationId = :integrationId AND handle = :handle",
                    [
                        "integrationId" => $id,
                        "handle"        => $handle,
                    ]
                );
        }

        foreach ($fields as $field) {
            // PERFORM INSERT
            if (in_array($field->getHandle(), $addableHandles)) {
                $record                = new Freeform_CrmFieldRecord();
                $record->integrationId = $id;
                $record->handle        = $field->getHandle();
                $record->label         = $field->getLabel();
                $record->required      = $field->isRequired();
                $record->save();
            }

            // PERFORM UPDATE
            if (in_array($field->getHandle(), $updatableHandles)) {
                craft()
                    ->db
                    ->createCommand()
                    ->update(
                        Freeform_CrmFieldRecord::TABLE,
                        [
                            "label"    => $field->getLabel(),
                            "type"     => $field->getType(),
                            "required" => $field->isRequired() ? 1 : 0,
                        ],
                        "integrationId = :integrationId AND handle = :handle",
                        [
                            "integrationId" => $id,
                            "handle"        => $field->getHandle(),
                        ]
                    );
            }
        }

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
     * Returns all FieldObjects of a particular CRM integration
     *
     * @param AbstractCRMIntegration $integration
     *
     * @return FieldObject[]
     */
    public function getFields(AbstractCRMIntegration $integration)
    {
        $data = craft()
            ->db
            ->createCommand()
            ->select("handle, label, type, required")
            ->from(Freeform_CrmFieldRecord::TABLE)
            ->where(
                "integrationId = :integrationId",
                ["integrationId" => $integration->getId()]
            )
            ->order("label ASC")
            ->queryAll();

        $fields = [];
        foreach ($data as $item) {
            $fields[] = new FieldObject(
                $item["handle"],
                $item["label"],
                $item["type"],
                $item["required"]
            );
        }

        return $fields;
    }

    /**
     * Flag the given CRM integration so that it's updated the next time it's accessed
     *
     * @param AbstractCRMIntegration $integration
     */
    public function flagIntegrationForUpdating(AbstractCRMIntegration $integration)
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
     * Push the mapped object values to the CRM
     *
     * @param IntegrationProperties $properties
     * @param Layout                $layout
     *
     * @return bool
     */
    public function pushObject(IntegrationProperties $properties, Layout $layout)
    {
        try {
            $integration = $this->getIntegrationById($properties->getIntegrationId());
        } catch (\Exception $e) {
            return false;
        }

        $mapping = $properties->getMapping();
        if (empty($mapping)) {
            Craft::log(
                Craft::t(
                    "No field mapping specified for '{integration}' integration",
                    ["integration" => $integration->getName()]
                ),
                LogLevel::Warning
            );

            return false;
        }

        /** @var FieldObject[] $crmFieldsByHandle */
        $crmFieldsByHandle = [];
        foreach ($integration->getFields() as $field) {
            $crmFieldsByHandle[$field->getHandle()] = $field;
        }

        $objectValues = [];
        foreach ($mapping as $crmHandle => $fieldHandle) {
            try {
                $crmField  = $crmFieldsByHandle[$crmHandle];
                $formField = $layout->getFieldByHandle($fieldHandle);

                if ($crmField->getType() === FieldObject::TYPE_ARRAY) {
                    $value = $formField->getValue();
                } else {
                    $value = $formField->getValueAsString(false);
                }

                $objectValues[$crmHandle] = $integration->convertCustomFieldValue($crmField, $value);
            } catch (FreeformException $e) {
                Craft::log($e->getMessage(), LogLevel::Error);
            }
        }

        if (!empty($objectValues)) {
            try {
                return $integration->pushObject($objectValues);
            } catch (BadResponseException $e) {
                if ($integration instanceof TokenRefreshInterface) {
                    if ($integration->refreshToken() && $integration->isAccessTokenUpdated()) {
                        try {
                            $this->updateAccessToken($integration);

                            try {
                                return $integration->pushObject($objectValues);
                            } catch (\Exception $e) {
                                Craft::log($e->getMessage(), LogLevel::Error);
                            }
                        } catch (Exception $e) {
                            Craft::log($e->getMessage(), LogLevel::Error);
                        }
                    }
                }

                Craft::log($e->getMessage(), LogLevel::Error);
            } catch (\Exception $e) {
                Craft::log($e->getMessage(), LogLevel::Error);
            }
        }

        return false;
    }

    /**
     * @param Freeform_IntegrationModel $model
     * @param bool                      $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_IntegrationModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
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
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
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

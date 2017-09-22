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

use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Exceptions\FreeformException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;
use Stringy\Stringy;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Freeform_FieldsService extends BaseApplicationComponent
{
    /** @var Freeform_FieldModel[] */
    private static $fieldCache;

    /** @var bool */
    private static $allFieldsLoaded;

    /** @var array */
    private static $fieldHandleCache;

    /**
     * @param bool $indexById
     *
     * @return Freeform_FieldModel[]
     */
    public function getAllFields($indexById = true)
    {
        if (null === self::$fieldCache || !self::$allFieldsLoaded) {
            $fieldDisplayOrder = craft()->freeform_settings->getFieldDisplayOrder();

            $fieldRecords     = Freeform_FieldRecord::model()->{$fieldDisplayOrder}()->findAll();
            self::$fieldCache = Freeform_FieldModel::populateModels($fieldRecords, $indexById ? 'id' : null);

            self::$allFieldsLoaded = true;
        }

        return self::$fieldCache;
    }

    /**
     * @param bool $indexById
     *
     * @return array
     */
    public function getAllFieldHandles($indexById = true)
    {
        if (null === self::$fieldHandleCache) {
            $results = craft()
                ->db
                ->createCommand()
                ->select('id, handle')
                ->from(Freeform_FieldRecord::TABLE)
                ->queryAll();

            $list = [];
            foreach ($results as $result) {
                $list[$result['id']] = $result['handle'];
            }

            if (!$indexById) {
                $list = array_values($list);
            }

            self::$fieldHandleCache = $list;
        }

        return self::$fieldHandleCache;
    }

    /**
     * @return array
     */
    public function getFieldTypes()
    {
        $fieldTypes = [
            FieldInterface::TYPE_TEXT               => 'Text',
            FieldInterface::TYPE_TEXTAREA           => 'Textarea',
            FieldInterface::TYPE_EMAIL              => 'Email',
            FieldInterface::TYPE_HIDDEN             => 'Hidden',
            FieldInterface::TYPE_SELECT             => 'Select',
            FieldInterface::TYPE_CHECKBOX           => 'Checkbox',
            FieldInterface::TYPE_CHECKBOX_GROUP     => 'Checkbox Group',
            FieldInterface::TYPE_RADIO_GROUP        => 'Radio Group',
            FieldInterface::TYPE_FILE               => 'File',
            FieldInterface::TYPE_DYNAMIC_RECIPIENTS => 'Dynamic Recipients',
        ];

        $finder = new Finder();
        $path   = __DIR__ . '/../Library/Pro/Fields';
        $interface = 'Solspace\Freeform\Library\Composer\Components\FieldInterface';
        $baseNamespace = 'Solspace\Freeform\Library\Pro\Fields';

        if (file_exists($path) && is_dir($path)) {
            /** @var SplFileInfo[] $files */
            $files = $finder->files()->in($path)->name('*.php');
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $baseName = substr(
                    $fileName,
                    0,
                    strpos($fileName, '.')
                );

                $className = $baseNamespace . "\\" . $baseName;

                $reflectionClass = new \ReflectionClass($className);
                if ($reflectionClass->implementsInterface($interface)) {
                    $name = $className::getFieldTypeName();
                    $type = $className::getFieldType();

                    $fieldTypes[$type] = $name;
                }
            }
        }

        return $fieldTypes;
    }

    /**
     * @param int $id
     *
     * @return Freeform_FieldModel|null
     */
    public function getFieldById($id)
    {
        if (null === self::$fieldCache || !isset(self::$fieldCache[$id])) {
            if (null === self::$fieldCache) {
                self::$fieldCache = [];
            }

            $fieldRecord = Freeform_FieldRecord::model()->findById($id);

            self::$fieldCache[$id] = $fieldRecord ? Freeform_FieldModel::populateModel($fieldRecord) : null;
        }

        return self::$fieldCache[$id];
    }

    /**
     * @param Freeform_FieldModel $field
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_FieldModel $field)
    {
        $isNewField = !$field->id;

        if (!$isNewField) {
            $fieldRecord = Freeform_FieldRecord::model()->findById($field->id);

            if (!$fieldRecord) {
                throw new Exception(Craft::t('Field with ID {id} not found', ['id' => $field->id]));
            }
        } else {
            $fieldRecord = new Freeform_FieldRecord();
        }

        $beforeSaveEvent = $this->onBeforeSave($field, $isNewField);

        // We allow setting the type only on field creation
        if (!$fieldRecord->id) {
            $fieldRecord->type = $field->type;
        }

        $fieldRecord->handle               = $field->handle;
        $fieldRecord->label                = $field->label;
        $fieldRecord->required             = $field->required;
        $fieldRecord->value                = $field->value;
        $fieldRecord->assetSourceId        = $field->assetSourceId ?: null;
        $fieldRecord->placeholder          = $field->placeholder;
        $fieldRecord->instructions         = $field->instructions;
        $fieldRecord->values               = $field->values;
        $fieldRecord->options              = $field->options;
        $fieldRecord->checked              = $field->checked;
        $fieldRecord->rows                 = $field->rows;
        $fieldRecord->maxFileSizeKB        = $field->maxFileSizeKB;
        $fieldRecord->fileKinds            = $field->fileKinds;
        $fieldRecord->additionalProperties = $field->additionalProperties;

        $fieldRecord->validate();
        $field->addErrors($fieldRecord->getErrors());

        if ($beforeSaveEvent->performAction && !$field->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $fieldRecord->save(false);

                if (!$field->id) {
                    $field->id = $fieldRecord->id;

                    if ($field->canStoreValues()) {
                        $this->createFieldInSubmissionsTable($field);
                    }
                }

                self::$fieldCache[$field->id] = $field;

                if ($transaction !== null) {
                    $transaction->commit();
                }

                $this->onAfterSave($field, $isNewField);

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
     * @param int $fieldId
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteById($fieldId)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FIELDS_MANAGE);

        $fieldModel = $this->getFieldById($fieldId);

        if (!$fieldModel) {
            return false;
        }

        if (!$this->onBeforeDelete($fieldModel)->performAction) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {
            $affectedRows = craft()->db
                ->createCommand()
                ->delete('freeform_fields', ['id' => $fieldModel->id]);

            $this->deleteFieldFromSubmissionsTable($fieldModel);
            $this->deleteFieldFromForms($fieldModel);

            if ($transaction !== null) {
                $transaction->commit();
            }

            $this->onAfterDelete($fieldModel);

            return (bool) $affectedRows;
        } catch (\Exception $exception) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $exception;
        }
    }

    /**
     * @param Freeform_FieldModel $fieldModel
     */
    private function createFieldInSubmissionsTable(Freeform_FieldModel $fieldModel)
    {
        $tableName       = Freeform_SubmissionRecord::TABLE;
        $fieldColumnName = Freeform_SubmissionRecord::getFieldColumnName($fieldModel->id);

        /** @var DbCommand $command */
        $command = craft()->db->createCommand();
        $command->addColumn($tableName, $fieldColumnName, $fieldModel->getColumnType());
    }

    /**
     * @param Freeform_FieldModel $fieldModel
     */
    private function deleteFieldFromSubmissionsTable(Freeform_FieldModel $fieldModel)
    {
        $tableName       = Freeform_SubmissionRecord::TABLE;
        $fieldColumnName = Freeform_SubmissionRecord::getFieldColumnName($fieldModel->id);

        /** @var DbCommand $command */
        $command = craft()->db->createCommand();
        $command->dropColumn($tableName, $fieldColumnName);
    }

    /**
     * @param Freeform_FieldModel $fieldModel
     */
    private function deleteFieldFromForms(Freeform_FieldModel $fieldModel)
    {
        $forms = $this->getFormsService()->getAllForms();

        foreach ($forms as $form) {
            try {
                $composer = $form->getComposer();
                $composer->removeFieldById($fieldModel->id);
                $form->layoutJson = $composer->getComposerStateJSON();
                $this->getFormsService()->save($form);
            } catch (FreeformException $e) {
            }
        }
    }

    /**
     * @return Freeform_FormsService
     */
    private function getFormsService()
    {
        return craft()->freeform_forms;
    }

    /**
     * @param Freeform_FieldModel $model
     * @param bool                $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_FieldModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_FieldModel $model
     * @param bool                $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_FieldModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_FieldModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_FieldModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_FieldModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_FieldModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}

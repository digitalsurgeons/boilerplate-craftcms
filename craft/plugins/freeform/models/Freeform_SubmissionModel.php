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

use Solspace\Freeform\Library\Composer\Components\AbstractField;
use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;

/**
 * Class Freeform_FieldModel
 *
 * @property int $id
 * @property int $incrementalId
 * @property int $statusId
 * @property int $formId
 */
class Freeform_SubmissionModel extends BaseElementModel
{
    const ELEMENT_TYPE = 'Freeform_Submission';

    /** @var string */
    protected $elementType = self::ELEMENT_TYPE;

    /** @var array */
    private static $fieldCache;

    /** @var array */
    private static $allFieldHandles;

    /**
     * @return $this
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        return UrlHelper::getCpUrl('freeform/submissions/' . parent::getAttribute('id'));
    }

    /**
     * @return Freeform_FormModel
     */
    public function getForm()
    {
        /** @var Freeform_FormsService $formService */
        $formService = craft()->freeform_forms;

        return $formService->getFormById(parent::getAttribute("formId"));
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        /** @var Freeform_StatusModel $status */
        $status = craft()->freeform_statuses->getStatusById(parent::getAttribute('statusId'));

        if ($status) {
            return $status->color;
        }

        return null;
    }

    /**
     * @return Freeform_StatusModel
     */
    public function getStatusModel()
    {
        /** @var Freeform_StatusModel $status */
        $status = craft()->freeform_statuses->getStatusById(parent::getAttribute('statusId'));

        if ($status) {
            return $status;
        }

        return null;
    }

    /**
     * @return DateTime
     */
    public function getSubmissionDate()
    {
        return $this->dateCreated;
    }

    /**
     * @return mixed
     */
    private function getAllFieldHandles()
    {
        return craft()->freeform_fields->getAllFieldHandles();
    }

    /**
     * @return AbstractField[]
     */
    public function getFieldMetadata()
    {
        $formId = parent::getAttribute('formId');

        if (null === self::$fieldCache || !isset(self::$fieldCache[$formId])) {
            if (null === self::$fieldCache) {
                self::$fieldCache = [];
            }

            $fields = [];
            foreach ($this->getForm()->getLayout()->getFields() as $field) {
                if (!$field->getHandle() || $field instanceof NoStorageInterface) {
                    continue;
                }

                $fields[$field->getHandle()] = $field;
            }

            self::$fieldCache[$formId] = $fields;
        }

        return self::$fieldCache[$formId];
    }

    /**
     * @param string $fieldColumnHandle - e.g. "field_1" or "field_52", etc
     *
     * @return AssetFileModel|null
     */
    public function getAssetModel($fieldColumnHandle)
    {
        $columnPrefix = Freeform_SubmissionRecord::FIELD_COLUMN_PREFIX;
        if (strpos($fieldColumnHandle, $columnPrefix) === 0) {
            $fieldId = (int)substr($fieldColumnHandle, strlen($columnPrefix));
            $value   = $this->{$fieldColumnHandle};

            $field = $this->getForm()->getLayout()->getFieldById($fieldId);

            if ($field->getType() !== FieldInterface::TYPE_FILE) {
                return null;
            }

            return craft()->assets->getFileById($value);
        }

        return null;
    }

    /**
     * Getter
     *
     * @param string $name
     *
     * @throws \Exception
     * @return mixed
     */
    public function __get($name)
    {
        $fields = $this->getFieldMetadata();
        if (array_key_exists($name, $fields)) {
            $field = $fields[$name];
            $fieldColumnName = Freeform_SubmissionRecord::getFieldColumnName($field->getId());

            $field->setValue(parent::__get($fieldColumnName));

            return $field;
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param array  $attributes
     *
     * @return bool|BaseModel|null
     */
    public function __call($name, $attributes = [])
    {
        $fields = $this->getFieldMetadata();
        if (array_key_exists($name, $fields)) {
            return true;
        }

        if (in_array($name, $this->getAllFieldHandles())) {
            return null;
        }

        return parent::__call($name, $attributes);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $fields = $this->getFieldMetadata();
        if (array_key_exists($name, $fields)) {
            return true;
        }

        if (in_array($name, $this->getAllFieldHandles())) {
            return null;
        }

        return parent::__isset($name);
    }


    /**
     * @return array
     */
    protected function defineAttributes()
    {
        /** @var Freeform_FieldModel[] $fields */
        $fields = craft()->freeform_fields->getAllFields();

        $attributes = [];
        foreach ($fields as $field) {
            $attributeType = $field->getAttributeType();
            $columnName    = Freeform_SubmissionRecord::getFieldColumnName($field->id);

            $attributes[$columnName] = $attributeType;
        }

        return array_merge(
            parent::defineAttributes(),
            [
                'incrementalId' => [AttributeType::Number, 'required' => false, 'default' => 0],
                "statusId"      => AttributeType::Number,
                "formId"        => AttributeType::Number,
            ],
            $attributes
        );
    }
}

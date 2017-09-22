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
use Solspace\Freeform\Library\Composer\Components\Fields\FileUploadField;
use Solspace\Freeform\Library\Helpers\HashHelper;

/**
 * Class Freeform_FieldModel
 *
 * @property int    $id
 * @property string $type
 * @property string $handle
 * @property string $label
 * @property bool   $required
 * @property string $groupValueType
 * @property string $value
 * @property bool   $checked
 * @property string $placeholder
 * @property string $instructions
 * @property array  $values
 * @property array  $options
 * @property int    $notificationId
 * @property int    $assetSourceId
 * @property int    $rows
 * @property array  $fileKinds
 * @property int    $maxFileSizeKB
 * @property array  $additionalProperties
 */
class Freeform_FieldModel extends BaseModel implements \JsonSerializable
{
    const SMALL_DATA_STORAGE_LENGTH = 100;

    /**
     * @return static
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
     * @return string
     */
    public function getHash()
    {
        return HashHelper::hash($this->id);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $returnArray = [
            'id'           => (int) $this->id,
            'hash'         => $this->getHash(),
            'type'         => $this->type,
            'handle'       => $this->handle,
            'label'        => $this->label,
            'required'     => (bool) $this->required,
            'instructions' => (string) $this->instructions,
        ];

        if (in_array(
            $this->type,
            [
                FieldInterface::TYPE_TEXT,
                FieldInterface::TYPE_TEXTAREA,
                FieldInterface::TYPE_HIDDEN,
            ],
            true
        )) {
            $returnArray['value']       = $this->value ?: '';
            $returnArray['placeholder'] = $this->placeholder ?: '';
        }

        if ($this->type === FieldInterface::TYPE_TEXTAREA) {
            $returnArray['rows'] = (int) $this->rows ?: 2;
        }

        if ($this->type === FieldInterface::TYPE_CHECKBOX) {
            $returnArray['value']   = $this->value ?: 'Yes';
            $returnArray['checked'] = (bool) $this->checked;
        }

        if ($this->type === FieldInterface::TYPE_EMAIL) {
            $returnArray['notificationId'] = 0;
            $returnArray['values']         = [];
            $returnArray['placeholder']    = $this->placeholder ?: '';
        }

        if ($this->type === FieldInterface::TYPE_DYNAMIC_RECIPIENTS) {
            $returnArray['notificationId'] = 0;
            $returnArray['value']          = 0;
            $returnArray['options']        = $this->options ?: [];
            $returnArray['showAsRadio']    = false;
        }

        if ($this->type === FieldInterface::TYPE_CHECKBOX_GROUP) {
            $returnArray['showCustomValues'] = $this->hasCustomOptionValues();
            $returnArray['values']           = $this->values ?: [];
            $returnArray['options']          = $this->options ?: [];
        }

        if ($this->type === FieldInterface::TYPE_FILE) {
            $returnArray['assetSourceId'] = (int) $this->assetSourceId ?: 0;
            $returnArray['maxFileSizeKB'] = (int) $this->maxFileSizeKB ?: FileUploadField::DEFAULT_MAX_FILESIZE_KB;
            $returnArray['fileKinds']     = $this->fileKinds ?: [];
        }

        if (in_array($this->type, [FieldInterface::TYPE_RADIO_GROUP, FieldInterface::TYPE_SELECT], true)) {
            $returnArray['showCustomValues'] = $this->hasCustomOptionValues();
            $returnArray['value']            = $this->value ?: '';
            $returnArray['options']          = $this->options ?: [];
        }

        if ($this->type === FieldInterface::TYPE_DATETIME) {
            $returnArray['value']               = $this->value ?: '';
            $returnArray['placeholder']         = $this->placeholder ?: '';
            $returnArray['initialValue']        = $this->getProperty('initialValue');
            $returnArray['dateTimeType']        = $this->getProperty('dateTimeType', 'both');
            $returnArray['generatePlaceholder'] = $this->getProperty('generatePlaceholder', true);
            $returnArray['dateOrder']           = $this->getProperty('dateOrder', 'ymd');
            $returnArray['date4DigitYear']      = $this->getProperty('date4DigitYear', true);
            $returnArray['dateLeadingZero']     = $this->getProperty('dateLeadingZero', true);
            $returnArray['dateSeparator']       = $this->getProperty('dateSeparator', '/');
            $returnArray['clock24h']            = $this->getProperty('clock24h', false);
            $returnArray['lowercaseAMPM']       = $this->getProperty('lowercaseAMPM', true);
            $returnArray['clockSeparator']      = $this->getProperty('clockSeparator', ':');
            $returnArray['clockAMPMSeparate']   = $this->getProperty('clockAMPMSeparate', true);
            $returnArray['useDatepicker']       = $this->getProperty('useDatepicker', true);
        }

        if ($this->type === FieldInterface::TYPE_NUMBER) {
            $returnArray['value']              = $this->value ?: '';
            $returnArray['placeholder']        = $this->placeholder ?: '';
            $returnArray['minLength']          = $this->getProperty('minLength');
            $returnArray['maxLength']          = $this->getProperty('maxLength');
            $returnArray['minValue']           = $this->getProperty('minValue');
            $returnArray['maxValue']           = $this->getProperty('maxValue');
            $returnArray['decimalCount']       = $this->getProperty('decimalCount');
            $returnArray['decimalSeparator']   = $this->getProperty('decimalSeparator', '.');
            $returnArray['thousandsSeparator'] = $this->getProperty('thousandsSeparator', ',');
            $returnArray['allowNegative']      = $this->getProperty('allowNegative', false);
        }

        if ($this->type === FieldInterface::TYPE_RATING) {
            $returnArray['value']         = (int) $this->value;
            $returnArray['maxValue']      = $this->getProperty('maxValue', 5);
            $returnArray['colorIdle']     = $this->getProperty('colorIdle', '#ddd');
            $returnArray['colorHover']    = $this->getProperty('colorHover', 'gold');
            $returnArray['colorSelected'] = $this->getProperty('colorSelected', '#f70');
        }

        if ($this->type === FieldInterface::TYPE_REGEX) {
            $returnArray['value']       = $this->value ?: '';
            $returnArray['placeholder'] = $this->placeholder ?: '';
            $returnArray['pattern']     = $this->getProperty('pattern');
            $returnArray['message']     = $this->getProperty('message');
        }

        if ($this->type === FieldInterface::TYPE_CONFIRMATION) {
            $returnArray['value']         = $this->value ?: '';
            $returnArray['placeholder']   = $this->placeholder ?: '';
            $returnArray['targetFieldId'] = $this->getProperty('targetFieldId');
        }

        if ($this->type === FieldInterface::TYPE_PHONE) {
            $returnArray['value']       = $this->value ?: '';
            $returnArray['placeholder'] = $this->placeholder ?: '';
            $returnArray['pattern']     = $this->getProperty('pattern');
        }

        if (in_array(
            $this->type,
            [FieldInterface::TYPE_HIDDEN, FieldInterface::TYPE_HTML, FieldInterface::TYPE_SUBMIT],
            true
        )) {
            unset($returnArray['instructions']);
        }

        return $returnArray;
    }

    /**
     * @param array $postValues
     * @param bool  $forceLabelToValue
     */
    public function setPostValues(array $postValues, $forceLabelToValue = false)
    {
        $labels           = $postValues['labels'];
        $values           = $postValues['values'];
        $checkedByDefault = $postValues['checked'];

        $savableValue   = null;
        $savableValues  = [];
        $savableOptions = [];
        foreach ($labels as $index => $label) {
            $value = $values[$index];

            if (empty($label) && empty($value)) {
                continue;
            }

            $fieldValue = $value;
            if (empty($label)) {
                $fieldLabel = $value;
            } else {
                $fieldValue = $value;
                $fieldLabel = $label;
            }

            if ($forceLabelToValue) {
                $fieldValue = $fieldLabel;
            }

            $isChecked = (bool) $checkedByDefault[$index];
            if ($isChecked) {
                switch ($this->type) {
                    case FieldInterface::TYPE_CHECKBOX_GROUP:
                        $savableValues[] = $fieldValue;
                        break;

                    case FieldInterface::TYPE_RADIO_GROUP:
                    case FieldInterface::TYPE_SELECT:
                    case FieldInterface::TYPE_DYNAMIC_RECIPIENTS:
                        $savableValue = $fieldValue;
                        break;
                }
            }

            $item        = new \stdClass();
            $item->value = $fieldValue;
            $item->label = $fieldLabel;

            $savableOptions[] = $item;
        }

        $this->options = !empty($savableOptions) ? $savableOptions : null;
        $this->values  = !empty($savableValues) ? $savableValues : null;
        $this->value   = !empty($savableValue) ? $savableValue : null;
        $this->checked = null;
    }

    /**
     * @return bool
     */
    public function hasCustomOptionValues()
    {
        $options = $this->options;
        if (empty($options)) {
            return false;
        }

        foreach ($options as $valueData) {
            if (is_object($valueData)) {
                $valueData = (array) $valueData;
            }

            if ($valueData['value'] !== $valueData['label']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines if the submission table should get a column for this field or not
     *
     * @return bool
     */
    public function canStoreValues()
    {
        return $this->type !== FieldInterface::TYPE_CONFIRMATION;
    }

    /**
     * Depending on the field type - return its attribute type for the model and record
     *
     * @return string
     */
    public function getAttributeType()
    {
        $attributeType = AttributeType::String;

        switch ($this->type) {
            case FieldInterface::TYPE_CHECKBOX_GROUP:
            case FieldInterface::TYPE_EMAIL:
                $attributeType = AttributeType::Mixed;

                break;
        }

        return $attributeType;
    }

    /**
     * Depending on the field type - return its column type for the database
     *
     * @return array
     */
    public function getColumnType()
    {
        $columnType = [ColumnType::Varchar, 'length' => self::SMALL_DATA_STORAGE_LENGTH];

        switch ($this->type) {
            case FieldInterface::TYPE_CHECKBOX_GROUP:
            case FieldInterface::TYPE_EMAIL:
            case FieldInterface::TYPE_TEXTAREA:
                $columnType = [ColumnType::Text];

                break;
        }

        return $columnType;
    }

    /**
     * @param string $name
     * @param mixed  $defaultValue
     *
     * @return mixed|null
     */
    public function getProperty($name, $defaultValue = null)
    {
        if (in_array($name, $this->defineAttributes(), true)) {
            if (null === $this->{$name}) {
                return $defaultValue;
            }

            return $this->{$name};
        }

        if (is_array($this->additionalProperties) && isset($this->additionalProperties[$name])) {
            $value = $this->additionalProperties[$name];

            if (null === $value) {
                return $defaultValue;
            }

            return $this->getCleanedPropertyValue($name, $value);
        }

        return $defaultValue;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setProperty($name, $value)
    {
        if (array_key_exists($name, $this->defineAttributes())) {
            $this->{$name} = $value;

            return $this;
        }

        $props = $this->getAttribute('additionalProperties') ?: [];

        $props[$name] = $this->getCleanedPropertyValue($name, $value);

        $this->setAttribute('additionalProperties', $props);

        return $this;
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'id'                   => AttributeType::Number,
            'type'                 => AttributeType::String,
            'handle'               => AttributeType::Handle,
            'label'                => AttributeType::String,
            'required'             => AttributeType::Bool,
            'value'                => AttributeType::String,
            'values'               => AttributeType::Mixed,
            'placeholder'          => AttributeType::String,
            'instructions'         => AttributeType::String,
            'options'              => AttributeType::Mixed,
            'checked'              => AttributeType::Bool,
            'notificationId'       => AttributeType::Number,
            'assetSourceId'        => AttributeType::Number,
            'rows'                 => AttributeType::Number,
            'fileKinds'            => AttributeType::Mixed,
            'maxFileSizeKB'        => AttributeType::Number,
            'additionalProperties' => AttributeType::Mixed,
        ];
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    private function getCleanedPropertyValue($name, $value)
    {
        static $customTypes = [
            'generatePlaceholder' => 'bool',
            'date4DigitYear'      => 'bool',
            'dateLeadingZero'     => 'bool',
            'clock24h'            => 'bool',
            'clockLeadingZero'    => 'bool',
            'lowercaseAMPM'       => 'bool',
            'allowNegative'       => 'bool',
            'minLength'           => 'int',
            'maxLength'           => 'int',
            'minValue'            => 'int',
            'maxValue'            => 'int',
        ];

        if (isset($customTypes[$name])) {
            switch ($customTypes[$name]) {
                case 'bool':
                    return (bool) $value ? true : false;

                case 'int':
                    return $value !== null ? (int) $value : null;
            }
        }

        return $value;
    }
}

<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2016, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Solspace\Freeform\Library\Composer\Components\Properties;

use Solspace\Freeform\Library\Composer\Components\Fields\DataContainers\Option;

class FieldProperties extends AbstractProperties
{
    /** @var string */
    protected $hash;

    /** @var int */
    protected $id;

    /** @var string */
    protected $handle;

    /** @var string */
    protected $label;

    /** @var boolean */
    protected $required;

    /** @var string */
    protected $placeholder;

    /** @var string */
    protected $instructions;

    /** @var string */
    protected $value;

    /** @var array */
    protected $values;

    /** @var array */
    protected $options;

    /** @var bool */
    protected $checked;

    /** @var bool */
    protected $showAsRadio;

    /** @var int */
    protected $notificationId;

    /** @var int */
    protected $assetSourceId;

    /** @var int */
    protected $integrationId;

    /** @var string */
    protected $resourceId;

    /** @var string */
    protected $emailFieldHash;

    /** @var string */
    protected $position;

    /** @var string */
    protected $labelNext;

    /** @var string */
    protected $labelPrev;

    /** @var bool */
    protected $disablePrev;

    /** @var array */
    protected $mapping;

    /** @var array */
    protected $fileKinds;

    /** @var int */
    protected $maxFileSizeKB;

    /** @var int */
    protected $rows;

    /** @var string */
    protected $dateTimeType;

    /** @var bool */
    protected $generatePlaceholder;

    /** @var string */
    protected $dateOrder;

    /** @var bool */
    protected $date4DigitYear;

    /** @var bool */
    protected $dateLeadingZero;

    /** @var string */
    protected $dateSeparator;

    /** @var bool */
    protected $clock24h;

    /** @var bool */
    protected $lowercaseAMPM;

    /** @var string */
    protected $clockSeparator;

    /** @var bool */
    protected $clockAMPMSeparate;

    /** @var bool */
    protected $useDatepicker;

    /** @var string */
    protected $initialValue;

    /** @var int */
    protected $minValue;

    /** @var int */
    protected $maxValue;

    /** @var int */
    protected $minLength;

    /** @var int */
    protected $maxLength;

    /** @var int */
    protected $decimalCount;

    /** @var string */
    protected $decimalSeparator;

    /** @var string */
    protected $thousandsSeparator;

    /** @var bool */
    protected $allowNegative;

    /** @var string */
    protected $pattern;

    /** @var string */
    protected $targetFieldHash;

    /** @var string */
    protected $message;

    /** @var string */
    protected $colorIdle;

    /** @var string */
    protected $colorHover;

    /** @var string */
    protected $colorSelected;

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        $return = [];
        if (is_array($this->options)) {
            foreach ($this->options as $option) {
                $isChecked = false;
                if (null !== $this->getValue()) {
                    $isChecked = $option['value'] === $this->getValue();
                } else if (null !== $this->getValues()) {
                    $isChecked = in_array($option['value'], $this->getValues(), true);
                }

                $return[] = new Option($option['label'], $option['value'], $isChecked);
            }
        }

        return $return;
    }

    /**
     * @return boolean
     */
    public function isChecked()
    {
        return (bool) $this->checked;
    }

    /**
     * @return boolean
     */
    public function isShowAsRadio()
    {
        return $this->showAsRadio;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @return int
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * @return int
     */
    public function getAssetSourceId()
    {
        return $this->assetSourceId;
    }

    /**
     * @return int
     */
    public function getIntegrationId()
    {
        return (int) $this->integrationId;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return (string) $this->resourceId;
    }

    /**
     * @return string
     */
    public function getEmailFieldHash()
    {
        return (string) $this->emailFieldHash;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getLabelNext()
    {
        return $this->labelNext;
    }

    /**
     * @return string
     */
    public function getLabelPrev()
    {
        return $this->labelPrev;
    }

    /**
     * @return boolean
     */
    public function isDisablePrev()
    {
        return $this->disablePrev;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @return array
     */
    public function getFileKinds()
    {
        return $this->fileKinds;
    }

    /**
     * @return int
     */
    public function getMaxFileSizeKB()
    {
        return $this->maxFileSizeKB;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return string
     */
    public function getDateTimeType()
    {
        return $this->dateTimeType;
    }

    /**
     * @return bool
     */
    public function isGeneratePlaceholder()
    {
        return $this->generatePlaceholder;
    }

    /**
     * @return string
     */
    public function getDateOrder()
    {
        return $this->dateOrder;
    }

    /**
     * @return bool
     */
    public function isDate4DigitYear()
    {
        return $this->date4DigitYear;
    }

    /**
     * @return bool
     */
    public function isDateLeadingZero()
    {
        return $this->dateLeadingZero;
    }

    /**
     * @return string
     */
    public function getDateSeparator()
    {
        return $this->dateSeparator;
    }

    /**
     * @return bool
     */
    public function isClock24h()
    {
        return $this->clock24h;
    }

    /**
     * @return bool
     */
    public function isLowercaseAMPM()
    {
        return $this->lowercaseAMPM;
    }

    /**
     * @return string
     */
    public function getClockSeparator()
    {
        return $this->clockSeparator;
    }

    /**
     * @return bool
     */
    public function isClockAMPMSeparate()
    {
        return $this->clockAMPMSeparate;
    }

    /**
     * @return bool
     */
    public function isUseDatepicker()
    {
        return $this->useDatepicker;
    }

    /**
     * @return string
     */
    public function getInitialValue()
    {
        return $this->initialValue;
    }

    /**
     * @return int
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     * @return int
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->minLength;
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @return int
     */
    public function getDecimalCount()
    {
        return $this->decimalCount;
    }

    /**
     * @return string
     */
    public function getDecimalSeparator()
    {
        return $this->decimalSeparator;
    }

    /**
     * @return string
     */
    public function getThousandsSeparator()
    {
        return $this->thousandsSeparator;
    }

    /**
     * @return bool
     */
    public function isAllowNegative()
    {
        return $this->allowNegative;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return int
     */
    public function getTargetFieldHash()
    {
        return $this->targetFieldHash;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getColorIdle()
    {
        return $this->colorIdle;
    }

    /**
     * @return string
     */
    public function getColorHover()
    {
        return $this->colorHover;
    }

    /**
     * @return string
     */
    public function getColorSelected()
    {
        return $this->colorSelected;
    }

    /**
     * Return a list of all property fields and their type
     * [propertyKey => propertyType, ..]
     * E.g. ["name" => "string", ..]
     *
     * @return array
     */
    protected function getPropertyManifest()
    {
        return [
            'hash'                => self::TYPE_STRING,
            'id'                  => self::TYPE_INTEGER,
            'handle'              => self::TYPE_STRING,
            'label'               => self::TYPE_STRING,
            'required'            => self::TYPE_BOOLEAN,
            'placeholder'         => self::TYPE_STRING,
            'instructions'        => self::TYPE_STRING,
            'value'               => self::TYPE_STRING,
            'values'              => self::TYPE_ARRAY,
            'options'             => self::TYPE_ARRAY,
            'checked'             => self::TYPE_BOOLEAN,
            'showAsRadio'         => self::TYPE_BOOLEAN,
            'notificationId'      => self::TYPE_STRING,
            'assetSourceId'       => self::TYPE_INTEGER,
            'integrationId'       => self::TYPE_INTEGER,
            'resourceId'          => self::TYPE_STRING,
            'emailFieldHash'      => self::TYPE_STRING,
            'position'            => self::TYPE_STRING,
            'labelNext'           => self::TYPE_STRING,
            'labelPrev'           => self::TYPE_STRING,
            'disablePrev'         => self::TYPE_BOOLEAN,
            'mapping'             => self::TYPE_ARRAY,
            'fileKinds'           => self::TYPE_ARRAY,
            'maxFileSizeKB'       => self::TYPE_INTEGER,
            'rows'                => self::TYPE_INTEGER,
            'dateTimeType'        => self::TYPE_STRING,
            'generatePlaceholder' => self::TYPE_BOOLEAN,
            'dateOrder'           => self::TYPE_STRING,
            'date4DigitYear'      => self::TYPE_BOOLEAN,
            'dateLeadingZero'     => self::TYPE_BOOLEAN,
            'dateSeparator'       => self::TYPE_STRING,
            'clock24h'            => self::TYPE_BOOLEAN,
            'lowercaseAMPM'       => self::TYPE_BOOLEAN,
            'clockSeparator'      => self::TYPE_STRING,
            'clockAMPMSeparate'   => self::TYPE_BOOLEAN,
            'useDatepicker'       => self::TYPE_BOOLEAN,
            'initialValue'        => self::TYPE_STRING,
            'minValue'            => self::TYPE_INTEGER,
            'maxValue'            => self::TYPE_INTEGER,
            'minLength'           => self::TYPE_INTEGER,
            'maxLength'           => self::TYPE_INTEGER,
            'decimalCount'        => self::TYPE_INTEGER,
            'decimalSeparator'    => self::TYPE_STRING,
            'thousandsSeparator'  => self::TYPE_STRING,
            'allowNegative'       => self::TYPE_BOOLEAN,
            'pattern'             => self::TYPE_STRING,
            'targetFieldHash'     => self::TYPE_STRING,
            'message'             => self::TYPE_STRING,
            'colorIdle'           => self::TYPE_STRING,
            'colorHover'          => self::TYPE_STRING,
            'colorSelected'       => self::TYPE_STRING,
        ];
    }
}

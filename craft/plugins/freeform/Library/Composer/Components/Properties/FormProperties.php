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

class FormProperties extends AbstractProperties
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $handle;

    /** @var string */
    protected $color;

    /** @var string */
    protected $submissionTitleFormat;

    /** @var string */
    protected $description;

    /** @var string */
    protected $returnUrl;

    /** @var bool */
    protected $storeData;

    /** @var int */
    protected $defaultStatus;

    /** @var string */
    protected $formTemplate;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getSubmissionTitleFormat()
    {
        return $this->submissionTitleFormat;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @return boolean
     */
    public function isStoreData()
    {
        return !is_null($this->storeData) ? (bool) $this->storeData : true;
    }

    /**
     * @return int
     */
    public function getDefaultStatus()
    {
        return $this->defaultStatus;
    }

    /**
     * @return string
     */
    public function getFormTemplate()
    {
        return $this->formTemplate;
    }

    /**
     * Return a list of all property fields and their type
     *
     * [propertyKey => propertyType, ..]
     * E.g. ["name" => "string", ..]
     *
     * @return array
     */
    protected function getPropertyManifest()
    {
        return [
            'name'                  => self::TYPE_STRING,
            'handle'                => self::TYPE_STRING,
            'color'                 => self::TYPE_STRING,
            'submissionTitleFormat' => self::TYPE_STRING,
            'description'           => self::TYPE_STRING,
            'returnUrl'             => self::TYPE_STRING,
            'storeData'             => self::TYPE_BOOLEAN,
            'defaultStatus'         => self::TYPE_INTEGER,
            'formTemplate'          => self::TYPE_STRING,
        ];
    }
}

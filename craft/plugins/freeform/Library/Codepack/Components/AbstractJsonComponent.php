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

namespace Solspace\Freeform\Library\Codepack\Components;

use Solspace\Freeform\Library\Codepack\Exceptions\CodepackException;
use Craft\IOHelper;

abstract class AbstractJsonComponent implements ComponentInterface
{
    /** @var string */
    protected $fileName;

    /** @var mixed */
    private $jsonData;

    /**
     * ComponentInterface constructor.
     *
     * @param string $location
     *
     * @throws CodepackException
     */
    public final function __construct($location)
    {
        $this->setProperties();

        if (is_null($this->fileName)) {
            throw new CodepackException("JSON file name not specified");
        }

        $this->parseJson($location);
    }

    /**
     * Returns the parsed JSON data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->jsonData;
    }

    /**
     * Calls the installation of this component
     *
     * @param string $prefix
     */
    abstract public function install($prefix = null);

    /**
     * This is the method that sets all vital properties
     * ::$fileName
     */
    abstract protected function setProperties();

    /**
     * @param string $location
     *
     * @return bool
     * @throws CodepackException
     */
    private final function parseJson($location)
    {
        $jsonFile = $location . '/' . $this->fileName;
        if (!IOHelper::fileExists($jsonFile)) {
            return false;
        }

        $content    = file_get_contents($jsonFile);
        $parsedData = json_decode($content);

        if (json_last_error()) {
            throw new CodepackException("Codepack JSON component: " . json_last_error_msg());
        }

        if ($parsedData) {
            $this->jsonData = $parsedData;
        }

        return true;
    }
}

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

namespace Solspace\Freeform\Library\Codepack\Components\FileObject;

use Solspace\Freeform\Library\Codepack\Exceptions\FileObject\FileNotFoundException;
use Craft\IOHelper;

abstract class FileObject
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var bool */
    protected $folder;

    /**
     * @param string $path
     *
     * @return FileObject
     * @throws FileNotFoundException
     */
    public static function createFromPath($path)
    {
        $path = IOHelper::getRealPath($path);

        if (!$path) {
            throw new FileNotFoundException(
                sprintf('Path points to nothing: "%s"', $path)
            );
        }

        $isFolder = is_dir($path);

        return $isFolder ? new Folder($path) : new File($path);
    }

    /**
     * @param $path
     */
    abstract protected function __construct($path);

    /**
     * Copy the file or directory to $target location
     *
     * @param string              $target
     * @param string|null         $prefix
     * @param array|callable|null $callable
     * @param string|null         $filePrefix
     *
     * @return void
     */
    abstract public function copy($target, $prefix = null, $callable = null, $filePrefix = null);

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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return boolean
     */
    public function isFolder()
    {
        return $this->folder;
    }
}

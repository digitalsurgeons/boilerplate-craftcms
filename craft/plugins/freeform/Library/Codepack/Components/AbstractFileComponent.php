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

use Solspace\Freeform\Library\Codepack\Components\FileObject\FileObject;
use Solspace\Freeform\Library\Codepack\Components\FileObject\Folder;
use Solspace\Freeform\Library\Codepack\Exceptions\CodepackException;

abstract class AbstractFileComponent implements ComponentInterface
{
    /** @var string */
    protected $installDirectory;

    /** @var string */
    protected $targetFilesDirectory;

    /** @var Folder */
    protected $contents;

    /** @var string */
    private $location;

    /**
     * @param string $location - the location of files
     *
     * @throws CodepackException
     */
    public final function __construct($location)
    {
        $this->location = $location;
        $this->contents = $this->locateFiles();
    }

    /**
     * @return string
     */
    abstract protected function getInstallDirectory();

    /**
     * @return string
     */
    abstract protected function getTargetFilesDirectory();

    /**
     * If anything must come after /{install_directory}/{prefix}demo/{???}
     * It is returned here
     *
     * @param string $prefix
     *
     * @return string
     */
    protected function getSubInstallDirectory($prefix)
    {
        return '';
    }

    /**
     * Installs the component files into the $installDirectory
     *
     * @param string|null $prefix
     */
    public function install($prefix = null)
    {
        $installDirectory = $this->getInstallDirectory();
        $installDirectory = rtrim($installDirectory, '/');
        $installDirectory .= '/' . $prefix . '/';
        $installDirectory .= ltrim($this->getSubInstallDirectory($prefix), '/');

        foreach ($this->contents as $file) {
            $file->copy($installDirectory, $prefix, array($this, 'postFileCopyAction'));
        }
    }

    /**
     * If anything has to be done with a file once it's copied over
     * This method does it
     *
     * @param string      $newFilePath
     * @param string|null $prefix
     */
    public function postFileCopyAction($newFilePath, $prefix = null)
    {
    }

    /**
     * @return FileObject
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return FileObject
     * @throws CodepackException
     */
    private function locateFiles()
    {
        $directory = FileObject::createFromPath($this->getFileLocation());

        if (!$directory instanceof Folder) {
            throw new CodepackException('Target directory is not a directory: ' . $this->getFileLocation());
        }

        return $directory;
    }

    /**
     * @return string
     */
    private function getFileLocation()
    {
        return $this->location . '/' . $this->getTargetFilesDirectory();
    }
}

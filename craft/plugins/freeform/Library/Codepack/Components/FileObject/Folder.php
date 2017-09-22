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

use Craft\FreeformPlugin;
use Solspace\Freeform\Library\Codepack\Exceptions\FileObject\FileObjectException;
use Craft\IOHelper;

class Folder extends FileObject implements \Iterator
{
    /** @var FileObject[]|null */
    protected $files;

    /** @var bool */
    protected $folder = true;

    /** @var int */
    private $fileCount;

    /**
     * Folder constructor.
     *
     * @param string $path
     */
    protected function __construct($path)
    {
        $folder = IOHelper::getFolder($path);

        $this->path = $path;
        $this->name = $folder->getFolderName(false);

        $files = array();
        foreach ($folder->getContents(false, null) as $filePath) {
            $files[] = FileObject::createFromPath($filePath);
        }

        $this->files = $files;
    }

    /**
     * Copy the file or directory to $target location
     *
     * @param string              $target
     * @param string|null         $prefix
     * @param array|callable|null $callable
     * @param string|null         $filePrefix
     *
     * @return void
     * @throws FileObjectException
     */
    public function copy($target, $prefix = null, $callable = null, $filePrefix = null)
    {
        $target           = rtrim($target, '/');
        $targetFolderPath = $target . '/' . $filePrefix . $this->name;
        if (!IOHelper::folderExists($targetFolderPath)) {
            IOHelper::createFolder($targetFolderPath, null, true);

            if (!IOHelper::folderExists($targetFolderPath)) {
                throw new FileObjectException(
                    sprintf(
                        'Permissions denied. Could not create a folder in "%s".<br>Check how to solve this problem <a href="%s">here</a>',
                        $targetFolderPath,
                        FreeformPlugin::PERMISSIONS_HELP_LINK
                    )
                );
            }
        }

        foreach ($this->files as $file) {
            $file->copy($targetFolderPath, $prefix, $callable);
        }
    }

    /**
     * Gets the total number of File instances this Folder object has, recursively
     *
     * @return int
     */
    public function getFileCount()
    {
        if (is_null($this->fileCount)) {
            $count = 0;
            foreach ($this->files as $file) {
                if ($file instanceof Folder) {
                    $count += $file->getFileCount();
                } else {
                    $count++;
                }
            }

            $this->fileCount = $count;
        }

        return $this->fileCount;
    }

    /**
     * @return FileObject[]|null
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->files);
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->files);
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->files);
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return !is_null($this->key()) && $this->key() !== false;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->files);
    }
}

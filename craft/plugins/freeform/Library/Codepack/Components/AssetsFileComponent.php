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

use Solspace\Freeform\Library\Codepack\Exceptions\FileObject\FileNotFoundException;
use Craft\IOHelper;

class AssetsFileComponent extends AbstractFileComponent
{
    private $modifiableFileExtensions = array(
        'css',
        'scss',
        'sass',
        'less',
        'js',
        'coffee',
    );

    private $modifiableCssFiles = array(
        'css',
        'scss',
        'sass',
        'less',
    );

    /**
     * @return string
     */
    protected function getInstallDirectory()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/assets';
    }

    /**
     * @return string
     */
    protected function getTargetFilesDirectory()
    {
        return 'assets';
    }

    /**
     * If anything has to be done with a file once it's copied over
     * This method does it
     *
     * @param string      $newFilePath
     * @param string|null $prefix
     *
     * @throws FileNotFoundException
     */
    public function postFileCopyAction($newFilePath, $prefix = null)
    {
        if (!IOHelper::fileExists($newFilePath)) {
            throw new FileNotFoundException(
                sprintf('Could not find file: %s', $newFilePath)
            );
        }

        $extension = strtolower(pathinfo($newFilePath, PATHINFO_EXTENSION));

        // Prevent from editing anything other than css and js files
        if (!in_array($extension, $this->modifiableFileExtensions)) {
            return;
        }

        $content = file_get_contents($newFilePath);

        if (in_array($extension, $this->modifiableCssFiles)) {
            $content = $this->updateImagesURL($content, $prefix);
            //$content = $this->updateRelativePaths($content, $prefix);
            $content = $this->replaceCustomPrefixCalls($content, $prefix);
        }

        file_put_contents($newFilePath, $content);
    }

    /**
     * This pattern matches all url(/images[..]) with or without surrounding quotes
     * And replaces it with the prefixed asset path
     *
     * @param string $content
     * @param string $prefix
     *
     * @return string
     */
    private function updateImagesURL($content, $prefix)
    {
        $pattern = '/url\s*\(\s*([\'"]?)\/((?:images)\/[a-zA-Z1-9_\-\.\/]+)[\'"]?\s*\)/';
        $replace = 'url($1/assets/' . $prefix . '/$2$1)';
        $content = preg_replace($pattern, $replace, $content);

        return $content;
    }

    /**
     * Updates all "../somePath/" urls to "../$prefix_somePath/" urls
     *
     * @param string $content
     * @param string $prefix
     *
     * @return string
     */
    private function updateRelativePaths($content, $prefix)
    {
        $pattern = '/([\(\'"])\.\.\/([^"\'())]+)([\'"\)])/';
        $replace = '$1../' . $prefix . '$2$3';
        $content = preg_replace($pattern, $replace, $content);

        return $content;
    }

    /**
     * @param string $content
     * @param string $prefix
     *
     * @return mixed
     */
    private function replaceCustomPrefixCalls($content, $prefix)
    {
        $pattern = '/(%prefix%)/';
        $content = preg_replace($pattern, $prefix, $content);

        return $content;
    }
}

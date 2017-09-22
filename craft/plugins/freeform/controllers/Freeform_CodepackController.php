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

use Solspace\Freeform\Library\Codepack\CodePack;
use Solspace\Freeform\Library\Codepack\Exceptions\FileObject\FileObjectException;
use Solspace\Freeform\Library\Codepack\Exceptions\Manifest\ManifestNotPresentException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_CodepackController extends BaseController
{
    const FLASH_VAR_KEY = 'codepack_prefix';

    public function init()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);
        
        parent::init();
    }

    /**
     * Show CodePack contents
     * Provide means to prefix the CodePack
     *
     * @throws HttpException
     */
    public function actionListContents()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $codePack = $this->getCodepack();

        $postInstallPrefix = craft()->userSession->getFlash(self::FLASH_VAR_KEY);
        if ($postInstallPrefix) {
            $this->renderTemplate(
                'freeform/codepack/_post_install',
                array(
                    'codePack' => $codePack,
                    'prefix'   => CodePack::getCleanPrefix($postInstallPrefix),
                )
            );
        }

        $this->renderTemplate(
            'freeform/codepack',
            array(
                'codePack' => $codePack,
                'prefix'   => 'freeform_demo',
            )
        );
    }

    /**
     * Perform the install feats
     *
     * @throws HttpException
     */
    public function actionInstall()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $this->requirePostRequest();

        $codePack = $this->getCodepack();
        $prefix   = craft()->request->getRequiredPost('prefix');

        $prefix = preg_replace('/[^a-zA-Z_0-9\/]/', '', $prefix);

        try {
            $codePack->install($prefix);
        } catch (FileObjectException $exception) {
            $this->renderTemplate(
                'freeform/codepack',
                array(
                    'codePack'         => $codePack,
                    'prefix'           => $prefix,
                    'exceptionMessage' => $exception->getMessage(),
                )
            );
        }

        craft()->userSession->setFlash('codepack_prefix', $prefix);

        $this->redirectToPostedUrl();
    }

    /**
     * @return CodePack
     * @throws HttpException
     */
    private function getCodepack()
    {
        try {
            $codePack = new CodePack(__DIR__ . '/../codepack');
        } catch (ManifestNotPresentException $exception) {
            $this->renderTemplate('freeform/codepack/_no_codepacks');

            return null;
        }

        return $codePack;
    }
}

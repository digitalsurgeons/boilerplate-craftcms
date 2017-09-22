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

use Solspace\Freeform\Library\Composer\Attributes\FormAttributes;
use Solspace\Freeform\Library\Composer\Composer;
use Solspace\Freeform\Library\Exceptions\Composer\ComposerException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;
use Solspace\Freeform\Library\Session\CraftRequest;
use Solspace\Freeform\Library\Session\CraftSession;
use Solspace\Freeform\Library\Translations\CraftTranslator;

class Freeform_FormsController extends Freeform_BaseController
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FORMS_ACCESS);

        $formService            = $this->getFormService();
        $forms                  = $formService->getAllForms();
        $totalSubmissionsByForm = $this->getSubmissionService()->getSubmissionCountByForm();

        craft()->templates->includeJsResource('freeform/js/cp/formIndex.js');

        $this->renderTemplate(
            'freeform/forms',
            [
                'forms'                  => $forms,
                'totalSubmissionsByForm' => $totalSubmissionsByForm,
            ]
        );
    }

    /**
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEdit(array $variables = [])
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FORMS_MANAGE);

        if (empty($variables['form'])) {
            $formId = isset($variables['formId']) ? $variables['formId'] : null;
            if ($formId) {
                /** @var Freeform_FormModel $form */
                $form = $this->getFormService()->getFormById($formId);

                if (!$form) {
                    throw new HttpException(404, Craft::t('Form with ID {id} not found', ['id' => $formId]));
                }

                $title = $form->name;
            } else {
                $form  = Freeform_FormModel::create();
                $title = Craft::t('Create a new form');
            }
        } else {
            /** @var Freeform_FormModel $form */
            $form   = $variables['form'];
            $formId = $form->id;
            $title  = Craft::t('Editing: {title}', ['title' => $form->name]);
        }

        craft()->templates->includeJsResource('freeform/js/composer/vendors.js');
        craft()->templates->includeJsResource('freeform/js/composer/app.js');
        craft()->templates->includeCssResource('freeform/css/builder.css');

        $notifications           = $this->getNotificationService()->getAllNotifications(false);
        $mailingListIntegrations = $this->getMailingListsService()->getAllIntegrations();
        $crmIntegrations         = $this->getCRMService()->getAllIntegrations();

        $templateVariables = [
            'form'                     => $form,
            'title'                    => $title,
            'continueEditingUrl'       => 'freeform/forms/{id}',
            'crumbs'                   => [
                ['label' => 'Freeform', 'url' => UrlHelper::getUrl('freeform')],
                ['label' => Craft::t('Forms'), 'url' => UrlHelper::getUrl('freeform/forms')],
                [
                    'label' => $title,
                    'url'   => UrlHelper::getUrl(
                        'freeform/forms/' . ($formId ?: 'new')
                    ),
                ],
            ],
            'fileKinds'                => $this->getEncodedJson(IOHelper::getFileKinds()),
            'fieldTypeList'            => $this->getEncodedJson($this->getFieldsService()->getFieldTypes()),
            'notificationList'         => $this->getEncodedJson($notifications),
            'mailingList'              => $this->getEncodedJson($mailingListIntegrations),
            'crmIntegrations'          => $this->getEncodedJson($crmIntegrations),
            'fieldList'                => $this->getEncodedJson($this->getFieldsService()->getAllFields(false)),
            'statuses'                 => $this->getEncodedJson($this->getStatusesService()->getAllStatuses(false)),
            'solspaceFormTemplates'    => $this->getEncodedJson(
                $this->getSettingsService()->getSolspaceFormTemplates()
            ),
            'formTemplates'            => $this->getEncodedJson($this->getSettingsService()->getCustomFormTemplates()),
            'assetSources'             => $this->getEncodedJson($this->getFilesService()->getAssetSources()),
            'showTutorial'             => $this->getSettingsService()->getSettingsModel()->showTutorial,
            'defaultTemplates'         => $this->getSettingsService()->getSettingsModel()->defaultTemplates,
            'canManageFields'          => PermissionsHelper::checkPermission(
                PermissionsHelper::PERMISSION_FIELDS_MANAGE
            ),
            'canManageNotifications'   => PermissionsHelper::checkPermission(
                PermissionsHelper::PERMISSION_NOTIFICATIONS_MANAGE
            ),
            'canManageSettings'        => PermissionsHelper::checkPermission(
                PermissionsHelper::PERMISSION_SETTINGS_ACCESS
            ),
            'isDbEmailTemplateStorage' => $this->getSettingsService()->isDbEmailTemplateStorage(),
            'isWidgetsInstalled'       => craft()->plugins->getPlugin('freeformwidgets') !== null,
        ];

        $this->renderTemplate('freeform/forms/edit', $templateVariables);
    }

    /**
     * @throws Exception
     * @throws \Craft\HttpException
     * @throws \Exception
     */
    public function actionSave()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FORMS_MANAGE);

        $post = craft()->request->getPost();
        if (!isset($post['formId'])) {
            throw new Exception('No form ID specified');
        }

        if (!isset($post['composerState'])) {
            throw new Exception('No composer data present');
        }

        $formId        = $post['formId'];
        $form          = $this->getNewOrExistingForm($formId);
        $composerState = json_decode($post['composerState'], true);

        if (craft()->request->getPost('duplicate', false)) {
            $oldHandle = $composerState['composer']['properties']['form']['handle'];

            if (preg_match('/^([a-zA-Z0-9]*[a-zA-Z]+)(\d+)$/', $oldHandle, $matches)) {
                list($string, $mainPart, $iterator) = $matches;

                $newHandle = $mainPart . ((int)$iterator + 1);
            } else {
                $newHandle = $oldHandle . '1';
            }

            $composerState['composer']['properties']['form']['handle'] = $newHandle;
        }

        try {
            $formAttributes = new FormAttributes($formId, new CraftSession(), new CraftRequest());
            $composer       = new Composer(
                $composerState,
                $formAttributes,
                craft()->freeform_forms,
                $this->getSubmissionService(),
                craft()->freeform_mailer,
                craft()->freeform_files,
                craft()->freeform_mailingLists,
                craft()->freeform_crm,
                craft()->freeform_statuses,
                new CraftTranslator()
            );
        } catch (ComposerException $exception) {
            $this->returnJson(
                [
                    'success' => false,
                    'errors'  => [$exception->getMessage()],
                ]
            );

            return;
        }

        $form->setLayout($composer);

        if ($this->getFormService()->save($form)) {
            $this->returnJson(
                [
                    'success' => true,
                    'id'      => $form->id,
                    'handle'  => $form->handle,
                ]
            );
        }

        $this->returnJson(['success' => false, 'errors' => $form->getAllErrors()]);
    }

    /**
     * Deletes a form
     *
     * @throws \Craft\HttpException
     * @throws \Exception
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FORMS_MANAGE);

        $formId = craft()->request->getRequiredPost('id');

        $this->getFormService()->deleteById($formId);
        $this->returnJson(['success' => true]);
    }

    /**
     * Resets the spam counter for a specific form
     *
     * @return bool
     * @throws \Craft\HttpException
     */
    public function actionResetSpamCounter()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();

        $formId = (int)craft()->request->getRequiredPost('formId');

        if (!$formId) {
            $this->returnErrorJson(Craft::t('No form ID specified'));
        }

        try {
            craft()
                ->db
                ->createCommand()
                ->update(
                    Freeform_FormRecord::TABLE,
                    ['spamBlockCount' => 0],
                    'id = :formId',
                    ['formId' => $formId]
                );
        } catch (\Exception $e) {
            $this->returnErrorJson($e->getMessage());
        }

        $this->returnJson(['success' => true]);
    }

    /**
     * @param int $formId
     *
     * @return Freeform_FormModel
     * @throws Exception
     */
    private function getNewOrExistingForm($formId)
    {
        if ($formId) {
            $form = $this->getFormService()->getFormById($formId);

            if (!$form) {
                throw new Exception(Craft::t('Form with ID {id} not found', ['id' => $formId]));
            }
        } else {
            $form = Freeform_FormModel::create();
        }

        return $form;
    }
}

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

use Solspace\Freeform\Library\Helpers\PermissionsHelper;
use Solspace\Freeform\Library\Integrations\MailingLists\MailingListOAuthConnector;

class Freeform_MailingListsController extends Freeform_BaseController
{
    /**
     * Make sure this controller requires a logged in member
     */
    public function init()
    {
        $this->requireLogin();
    }

    /**
     * Presents a list of all mailing list integrations
     */
    public function actionIndex()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $mailingListIntegrations = $this->getMailingListsService()->getAllModels();

        craft()->templates->includeCssResource('freeform/css/integrations.css');

        $this->renderTemplate(
            'freeform/settings/_mailing_lists',
            [
                'integrations' => $mailingListIntegrations,
                'providers'    => $this->getMailingListsService()->getAllMailingListServiceProviders(),
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
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        if (empty($variables['integration'])) {
            $handle = isset($variables['handle']) ? $variables['handle'] : null;
            if ($handle) {

                $model = $this
                    ->getMailingListsService()
                    ->getModelByHandle($handle);

                if (!$model) {
                    throw new HttpException(
                        404,
                        Craft::t(
                            "Mailing list with handle '{handle}' not found",
                            ['handle' => $handle]
                        )
                    );
                }

                $title = $model->name;
            } else {
                $model = Freeform_IntegrationModel::create(Freeform_IntegrationRecord::TYPE_MAILING_LIST);
                $title = Craft::t('Add a Mailing List integration');
            }
        } else {
            /** @var Freeform_IntegrationModel $model */
            $model  = $variables['integration'];
            $handle = $model->handle;
            $title  = $model->name;
        }

        if (craft()->request->getParam('code')) {
            $this->handleAuthorization($model);
        }

        $serviceProviderTypes = $this->getMailingListsService()->getAllMailingListServiceProviders();
        $settingBlueprints    = $this->getMailingListsService()->getAllMailingListSettingBlueprints();

        craft()->templates->includeCssResource('freeform/css/integrations.css');
        craft()->templates->includeJsResource('freeform/js/cp/integrations.js');

        $variables = array_merge(
            $variables,
            [
                'integration'          => $model,
                'blockTitle'           => $title,
                'serviceProviderTypes' => $serviceProviderTypes,
                'continueEditingUrl'   => 'freeform/settings/mailing-lists/{handle}',
                'settings'             => $settingBlueprints,
                'crumbs'               => [
                    ['label' => 'Freeform', 'url' => UrlHelper::getUrl('freeform')],
                    ['label' => Craft::t('Settings'), 'url' => UrlHelper::getUrl('freeform/settings/mailing-lists')],
                    [
                        'label' => $title,
                        'url'   => UrlHelper::getUrl(
                            'freeform/settings/mailing-lists/' . ($handle ? $handle : 'new')
                        ),
                    ],
                ],
            ]
        );

        $this->renderTemplate('freeform/settings/_mailing_list_edit', $variables);
    }

    /**
     * Saves an integration
     */
    public function actionSave()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $this->requirePostRequest();

        $post = craft()->request->getPost();

        $handle = isset($post['handle']) ? $post['handle'] : null;
        $model  = $this->getNewOrExistingMailingListIntegrationModel($handle);

        $isNewIntegration = !$model->id;

        $postedClass  = $post['class'];
        $model->class = $postedClass;

        $postedClassSettings = isset($post['settings'][$postedClass]) ? $post['settings'][$postedClass] : [];
        unset($post['settings']);

        $settingBlueprints = $this->getMailingListsService()->getMailingListSettingBlueprints($postedClass);

        foreach ($postedClassSettings as $key => $value) {
            $isValueValid = false;

            foreach ($settingBlueprints as $blueprint) {
                if ($blueprint->getHandle() === $key) {
                    $isValueValid = true;

                    if (!$value && $blueprint->isRequired()) {
                        $model->addError($postedClass . $key, Craft::t('This field is required'));
                    }

                    break;
                }
            }

            if (!$isValueValid) {
                unset($postedClassSettings[$key]);
            }
        }

        // Adding hidden stored settings to the list
        foreach ($model->getIntegrationObject()->getSettings() as $key => $value) {
            if (!isset($postedClassSettings[$key])) {
                $postedClassSettings[$key] = $value;
            }
        }

        $post['settings'] = $postedClassSettings ?: null;

        $model->setAttributes($post);
        $model->getIntegrationObject()->onBeforeSave($model);

        if ($this->getMailingListsService()->save($model)) {

            // If it's a new integration - we make the user complete OAuth2 authentication
            if ($isNewIntegration) {
                $model->getIntegrationObject()->initiateAuthentication();
            }

            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => true]);
            } else {
                craft()->userSession->setNotice(Craft::t('Mailing List Integration saved'));
                craft()->userSession->setFlash('Mailing List Integration saved', true);
                $this->redirectToPostedUrl($model);
            }
        } else {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => false]);
            } else {
                craft()->userSession->setError(Craft::t('Mailing List Integration not saved'));

                // Send the event back to the template
                craft()->urlManager->setRouteVariables(
                    [
                        'integration' => $model,
                        'errors'      => $model->getErrors(),
                    ]
                );
            }
        }
    }

    /**
     * Checks integration connection
     */
    public function actionCheckIntegrationConnection()
    {
        $this->requireAjaxRequest();
        $id = craft()->request->getPost('id');

        $integration = $this->getMailingListsService()->getIntegrationById($id);

        if ($integration->checkConnection()) {
            $this->returnJson(['success' => true]);
        } else {
            $this->returnJson(['success' => false]);
        }
    }

    /**
     * Checks integration connection
     *
     * @param array $variables
     *
     * @throws Exception
     */
    public function actionForceAuthorization(array $variables = [])
    {
        $handle = isset($variables['handle']) ? $variables['handle'] : null;
        $model  = $this->getMailingListsService()->getModelByHandle($handle);

        if (!$model) {
            throw new Exception(
                Craft::t("Mailing list with handle '{handle}' not found", ['handle' => $handle])
            );
        }

        $integration = $model->getIntegrationObject();

        $integration->initiateAuthentication();
    }

    /**
     * Deletes a mailing integration
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $id = craft()->request->getRequiredPost('id');

        $this->getMailingListsService()->delete($id);
        $this->returnJson(['success' => true]);
    }

    /**
     * @param string $handle
     *
     * @return Freeform_IntegrationModel
     */
    private function getNewOrExistingMailingListIntegrationModel($handle)
    {
        $mailingListIntegration = $this->getMailingListsService()->getModelByHandle($handle);

        if (!$mailingListIntegration) {
            $mailingListIntegration = Freeform_IntegrationModel::create(Freeform_IntegrationRecord::TYPE_MAILING_LIST);
        }

        return $mailingListIntegration;
    }

    /**
     * Handle OAuth2 authorization
     *
     * @param Freeform_IntegrationModel $model
     */
    private function handleAuthorization(Freeform_IntegrationModel $model)
    {
        $integration = $model->getIntegrationObject();
        $code        = craft()->request->getParam('code');

        if (!$integration instanceof MailingListOAuthConnector || empty($code)) {
            return;
        }

        $accessToken = $integration->fetchAccessToken();

        $model->accessToken = $accessToken;
        $model->settings    = $integration->getSettings();

        if ($this->getMailingListsService()->save($model)) {
            // Return JSON response if the request is an AJAX request
            craft()->userSession->setNotice(Craft::t('Mailing List Integration saved'));
            craft()->userSession->setFlash(Craft::t('Mailing List Integration saved'), true);

            craft()->request->redirect(UrlHelper::getCpUrl('freeform/settings/mailing-lists/' . $model->handle));
        } else {
            craft()->userSession->setError(Craft::t('Mailing List Integration not saved'));

            craft()->request->redirect(UrlHelper::getCpUrl('freeform/settings/mailing-lists/' . $model->handle));
        }
    }
}

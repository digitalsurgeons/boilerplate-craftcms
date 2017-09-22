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

use Solspace\Freeform\Library\Exceptions\FreeformException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_SettingsController extends Freeform_BaseController
{
    /**
     * Make sure this controller requires a logged in member
     */
    public function init()
    {
        $this->requireLogin();
    }

    /**
     * Redirects to the default selected view
     */
    public function actionDefaultView()
    {
        $defaultView = $this->getSettingsModel()->defaultView;

        $canAccessForms = PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_FORMS_ACCESS);
        $canAccessSubmissions = PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_SUBMISSIONS_ACCESS);
        $canAccessFields = PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_FIELDS_ACCESS);
        $canAccessNotifications = PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_ACCESS);
        $canAccessSettings = PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $isFormView = $defaultView === FreeformPlugin::VIEW_FORMS;
        $isSubmissionView = $defaultView === FreeformPlugin::VIEW_SUBMISSIONS;

        if (($isFormView && !$canAccessForms) || ($isSubmissionView && !$canAccessSubmissions)) {
            if ($canAccessForms) {
                $this->redirect(UrlHelper::getCpUrl("freeform/" . FreeformPlugin::VIEW_FORMS));
            }

            if ($canAccessSubmissions) {
                $this->redirect(UrlHelper::getCpUrl("freeform/" . FreeformPlugin::VIEW_SUBMISSIONS));
            }

            if ($canAccessFields) {
                $this->redirect(UrlHelper::getCpUrl("freeform/" . FreeformPlugin::VIEW_FIELDS));
            }

            if ($canAccessNotifications) {
                $this->redirect(UrlHelper::getCpUrl("freeform/" . FreeformPlugin::VIEW_NOTIFICATIONS));
            }

            if ($canAccessSettings) {
                $this->redirect(UrlHelper::getCpUrl("freeform/" . FreeformPlugin::VIEW_SETTINGS));
            }
        }

        $this->redirect(UrlHelper::getCpUrl("freeform/$defaultView"));
    }

    /**
     * Attempt cloning a demo template into the user's specified template directory
     */
    public function actionAddDemoTemplate()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $this->requirePostRequest();

        $errors    = [];
        $settings  = $this->getSettingsModel();
        $extension = ".html";

        $templateDirectory = $settings->getAbsoluteFormTemplateDirectory();
        $templateName      = craft()->request->getPost("templateName", null);

        if (!$templateDirectory) {
            $errors[] = Craft::t("No custom template directory specified in settings");
        } else {
            if ($templateName) {
                $templateName = StringHelper::toSnakeCase($templateName);

                $templatePath = $templateDirectory . "/" . $templateName . $extension;
                if (file_exists($templatePath)) {
                    $errors[] = Craft::t("Template '{name}' already exists", ["name" => $templateName . $extension]);
                } else {
                    try {
                        IOHelper::writeToFile($templatePath, $settings->getDemoTemplateContent());
                    } catch (FreeformException $exception) {
                        $errors[] = $exception->getMessage();
                    }
                }
            } else {
                $errors[] = Craft::t("No template name specified");
            }
        }

        $this->returnJson(
            [
                "templateName" => $templateName . $extension,
                "errors"       => $errors,
            ]
        );
    }

    /**
     * Attempt cloning a demo email template into the user's specified template directory
     */
    public function actionAddEmailTemplate()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $this->requirePostRequest();

        $errors    = [];
        $settings  = $this->getSettingsModel();
        $extension = ".html";

        $templateDirectory = $settings->getAbsoluteEmailTemplateDirectory();
        $templateName      = craft()->request->getPost("templateName", null);

        if (!$templateDirectory) {
            $errors[] = Craft::t("No custom template directory specified in settings");
        } else {
            if ($templateName) {
                $templateName = StringHelper::toSnakeCase($templateName);

                $templatePath = $templateDirectory . "/" . $templateName . $extension;
                if (file_exists($templatePath)) {
                    $errors[] = Craft::t("Template '{name}' already exists", ["name" => $templateName . $extension]);
                } else {
                    try {
                        IOHelper::writeToFile($templatePath, $settings->getEmailTemplateContent());
                    } catch (FreeformException $exception) {
                        $errors[] = $exception->getMessage();
                    }
                }
            } else {
                $errors[] = Craft::t("No template name specified");
            }
        }

        $this->returnJson(
            [
                "templateName" => $templateName,
                "errors"       => $errors,
            ]
        );
    }

    /**
     * Renders the License settings page template
     */
    public function actionLicense()
    {
        $this->provideTemplate('license');
    }

    /**
     * Renders the General settings page template
     */
    public function actionGeneral()
    {
        $this->provideTemplate('general');
    }

    /**
     * Renders the General settings page template
     */
    public function actionFormattingTemplates()
    {
        craft()->templates->includeCssResource("freeform/css/code-pack.css");

        $this->provideTemplate('formatting_templates');
    }

    /**
     * Renders the General settings page template
     */
    public function actionEmailTemplates()
    {
        craft()->templates->includeCssResource("freeform/css/code-pack.css");

        $this->provideTemplate('email_templates');
    }

    /**
     * @throws HttpException
     */
    public function actionSaveSettings()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $this->requirePostRequest();
        $postData = craft()->request->getPost('settings', []);

        $plugin = craft()->plugins->getPlugin('freeform');
        if (craft()->plugins->savePluginSettings($plugin, $postData)) {
            craft()->userSession->setNotice(Craft::t("Settings Saved"));
            $this->redirectToPostedUrl();
        } else {
            craft()->userSession->setError(Craft::t("Settings not saved"));
        }
    }

    /**
     * Determines which template has to be rendered based on $template
     * Adds a Freeform_SettingsModel to template variables
     *
     * @param string $template
     *
     * @throws HttpException
     */
    private function provideTemplate($template)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $this->renderTemplate(
            'freeform/settings/_' . $template,
            [
                'settings' => $this->getSettingsModel(),
            ]
        );
    }

    /**
     * @return Freeform_SettingsModel
     */
    private function getSettingsModel()
    {
        /** @var Freeform_SettingsService $settingsService */
        $settingsService = craft()->freeform_settings;

        return $settingsService->getSettingsModel();
    }
}

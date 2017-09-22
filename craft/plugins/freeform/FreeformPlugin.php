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

use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

require_once __DIR__ . '/vendor/autoload.php';

class FreeformPlugin extends BasePlugin
{
    const EVENT_BEFORE_SAVE   = 'onBeforeSave';
    const EVENT_AFTER_SAVE    = 'onAfterSave';
    const EVENT_BEFORE_DELETE = 'onBeforeDelete';
    const EVENT_AFTER_DELETE  = 'onAfterDelete';
    const EVENT_BEFORE_UPLOAD = 'onBeforeUpload';
    const EVENT_AFTER_UPLOAD  = 'onAfterUpload';
    const EVENT_BEFORE_SEND   = 'onBeforeSend';
    const EVENT_AFTER_SEND    = 'onAfterSend';

    const VIEW_FORMS           = 'forms';
    const VIEW_SUBMISSIONS     = 'submissions';
    const VIEW_FIELDS          = 'fields';
    const VIEW_NOTIFICATIONS   = 'notifications';
    const VIEW_EXPORT_PROFILES = 'exportProfiles';
    const VIEW_SETTINGS        = 'settings';

    const FIELD_DISPLAY_ORDER_TYPE = 'type';
    const FIELD_DISPLAY_ORDER_NAME = 'name';

    const PERMISSIONS_HELP_LINK = 'https://solspace.com/craft/freeform/docs/demo-templates';

    /** @var string */
    private $version = '1.6.1';

    /** @var string */
    private $schemaVersion = '1.0.10';

    /** @var string */
    private $pluginName = 'Freeform';

    /** @var string */
    private $pluginDescription = 'A powerful form building plugin that gives you full control to create simple or complex forms.';

    /** @var string */
    private $developer = 'Solspace';

    /** @var string */
    private $developerUrl = 'https://solspace.com/craft';

    /** @var string */
    private $documentationUrl = 'https://solspace.com/craft/freeform/docs';

    /** @var string */
    private $releaseFeedUrl = 'https://solspace.com/craft/updates/freeform.json';

    /**
     * Includes CSS and JS files
     * Registers custom class auto-loader
     */
    public function init()
    {
        parent::init();

        // Perform unfinalized asset cleanup
        craft()->freeform_files->cleanUpUnfinalizedAssets();

        if (craft()->request->isCpRequest() && craft()->userSession->isLoggedIn()) {
            craft()->templates->includeCssResource('freeform/css/main.css');
            craft()->templates->hook('freeform.prepareCpTemplate', [$this, 'prepareCpTemplate']);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->pluginName;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->pluginDescription;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getSchemaVersion()
    {
        return $this->schemaVersion;
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return $this->developerUrl;
    }

    /**
     * @return string|null
     */
    public function getDocumentationUrl()
    {
        return $this->documentationUrl;
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return $this->releaseFeedUrl;
    }

    /**
     * @return string
     */
    public function getSettingsHtml()
    {
        return craft()->templates->render('freeform/settings');
    }

    /**
     * @return bool
     */
    public function hasSettings()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * @return array
     */
    public function registerUserPermissions()
    {
        /** @var Freeform_FormsService $formService */
        $formService = craft()->freeform_forms;
        $forms       = $formService->getAllForms();

        $submissionNestedPermissions = [
            PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE => ['label' => Craft::t('Manage All Submissions')],
        ];

        foreach ($forms as $form) {
            $permissionName                               = PermissionsHelper::prepareNestedPermission(
                PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE,
                $form->id
            );
            $submissionNestedPermissions[$permissionName] = ['label' => 'For ' . $form->name];
        }


        $permissions = [
            PermissionsHelper::PERMISSION_SUBMISSIONS_ACCESS   => [
                'label'  => Craft::t('Access Submissions'),
                'nested' => $submissionNestedPermissions,
            ],
            PermissionsHelper::PERMISSION_FORMS_ACCESS         => [
                'label'  => Craft::t('Access Forms'),
                'nested' => [
                    PermissionsHelper::PERMISSION_FORMS_MANAGE => ['label' => Craft::t('Manage Forms')],
                ],
            ],
            PermissionsHelper::PERMISSION_FIELDS_ACCESS        => [
                'label'  => Craft::t('Access Fields'),
                'nested' => [
                    PermissionsHelper::PERMISSION_FIELDS_MANAGE => ['label' => Craft::t('Manage Fields')],
                ],
            ],
            PermissionsHelper::PERMISSION_NOTIFICATIONS_ACCESS => [
                'label'  => Craft::t('Access Email Templates'),
                'nested' => [
                    PermissionsHelper::PERMISSION_NOTIFICATIONS_MANAGE => [
                        'label' => Craft::t(
                            'Manage Email Templates'
                        ),
                    ],
                ],
            ],
            PermissionsHelper::PERMISSION_EXPORT_PROFILES_ACCESS => [
                'label'  => Craft::t('Access Export Profiles'),
                'nested' => [
                    PermissionsHelper::PERMISSION_EXPORT_PROFILES_MANAGE => [
                        'label' => Craft::t(
                            'Manage Export Profiles'
                        ),
                    ],
                ],
            ],
            PermissionsHelper::PERMISSION_SETTINGS_ACCESS      => ['label' => Craft::t('Access Settings')],
        ];

        if (!class_exists('\Craft\Freeform_ExportProfilesController')) {
            unset($permissions[PermissionsHelper::PERMISSION_EXPORT_PROFILES_ACCESS]);
        }

        return $permissions;
    }

    /**
     * @return Freeform_SettingsModel
     */
    protected function getSettingsModel()
    {
        return new Freeform_SettingsModel();
    }

    /**
     * Prepares a CP template.
     *
     * @param mixed &$context The current template context
     */
    public function prepareCpTemplate(&$context)
    {
        $context['subnav'] = [];

        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_SUBMISSIONS_ACCESS)) {
            $context['subnav'][self::VIEW_SUBMISSIONS] = [
                'label' => Craft::t('Submissions'),
                'url'   => UrlHelper::getCpUrl('freeform/submissions'),
            ];
        }

        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_FORMS_ACCESS)) {
            $context['subnav'][self::VIEW_FORMS] = [
                'label' => Craft::t('Forms'),
                'url'   => UrlHelper::getCpUrl('freeform/forms'),
                'class' => 'icon-cog',
            ];
        }

        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_FIELDS_ACCESS)) {
            $context['subnav'][self::VIEW_FIELDS] = [
                'label' => Craft::t('Fields'),
                'url'   => UrlHelper::getCpUrl('freeform/fields'),
            ];
        }

        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_ACCESS)) {
            $context['subnav'][self::VIEW_NOTIFICATIONS] = [
                'label' => Craft::t('Email Notifications'),
                'url'   => UrlHelper::getCpUrl('freeform/notifications'),
            ];
        }

        $canEditProfiles = PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_EXPORT_PROFILES_ACCESS);
        if ($canEditProfiles && class_exists('\Craft\Freeform_ExportProfilesController')) {
            $context['subnav'][self::VIEW_EXPORT_PROFILES] = [
                'label' => Craft::t('Export'),
                'url'   => UrlHelper::getCpUrl('freeform/export-profiles'),
            ];
        }

        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS)) {
            $context['subnav'][self::VIEW_SETTINGS] = [
                'label' => Craft::t('Settings'),
                'url'   => UrlHelper::getCpUrl('freeform/settings'),
            ];
        }
    }

    /**
     * @return array
     */
    public function registerCpRoutes()
    {
        return [
            'freeform'                                            => ['action' => 'freeform/settings/defaultView'],
            // Settings
            'freeform/settings/license'                           => ['action' => 'freeform/settings/license'],
            'freeform/settings/general'                           => ['action' => 'freeform/settings/general'],
            'freeform/settings/formatting-templates'              => ['action' => 'freeform/settings/formattingTemplates'],
            'freeform/settings/email-templates'                   => ['action' => 'freeform/settings/emailTemplates'],
            'freeform/settings/addDemoTemplate'                   => ['action' => 'freeform/settings/addDemoTemplate'],
            'freeform/settings/addEmailTemplate'                  => ['action' => 'freeform/settings/addEmailTemplate'],
            'freeform/settings/demo-templates'                    => ['action' => 'freeform/codepack/listContents'],
            // Api
            'freeform/api/fields'                                 => ['action' => 'freeform/api/fields'],
            'freeform/api/notifications'                          => ['action' => 'freeform/api/notifications'],
            'freeform/api/formTemplates'                          => ['action' => 'freeform/api/formTemplates'],
            'freeform/api/mailing-lists'                          => ['action' => 'freeform/api/mailingLists'],
            'freeform/api/crm-integrations'                       => ['action' => 'freeform/api/crmIntegrations'],
            'freeform/api/quickCreateField'                       => ['action' => 'freeform/api/quickCreateField'],
            'freeform/api/quickCreateNotification'                => ['action' => 'freeform/api/quickCreateNotification'],
            'freeform/api/finish-tutorial'                        => ['action' => 'freeform/api/finishTutorial'],
            // Forms
            'freeform/forms'                                      => ['action' => 'freeform/forms/index'],
            'freeform/forms/new'                                  => ['action' => 'freeform/forms/edit'],
            'freeform/forms/(?P<formId>\d+)'                      => ['action' => 'freeform/forms/edit'],
            'freeform/forms/save'                                 => ['action' => 'freeform/forms/save'],
            'freeform/forms/delete'                               => ['action' => 'freeform/forms/delete'],
            // Fields
            'freeform/fields'                                     => ['action' => 'freeform/fields/index'],
            'freeform/fields/new'                                 => ['action' => 'freeform/fields/edit'],
            'freeform/fields/(?P<fieldId>\d+)'                    => ['action' => 'freeform/fields/edit'],
            'freeform/fields/save'                                => ['action' => 'freeform/fields/save'],
            'freeform/fields/delete'                              => ['action' => 'freeform/fields/delete'],
            // Statuses
            'freeform/settings/statuses'                          => ['action' => 'freeform/statuses/index'],
            'freeform/settings/statuses/new'                      => ['action' => 'freeform/statuses/edit'],
            'freeform/settings/statuses/(?P<statusId>\d+)'        => ['action' => 'freeform/statuses/edit'],
            'freeform/settings/statuses/save'                     => ['action' => 'freeform/statuses/save'],
            'freeform/settings/statuses/delete'                   => ['action' => 'freeform/statuses/delete'],
            // Notifications
            'freeform/notifications'                              => ['action' => 'freeform/notifications/index'],
            'freeform/notifications/new'                          => ['action' => 'freeform/notifications/edit'],
            'freeform/notifications/(?P<notificationId>\d+)'      => ['action' => 'freeform/notifications/edit'],
            'freeform/notifications/save'                         => ['action' => 'freeform/notifications/save'],
            'freeform/notifications/delete'                       => ['action' => 'freeform/notifications/delete'],
            // Submissions
            'freeform/submissions'                                => ['action' => 'freeform/submissions/index'],
            'freeform/submissions/export'                         => ['action' => 'freeform/submissions/export'],
            'freeform/submissions/(?P<submissionId>\d+)'          => ['action' => 'freeform/submissions/edit'],
            'freeform/submissions/save'                           => ['action' => 'freeform/submissions/save'],
            'freeform/submissions/delete'                         => ['action' => 'freeform/submissions/delete'],
            // Mailing Lists
            'freeform/settings/mailing-lists'                     => ['action' => 'freeform/mailingLists/index'],
            'freeform/settings/mailing-lists/new'                 => ['action' => 'freeform/mailingLists/edit'],
            'freeform/settings/mailing-lists/(?P<handle>\w+)'     => ['action' => 'freeform/mailingLists/edit'],
            'freeform/mailing-lists/authenticate/(?P<handle>\w+)' => ['action' => 'freeform/mailingLists/forceAuthorization'],
            'freeform/mailing_list/check'                         => ['action' => 'freeform/mailingLists/checkIntegrationConnection'],
            // CRM
            'freeform/settings/crm'                               => ['action' => 'freeform/crm/index'],
            'freeform/settings/crm/new'                           => ['action' => 'freeform/crm/edit'],
            'freeform/settings/crm/(?P<handle>\w+)'               => ['action' => 'freeform/crm/edit'],
            'freeform/crm/check'                                  => ['action' => 'freeform/crm/checkIntegrationConnection'],
            'freeform/crm/authenticate/(?P<handle>\w+)'           => ['action' => 'freeform/crm/forceAuthorization'],
            // Export
            'freeform/export/export-dialogue'                     => ['action' => 'freeform/export/exportDialogue'],
            'freeform/export'                                     => ['action' => 'freeform/export/index'],
            // Export Profiles
            'freeform/export-profiles'                            => ['action' => 'freeform/exportProfiles/index'],
            'freeform/export-profiles/delete'                     => ['action' => 'freeform/exportProfiles/delete'],
            'freeform/export-profiles/new/(?P<formHandle>[a-zA-Z_\-]+)'    => ['action' => 'freeform/exportProfiles/edit'],
            'freeform/export-profiles/(?P<profileId>\d+)'         => ['action' => 'freeform/exportProfiles/edit'],
        ];
    }

    /**
     * On install - insert default statuses
     *
     * @return void
     */
    public function onAfterInstall()
    {
        /** @var Freeform_FieldsService $fieldService */
        $fieldService = craft()->freeform_fields;

        $field         = Freeform_FieldModel::create();
        $field->handle = 'firstName';
        $field->label  = 'First Name';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'lastName';
        $field->label  = 'Last Name';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'email';
        $field->label  = 'Email';
        $field->type   = FieldInterface::TYPE_EMAIL;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'website';
        $field->label  = 'Website';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'cellPhone';
        $field->label  = 'Cell Phone';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'homePhone';
        $field->label  = 'Home Phone';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'companyName';
        $field->label  = 'Company Name';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'address';
        $field->label  = 'Address';
        $field->rows   = 2;
        $field->type   = FieldInterface::TYPE_TEXTAREA;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'city';
        $field->label  = 'City';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field          = Freeform_FieldModel::create();
        $field->handle  = 'state';
        $field->label   = 'State';
        $field->type    = FieldInterface::TYPE_SELECT;
        $field->options = include __DIR__ . '/resources/states.php';
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'zipCode';
        $field->label  = 'Zip Code';
        $field->type   = FieldInterface::TYPE_TEXT;
        $fieldService->save($field);

        $field         = Freeform_FieldModel::create();
        $field->handle = 'message';
        $field->label  = 'Message';
        $field->rows   = 5;
        $field->type   = FieldInterface::TYPE_TEXTAREA;
        $fieldService->save($field);

        /** @var Freeform_StatusesService $statusService */
        $statusService = craft()->freeform_statuses;

        $status            = Freeform_StatusModel::create();
        $status->name      = 'Pending';
        $status->handle    = 'pending';
        $status->color     = 'light';
        $status->sortOrder = 1;
        $statusService->save($status);

        $status            = Freeform_StatusModel::create();
        $status->name      = 'Open';
        $status->handle    = 'open';
        $status->color     = 'green';
        $status->sortOrder = 2;
        $status->isDefault = 1;
        $statusService->save($status);

        $status            = Freeform_StatusModel::create();
        $status->name      = 'Closed';
        $status->handle    = 'closed';
        $status->color     = 'grey';
        $status->sortOrder = 3;
        $statusService->save($status);
    }
}

/**
 * @return FreeformService
 */
function freeform()
{
    static $instance;

    if (null === $instance) {
        $instance = Craft::app()->getComponent('freeform');
    }

    return $instance;
}

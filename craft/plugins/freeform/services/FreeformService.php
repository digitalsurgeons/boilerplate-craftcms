<?php

namespace Craft;

class FreeformService extends BaseApplicationComponent
{
    /** @var Freeform_CrmService */
    public $crm;

    /** @var Freeform_ExportProfilesService */
    public $exportProfiles;

    /** @var Freeform_FieldsService */
    public $fields;

    /** @var Freeform_FilesService */
    public $files;

    /** @var Freeform_FormsService */
    public $forms;

    /** @var Freeform_MailerService */
    public $mailer;

    /** @var Freeform_MailingListsService */
    public $mailingLists;

    /** @var Freeform_NotificationsService */
    public $notifications;

    /** @var Freeform_SettingsService */
    public $settings;

    /** @var Freeform_StatusesService */
    public $statuses;

    /** @var Freeform_SubmissionsService */
    public $submissions;

    /** @var Freeform_WidgetsService */
    public $widgets;

    /**
     * Loads Calendar services
     */
    public function init()
    {
        parent::init();

        $this->crm            = Craft::app()->getComponent('freeform_crm');
        $this->exportProfiles = Craft::app()->getComponent('freeform_exportProfiles');
        $this->fields         = Craft::app()->getComponent('freeform_fields');
        $this->files          = Craft::app()->getComponent('freeform_files');
        $this->forms          = Craft::app()->getComponent('freeform_forms');
        $this->mailer         = Craft::app()->getComponent('freeform_mailer');
        $this->mailingLists   = Craft::app()->getComponent('freeform_mailingLists');
        $this->notifications  = Craft::app()->getComponent('freeform_notifications');
        $this->settings       = Craft::app()->getComponent('freeform_settings');
        $this->statuses       = Craft::app()->getComponent('freeform_statuses');
        $this->submissions    = Craft::app()->getComponent('freeform_submissions');
        $this->widgets        = Craft::app()->getComponent('freeform_widgets');
    }

}

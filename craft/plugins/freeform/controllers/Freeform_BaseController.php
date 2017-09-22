<?php

namespace Craft;

class Freeform_BaseController extends BaseController
{
    /**
     * @return Freeform_FormsService
     */
    protected function getFormService()
    {
        return freeform()->forms;
    }

    /**
     * @return Freeform_FieldsService
     */
    protected function getFieldsService()
    {
        return freeform()->fields;
    }

    /**
     * @return Freeform_FilesService
     */
    protected function getFilesService()
    {
        return freeform()->files;
    }

    /**
     * @return Freeform_NotificationsService
     */
    protected function getNotificationService()
    {
        return freeform()->notifications;
    }

    /**
     * @return Freeform_MailingListsService
     */
    protected function getMailingListsService()
    {
        return freeform()->mailingLists;
    }

    /**
     * @return Freeform_CrmService
     */
    protected function getCRMService()
    {
        return freeform()->crm;
    }

    /**
     * @return Freeform_StatusesService
     */
    protected function getStatusesService()
    {
        return freeform()->statuses;
    }

    /**
     * @return Freeform_SettingsService
     */
    protected function getSettingsService()
    {
        return freeform()->settings;
    }

    /**
     * @return Freeform_SubmissionsService
     */
    protected function getSubmissionService()
    {
        return freeform()->submissions;
    }

    /**
     * @return Freeform_ExportProfilesService
     */
    protected function getExportProfileService()
    {
        return freeform()->exportProfiles;
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function getEncodedJson($data)
    {
        return json_encode($data, JSON_OBJECT_AS_ARRAY);
    }
}

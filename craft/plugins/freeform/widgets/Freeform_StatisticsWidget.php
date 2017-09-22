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

use Solspace\Freeform\Library\DataObjects\FreeformStatistics;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_StatisticsWidget extends BaseWidget
{
    /**
     * @return bool
     */
    public function isSelectable()
    {
        // This widget is only available to users that can manage submissions
        return craft()->userSession->checkPermission(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('Freeform Statistics');
    }

    /**
     * @return string
     */
    public function getIconPath()
    {
        return craft()->path->getPluginsPath() . 'freeform/resources/icon-mask.svg';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return parent::getTitle();
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        $settings = $this->getSettings();
        $forms    = $this->getFormService()->getAllForms();

        $selectedStatusIds = $settings->statusIds;
        if ($selectedStatusIds === "*") {
            $selectedStatusIds = null;
        }

        $selectedFormIds = $settings->formIds;
        if ($selectedFormIds === "*") {
            $selectedFormIds = null;
        }

        $formStatistics  = [];
        if (null !== $selectedFormIds) {
            foreach ($forms as $form) {
                if (in_array($form->id, $selectedFormIds)) {
                    $submissionCount = $this->getSubmissionService()->getSubmissionCount(
                        [$form->id],
                        $selectedStatusIds
                    );

                    $formStatistics[] = [
                        'label'      => $form->name,
                        'statistics' => new FreeformStatistics($submissionCount, $form->spamBlockCount),
                    ];
                }
            }
        }

        if (empty($selectedFormIds)) {
            $submissionCount = $this->getSubmissionService()->getSubmissionCount(null, $selectedStatusIds);
            $spamBlockCount  = 0;
            foreach ($forms as $form) {
                $spamBlockCount += $form->spamBlockCount;
            }

            $formStatistics[] = [
                'statistics' => new FreeformStatistics($submissionCount, $spamBlockCount),
            ];
        }

        $fieldCount        = null;
        $formCount         = null;
        $notificationCount = null;
        if ($settings->showGlobalStatistics) {
            $fieldCount        = count($this->getFieldService()->getAllFieldHandles());
            $formCount         = count($forms);
            $notificationCount = count($this->getNotificationService()->getAllNotifications());
        }

        craft()->templates->includeCssResource('freeform/css/widgets/statistics.css');

        return craft()->templates->render(
            'freeform/_widgets/statistics/body',
            [
                'formStatistics'       => $formStatistics,
                'showGlobalStatistics' => $settings->showGlobalStatistics,
                'fieldCount'           => $fieldCount,
                'formCount'            => $formCount,
                'notificationCount'    => $notificationCount,
            ]
        );
    }

    /**
     * @return string
     */
    public function getSettingsHtml()
    {
        $statuses   = $this->getStatusService()->getAllStatuses();
        $statusList = [];
        foreach ($statuses as $status) {
            $statusList[$status->id] = $status->name;
        }

        $forms    = $this->getFormService()->getAllForms();
        $formList = [];
        foreach ($forms as $form) {
            $formList[$form->id] = $form->name;
        }

        return craft()->templates->render(
            'freeform/_widgets/statistics/settings',
            [
                'settings'   => $this->getSettings(),
                'statusList' => $statusList,
                'formList'   => $formList,
            ]
        );
    }

    /**
     * @inheritDoc BaseSavableComponentType::defineSettings()
     *
     * @return array
     */
    protected function defineSettings()
    {
        return [
            'formIds'              => [AttributeType::Mixed, 'required' => true],
            'statusIds'            => [AttributeType::Mixed, 'required' => true],
            'showGlobalStatistics' => AttributeType::Bool,
        ];
    }

    /**
     * @return Freeform_StatusesService
     */
    private function getStatusService()
    {
        return craft()->freeform_statuses;
    }

    /**
     * @return Freeform_FormsService
     */
    private function getFormService()
    {
        return craft()->freeform_forms;
    }

    /**
     * @return Freeform_SubmissionsService
     */
    private function getSubmissionService()
    {
        return craft()->freeform_submissions;
    }

    /**
     * @return Freeform_FieldsService
     */
    private function getFieldService()
    {
        return craft()->freeform_fields;
    }

    /**
     * @return Freeform_NotificationsService
     */
    private function getNotificationService()
    {
        return craft()->freeform_notifications;
    }
}

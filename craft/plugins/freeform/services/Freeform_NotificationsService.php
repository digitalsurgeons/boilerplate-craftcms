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

use Markdownify\Converter;
use Solspace\Freeform\Library\DataObjects\EmailNotificationTemplate;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_NotificationsService extends BaseApplicationComponent
{
    /** @var Freeform_NotificationModel[] */
    private static $notificationCache;

    /** @var bool */
    private static $allNotificationsLoaded;


    /**
     * @param bool $indexById
     *
     * @return Freeform_NotificationModel[]
     */
    public function getAllNotifications($indexById = true)
    {
        if (is_null(self::$notificationCache) || !self::$allNotificationsLoaded) {
            $notificationRecords = Freeform_NotificationRecord::model()->ordered()->findAll();
            self::$notificationCache = Freeform_NotificationModel::populateModels(
                $notificationRecords,
                $indexById ? "id" : null
            );

            /** @var Freeform_SettingsModel $settings */
            $settings = craft()->freeform_settings->getSettingsModel();
            foreach ($settings->listTemplatesInEmailTemplateDirectory() as $filePath => $name) {
                $model = Freeform_NotificationModel::createFromTemplate($filePath);
                self::$notificationCache[$model->id] = $model;
            }

            self::$allNotificationsLoaded = true;
        }

        return self::$notificationCache;
    }

    /**
     * @param int $id
     *
     * @return Freeform_NotificationModel
     */
    public function getNotificationById($id)
    {
        if (is_null(self::$notificationCache) || !isset(self::$notificationCache[$id])) {
            if (is_numeric($id)) {
                $notificationRecord = Freeform_NotificationRecord::model()->findById($id);
            } else {
                $notificationRecord = Freeform_NotificationRecord::model()->findByAttributes(
                    [
                        "handle" => $id,
                    ]
                );
            }

            self::$notificationCache[$id] = null;

            if ($notificationRecord) {
                self::$notificationCache[$id] = Freeform_NotificationModel::populateModel($notificationRecord);
            } else {

                /** @var Freeform_SettingsModel $settings */
                $settings = craft()->freeform_settings->getSettingsModel();
                foreach ($settings->listTemplatesInEmailTemplateDirectory() as $filePath => $name) {
                    if ($id === $name) {
                        $model = Freeform_NotificationModel::createFromTemplate($filePath);
                        self::$notificationCache[$id] = $model;
                    }
                }
            }
        }

        return self::$notificationCache[$id];
    }

    /**
     * @param Freeform_NotificationModel $notification
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_NotificationModel $notification)
    {
        $isNewNotification = !$notification->id;

        if (!$isNewNotification) {
            $notificationRecord = Freeform_NotificationRecord::model()->findById($notification->id);

            if (!$notificationRecord) {
                throw new Exception(Craft::t("Notification with ID {id} not found", ["id" => $notification->id]));
            }
        } else {
            $notificationRecord = new Freeform_NotificationRecord();
        }

        $markdownify = new Converter();

        // Replace all &nbsp; occurrences with a blank space, since it might mess up
        // Twig parsing. These non-breakable spaces are caused by the HTML editor
        $notification->bodyHtml = str_replace("&nbsp;", " ", $notification->bodyHtml);
        $notification->bodyText = $markdownify->parseString($notification->bodyHtml);

        $beforeSaveEvent = $this->onBeforeSave($notification, $isNewNotification);

        $notificationRecord->name = $notification->name;
        $notificationRecord->handle = $notification->handle;
        $notificationRecord->description = $notification->description;
        $notificationRecord->fromName = $notification->fromName;
        $notificationRecord->fromEmail = $notification->fromEmail;
        $notificationRecord->replyToEmail = $notification->replyToEmail;
        $notificationRecord->includeAttachments = $notification->includeAttachments;
        $notificationRecord->subject = $notification->subject;
        $notificationRecord->bodyHtml = $notification->bodyHtml;
        $notificationRecord->bodyText = $notification->bodyText;
        $notificationRecord->sortOrder = $notification->sortOrder;

        $notificationRecord->validate();
        $notification->addErrors($notificationRecord->getErrors());

        if ($beforeSaveEvent->performAction && !$notification->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $notificationRecord->save(false);

                if (!$notification->id) {
                    $notification->id = $notificationRecord->id;
                }

                self::$notificationCache[$notification->id] = $notification;

                if ($transaction !== null) {
                    $transaction->commit();
                }

                $this->onAfterSave($notification, $isNewNotification);

                return true;
            } catch (\Exception $e) {
                if ($transaction !== null) {
                    $transaction->rollback();
                }

                throw $e;
            }
        }

        return false;
    }

    /**
     * @param int $notificationId
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteById($notificationId)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_MANAGE);

        $notificationModel = $this->getNotificationById($notificationId);

        if (!$notificationModel) {
            return false;
        }

        if (!$this->onBeforeDelete($notificationModel)->performAction) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {
            $affectedRows = craft()->db
                ->createCommand()
                ->delete('freeform_notifications', ['id' => $notificationModel->id]);

            if ($transaction !== null) {
                $transaction->commit();
            }

            $this->onAfterDelete($notificationModel);

            return (bool)$affectedRows;
        } catch (\Exception $exception) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $exception;
        }
    }

    /**
     * @param Freeform_NotificationModel $model
     * @param bool $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_NotificationModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_NotificationModel $model
     * @param bool $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_NotificationModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_NotificationModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_NotificationModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_NotificationModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_NotificationModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}

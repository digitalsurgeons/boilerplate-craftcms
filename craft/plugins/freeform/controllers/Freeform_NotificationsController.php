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

class Freeform_NotificationsController extends Freeform_BaseController
{
    public function actionIndex()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_ACCESS);

        $notifications = $this->getNotificationService()->getAllNotifications();

        $this->renderTemplate(
            "freeform/notifications",
            [
                "notifications" => $notifications,
                "settings"      => craft()->freeform_settings->getSettingsModel(),
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
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_MANAGE);

        if (empty($variables["notification"])) {
            $notificationId = isset($variables["notificationId"]) ? $variables["notificationId"] : null;
            if ($notificationId) {
                /** @var Freeform_NotificationModel $notification */
                $notification = $this->getNotificationService()->getNotificationById($notificationId);

                if (!$notification) {
                    throw new HttpException(
                        404,
                        Craft::t("Notification with ID {id} not found", ["id" => $notificationId])
                    );
                }

                $title = $notification->name;
            } else {
                $notification = Freeform_NotificationModel::create();
                $title        = Craft::t("Create a new email notification template");
            }
        } else {
            /** @var Freeform_NotificationModel $notification */
            $notification   = $variables["notification"];
            $notificationId = $notification->id;
            $title          = $notification->name;
        }

        $variables = array_merge(
            $variables,
            [
                "notification"       => $notification,
                "title"              => $title,
                "continueEditingUrl" => "freeform/notifications/{id}",
                "crumbs"             => [
                    ["label" => "Freeform", "url" => UrlHelper::getUrl("freeform")],
                    ["label" => Craft::t("Notifications"), "url" => UrlHelper::getUrl("freeform/notifications")],
                    [
                        "label" => $title,
                        "url"   => UrlHelper::getUrl(
                            "freeform/notifications/" . ($notificationId ? $notificationId : "new")
                        ),
                    ],
                ],
            ]
        );

        $this->renderTemplate("freeform/notifications/edit", $variables);
    }

    /**
     * @throws Exception
     */
    public function actionSave()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_MANAGE);

        $post = craft()->request->getPost();

        $notificationId = isset($post["notificationId"]) ? $post["notificationId"] : null;
        $notification   = $this->getNewOrExistingNotification($notificationId);
        $notification->setAttributes($post);

        if ($this->getNotificationService()->save($notification)) {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => true]);
            } else {
                craft()->userSession->setNotice(Craft::t("Notification saved"));
                craft()->userSession->setFlash(Craft::t("Notification saved"), true);
                $this->redirectToPostedUrl($notification);
            }
        } else {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => false]);
            } else {
                craft()->userSession->setError(Craft::t("Notification not saved"));

                // Send the event back to the template
                craft()->urlManager->setRouteVariables(
                    [
                        'notification' => $notification,
                        "errors"       => $notification->getErrors(),
                    ]
                );
            }
        }
    }

    /**
     * Deletes a notification
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_NOTIFICATIONS_MANAGE);

        $notificationId = craft()->request->getRequiredPost("id");

        $this->getNotificationService()->deleteById($notificationId);
        $this->returnJson(['success' => true]);
    }

    /**
     * @param int $notificationId
     *
     * @return Freeform_NotificationModel
     * @throws Exception
     */
    private function getNewOrExistingNotification($notificationId)
    {
        if ($notificationId) {
            $notification = $this->getNotificationService()->getNotificationById($notificationId);

            if (!$notification) {
                throw new Exception(Craft::t("Notification with ID {id} not found", ["id" => $notificationId]));
            }
        } else {
            $notification = Freeform_NotificationModel::create();
        }

        return $notification;
    }
}

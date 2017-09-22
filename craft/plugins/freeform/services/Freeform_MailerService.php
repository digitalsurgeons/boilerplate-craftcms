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
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\FileUploadInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Form;
use Solspace\Freeform\Library\Logging\CraftLogger;
use Solspace\Freeform\Library\Mailing\MailHandlerInterface;
use Solspace\Freeform\Library\Mailing\NotificationInterface;

class Freeform_MailerService extends BaseApplicationComponent implements MailHandlerInterface
{
    /**
     * Send out an email to recipients using the given mail template
     *
     * @param Form                     $form
     * @param array                    $recipients
     * @param int                      $notificationId
     * @param FieldInterface[]         $fields
     * @param Freeform_SubmissionModel $submission
     *
     * @return int - number of successfully sent emails
     * @throws Exception
     */
    public function sendEmail(
        Form $form,
        array $recipients,
        $notificationId,
        array $fields,
        Freeform_SubmissionModel $submission = null
    ) {
        $logger        = new CraftLogger();
        $sentMailCount = 0;
        $notification  = $this->getNotificationById($notificationId);

        if (!$notification) {
            throw new Exception(
                Craft::t('Email notification template with ID {id} not found', ['id' => $notificationId])
            );
        }

        $fieldValues = $this->getFieldValues($fields, $form, $submission);

        foreach ($recipients as $recipientName => $emailAddress) {
            $email              = new EmailModel();

            try {
                $email->toEmail     = $emailAddress;
                $email->toFirstName = $recipientName;
                $email->fromName    = craft()->templates->renderString($notification->getFromName(), $fieldValues);
                $email->fromEmail   = craft()->templates->renderString($notification->getFromEmail(), $fieldValues);
                $email->replyTo     = craft()->templates->renderString($notification->getReplyToEmail(), $fieldValues);
                $email->subject     = craft()->templates->renderString($notification->getSubject(), $fieldValues);
                $email->htmlBody    = craft()->templates->renderString($notification->getBodyHtml(), $fieldValues);
                $email->body        = craft()->templates->renderString($notification->getBodyText(), $fieldValues);
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $message = 'Email notification [' . $notification->getHandle() . ']: ' . $message;

                $logger->log(CraftLogger::LEVEL_ERROR, $message);
                continue;
            }

            if ($notification->isIncludeAttachmentsEnabled()) {
                foreach ($fields as $field) {
                    if ($field instanceof FileUploadInterface) {
                        $asset = craft()->assets->getFileById($field->getValue());
                        if ($asset) {
                            $source = $asset->getSource();

                            if ($source->getSourceType() === 'Local') {
                                $email->addAttachment($asset->getUrl(), $asset->filename);
                            } else {
                                $email->addAttachment($asset->getTransformSource(), $asset->filename);
                            }
                        }
                    }
                }
            }

            try {
                if (!$this->onBeforeSend($email)->performAction) {
                    continue;
                }

                $emailSent = craft()->email->sendEmail($email, $fieldValues);

                $this->onAfterSend($email, $emailSent);

                if ($emailSent) {
                    $sentMailCount++;
                }
            } catch (Exception $e) {
                $message = $e->getMessage();
                $message = 'Email notification [' . $notification->getHandle() . ']: ' . $message;

                $logger->log(CraftLogger::LEVEL_ERROR, $message);
            }
        }

        return $sentMailCount;
    }

    /**
     * @param int $id
     *
     * @return NotificationInterface
     */
    public function getNotificationById($id)
    {
        return craft()->freeform_notifications->getNotificationById($id);
    }

    /**
     * @param FieldInterface[]         $fields
     * @param Form                     $form
     * @param Freeform_SubmissionModel $submission
     *
     * @return array
     */
    private function getFieldValues(array $fields, Form $form, Freeform_SubmissionModel $submission = null)
    {
        $postedValues = [];
        foreach ($fields as $field) {
            if ($field instanceof NoStorageInterface || $field instanceof FileUploadInterface) {
                continue;
            }

            $postedValues[$field->getHandle()] = $field;
        }

        $envVariables = craft()->config->get("environmentVariables");

        $postedValues["allFields"]   = $postedValues;
        $postedValues["form"]        = $form;
        $postedValues["submission"]  = $submission;
        $postedValues["dateCreated"] = new DateTime();

        $postedValues = array_merge($envVariables, $postedValues);

        return $postedValues;
    }

    /**
     * @param EmailModel $model
     *
     * @return Event
     */
    private function onBeforeSend(EmailModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SEND, $event);

        return $event;
    }

    /**
     * @param EmailModel $model
     * @param            $isSent
     *
     * @return Event
     */
    private function onAfterSend(EmailModel $model, $isSent)
    {
        $event = new Event($this, ['model' => $model, 'isSent' => $isSent]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SEND, $event);

        return $event;
    }
}

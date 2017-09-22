<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2016, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Solspace\Freeform\Library\Mailing;

use Craft\Freeform_SubmissionModel;
use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Composer\Components\Form;

interface MailHandlerInterface
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
     * @return bool
     */
    public function sendEmail(
        Form $form,
        array $recipients,
        $notificationId,
        array $fields,
        Freeform_SubmissionModel $submission = null
    );

    /**
     * @param int $id
     *
     * @return NotificationInterface
     */
    public function getNotificationById($id);
}

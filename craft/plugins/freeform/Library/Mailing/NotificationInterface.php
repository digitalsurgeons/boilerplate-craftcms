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

interface NotificationInterface
{
    /**
     * @return string
     */
    public function getHandle();

    /**
     * @return string
     */
    public function getFromName();

    /**
     * @return string
     */
    public function getFromEmail();

    /**
     * @return string
     */
    public function getReplyToEmail();

    /**
     * @return bool
     */
    public function isIncludeAttachmentsEnabled();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getBodyHtml();

    /**
     * @return string
     */
    public function getBodyText();
}

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

namespace Solspace\Freeform\Library\Composer\Components\Fields\Traits;

trait RecipientTrait
{
    /** @var int */
    protected $notificationId;

    /**
     * @return int|null
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * Returns true/false based on if the field should or should not act
     * as a recipient email field and receive emails
     *
     * @return bool
     */
    public function shouldReceiveEmail()
    {
        return (bool)$this->getNotificationId();
    }
}

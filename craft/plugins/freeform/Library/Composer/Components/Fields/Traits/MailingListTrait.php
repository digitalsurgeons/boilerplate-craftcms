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

trait MailingListTrait
{
    /** @var int */
    protected $integrationId;

    /** @var string */
    protected $resourceId;

    /** @var string */
    protected $emailFieldHash;

    /**
     * @return int
     */
    public function getIntegrationId()
    {
        return $this->integrationId;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return string
     */
    public function getEmailFieldHash()
    {
        return $this->emailFieldHash;
    }
}

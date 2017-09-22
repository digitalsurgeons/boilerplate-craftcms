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

namespace Solspace\Freeform\Library\Composer\Components\Properties;

class AdminNotificationProperties extends AbstractProperties
{
    /** @var int */
    protected $notificationId;

    /** @var string */
    protected $recipients;

    /**
     * @return int
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * @return string
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Gets all recipients as an array
     *
     * @return array
     */
    public function getRecipientArray()
    {
        $recipients = $this->getRecipients();

        if (empty($recipients)) {
            return [];
        }

        $list = explode("\n", $recipients);
        $list = array_map("trim", $list);
        $list = array_unique($list);
        $list = array_filter($list);

        return $list;
    }

    /**
     * Return a list of all property fields and their type
     *
     * [propertyKey => propertyType, ..]
     * E.g. ["name" => "string", ..]
     *
     * @return array
     */
    protected function getPropertyManifest()
    {
        return [
            "notificationId" => self::TYPE_STRING,
            "recipients"     => self::TYPE_STRING,
        ];
    }
}

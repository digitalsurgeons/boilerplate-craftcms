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

/**
 * Class Freeform_NotificationRecord
 *
 * @property string   $name
 * @property string   $handle
 * @property string   $type
 * @property string   $class
 * @property string   $clientId
 * @property string   $clientSecret
 * @property string   $accessToken
 * @property string   $settings
 * @property bool     $forceUpdate
 * @property DateTime $lastUpdate
 */
class Freeform_IntegrationRecord extends BaseRecord
{
    const TYPE_MAILING_LIST = "mailing_list";
    const TYPE_CRM          = "crm";

    /**
     * @return string
     */
    public function getTableName()
    {
        return "freeform_integrations";
    }

    /**
     * @return array
     */
    public function defineIndexes()
    {
        return [
            ['columns' => ["class", "handle"], 'unique' => true],
        ];
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return [
            "ordered" => ["order" => "name"],
        ];
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            "name"         => [
                "type"     => AttributeType::String,
                "required" => true,
            ],
            "handle"       => [
                "type"     => AttributeType::Handle,
                "required" => true,
                "unique"   => true,
            ],
            "type"         => [
                "type"     => AttributeType::Enum,
                "required" => true,
                "values"   => [self::TYPE_MAILING_LIST, self::TYPE_CRM],
            ],
            "class"        => [
                "type"     => AttributeType::String,
                "required" => true,
            ],
            "accessToken"  => AttributeType::String,
            "settings"     => AttributeType::Mixed,
            "forceUpdate"  => AttributeType::Bool,
            "lastUpdate"   => AttributeType::DateTime,
        ];
    }
}

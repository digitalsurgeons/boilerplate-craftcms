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
 * @property int    $integrationId
 * @property string $resourceId
 * @property string $name
 * @property int    $memberCount
 */
class Freeform_MailingListRecord extends BaseRecord
{
    const TABLE = "freeform_mailing_lists";

    /**
     * @return string
     */
    public function getTableName()
    {
        return self::TABLE;
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return [
            'integration' => [
                static::BELONGS_TO,
                'Freeform_IntegrationRecord',
                'integrationId',
                'required' => true,
                'onDelete' => static::CASCADE,
            ],
        ];
    }

    /**
     * @return array
     */
    public function defineIndexes()
    {
        return [
            ['columns' => ["integrationId", "resourceId"], 'unique' => true],
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
            "resourceId"  => [
                "type"     => AttributeType::String,
                "required" => true,
            ],
            "name"        => [
                "type"     => AttributeType::String,
                "required" => true,
            ],
            "memberCount" => [
                "type"   => AttributeType::Number,
                "column" => ColumnType::Int,
            ],
        ];
    }
}

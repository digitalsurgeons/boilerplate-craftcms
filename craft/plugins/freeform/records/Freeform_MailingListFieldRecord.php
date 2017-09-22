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

use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;

/**
 * @property string $mailingListId
 * @property string $handle
 * @property string $label
 * @property string $type
 * @property bool   $required
 */
class Freeform_MailingListFieldRecord extends BaseRecord
{
    const TABLE = "freeform_mailing_list_fields";

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
            'mailingList' => [
                static::BELONGS_TO,
                'Freeform_MailingListRecord',
                'mailingListId',
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
            ['columns' => ["mailingListId", "handle"], 'unique' => true],
        ];
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return [
            "ordered" => ["order" => "label"],
        ];
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            "handle"  => [
                "type"     => AttributeType::String,
                "required" => true,
            ],
            "label"        => [
                "type"     => AttributeType::String,
                "required" => true,
            ],
            "type"        => [
                "type"     => AttributeType::Enum,
                "required" => true,
                'values'   => FieldObject::getTypes(),
                'default'  => FieldObject::getDefaultType(),
            ],
            "required" => [
                "type"   => AttributeType::Bool,
                "column" => ColumnType::Int,
            ],
        ];
    }
}

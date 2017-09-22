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
 * @property int    $integrationId
 * @property string $handle
 * @property string $label
 * @property string $type
 * @property bool   $required
 */
class Freeform_CrmFieldRecord extends BaseRecord
{
    const TABLE = "freeform_crm_fields";

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
            ['columns' => ["integrationId", "handle"], 'unique' => true],
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

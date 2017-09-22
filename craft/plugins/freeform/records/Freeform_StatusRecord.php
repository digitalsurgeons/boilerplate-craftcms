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
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property string $color
 * @property int    $isDefault
 * @property int    $sortOrder
 */
class Freeform_StatusRecord extends BaseRecord
{
    const TABLE = "freeform_statuses";

    /**
     * @return array
     */
    public static function getAllowedColors()
    {
        return [
            'green',
            'blue',
            'yellow',
            'orange',
            'red',
            'pink',
            'purple',
            'turquoise',
            'light',
            'grey',
            'black',
        ];
    }

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
    public function defineIndexes()
    {
        return [
            ['columns' => ['name'], 'unique' => true],
            ['columns' => ['handle'], 'unique' => true],
        ];
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return [
            'ordered' => ['order' => 'sortOrder ASC'],
        ];
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'name'      => [
                AttributeType::String,
                "required" => true,
            ],
            'handle'    => [
                AttributeType::Handle,
                "required" => true,
            ],
            'color'     => [
                AttributeType::Enum,
                'values'   => self::getAllowedColors(),
                'required' => true,
                'default'  => 'grey',
            ],
            "isDefault" => AttributeType::Bool,
            'sortOrder' => AttributeType::Number,
        ];
    }
}

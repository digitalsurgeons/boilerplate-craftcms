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

use Solspace\Freeform\Library\Composer\Components\AbstractField;

/**
 * Class Freeform_FieldRecord
 *
 * @property int    $id
 * @property string $type
 * @property string $handle
 * @property string $label
 * @property bool   $required
 * @property string $value
 * @property string $placeholder
 * @property string $instructions
 * @property array  $values
 * @property array  $options
 * @property bool   $checked
 * @property int    $notificationId
 * @property int    $assetSourceId
 * @property int    $rows
 * @property array  $fileKinds
 * @property int    $maxFileSizeKB
 * @property array  $additionalProperties
 */
class Freeform_FieldRecord extends BaseRecord
{
    const TABLE = 'freeform_fields';

    /**
     * Returns the name of the associated database table.
     *
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
            'notification' => [
                static::BELONGS_TO,
                'Freeform_NotificationRecord',
                'notificationId',
                'required' => false,
                'onDelete' => static::SET_NULL,
            ],
            'assetSource'  => [
                static::BELONGS_TO,
                'AssetSourceRecord',
                'assetSourceId',
                'required' => false,
                'onDelete' => static::SET_NULL,
            ],
        ];
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return [
            FreeformPlugin::FIELD_DISPLAY_ORDER_TYPE => ['order' => 'type ASC, label ASC'],
            FreeformPlugin::FIELD_DISPLAY_ORDER_NAME => ['order' => 'label ASC'],
        ];
    }

    /**
     * Defines this model"s attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'type'                 => [
                'type'   => AttributeType::Enum,
                'values' => array_keys(AbstractField::getFieldTypes()),
            ],
            'handle'               => [
                'type'     => AttributeType::Handle,
                'required' => true,
                'unique'   => true,
            ],
            'label'                => [
                'type'     => AttributeType::String,
                'required' => true,
            ],
            'required'             => [
                'type' => AttributeType::Bool,
            ],
            'value'                => AttributeType::String,
            'placeholder'          => AttributeType::String,
            'instructions'         => [
                'type'   => AttributeType::String,
                'column' => ColumnType::Text,
            ],
            'values'               => AttributeType::Mixed,
            'options'              => AttributeType::Mixed,
            'checked'              => [AttributeType::Bool, 'required' => false],
            'rows'                 => AttributeType::Number,
            'fileKinds'            => AttributeType::Mixed,
            'maxFileSizeKB'        => AttributeType::Number,
            'additionalProperties' => AttributeType::Mixed,
        ];
    }
}

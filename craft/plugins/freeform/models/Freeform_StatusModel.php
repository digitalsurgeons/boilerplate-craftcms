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
 * Class Freeform_FieldModel
 *
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property bool   $isDefault
 * @property string $color
 * @property int    $sortOrder
 */
class Freeform_StatusModel extends BaseModel implements \JsonSerializable
{
    /**
     * @return Freeform_StatusModel
     */
    public static function create()
    {
        $colors = Freeform_StatusRecord::getAllowedColors();
        shuffle($colors);
        $randomColor = reset($colors);

        $status        = new static();
        $status->color = $randomColor;

        return $status;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        return [
            'id'        => (int) $this->id,
            'name'      => $this->name,
            'handle'    => $this->handle,
            'isDefault' => (bool) $this->isDefault,
            'color'     => $this->color,
        ];
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'id'        => AttributeType::Number,
            'name'      => AttributeType::String,
            'handle'    => AttributeType::Handle,
            'isDefault' => AttributeType::Bool,
            'color'     => AttributeType::String,
            'sortOrder' => AttributeType::Number,
        ];
    }
}

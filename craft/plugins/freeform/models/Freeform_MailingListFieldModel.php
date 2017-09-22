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
 * @property int    $mailingListId
 * @property string $handle
 * @property string $label
 * @property string $type
 * @property bool   $required
 */
class Freeform_MailingListFieldModel extends BaseModel
{
    public static function create()
    {
        $model = new Freeform_MailingListFieldModel();

        return $model;
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            "id"            => AttributeType::Number,
            "mailingListId" => AttributeType::Number,
            "handle"        => AttributeType::String,
            "label"         => AttributeType::String,
            "type"          => AttributeType::String,
            "required"      => AttributeType::Bool,
        ];
    }
}

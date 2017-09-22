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
 * @property string $name
 * @property string $handle
 * @property string $description
 * @property string $fromName
 * @property string $fromEmail
 * @property string $replyToEmail
 * @property bool   $includeAttachments
 * @property string $subject
 * @property string $bodyHtml
 * @property string $bodyText
 * @property int    $sortOrder
 */
class Freeform_NotificationRecord extends BaseRecord
{
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [
            "name, subject, fromName, fromEmail",
            "required",
        ];

        return $rules;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return "freeform_notifications";
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
            "name"               => AttributeType::String,
            "handle"             => [
                "type"     => AttributeType::Handle,
                "required" => true,
                "unique"   => true,
            ],
            "description"        => [
                "type"   => AttributeType::String,
                "column" => ColumnType::Text,
            ],
            "fromName"           => AttributeType::String,
            "fromEmail"          => AttributeType::String,
            "replyToEmail"       => AttributeType::String,
            "includeAttachments" => AttributeType::Bool,
            "subject"            => AttributeType::String,
            "bodyHtml"           => [
                "type"   => AttributeType::String,
                "column" => ColumnType::Text,
            ],
            "bodyText"           => [
                "type"   => AttributeType::String,
                "column" => ColumnType::Text,
            ],
            "sortOrder"          => AttributeType::Number,
        ];
    }
}

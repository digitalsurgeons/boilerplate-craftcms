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
 * Class Freeform_FormRecord
 *
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property int    $spamBlockCount
 * @property string $submissionTitleFormat
 * @property string $description
 * @property string $layoutJson
 * @property string $returnUrl
 * @property int    $defaultStatus
 * @property int    $formTemplateId
 * @property string $color
 */
class Freeform_FormRecord extends BaseRecord
{
    const TABLE = 'freeform_forms';

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
    public function scopes()
    {
        return [
            'ordered' => ['order' => 'name'],
        ];
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'name'                  => [
                'type'     => AttributeType::String,
                'required' => true,
            ],
            'handle'                => [
                'type'     => AttributeType::Slug,
                'required' => true,
                'unique'   => true,
            ],
            'spamBlockCount'        => [
                'type'    => AttributeType::Number,
                'default' => 0,
            ],
            'submissionTitleFormat' => [
                'type'     => AttributeType::String,
                'required' => true,
            ],
            'description'           => [
                'type'   => AttributeType::String,
                'column' => ColumnType::Text,
            ],
            'layoutJson'            => [
                'type'     => AttributeType::String,
                'column'   => ColumnType::Text,
                'required' => true,
            ],
            'returnUrl'             => AttributeType::String,
            'defaultStatus'         => [
                'type'     => AttributeType::Number,
                'required' => true,
            ],
            'color'                 => AttributeType::String,
        ];
    }
}

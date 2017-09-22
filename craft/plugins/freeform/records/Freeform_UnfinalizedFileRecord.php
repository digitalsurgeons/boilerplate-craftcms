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
 * Class Freeform_FieldRecord
 *
 * @property int $id
 * @property int $assetId
 */
class Freeform_UnfinalizedFileRecord extends BaseRecord
{
    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public function getTableName()
    {
        return "freeform_unfinalized_files";
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return [
            "assetSource" => [
                static::BELONGS_TO,
                'AssetFileRecord',
                'assetId',
                'required' => true,
                'onDelete' => static::CASCADE,
            ],
        ];
    }
}

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
 * Class Freeform_ExportSettingRecord
 *
 * @property int   $id
 * @property int   $userId
 * @property array $setting
 */
class Freeform_ExportSettingRecord extends BaseRecord
{
    const TABLE = 'freeform_export_settings';

    /**
     * @param int    $userId
     *
     * @return Freeform_ExportSettingRecord
     */
    public static function create($userId)
    {
        $record = new Freeform_ExportSettingRecord();
        $record->userId = $userId;

        $record->setting = [];

        return $record;
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
    public function defineRelations()
    {
        return [
            'user' => [
                static::BELONGS_TO,
                'UserRecord',
                'userId',
                'required' => true,
                'onDelete' => static::CASCADE,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'setting'                  => [
                'type'     => AttributeType::Mixed,
                'required' => true,
            ],
        ];
    }
}

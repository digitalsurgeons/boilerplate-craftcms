<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170627_113056_freeform_AddExportSettings extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        $columns = [
            'userId'  => ['column' => ColumnType::Int, 'required' => true],
            'setting' => ['column' => ColumnType::Text, 'required' => true],
        ];

        craft()->db->createCommand()->createTable('freeform_export_settings', $columns);

        craft()->db->createCommand()->addForeignKey(
            'freeform_export_settings',
            'userId',
            'users',
            'id',
            'CASCADE'
        );

        return true;
    }
}

<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170629_062621_freeform_AddExportProfiles extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        $columns = [
            'name'      => ['column' => ColumnType::Varchar, 'required' => true],
            'formId'    => ['column' => ColumnType::Int],
            'limit'     => ['column' => ColumnType::Int],
            'dateRange' => ['column' => ColumnType::Varchar],
            'fields'    => ['column' => ColumnType::Text, 'required' => true],
            'filters'   => ['column' => ColumnType::Text],
        ];

        craft()->db->createCommand()->createTable('freeform_export_profiles', $columns);

        craft()->db->createCommand()->addForeignKey(
            'freeform_export_profiles',
            'formId',
            'freeform_forms',
            'id',
            'CASCADE'
        );

        return true;
    }
}

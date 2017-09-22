<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170609_142437_freeform_AddNewPropertiesToFields extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        craft()->db
            ->createCommand()
            ->addColumn(
                'freeform_fields',
                'additionalProperties',
                [
                    ColumnType::Text,
                    'required' => false,
                    'default'  => null,
                ]
            );

        return true;
    }
}

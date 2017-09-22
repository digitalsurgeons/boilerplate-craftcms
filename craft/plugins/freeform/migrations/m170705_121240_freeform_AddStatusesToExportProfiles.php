<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170705_121240_freeform_AddStatusesToExportProfiles extends BaseMigration
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
                'freeform_export_profiles',
                'statuses',
                [
                    ColumnType::Text,
                    'required' => false,
                ]
            );

		return true;
	}
}

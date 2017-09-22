<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m161121_101534_freeform_AddCheckedAttributeToFieldModel extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{
        craft()->db->createCommand()->addColumn("freeform_fields", "checked", ColumnType::Bool);

		return true;
	}
}

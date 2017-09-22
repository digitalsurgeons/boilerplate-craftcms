<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170127_083920_freeform_MakeFieldCheckedNullable extends BaseMigration
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
            ->alterColumn(
                "freeform_fields",
                "checked",
                [ColumnType::Bool, "required" => false]
            );

		return true;
	}
}

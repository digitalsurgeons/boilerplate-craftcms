<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m161129_134744_freeform_ChangingFieldDatabaseTypes extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 *
	 * @return bool
	 */
	public function safeUp()
	{
	    $db = craft()->db;

	    $toText = $db
            ->createCommand()
            ->select("id")
            ->from('freeform_fields')
            ->where('`type` IN ("checkbox_group", "email", "textarea")')
            ->queryColumn();

        foreach ($toText as $fieldId) {
            try {
                $db->createCommand()->alterColumn(
                    "freeform_submissions",
                    "field_{$fieldId}",
                    ColumnType::Text
                );
            } catch (Exception $e) {
            }
        }

        $toVarchar = $db
            ->createCommand()
            ->select("id")
            ->from('freeform_fields')
            ->where('`type` NOT IN ("checkbox_group", "email", "textarea")')
            ->queryColumn();


        foreach ($toVarchar as $fieldId) {
            try {
                $db->createCommand()->alterColumn(
                    "freeform_submissions",
                    "field_{$fieldId}",
                    [ColumnType::Varchar, "length" => 100]
                );
            } catch (Exception $e) {
            }
        }

        return true;
	}
}

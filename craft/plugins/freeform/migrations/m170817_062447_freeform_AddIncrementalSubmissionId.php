<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170817_062447_freeform_AddIncrementalSubmissionId extends BaseMigration
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
            ->addColumnAfter(
                'freeform_submissions',
                'incrementalId',
                [
                    ColumnType::Int,
                    'required' => false,
                    'default'  => 0,
                ],
                'id'
            );

        craft()->db
            ->createCommand()
            ->createIndex(
                'freeform_submissions',
                ['incrementalId']
            );

        $ids = craft()->db->createCommand()->select('id')->from('freeform_submissions')->queryColumn();

        $autoIncrement = 0;
        foreach ($ids as $id) {
            craft()->db
                ->createCommand()
                ->update(
                    'freeform_submissions',
                    ['incrementalId' => ++$autoIncrement],
                    'id = :id',
                    ['id' => $id]
                );
        }

		return true;
	}
}

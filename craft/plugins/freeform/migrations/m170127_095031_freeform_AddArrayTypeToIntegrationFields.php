<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170127_095031_freeform_AddArrayTypeToIntegrationFields extends BaseMigration
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
                'freeform_crm_fields',
                'type',
                [
                    AttributeType::Enum,
                    'required' => true,
                    'values'   => ['string', 'array', 'numeric', 'boolean'],
                    'default'  => 'string',
                ]
            );

        craft()->db
            ->createCommand()
            ->alterColumn(
                'freeform_mailing_list_fields',
                'type',
                [
                    AttributeType::Enum,
                    'required' => true,
                    'values'   => ['string', 'array', 'numeric', 'boolean'],
                    'default'  => 'string',
                ]
            );

        return true;
    }
}

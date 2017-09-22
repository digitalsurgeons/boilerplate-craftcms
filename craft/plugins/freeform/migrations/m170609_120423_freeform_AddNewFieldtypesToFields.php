<?php

namespace Craft;

use Solspace\Freeform\Library\Composer\Components\AbstractField;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170609_120423_freeform_AddNewFieldtypesToFields extends BaseMigration
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
                'freeform_fields',
                'type',
                [
                    AttributeType::Enum,
                    'required' => true,
                    'values'   => [
                        'text',
                        'textarea',
                        'email',
                        'hidden',
                        'select',
                        'checkbox',
                        'checkbox_group',
                        'radio_group',
                        'file',
                        'dynamic_recipients',
                        'datetime',
                        'number',
                        'phone',
                        'website',
                        'rating',
                        'regex',
                        'confirmation',
                    ],
                ]
            );

        return true;
    }
}

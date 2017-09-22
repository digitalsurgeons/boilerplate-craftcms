<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m170207_111312_freeform_AddColorToForms extends BaseMigration
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
                'freeform_forms',
                'color',
                [
                    ColumnType::Varchar,
                    'length'   => 7,
                    'required' => false,
                ]
            );

        $formIds = craft()->db
            ->createCommand()
            ->select('id, layoutJson')
            ->from('freeform_forms')
            ->queryAll();

        foreach ($formIds as $form) {
            $id         = $form['id'];
            $layoutJson = $form['layoutJson'];

            $color = '#' . substr(md5($id), 0, 6);

            $layout = json_decode($layoutJson, true);
            if (!isset($layout['composer']['properties']['form']['color'])) {
                $layout['composer']['properties']['form']['color'] = $color;
            }

            $updatedLayoutJson = json_encode($layout);

            craft()->db
                ->createCommand()
                ->update(
                    'freeform_forms',
                    [
                        'color'      => $color,
                        'layoutJson' => $updatedLayoutJson,
                    ],
                    'id = :id',
                    ['id' => $id]
                );
        }

        return true;
    }
}

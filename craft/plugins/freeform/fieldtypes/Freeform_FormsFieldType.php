<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2017, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Craft;

use Solspace\Freeform\Library\Composer\Components\Form;

class Freeform_FormsFieldType extends BaseFieldType
{
    /**
     * @inheritDoc IComponentType::getName()
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Freeform Form');
    }

    /**
     * @inheritDoc IFieldType::defineContentAttribute()
     *
     * @return mixed
     */
    public function defineContentAttribute()
    {
        return AttributeType::Number;
    }

    /**
     * @inheritDoc IFieldType::getInputHtml()
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        $forms = craft()->freeform_forms->getAllForms();

        $formOptions = [
            "" => Craft::t('Select a form'),
        ];
        /** @var Freeform_FormModel $form */
        foreach ($forms as $form) {
            if (is_array($form)) {
                $formOptions[$form['id']] = $form['name'];
            } else if ($form instanceof Freeform_FormModel) {
                $formOptions[$form->id] = $form->name;
            }
        }

        return craft()->templates->render(
            "freeform/_components/fieldTypes/form",
            [
                "name"         => $name,
                "forms"        => $forms,
                "formOptions"  => $formOptions,
                "selectedForm" => $value,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function prepValueFromPost($value)
    {
        return $value ? (int)$value : null;
    }

    /**
     * @inheritDoc IFieldType::prepValue()
     *
     * @param int $value
     *
     * @return Form|null
     */
    public function prepValue($value)
    {
        /** @var Freeform_FormModel $model */
        $model = craft()->freeform_forms->getFormById($value);

        if ($model) {
            return $model->getForm();
        }

        return null;
    }
}

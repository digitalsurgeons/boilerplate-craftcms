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

use Solspace\Freeform\Library\Composer\Components\Attributes\CustomFormAttributes;
use Solspace\Freeform\Library\Composer\Components\Form;

class FreeformVariable
{
    /**
     * @param string|int $handleOrId
     * @param array|null $attributes
     *
     * @return null|Form
     */
    public function form($handleOrId, $attributes = null)
    {
        $formService = $this->getFormService();

        $form = $formService->getFormByHandleOrId($handleOrId);

        if ($form) {
            $formObject = $form->getForm();
            $formObject->setAttributes($attributes);

            return $formObject;
        }

        return null;
    }

    /**
     * @param null $attributes
     *
     * @return Freeform_FormModel[]
     */
    public function forms($attributes = null)
    {
        $formService = $this->getFormService();

        $forms = $formService->getAllForms();

        return $forms ?: [];
    }

    /**
     * @param array|null $attributes
     *
     * @return ElementCriteriaModel
     */
    public function submissions(array $attributes = null)
    {
        $elementType = craft()
            ->components
            ->getComponentByTypeAndClass(
                ComponentType::Element,
                Freeform_SubmissionModel::ELEMENT_TYPE
            );

        return new ElementCriteriaModel($attributes, $elementType);
    }

    /**
     * @return Freeform_SettingsModel
     */
    public function getSettings()
    {
        return craft()->freeform_settings->getSettingsModel();
    }

    /**
     * @return Freeform_FormsService
     */
    private function getFormService()
    {
        return craft()->freeform_forms;
    }

    /**
     * @return Freeform_SubmissionsService
     */
    private function getSubmissionService()
    {
        return craft()->freeform_submissions;
    }
}

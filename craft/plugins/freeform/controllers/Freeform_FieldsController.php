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

use Solspace\Freeform\Library\Composer\Components\AbstractField;
use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_FieldsController extends Freeform_BaseController
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FIELDS_ACCESS);

        $fieldsService = $this->getFieldsService();
        $fields        = $fieldsService->getAllFields();

        $this->renderTemplate(
            'freeform/fields',
            [
                'fields'     => $fields,
                'fieldTypes' => $this->getFieldsService()->getFieldTypes(),
            ]
        );
    }

    /**
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEdit(array $variables = [])
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FIELDS_MANAGE);

        if (empty($variables['field'])) {
            $fieldId = isset($variables['fieldId']) ? $variables['fieldId'] : null;
            if ($fieldId) {
                /** @var Freeform_FieldModel $field */
                $field = $this->getFieldsService()->getFieldById($fieldId);

                if (!$field) {
                    throw new HttpException(404, Craft::t('Field with ID {id} not found', ['id' => $fieldId]));
                }

                $title = $field->label;
            } else {
                $field = Freeform_FieldModel::create();
                $title = Craft::t('Create a new field');
            }
        } else {
            /** @var Freeform_FieldModel $field */
            $field   = $variables['field'];
            $fieldId = $field->id;
            $title   = $field->label;
        }

        craft()->templates->includeCssResource('freeform/css/fieldEditor.css');
        craft()->templates->includeJsResource('freeform/js/cp/fieldEditor.js');

        $variables = array_merge(
            $variables,
            [
                'field'              => $field,
                'title'              => $title,
                'fieldTypes'         => $this->getFieldsService()->getFieldTypes(),
                'fileKinds'          => IOHelper::getFileKinds(),
                'assetSources'       => $this->getFilesService()->getAssetSourceList(),
                'continueEditingUrl' => 'freeform/fields/{id}',
                'crumbs'             => [
                    ['label' => 'Freeform', 'url' => UrlHelper::getUrl('freeform')],
                    ['label' => Craft::t('Fields'), 'url' => UrlHelper::getUrl('freeform/fields')],
                    [
                        'label' => $title,
                        'url'   => UrlHelper::getUrl(
                            'freeform/fields/' . ($fieldId ? $fieldId : 'new')
                        ),
                    ],
                ],
            ]
        );

        $this->renderTemplate('freeform/fields/edit', $variables);
    }

    /**
     * @throws Exception
     */
    public function actionSave()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FIELDS_MANAGE);

        $post = craft()->request->getPost();

        $fieldId = isset($post['fieldId']) ? $post['fieldId'] : null;
        $field   = $this->getNewOrExistingField($fieldId);

        if ($field->id && isset($post['type'])) {
            unset($post['type']);
        }

        $field->setAttributes($post);

        $fieldHasOptions = in_array(
            $field->type,
            [
                FieldInterface::TYPE_RADIO_GROUP,
                FieldInterface::TYPE_CHECKBOX_GROUP,
                FieldInterface::TYPE_SELECT,
                FieldInterface::TYPE_DYNAMIC_RECIPIENTS,
            ],
            true
        );

        if (isset($post['types'][$field->type])) {
            $fieldSpecificPost = $post['types'][$field->type];

            foreach ($fieldSpecificPost as $key => $val) {
                $field->setProperty($key, $val);
            }

            $hasValues = isset($fieldSpecificPost['values']) && is_array($fieldSpecificPost['values']);
            $forceLabelOnValue = isset($fieldSpecificPost['customValues']) && $fieldSpecificPost['customValues'] !== '1';

            if ($fieldHasOptions && $hasValues) {
                $field->setPostValues($fieldSpecificPost, $forceLabelOnValue);
            } else {
                $field->values = null;
            }
        }

        if ($this->getFieldsService()->save($field)) {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => true]);
            } else {
                craft()->userSession->setNotice(Craft::t('Field saved'));
                craft()->userSession->setFlash('Field saved', true);
                $this->redirectToPostedUrl($field);
            }
        } else {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => false]);
            } else {
                craft()->userSession->setError(Craft::t('Field not saved'));

                // Send the event back to the template
                craft()->urlManager->setRouteVariables(['field' => $field, 'errors' => $field->getErrors()]);
            }
        }
    }

    /**
     * Deletes a field
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FIELDS_MANAGE);

        $fieldId = craft()->request->getRequiredPost('id');

        $this->getFieldsService()->deleteById($fieldId);
        $this->returnJson(['success' => true]);
    }

    /**
     * @param int $fieldId
     *
     * @return Freeform_FieldModel
     * @throws Exception
     */
    private function getNewOrExistingField($fieldId)
    {
        if ($fieldId) {
            $field = $this->getFieldsService()->getFieldById($fieldId);

            if (!$field) {
                throw new Exception(Craft::t('Field with ID {id} not found', ['id' => $fieldId]));
            }
        } else {
            $field = Freeform_FieldModel::create();
        }

        return $field;
    }
}

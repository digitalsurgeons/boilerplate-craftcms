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
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_SubmissionElementType extends BaseElementType
{
    private static $fieldCache;

    /**
     * Returns the element type name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Freeform Submissions');
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public function hasContent()
    {
        return true;
    }

    /**
     * Returns whether this element type has titles.
     *
     * @return bool
     */
    public function hasTitles()
    {
        return true;
    }

    /**
     * @inheritDoc IElementType::hasStatuses()
     * @return bool
     */
    public function hasStatuses()
    {
        return true;
    }

    /**
     * Returns a list of statuses for this element type
     *
     * @return array
     */
    public function getStatuses()
    {
        /** @var Freeform_StatusModel[] $statusModels */
        $statusModels = craft()->freeform_statuses->getAllStatuses();
        $statuses     = [];

        foreach ($statusModels as $status) {
            $statuses[$status->handle . ' ' . $status->color] = $status->name;
        }

        return $statuses;
    }

    /**
     * Returns this element type's sources.
     *
     * @param string|null $context
     *
     * @return array|false
     */
    public function getSources($context = null)
    {
        $sources = [
            '*' => [
                'label' => Craft::t('All Submissions'),
            ],
            ['heading' => Craft::t('Forms')],
        ];

        /** @var Freeform_FormsService $formsService */
        $formsService = freeform()->forms;

        /** @var array|null $allowedFormIds */
        $allowedFormIds = freeform()->submissions->getAllowedSubmissionFormIds();

        /** @var Freeform_FormModel $form */
        foreach ($formsService->getAllForms() as $form) {
            if (null !== $allowedFormIds && !in_array($form->id, $allowedFormIds)) {
                continue;
            }

            $key = 'form:' . $form->id;

            $sources[$key] = [
                'label'    => $form->name,
                'criteria' => ['formId' => $form->id],
            ];
        }

        return $sources;
    }

    /**
     * @param null $source
     *
     * @return array
     */
    public function getAvailableActions($source = null)
    {
        $actions = [];

        if (preg_match('/^form:/', $source)) {
            $exportAction = craft()->elements->getAction('Freeform_ExportCSV');
            $actions[]    = $exportAction;
        }

        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE)) {
            // Allow deletion
            $deleteAction = craft()->elements->getAction('Freeform_DeleteSubmission');
            $deleteAction->setParams(
                [
                    'confirmationMessage' => Craft::t('Are you sure you want to delete these submissions?'),
                    'successMessage'      => Craft::t('Submissions deleted successfully.'),
                ]
            );
            $actions[] = $deleteAction;
        }

        $actions[] = craft()->elements->getAction('Freeform_SetStatus');

        return $actions;
    }

    /**
     * Returns the attributes that can be shown/sorted by in table views.
     *
     * @param string|null $source
     *
     * @return array
     */
    public function defineTableAttributes($source = null)
    {
        $formId     = null;
        $attributes = [
            'title'          => Craft::t('Title'),
            'id'             => Craft::t('ID'),
            'incrementalId'  => Craft::t('Incremental ID'),
            'submissionDate' => Craft::t('Submission Date'),
            'form'           => Craft::t('Form'),
        ];

        if (strpos($source, 'form:') !== false) {
            unset($attributes['form']);

            $formId = (int) str_replace('form:', '', $source);
        }

        return array_merge($attributes, $this->getFieldColumns($formId));
    }

    /**
     * Returns the table view HTML for a given attribute.
     *
     * @param BaseElementModel $element
     * @param string           $attribute
     *
     * @return string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        /** @var Freeform_FieldModel[] $fields */
        $fields = $this->getFieldsService()->getAllFields();

        /** @var Calendar_EventModel $element */
        switch ($attribute) {
            case 'form':
                return $element->getForm()->name;

            default:
                $columnPrefix = Freeform_SubmissionRecord::FIELD_COLUMN_PREFIX;
                if (strpos($attribute, $columnPrefix) === 0) {
                    $fieldId = (int) substr($attribute, strlen($columnPrefix));
                    $value   = $element->$attribute;
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }

                    if (isset($fields[$fieldId])) {
                        $field = $fields[$fieldId];

                        switch ($field->type) {
                            case FieldInterface::TYPE_CHECKBOX:
                                return $value ?: '-';

                            case FieldInterface::TYPE_RATING:
                                return (int) $value . '/' . $field->getProperty('maxValue');

                            case FieldInterface::TYPE_FILE:
                                $asset = craft()->assets->getFileById($value);

                                if ($asset) {
                                    return craft()->templates->render(
                                        'freeform/_components/fields/file.html',
                                        ['asset' => $asset]
                                    );
                                }

                                break;

                        }
                    }

                    return HtmlHelper::encode($value);
                }

                return parent::getTableAttributeHtml($element, $attribute);
        }
    }

    /**
     * Defines any custom element criteria attributes for this element type.
     *
     * @return array
     */
    public function defineCriteriaAttributes()
    {
        return [
            'form'   => AttributeType::Mixed,
            'formId' => AttributeType::Number,
            'order'  => [AttributeType::String, 'default' => 'fs.id desc'],
        ];
    }

    /**
     * @inheritDoc IElementType::getElementQueryStatusCondition()
     *
     * @param DbCommand $query
     * @param string    $status
     *
     * @return array|false|string|void
     */
    public function getElementQueryStatusCondition(DbCommand $query, $status)
    {
        $statusHandle = preg_replace('/ \w+$/', '', $status);
        $query->andWhere(DbHelper::parseParam('fstatus.handle', $statusHandle, $query->params));
    }

    /**
     * Modifies an element query targeting elements of this type.
     *
     * @param DbCommand            $query
     * @param ElementCriteriaModel $criteria
     *
     * @return mixed
     */
    public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
    {
        $query
            ->addSelect(
                'ff.name AS form,
                fs.dateCreated as submissionDate,
                fs.*'
            )
            ->join('freeform_submissions fs', 'fs.id = elements.id')
            ->join('freeform_forms ff', 'ff.id = fs.formId')
            ->join('freeform_statuses fstatus', 'fstatus.id = fs.statusId');

        if ($criteria->form) {
            $query->andWhere(DbHelper::parseParam('ff.handle', $criteria->form, $query->params));
        }

        if ($criteria->formId && $criteria->formId !== '*') {
            $query->andWhere(DbHelper::parseParam('fs.formId', $criteria->formId, $query->params));
        }

        if (!$criteria->form && !$criteria->formId) {
            /** @var array|null $allowedFormIds */
            $allowedFormIds = craft()->freeform_submissions->getAllowedSubmissionFormIds();

            if (null !== $allowedFormIds) {
                $query->andWhere(DbHelper::parseParam('fs.formId', $allowedFormIds, $query->params));
            }
        }

        $status = $criteria->status;
        if ($status && $status !== "enabled") {
            if (!is_array($status)) {
                $status = explode(" ", $status);
                $status = reset($status);
            }

            $query->andWhere(DbHelper::parseParam('fstatus.handle', $status, $query->params));
        }

        // Replace all "fieldHandles" with their respective "field_{ID}" counterparts
        if ($criteria->order) {
            $orderString = $criteria->order;
            preg_match_all('/(?:[a-zA-Z0-9]+\.)?(\w+)/', $orderString, $matches);

            if (isset($matches[1]) && !empty($matches[1])) {
                $fieldHandles = $this->getFieldsService()->getAllFieldHandles();

                foreach ($matches[1] as $matchedString) {
                    if (in_array(strtolower($matchedString), ['asc', 'desc'])) {
                        continue;
                    }

                    $id = array_search($matchedString, $fieldHandles, true);
                    if ($id !== false) {
                        $columnName = Freeform_SubmissionRecord::getFieldColumnName($id);

                        $orderString = str_replace($matchedString, $columnName, $orderString);
                    }
                }
            }

            $criteria->order = $orderString;
        }
    }

    /**
     * @inheritDoc IElementType::defineSearchableAttributes()
     * @return array
     */
    public function defineSearchableAttributes()
    {
        return array_keys($this->getFieldColumns());
    }

    /**
     * @return array
     */
    public function defineSortableAttributes()
    {
        $sortableAttributes = [
            'title'          => Craft::t('Title'),
            'submissionDate' => Craft::t('Submission date'),
            'form'           => Craft::t('Form'),
        ];

        return array_merge($sortableAttributes, $this->getFieldColumns());
    }

    /**
     * Populates an element model based on a query result.
     *
     * @param array $row
     *
     * @return array
     */
    public function populateElementModel($row)
    {
        return Freeform_SubmissionModel::populateModel($row);
    }


    /**
     * Gets a list of field columns based on the $formId provided
     *
     * @param int $formId
     *
     * @return array - [field_1 => "Some label", ..]
     */
    private function getFieldColumns($formId = null)
    {
        if (null === $formId) {
            $formId = '*';
        }

        if (null === self::$fieldCache || !isset(self::$fieldCache[$formId])) {
            $fieldColumns = [];

            // Fetch all Field Models from the DB if no specific form is selected
            if ($formId === '*') {
                /** @var Freeform_FieldModel[] $fields */
                $fields = $this->getFieldsService()->getAllFields();

                foreach ($fields as $field) {
                    $fieldColumnName                = Freeform_SubmissionRecord::getFieldColumnName($field->id);
                    $fieldColumns[$fieldColumnName] = $field->label;
                }

                // Fetch only the fields the current selected form has in it's layout
            } else {
                /** @var Freeform_FormModel $form */
                $form = craft()->freeform_forms->getFormById($formId);

                if ($form) {
                    $fields = $form->getLayout()->getFields();

                    /** @var AbstractField $field */
                    foreach ($fields as $field) {
                        if ($field instanceof NoStorageInterface) {
                            continue;
                        }

                        $fieldColumnName = Freeform_SubmissionRecord::getFieldColumnName(
                            $field->getId()
                        );

                        $fieldAttributes[$fieldColumnName] = $field->getLabel();
                    }
                }
            }

            self::$fieldCache[$formId] = $fieldColumns;
        }

        return self::$fieldCache[$formId];
    }

    /**
     * @return Freeform_FieldsService
     */
    private function getFieldsService()
    {
        return craft()->freeform_fields;
    }
}

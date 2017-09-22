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

use Solspace\Freeform\Library\Composer\Components\FieldInterface;

/**
 * Class Freeform_FieldRecord
 *
 * @property int    $id
 * @property int    $incrementalId
 * @property number $statusId
 * @property number $formId
 */
class Freeform_SubmissionRecord extends BaseRecord
{
    const TABLE               = "freeform_submissions";
    const FIELD_COLUMN_PREFIX = "field_";

    /**
     * @param int $fieldId
     *
     * @return string
     */
    public static function getFieldColumnName($fieldId)
    {
        return self::FIELD_COLUMN_PREFIX . $fieldId;
    }

    /**
     * @return array
     */
    public function rules()
    {
        /** @var Freeform_FormsService $formService */
        $formService = craft()->freeform_forms;
        $layout = $formService->getFormById($this->formId)->getLayout();

        $rules = [];

        foreach ($layout->getFields() as $field) {
            // We don't add the required for files, since they cannot be edited
            if ($field->getType() === FieldInterface::TYPE_FILE) {
                continue;
            }

            if ($field->isRequired()) {
                $fieldColumn = self::getFieldColumnName($field->getId());
                $rules[] = [
                    $fieldColumn,
                    "required",
                    "message" => Craft::t("{attribute} cannot be blank", ["attribute" => $field->getLabel()])
                ];
            }
        }

        return $rules;
    }

    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public function getTableName()
    {
        return self::TABLE;
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return [
            'element' => [
                static::BELONGS_TO,
                'ElementRecord',
                'id',
                'required' => true,
                'onDelete' => static::CASCADE,
            ],
            'status'  => [
                static::BELONGS_TO,
                'Freeform_StatusRecord',
                'statusId',
                'required' => false,
                'onDelete' => static::SET_NULL,
            ],
            'form'    => [
                static::BELONGS_TO,
                'Freeform_FormRecord',
                'formId',
                'required' => true,
                'onDelete' => static::CASCADE,
            ],
        ];
    }

    /**
     * @return array
     */
    public function defineIndexes()
    {
        return [
            ['columns' => ['incrementalId'], 'unique' => false],
        ];
    }

    /**
     * Defines this model"s attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        /** @var Freeform_FieldModel[] $fields */
        $fields = craft()->freeform_fields->getAllFields();

        $attributes = [
            'incrementalId' => [AttributeType::Number, 'required' => false, 'default' => 0],
        ];

        foreach ($fields as $field) {
            $attributeType = $field->getAttributeType();

            $attributes["field_" . $field->id] = $attributeType;
        }

        return $attributes;
    }
}

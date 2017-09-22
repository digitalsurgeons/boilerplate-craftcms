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

use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;

/**
 * @property int    $id
 * @property int    $integrationId
 * @property string $resourceId
 * @property string $name
 * @property int    $memberCount
 */
class Freeform_MailingListModel extends BaseModel
{
    public static function create()
    {
        $model = new Freeform_MailingListModel();

        return $model;
    }

    /**
     * @return FieldObject[]
     */
    public function getFieldObjects()
    {
        /** @var Freeform_MailingListFieldRecord[] $fields */
        $fields = Freeform_MailingListFieldRecord::model()->findAllByAttributes(["mailingListId" => $this->id]);

        $fieldObjects = [];
        foreach ($fields as $field) {
            $fieldObjects[] = new FieldObject(
                $field->handle,
                $field->label,
                $field->type,
                $field->required
            );
        }

        return $fieldObjects;
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            "id"            => AttributeType::Number,
            "integrationId" => AttributeType::Number,
            "resourceId"    => AttributeType::Number,
            "name"          => AttributeType::String,
            "memberCount"   => AttributeType::Number,
        ];
    }
}

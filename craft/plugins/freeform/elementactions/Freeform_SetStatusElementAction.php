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

class Freeform_SetStatusElementAction extends BaseElementAction
{
    /**
     * @inheritDoc IElementAction::getTriggerHtml()
     *
     * @return string|null
     */
    public function getTriggerHtml()
    {
        return craft()->templates->render(
            'freeform/_components/fieldTypes/setStatusTrigger',
            ["statuses" => $this->getStatuses()]
        );
    }

    /**
     * @inheritDoc IElementAction::performAction()
     *
     * @param ElementCriteriaModel $criteria
     *
     * @return bool
     */
    public function performAction(ElementCriteriaModel $criteria)
    {
        $status = $this->getParams()->status;

        $elementIds = $criteria->ids();

        // Update their statuses
        craft()->db->createCommand()->update(
            Freeform_SubmissionRecord::TABLE,
            ['statusId' => $status],
            ['in', 'id', $elementIds]
        );

        // Clear their template caches
        craft()->templateCache->deleteCachesByElementId($elementIds);

        $this->setMessage(Craft::t('Statuses updated.'));

        return true;
    }

    /**
     * @inheritDoc BaseElementAction::defineParams()
     *
     * @return array
     */
    protected function defineParams()
    {
        return [
            'status' => [
                AttributeType::Enum,
                'values'   => array_keys($this->getStatuses()),
                'required' => true,
            ],
        ];
    }

    /**
     * @return Freeform_StatusModel[]
     */
    private function getStatuses()
    {
        $statuses = $this->getStatusService()->getAllStatuses();

        return $statuses;
    }

    /**
     * @return Freeform_StatusesService
     */
    private function getStatusService()
    {
        return craft()->freeform_statuses;
    }
}

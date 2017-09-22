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

class Freeform_DeleteSubmissionElementAction extends BaseElementAction
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritDoc IComponentType::getName()
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Delete…');
    }

    /**
     * @inheritDoc IElementAction::isDestructive()
     *
     * @return bool
     */
    public function isDestructive()
    {
        return true;
    }

    /**
     * @inheritDoc IElementAction::getConfirmationMessage()
     *
     * @return string|null
     */
    public function getConfirmationMessage()
    {
        return $this->getParams()->confirmationMessage;
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
        foreach($criteria->find() as $submission){
            craft()->freeform_submissions->delete($submission);
        }

        $this->setMessage($this->getParams()->successMessage);

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritDoc BaseElementAction::defineParams()
     *
     * @return array
     */
    protected function defineParams()
    {
        return array(
            'confirmationMessage' => array(AttributeType::String),
            'successMessage'      => array(AttributeType::String),
        );
    }
}

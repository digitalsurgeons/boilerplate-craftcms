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

class Freeform_SubmissionsFieldType extends BaseElementFieldType
{
    /** @var string $elementType */
    protected $elementType = Freeform_SubmissionModel::ELEMENT_TYPE;

    /**
     * Returns the label for the "Add" button.
     *
     * @access protected
     * @return string
     */
    protected function getAddButtonLabel()
    {
        return Craft::t('Add a submission');
    }
}

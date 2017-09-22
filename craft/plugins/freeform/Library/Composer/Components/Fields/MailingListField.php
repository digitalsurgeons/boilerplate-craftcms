<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2016, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Solspace\Freeform\Library\Composer\Components\Fields;

use Solspace\Freeform\Library\Composer\Components\AbstractField;
use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\InputOnlyInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\MailingListInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\SingleValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\MailingListTrait;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\SingleValueTrait;

class MailingListField extends AbstractField implements NoStorageInterface, SingleValueInterface, InputOnlyInterface, MailingListInterface
{
    use SingleValueTrait;
    use MailingListTrait;

    /** @var array */
    protected $mapping;

    /**
     * Return the field TYPE
     *
     * @return string
     */
    public function getType()
    {
        return FieldInterface::TYPE_MAILING_LIST;
    }

    /**
     * MailingList uses its HASH as the Handle
     *
     * @return string
     */
    public function getHandle()
    {
        return $this->getHash();
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Outputs the HTML of input
     *
     * @return string
     */
    public function getInputHtml()
    {
        $attributes = $this->getCustomAttributes();
        $output     = '';

        $isSelected = (bool)$this->getValue();

        $output .= '<label'
            . $this->getAttributeString("class", $attributes->getLabelClass())
            . '>';

        $output .= '<input '
            . $this->getAttributeString("name", $this->getHash())
            . $this->getAttributeString("type", "checkbox")
            . $this->getAttributeString("id", $this->getIdAttribute())
            . $this->getAttributeString("class", $attributes->getClass())
            . $this->getAttributeString("value", 1, false)
            . $this->getRequiredAttribute()
            . $attributes->getInputAttributesAsString()
            . ($isSelected ? 'checked ' : '')
            . '/>';
        $output .= $this->getLabel();
        $output .= '</label>';

        return $output;
    }
}

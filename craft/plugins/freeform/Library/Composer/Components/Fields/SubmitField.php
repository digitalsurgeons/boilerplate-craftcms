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
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\InputOnlyInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\SingleValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\SingleStaticValueTrait;

class SubmitField extends AbstractField implements SingleValueInterface, InputOnlyInterface, NoStorageInterface
{
    const PREVIOUS_PAGE_INPUT_NAME = "form_previous_page_button";
    const SUBMIT_INPUT_NAME        = "form_page_submit";

    const POSITION_LEFT   = "left";
    const POSITION_CENTER = "center";
    const POSITION_RIGHT  = "right";
    const POSITION_SPREAD = "spread";

    use SingleStaticValueTrait;

    /** @var string */
    protected $labelNext;

    /** @var string */
    protected $labelPrev;

    /** @var bool */
    protected $disablePrev;

    /** @var string */
    protected $position = self::POSITION_RIGHT;

    /**
     * Returns either "left", "center", "right" or "spread"
     * Does not return "spread" if the back button is disabled or this is the first page
     * In that case "left" is returned
     *
     * @return string
     */
    public function getPosition()
    {
        if ($this->position === self::POSITION_SPREAD) {
            if ($this->isDisablePrev() || $this->isFirstPage()) {
                return self::POSITION_LEFT;
            }
        }

        return $this->position;
    }

    /**
     * @return string
     */
    public function getLabelNext()
    {
        return $this->translate($this->labelNext);
    }

    /**
     * @return string
     */
    public function getLabelPrev()
    {
        return $this->translate($this->labelPrev);
    }

    /**
     * @return boolean
     */
    public function isDisablePrev()
    {
        return $this->disablePrev;
    }

    /**
     * Return the field TYPE
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE_SUBMIT;
    }

    /**
     * Outputs the HTML of input
     *
     * @return string
     */
    public function getInputHtml()
    {
        $attributes = $this->getCustomAttributes();
        $submitClass = $attributes->getInputClassOnly();
        $formSubmitClass = $this->getForm()->getCustomAttributes()->getSubmitClass();

        $submitClass = trim($submitClass . " " . $formSubmitClass);

        $output = "";

        if (!$this->isFirstPage() && !$this->isDisablePrev()) {
            $output .= '<input '
                . $this->getAttributeString("style", "height: 0px !important; width: 0px !important; visibility: hidden !important; position: absolute !important; left: -99999px !important; top: -9999px !important;")
                . $this->getAttributeString("type", "submit")
                . $this->getAttributeString("tabindex", -1, false)
                . ' />';

            $output .= '<button '
                . $this->getAttributeString("type", "submit")
                . $this->getAttributeString("class", $submitClass)
                . $this->getAttributeString("name", self::PREVIOUS_PAGE_INPUT_NAME)
                . $attributes->getInputAttributesAsString()
                . '>'
                . $this->getLabelPrev()
                . '</button>';
        }

        $output .= '<button '
            . $this->getAttributeString("type", "submit")
            . $this->getAttributeString("class", $submitClass)
            . $this->getAttributeString("name", self::SUBMIT_INPUT_NAME)
            . $attributes->getInputAttributesAsString()
            . '>'
            . $this->getLabelNext()
            . '</button>';

        return $output;
    }

    /**
     * @return bool
     */
    private function isFirstPage()
    {
        return $this->getPageIndex() === 0;
    }
}

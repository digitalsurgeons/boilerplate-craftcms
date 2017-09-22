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
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\MultipleValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\OptionsInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\MultipleValueTrait;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\OptionsTrait;

class CheckboxGroupField extends AbstractField implements MultipleValueInterface, OptionsInterface
{
    use MultipleValueTrait;
    use OptionsTrait;

    /**
     * Return the field TYPE
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE_CHECKBOX_GROUP;
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

        foreach ($this->options as $option) {
            $output .= '<label>';

            $output .= '<input '
                . $this->getAttributeString('name', $this->getHandle() . '[]')
                . $this->getAttributeString('type', 'checkbox')
                . $this->getAttributeString('id', $this->getIdAttribute())
                . $this->getAttributeString('class', $attributes->getClass())
                . $this->getAttributeString('value', $option->getValue(), false)
                . $attributes->getInputAttributesAsString()
                . ($option->isChecked() ? 'checked ' : '')
                . '/>';
            $output .= $this->translate($option->getLabel());
            $output .= '</label>';
        }

        return $output;
    }

    /**
     * @param bool $optionsAsValues
     *
     * @return string
     */
    public function getValueAsString($optionsAsValues = true)
    {
        if (!$optionsAsValues) {
            return implode(', ', $this->getValue());
        }

        $labels = [];
        foreach ($this->getOptions() as $option) {
            if ($option->isChecked()) {
                $labels[] = (string) $option->getLabel();
            }
        }

        return implode(', ', $labels);
    }
}

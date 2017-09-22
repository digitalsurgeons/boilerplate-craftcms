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

use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\DataContainers\Option;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\ObscureValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\RecipientInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\RecipientTrait;

class DynamicRecipientField extends SelectField implements RecipientInterface, ObscureValueInterface
{
    use RecipientTrait;

    /** @var bool */
    protected $showAsRadio;

    /**
     * @return boolean
     */
    public function isShowAsRadio()
    {
        return $this->showAsRadio;
    }

    /**
     * Return the field TYPE
     *
     * @return string
     */
    public function getType()
    {
        return FieldInterface::TYPE_DYNAMIC_RECIPIENTS;
    }

    /**
     * Outputs the HTML of input
     *
     * @return string
     */
    public function getInputHtml()
    {
        return $this->isShowAsRadio() ? $this->renderAsRadios() : $this->renderAsSelect();
    }

    /**
     * @param bool $optionsAsValues
     *
     * @return string
     */
    public function getValueAsString($optionsAsValues = true)
    {
        if (!$optionsAsValues) {
            return $this->getActualValue($this->getValue());
        }

        foreach ($this->getOptions() as $option) {
            if ($option->getValue() === $this->getActualValue($this->getValue())) {
                return (string) $option->getLabel();
            }
        }

        return '';
    }

    /**
     * Returns an array value of all possible recipient Email addresses
     *
     * Either returns an ["email", "email"] array
     * Or an array with keys as recipient names, like ["Jon Doe" => "email", ..]
     *
     * @return array
     */
    public function getRecipients()
    {
        /** @var Option[] $options */
        $options = $this->getOptions();
        $value   = $this->getValue();

        if (null !== $value && array_key_exists($value, $options)) {
            $option = $options[$value];

            return [$option->getLabel() => $option->getValue()];
        }

        return [];
    }

    /**
     * Return the real value of this field
     * Instead of the obscured one
     *
     * @param mixed $obscureValue
     *
     * @return mixed
     */
    public function getActualValue($obscureValue)
    {
        $options = $this->getOptions();
        if (isset($options[$obscureValue])) {
            return $options[$obscureValue]->getValue();
        }

        return null;
    }

    /**
     * @return string
     */
    private function renderAsSelect()
    {
        $attributes = $this->getCustomAttributes();

        $output = '<select '
            . $this->getAttributeString('name', $this->getHandle())
            . $this->getAttributeString('type', $this->getType())
            . $this->getAttributeString('id', $this->getIdAttribute())
            . $this->getAttributeString('class', $attributes->getClass())
            . $this->getRequiredAttribute()
            . $attributes->getInputAttributesAsString()
            . '>';

        foreach ($this->getOptions() as $index => $option) {
            $output .= '<option value="' . $index . '"' . ($option->isChecked() ? ' selected' : '') . '>';
            $output .= $this->getForm()->getTranslator()->translate($option->getLabel());
            $output .= '</option>';
        }

        $output .= '</select>';

        return $output;
    }

    /**
     * @return string
     */
    private function renderAsRadios()
    {
        $attributes = $this->getCustomAttributes();
        $output     = '';

        foreach ($this->options as $option) {
            $output .= '<label>';

            $output .= '<input '
                . $this->getAttributeString('name', $this->getHandle())
                . $this->getAttributeString('type', 'radio')
                . $this->getAttributeString('id', $this->getIdAttribute())
                . $this->getAttributeString('class', $attributes->getClass())
                . $this->getAttributeString('value', $this->getValue(), false)
                . $attributes->getInputAttributesAsString()
                . ($option->isChecked() ? 'checked ' : '')
                . '/>';
            $output .= $this->translate($option->getLabel());
            $output .= '</label>';
        }

        return $output;
    }
}

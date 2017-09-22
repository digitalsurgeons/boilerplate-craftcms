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

namespace Solspace\Freeform\Library\Composer\Components\Fields\Traits;

use Solspace\Freeform\Library\Composer\Components\Fields\CheckboxField;
use Solspace\Freeform\Library\Composer\Components\Fields\DataContainers\Option;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\OptionsInterface;

trait SingleValueTrait
{
    /** @var string */
    protected $value;

    /**
     * @return string
     */
    public function getValue()
    {
        if ($this->getValueOverride()) {
            return $this->getValueOverride();
        }

        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        if ($this instanceof OptionsInterface) {
            $updatedOptions = [];
            foreach ($this->getOptions() as $option) {
                if (is_numeric($option->getValue()) && is_numeric($this->getValue())) {
                    $checked = (int) $option->getValue() === (int) $this->getValue();
                } else {
                    $checked = $option->getValue() === $this->getValue();
                }

                $updatedOptions[] = new Option(
                    $option->getLabel(),
                    $option->getValue(),
                    $checked
                );
            }

            $this->options = $updatedOptions;
        }

        if ($this instanceof CheckboxField && $value !== $this->getStaticValue()) {
            $this->checkedByPost = $value === '1';
        }

        return $this;
    }
}

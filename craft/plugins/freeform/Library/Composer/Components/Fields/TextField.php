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
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\PlaceholderInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\SingleValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\PlaceholderTrait;
use Solspace\Freeform\Library\Composer\Components\Fields\Traits\SingleValueTrait;

class TextField extends AbstractField implements SingleValueInterface, PlaceholderInterface
{
    use PlaceholderTrait;
    use SingleValueTrait;

    /**
     * Return the field TYPE
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE_TEXT;
    }

    /**
     * Outputs the HTML of input
     *
     * @return string
     */
    protected function getInputHtml()
    {
        $attributes  = $this->getCustomAttributes();
        $classString = $attributes->getClass() . ' ' . $this->getInputClassString();

        return '<input '
            . $this->getAttributeString('name', $this->getHandle())
            . $this->getAttributeString('type', 'text')
            . $this->getAttributeString('id', $this->getIdAttribute())
            . $this->getAttributeString('class', $classString)
            . $this->getAttributeString(
                'placeholder',
                $this->translate($attributes->getPlaceholder() ?: $this->getPlaceholder())
            )
            . $this->getAttributeString('value', $this->getValue(), false)
            . $this->getRequiredAttribute()
            . $attributes->getInputAttributesAsString()
            . '/>';
    }
}

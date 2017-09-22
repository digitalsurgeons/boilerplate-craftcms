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

class TextareaField extends AbstractField implements SingleValueInterface, PlaceholderInterface
{
    use PlaceholderTrait;
    use SingleValueTrait;

    /** @var int */
    protected $rows;

    /**
     * Return the field TYPE
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE_TEXTAREA;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Outputs the HTML of input
     *
     * @return string
     */
    public function getInputHtml()
    {
        $attributes = $this->getCustomAttributes();

        return '<textarea '
            . $this->getAttributeString("name", $this->getHandle())
            . $this->getAttributeString("id", $this->getIdAttribute())
            . $this->getAttributeString("class", $attributes->getClass())
            . $this->getAttributeString("rows", $this->getRows())
            . $this->getRequiredAttribute()
            . $attributes->getInputAttributesAsString()
            . $this->getAttributeString(
                "placeholder",
                $this->translate($attributes->getPlaceholder() ?: $this->getPlaceholder())
            )
            . '>'
            . $this->getValue()
            . '</textarea>';
    }
}

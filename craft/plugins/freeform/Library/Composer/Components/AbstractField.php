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

namespace Solspace\Freeform\Library\Composer\Components;

use Craft\TemplateHelper;
use Solspace\Freeform\Library\Composer\Components\Attributes\CustomFieldAttributes;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\InputOnlyInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoRenderInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\ObscureValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\StaticValueInterface;
use Solspace\Freeform\Library\Composer\Components\Properties\FieldProperties;
use Solspace\Freeform\Library\Composer\Components\Validation\Constraints\ConstraintInterface;
use Solspace\Freeform\Library\Composer\Components\Validation\Validator;
use Solspace\Freeform\Library\Session\FormValueContext;
use Stringy\Stringy;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractField implements FieldInterface, \JsonSerializable
{
    /** @var Form */
    private $form;

    /** @var string */
    protected $hash;

    /** @var int */
    protected $id;

    /** @var string */
    protected $handle;

    /** @var string */
    protected $label;

    /** @var string */
    protected $instructions;

    /** @var bool */
    protected $required = false;

    /** @var array */
    protected $errors;

    /** @var CustomFieldAttributes */
    protected $customAttributes;

    /** @var int */
    protected $pageIndex;

    /** @var array */
    private $inputClasses;

    /**
     * @param Form             $form
     * @param FieldProperties  $properties
     * @param FormValueContext $formValueContext
     * @param int              $pageIndex
     *
     * @return AbstractField
     */
    final public static function createFromProperties(
        Form $form,
        FieldProperties $properties,
        FormValueContext $formValueContext,
        $pageIndex
    ) {
        $calledClass = get_called_class();

        $objectProperties = get_class_vars($calledClass);
        $accessor         = PropertyAccess::createPropertyAccessor();

        $field            = new static($form);
        $field->pageIndex = $pageIndex;

        foreach ($objectProperties as $fieldName => $type) {
            if ($fieldName === 'errors') {
                continue;
            }
            try {
                $field->{$fieldName} = $accessor->getValue($properties, $fieldName);
            } catch (NoSuchPropertyException $e) {
                // Pass along
            }
        }

        if ($field instanceof StaticValueInterface) {
            $field->staticValue = $field->getValue();
        }

        $storedValue = $formValueContext->getStoredValue($field->getHandle(), $field->getValue());
        $field->setValue($storedValue);

        return $field;
    }

    /**
     * @return array
     */
    public static function getFieldTypes()
    {
        return [
            self::TYPE_TEXT               => 'Text',
            self::TYPE_TEXTAREA           => 'Textarea',
            self::TYPE_EMAIL              => 'Email',
            self::TYPE_HIDDEN             => 'Hidden',
            self::TYPE_SELECT             => 'Select',
            self::TYPE_CHECKBOX           => 'Checkbox',
            self::TYPE_CHECKBOX_GROUP     => 'Checkbox group',
            self::TYPE_RADIO_GROUP        => 'Radio group',
            self::TYPE_FILE               => 'File upload',
            self::TYPE_DYNAMIC_RECIPIENTS => 'Dynamic Recipients',
            self::TYPE_DATETIME           => 'Date & Time',
            self::TYPE_NUMBER             => 'Number',
            self::TYPE_PHONE              => 'Phone',
            self::TYPE_WEBSITE            => 'Website',
            self::TYPE_RATING             => 'Rating',
            self::TYPE_REGEX              => 'Regex',
            self::TYPE_CONFIRMATION       => 'Confirmation',
        ];
    }

    /**
     * @return string
     */
    public static function getFieldTypeName()
    {
        return (string) Stringy::create(static::getFieldType())->humanize();
    }

    /**
     * @return string
     */
    public static function getFieldType()
    {
        $name = (new \ReflectionClass(get_called_class()))->getShortName();
        $name = str_replace('Field', '', $name);

        return (string) Stringy::create($name)->underscored();
    }

    /**
     * AbstractField constructor.
     *
     * @param Form $form
     */
    public final function __construct(Form $form)
    {
        $this->form             = $form;
        $this->customAttributes = new CustomFieldAttributes($this, [], $this->getForm()->getCustomAttributes());
        $this->inputClasses     = [];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValueAsString();
    }

    /**
     * Render the complete set of HTML for this field
     * That includes the Label, Input and Error messages
     *
     * @param array $customAttributes
     *
     * @return string
     */
    public final function render(array $customAttributes = null)
    {
        $this->setCustomAttributes($customAttributes);

        $output = '';
        if (!$this instanceof InputOnlyInterface) {
            $output .= $this->getLabelHtml();
        }

        // Show instructions above by default
        if (!$this->getCustomAttributes()->isInstructionsBelowField()) {
            $output .= $this->getInstructionsHtml();
        }

        $output .= $this->onBeforeInputHtml();
        $output .= $this->getInputHtml();
        $output .= $this->onAfterInputHtml();

        // Show instructions below only if set by a property
        if ($this->getCustomAttributes()->isInstructionsBelowField()) {
            $output .= $this->getInstructionsHtml();
        }

        if ($this->getErrors()) {
            $output .= $this->renderErrors();
        }

        return $this->renderRaw($output);
    }

    /**
     * Render the Label HTML
     *
     * @param array $customAttributes
     *
     * @return \Twig_Markup
     */
    public final function renderLabel(array $customAttributes = null)
    {
        $this->setCustomAttributes($customAttributes);

        return $this->renderRaw($this->getLabelHtml());
    }

    /**
     * @param array|null $customAttributes
     *
     * @return \Twig_Markup
     */
    public function renderInstructions(array $customAttributes = null)
    {
        $this->setCustomAttributes($customAttributes);

        return $this->renderRaw($this->getInstructionsHtml());
    }

    /**
     * Render the Input HTML
     *
     * @param array $customAttributes
     *
     * @return \Twig_Markup
     */
    public final function renderInput(array $customAttributes = null)
    {
        $this->setCustomAttributes($customAttributes);

        return $this->renderRaw($this->getInputHtml());
    }

    /**
     * Outputs the HTML of errors
     *
     * @param array $customAttributes
     *
     * @return string
     */
    public final function renderErrors(array $customAttributes = null)
    {
        $this->setCustomAttributes($customAttributes);

        return $this->renderRaw($this->getErrorHtml());
    }

    /**
     * @return bool
     */
    public final function canRender()
    {
        return (!$this instanceof NoRenderInterface);
    }

    /**
     * @return bool
     */
    public final function canStoreValues()
    {
        return (!$this instanceof NoStorageInterface);
    }

    /**
     * @return bool
     */
    public function isInputOnly()
    {
        return $this instanceof InputOnlyInterface;
    }

    /**
     * Validates the Field value
     *
     * @return bool
     */
    public function isValid()
    {
        $this->errors = $this->validate();

        return empty($this->errors);
    }

    /**
     * Returns an array of error messages
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        $errors = $this->getErrors();

        return !empty($errors);
    }

    /**
     * Return the field TYPE
     *
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->translate($this->label);
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->translate($this->instructions);
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return (bool) $this->required;
    }

    /**
     * @return int
     */
    public function getPageIndex()
    {
        return $this->pageIndex;
    }

    /**
     * Gets whatever value is set and returns its string representation
     *
     * @param bool $optionsAsValues
     *
     * @return string
     */
    public function getValueAsString($optionsAsValues = true)
    {
        $value = $this->getValue();

        if (!is_string($value)) {
            if (is_array($value)) {
                return implode(', ', $value);
            }

            return (string) $value;
        }

        return $value;
    }

    /**
     * Either gets the ID attribute specified in custom attributes
     * or generates a new one: "form-input-{handle}"
     *
     * @return string
     */
    public function getIdAttribute()
    {
        if ($this->getCustomAttributes()->getId()) {
            return $this->getCustomAttributes()->getId();
        }

        return sprintf("form-input-%s", $this->getHandle());
    }

    /**
     * Gets the overriden value if any present
     *
     * @return mixed
     */
    public function getValueOverride()
    {
        return $this->getCustomAttributes()->getOverrideValue();
    }

    /**
     * An alias for ::setCustomAttributes()
     *
     * @param array|null $attributes
     */
    public function setAttributes(array $attributes = null)
    {
        $this->setCustomAttributes($attributes);
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        return [];
    }

    /**
     * @return string
     */
    protected function getInputClassString()
    {
        return implode(' ', $this->inputClasses);
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    protected function addInputClass($class)
    {
        $this->inputClasses[] = $class;

        return $this;
    }

    /**
     * Assemble the Label HTML string
     *
     * @return string
     */
    protected function getLabelHtml()
    {
        $classAttribute = $this->getCustomAttributes()->getLabelClass();
        $classAttribute = $classAttribute ? ' class="' . $classAttribute . '"' : "";

        $forAttribute = sprintf(' for="%s"', $this->getIdAttribute());

        $output = '<label' . $classAttribute . $forAttribute . '>';
        $output .= $this->getLabel();
        $output .= '</label>';
        $output .= PHP_EOL;

        return $output;
    }

    /**
     * Assemble the Instructions HTML string
     *
     * @return string
     */
    protected function getInstructionsHtml()
    {
        if (!$this->getInstructions()) {
            return "";
        }

        $classAttribute = $this->getCustomAttributes()->getInstructionsClass();
        $classAttribute = $classAttribute ? ' class="' . $classAttribute . '"' : "";

        $output = '<div' . $classAttribute . '>';
        $output .= $this->getInstructions();
        $output .= '</div>';
        $output .= PHP_EOL;

        return $output;
    }

    /**
     * Assemble the Error HTML output string
     *
     * @return string
     */
    protected function getErrorHtml()
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return "";
        }

        $class = "errors ";
        $class .= $this->getCustomAttributes()->getErrorClass();

        $output = '<ul class="' . $class . '">';

        foreach ($errors as $error) {
            $output .= '<li>' . $error . '</li>';
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * @return CustomFieldAttributes
     */
    protected function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    /**
     * Outputs ' $name="$value"' where the $value is escaped
     * using htmlspecialchars() if $escapeValue is TRUE
     *
     * @param string $name
     * @param string $value
     * @param bool   $escapeValue
     *
     * @return string
     */
    protected function getAttributeString($name, $value, $escapeValue = true)
    {
        $value = trim($value);
        if (!empty($value)) {
            return sprintf(
                ' %s="%s"',
                $name,
                $escapeValue ? htmlspecialchars($value) : $value
            );
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getRequiredAttribute()
    {
        $attribute = '';

        if ($this->getCustomAttributes()->getUseRequiredAttribute() && $this->isRequired()) {
            $attribute = ' required';
        }

        return $attribute;
    }

    /**
     * Assemble the Input HTML string
     *
     * @return string
     */
    abstract protected function getInputHtml();

    /**
     * Output something before an input HTML is output
     *
     * @return string
     */
    protected function onBeforeInputHtml()
    {
    }

    /**
     * Output something after an input HTML is output
     *
     * @return string
     */
    protected function onAfterInputHtml()
    {
    }

    /**
     * Validate the field and add error messages if any
     *
     * @return array
     */
    protected function validate()
    {
        $errors = [];

        if ($this instanceof ObscureValueInterface) {
            $value = $this->getActualValue($this->getValue());
        } else {
            $value = $this->getValue();
        }

        if ($this->isRequired()) {
            if (is_array($value)) {
                $value = array_filter($value);

                if (empty($value)) {
                    $errors[] = $this->translate('This field is required');
                }
            } else if (!strlen($value)) {
                $errors[] = $this->translate('This field is required');
            }
        }

        if (!empty($value)) {
            static $validator;

            if (null === $validator) {
                $validator = new Validator();
            }

            $violationList = $validator->validate($this, $value);

            $errors = array_merge($errors, $violationList->getErrors());
        }

        return $errors;
    }

    /**
     * @return Form
     */
    protected function getForm()
    {
        return $this->form;
    }

    /**
     * An alias method for translator
     *
     * @param string $string
     * @param array  $variables
     *
     * @return string
     */
    protected function translate($string, array $variables = [])
    {
        return $this->getForm()->getTranslator()->translate($string, $variables);
    }

    /**
     * @param string $output
     *
     * @return \Twig_Markup
     */
    private function renderRaw($output)
    {
        return TemplateHelper::getRaw($output);
    }

    /**
     * Sets the custom field attributes
     *
     * @param array|null $attributes
     */
    private function setCustomAttributes(array $attributes = null)
    {
        if (!is_null($attributes)) {
            $this->customAttributes->mergeAttributes($attributes);
        }
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->hash;
    }
}

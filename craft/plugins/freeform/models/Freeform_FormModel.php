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

use Solspace\Freeform\Library\Composer\Attributes\FormAttributes;
use Solspace\Freeform\Library\Composer\Components\Attributes\CustomFormAttributes;
use Solspace\Freeform\Library\Composer\Components\Form;
use Solspace\Freeform\Library\Composer\Components\Layout;
use Solspace\Freeform\Library\Composer\Composer;
use Solspace\Freeform\Library\Exceptions\Composer\ComposerException;
use Solspace\Freeform\Library\Session\CraftRequest;
use Solspace\Freeform\Library\Session\CraftSession;
use Solspace\Freeform\Library\Translations\CraftTranslator;

/**
 * Class Freeform_FormModel
 *
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property int    $spamBlockCount
 * @property string $submissionTitleFormat
 * @property string $description
 * @property string $layoutJson
 * @property string $returnUrl
 * @property int    $defaultStatus
 * @property int    $formTemplateId
 * @property string $color
 */
class Freeform_FormModel extends BaseModel
{
    /** @var Composer */
    private $composer;

    /**
     * Factory Method
     *
     * @return Freeform_FormModel
     */
    public static function create()
    {
        $form = new Freeform_FormModel();
        $form->spamBlockCount = 0;

        return $form;
    }

    /**
     * Sets names, handles, descriptions
     * And updates the layout JSON
     *
     * @param Composer $composer
     */
    public function setLayout(Composer $composer)
    {
        $form                        = $composer->getForm();
        $this->name                  = $form->getName();
        $this->handle                = $form->getHandle();
        $this->submissionTitleFormat = $form->getSubmissionTitleFormat();
        $this->description           = $form->getDescription();
        $this->defaultStatus         = $form->getDefaultStatus();
        $this->returnUrl             = $form->getReturnUrl();
        $this->color                 = $form->getColor();
        $this->layoutJson            = $composer->getComposerStateJSON();
    }

    /**
     * Assembles the composer object and returns it
     *
     * @return Composer
     * @throws ComposerException
     */
    public function getComposer()
    {
        return $this->composer = new Composer(
            json_decode($this->layoutJson, true),
            $this->getFormAttributes(),
            craft()->freeform_forms,
            craft()->freeform_submissions,
            craft()->freeform_mailer,
            craft()->freeform_files,
            craft()->freeform_mailingLists,
            craft()->freeform_crm,
            craft()->freeform_statuses,
            new CraftTranslator()
        );
    }

    /**
     * @return Layout
     */
    public function getLayout()
    {
        return $this->getComposer()->getForm()->getLayout();
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->getComposer()->getForm();
    }

    /**
     * @return array
     */
    public function getLayoutAsJson()
    {
        return $this->getComposer()->getComposerStateJSON();
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        return UrlHelper::getCpUrl('freeform/forms/' . $this->id);
    }

    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'id'                    => AttributeType::Number,
            'name'                  => AttributeType::String,
            'handle'                => AttributeType::Handle,
            'spamBlockCount'        => AttributeType::Number,
            'submissionTitleFormat' => AttributeType::String,
            'description'           => AttributeType::Mixed,
            'layoutJson'            => ColumnType::LongText,
            'returnUrl'             => AttributeType::String,
            'defaultStatus'         => AttributeType::Number,
            'color'                 => AttributeType::String,
        ];
    }

    /**
     * @return FormAttributes
     */
    private function getFormAttributes()
    {
        $attributes = new FormAttributes($this->id, new CraftSession(), new CraftRequest());
        $attributes
            ->setActionUrl("freeform/api/form")
            ->setCsrfEnabled(craft()->config->get("enableCsrfProtection"))
            ->setCsrfToken(craft()->request->csrfToken)
            ->setCsrfTokenName(craft()->config->get("csrfTokenName"));

        return $attributes;
    }
}

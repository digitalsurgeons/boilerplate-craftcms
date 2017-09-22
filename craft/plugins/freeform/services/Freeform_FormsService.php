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

use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\LargeDataStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\MediumDataStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Form;
use Solspace\Freeform\Library\Database\FormHandlerInterface;
use Solspace\Freeform\Library\Exceptions\FreeformException;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_FormsService extends BaseApplicationComponent implements FormHandlerInterface
{
    /** @var Freeform_FormModel[] */
    private static $formCache;
    private static $allFormsLoaded;
    private static $spamCountIncrementedForms = [];

    /**
     * @return Freeform_FormModel[]
     */
    public function getAllForms()
    {
        if (null === self::$formCache || !self::$allFormsLoaded) {
            $formRecords     = Freeform_FormRecord::model()->ordered()->findAll();
            self::$formCache = Freeform_FormModel::populateModels($formRecords, 'id');

            self::$allFormsLoaded = true;
        }

        return self::$formCache;
    }

    /**
     * @param bool $indexById
     *
     * @return array
     */
    public function getAllFormNames($indexById = true)
    {
        $forms = $this->getAllForms();

        $list = [];
        foreach ($forms as $form) {
            if ($indexById) {
                $list[$form->id] = $form->name;
            } else {
                $list[] = $form->name;
            }
        }

        return $list;
    }

    /**
     * @param int $id
     *
     * @return Freeform_FormModel|null
     */
    public function getFormById($id)
    {
        if (null === self::$formCache || !isset(self::$formCache[$id])) {
            if (null === self::$formCache) {
                self::$formCache = [];
            }

            $formRecord = Freeform_FormRecord::model()->findById($id);

            self::$formCache[$id] = $formRecord ? Freeform_FormModel::populateModel($formRecord) : null;
        }

        return self::$formCache[$id];
    }

    /**
     * @param string $handle
     *
     * @return Freeform_FormModel|null
     */
    public function getFormByHandle($handle)
    {
        $formRecord = Freeform_FormRecord::model()->findByAttributes(["handle" => $handle]);

        return $formRecord ? Freeform_FormModel::populateModel($formRecord) : null;
    }

    /**
     * @param $handleOrId
     *
     * @return Freeform_FormModel|null
     */
    public function getFormByHandleOrId($handleOrId)
    {
        if (is_numeric($handleOrId)) {
            return $this->getFormById($handleOrId);
        }

        return $this->getFormByHandle($handleOrId);
    }

    /**
     * @param Freeform_FormModel $form
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_FormModel $form)
    {
        $isNewForm = !$form->id;

        if (!$isNewForm) {
            $formRecord = Freeform_FormRecord::model()->findById($form->id);

            if (!$formRecord) {
                throw new Exception(Craft::t('Form with ID {id} not found', ['id' => $form->id]));
            }
        } else {
            $formRecord = new Freeform_FormRecord();
        }

        $beforeSaveEvent = $this->onBeforeSave($form, $isNewForm);

        $formRecord->name                  = $form->name;
        $formRecord->handle                = $form->handle;
        $formRecord->spamBlockCount        = $form->spamBlockCount;
        $formRecord->description           = $form->description;
        $formRecord->layoutJson            = $form->layoutJson;
        $formRecord->submissionTitleFormat = $form->submissionTitleFormat;
        $formRecord->returnUrl             = $form->returnUrl;
        $formRecord->defaultStatus         = $form->defaultStatus;
        $formRecord->color                 = $form->color;

        $formRecord->validate();
        $form->addErrors($formRecord->getErrors());

        if ($beforeSaveEvent->performAction && !$form->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $formRecord->save(false);

                if (!$form->id) {
                    $form->id = $formRecord->id;
                }

                self::$formCache[$form->id] = $form;

                if ($transaction !== null) {
                    $transaction->commit();
                }

                $this->onAfterSave($form, $isNewForm);

                return true;
            } catch (\Exception $e) {
                if ($transaction !== null) {
                    $transaction->rollback();
                }

                throw $e;
            }
        }

        return false;
    }

    /**
     * Increments the spam block counter by 1
     *
     * @param Form $form
     *
     * @return int - new spam block count
     */
    public function incrementSpamBlockCount(Form $form)
    {
        $handle = $form->getHandle();
        if (isset(self::$spamCountIncrementedForms[$handle])) {
            return self::$spamCountIncrementedForms[$handle];
        }

        $spamBlockCount = (int) craft()
            ->db
            ->createCommand()
            ->select('spamBlockCount')
            ->from(Freeform_FormRecord::TABLE)
            ->where('id = :formId', ['formId' => $form->getId()])
            ->queryScalar();

        craft()
            ->db
            ->createCommand()
            ->update(
                Freeform_FormRecord::TABLE,
                ['spamBlockCount' => (++$spamBlockCount)],
                'id = :formId',
                ['formId' => $form->getId()]
            );

        self::$spamCountIncrementedForms[$handle] = $spamBlockCount;

        return $spamBlockCount;
    }

    /**
     * @param int $formId
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteById($formId)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_FORMS_MANAGE);

        $formModel = $this->getFormById($formId);

        if (!$formModel) {
            return false;
        }

        if (!$this->onBeforeDelete($formModel)->performAction) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {
            $affectedRows = craft()->db
                ->createCommand()
                ->delete('freeform_forms', ['id' => $formId]);

            if ($transaction !== null) {
                $transaction->commit();
            }

            $this->onAfterDelete($formModel);

            return (bool) $affectedRows;
        } catch (\Exception $exception) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $exception;
        }
    }

    /**
     * @param Form   $form
     * @param string $templateName
     *
     * @return string
     * @throws FreeformException
     */
    public function renderFormTemplate(Form $form, $templateName)
    {
        $settings = $this->getSettingsService();

        if (empty($templateName)) {
            throw new FreeformException(Craft::t("Can't use render() if no form template specified"));
        }

        $customTemplates   = $settings->getCustomFormTemplates();
        $solspaceTemplates = $settings->getSolspaceFormTemplates();

        $templatePath = null;
        foreach ($customTemplates as $template) {
            if ($template->getFileName() === $templateName) {
                $templatePath = $template->getFilePath();
                break;
            }
        }

        if (!$templatePath) {
            foreach ($solspaceTemplates as $template) {
                if ($template->getFileName() === $templateName) {
                    $templatePath = $template->getFilePath();
                    break;
                }
            }
        }

        if (null === $templatePath || !file_exists($templatePath)) {
            throw new FreeformException(Craft::t("Form template '{name}' not found", ['name' => $templateName]));
        }

        $pathinfo = pathinfo($templatePath);

        craft()->templates->setTemplatesPath($pathinfo['dirname']);

        $output = craft()->templates->render(
            $pathinfo['basename'],
            [
                'form' => $form,
            ]
        );

        craft()->templates->setTemplatesPath(craft()->path->getSiteTemplatesPath());

        return TemplateHelper::getRaw($output);
    }

    /**
     * @return bool
     */
    public function isSpamProtectionEnabled()
    {
        return $this->getSettingsService()->isSpamProtectionEnabled();
    }

    /**
     * @param $deletedStatusId
     * @param $newStatusId
     */
    public function swapDeletedStatusToDefault($deletedStatusId, $newStatusId)
    {
        $deletedStatusId = (int) $deletedStatusId;
        $newStatusId     = (int) $newStatusId;

        $pattern = "/\"defaultStatus\":$deletedStatusId(\}|,)/";

        $forms = $this->getAllForms();
        foreach ($forms as $form) {
            $layout = $form->layoutJson;
            if (preg_match($pattern, $layout)) {
                $layout = preg_replace(
                    $pattern,
                    "\"defaultStatus\":$newStatusId$1",
                    $form->layoutJson
                );

                $form->layoutJson = $layout;
                $this->save($form);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function addScriptsToPage(Form $form)
    {
        if ($this->isSpamProtectionEnabled()) {
            craft()->templates->includeJs($form->getHoneypotJavascriptScript());
        }

        if ($form->getLayout()->hasDatepickerEnabledFields()) {
            static $datepickerLoaded;

            if (null === $datepickerLoaded) {
                $flatpickrCss = file_get_contents(__DIR__ . '/../resources/css/fields/datepicker.css');
                craft()->templates->includeCss($flatpickrCss, true);

                $flatpickrJs = file_get_contents(__DIR__ . '/../resources/js/cp/fields/flatpickr.js');
                $datepickerJs = file_get_contents(__DIR__ . '/../resources/js/cp/fields/datepicker.js');
                craft()->templates->includeJs($flatpickrJs);
                craft()->templates->includeJs($datepickerJs);

                $datepickerLoaded = true;
            }
        }
    }

    /**
     * @return Freeform_SettingsService
     */
    private function getSettingsService()
    {
        return craft()->freeform_settings;
    }

    /**
     * @param Freeform_FormModel $model
     * @param bool               $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_FormModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_FormModel $model
     * @param bool               $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_FormModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_FormModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_FormModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_FormModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_FormModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}

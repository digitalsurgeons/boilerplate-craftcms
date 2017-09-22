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

use Solspace\Freeform\Library\DataObjects\FormTemplate;

class Freeform_SettingsService extends BaseApplicationComponent
{
    /** @var Freeform_SettingsModel */
    private static $settingsModel;

    /**
     * @return bool
     */
    public function isSpamProtectionEnabled()
    {
        return (bool)$this->getSettingsModel()->spamProtectionEnabled;
    }

    /**
     * @return string
     */
    public function getFieldDisplayOrder()
    {
        return $this->getSettingsModel()->fieldDisplayOrder;
    }

    /**
     * @return string
     */
    public function getFormTemplateDirectory()
    {
        return $this->getSettingsModel()->formTemplateDirectory;
    }

    /**
     * @return string
     */
    public function getSolspaceFormTemplateDirectory()
    {
        return CRAFT_PLUGINS_PATH . "freeform/templates/_defaultFormTemplates";
    }

    /**
     * Mark the tutorial as finished
     */
    public function finishTutorial()
    {
        $plugin = craft()->plugins->getPlugin('freeform');
        if (craft()->plugins->savePluginSettings($plugin, ["showTutorial" => false])) {
            return true;
        }

        return false;
    }

    /**
     * @return FormTemplate[]
     */
    public function getSolspaceFormTemplates()
    {
        $templateDirectoryPath = $this->getSolspaceFormTemplateDirectory();

        $templates = [];
        foreach (IOHelper::getFiles($templateDirectoryPath) as $file) {
            if (@is_dir($file)) {
                continue;
            }

            $templates[] = new FormTemplate($file);
        }

        return $templates;
    }

    /**
     * @return FormTemplate[]
     */
    public function getCustomFormTemplates()
    {
        $templates = [];
        foreach ($this->getSettingsModel()->listTemplatesInFormTemplateDirectory() as $path => $name) {
            $templates[] = new FormTemplate($path);
        }

        return $templates;
    }

    /**
     * @return bool
     */
    public function isDbEmailTemplateStorage()
    {
        $settings = $this->getSettingsModel();

        return !$settings->emailTemplateDirectory || $settings->emailTemplateStorage == Freeform_SettingsModel::EMAIL_TEMPLATE_STORAGE_DB;
    }

    /**
     * @return Freeform_SettingsModel
     */
    public function getSettingsModel()
    {
        if (is_null(self::$settingsModel)) {
            /** @var FreeformPlugin $plugin */
            $plugin              = craft()->plugins->getPlugin('freeform');
            self::$settingsModel = $plugin->getSettings();
        }

        return self::$settingsModel;
    }
}

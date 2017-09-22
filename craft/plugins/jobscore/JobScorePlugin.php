<?php
/**
 * Job Score plugin for Craft CMS
 *
 * Sync Job Score postings to your Craft CMS
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScorePlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init()
    {
    }

    /**
     * @return mixed
     */
    public function getName()
    {
         return Craft::t('Job Score');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('Sync Job Score postings to your Craft CMS');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/owldesign/jobscore/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/owldesign/jobscore/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '0.0.1';
    }

    /**
     * @return string
     */
    public function getSchemaVersion()
    {
        return '0.0.1';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Vadim Goncharov';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://roundhouseagency.com';
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     */
    public function onBeforeInstall()
    {
    }

    /**
        After install redirect to settings page
     */
    public function onAfterInstall()
    {
        craft()->request->redirect(UrlHelper::getCpUrl('settings/plugins/jobscore'));
    }

    /**
     */
    public function onBeforeUninstall()
    {
    }

    /**
     */
    public function onAfterUninstall()
    {
    }

    /**
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'organizationName' => AttributeType::String
        );
    }

    /**
     * @return mixed
     */
    public function getSettingsHtml()
    {
       return craft()->templates->render('jobscore/JobScore_Settings', array(
           'settings' => $this->getSettings()
       ));
    }

    /**
     * @param mixed $settings  The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings($settings)
    {
        // Modify $settings here...

        return $settings;
    }

    /**
     * @param mixed $settings  Plugin Routes
     *
     * @return mixed
     */
    public function registerCpRoutes()
    {
      return array(
        'jobscore' => array('action' => 'jobScore/index'),
      );
    }

}
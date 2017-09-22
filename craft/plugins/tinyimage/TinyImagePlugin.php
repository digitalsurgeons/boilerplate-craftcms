<?php

namespace Craft;

class TinyImagePlugin extends BasePlugin
{
    /**
     * Initialize the plugin.
     */
    public function init()
    {
        parent::init();

        // Event for assets.onSaveAsset
        craft()->on('assets.onSaveAsset', function (Event $event) {

            // if new asset and optimizeOnAssetSave
            if ($event->params['isNewAsset']) {
                // check if we should optimize the asset on save
                craft()->tinyImage_image->checkOptimizeOnAssetSave($event->params['asset']);
            }

        });

        // Event for assets.onBeforeDeleteAsset
        craft()->on('assets.onBeforeDeleteAsset', function (Event $event) {
            if ($event->params['asset']) {
                craft()->tinyImage_image->isAssetIgnored($event->params['asset']);
            }
        });
    }

    /**
     * Return the plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Tiny Image';
    }

    /**
     * Return the plugin version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.1.0';
    }

    /**
     * Return the developers name.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Jason McCallister';
    }

    /**
     * Return the developer URL.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://mccallister.io';
    }

    /**
     * Return if the plugin has a CP section.
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * Define the plugin settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'apiKey' => array(
                'type' => AttributeType::String,
                'required' => true,
            ),
            'maximumSize' => array(
                'type' => AttributeType::String,
            ),
            'optimizeOnAssetSave' => array(
                'type' => AttributeType::Bool,
            ),
            'protocol' => array(
                'type' => AttributeType::String,
                'default' => 'fopen',
            ),
            'useSslCert' => array(
                'type' => AttributeType::Bool,
                'default' => false,
            ),
        );
    }

    /**
     * Get the settings HTML.
     *
     * @return string
     */
    public function getSettingsHtml()
    {
        return craft()->templates->render('tinyimage/settings', array(
            'settings' => $this->getSettings(),
        ));
    }

    /**
     * Register any CP routes.
     *
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'tinyimage' => array(
                'action' => 'tinyImage/index',
            ),
            'tinyimage/sources' => array(
                'action' => 'tinyImage/sources',
            ),
            'tinyimage/ignored' => array(
                'action' => 'tinyImage/ignored',
            ),
            'tinyimage\/optimizeImage\/(?P<imageId>\d+)' => array(
                'action' => 'tinyImage/optimize',
            ),
            'tinyimage\/optimizeSource\/(?P<imageId>\d+)' => array(
                'action' => 'tinyImage/optimize',
            ),
            'tinyimage\/ignore\/(?P<imageId>\d+)' => array(
                'action' => 'tinyImage/ignore',
            ),
            'tinyimage\/remove\/(?P<imageId>\d+)' => array(
                'action' => 'tinyImage/unignore',
            ),
        );
    }
}

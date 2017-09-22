<?php

namespace Craft;

class TinyImageController extends BaseController
{
    /**
     * API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Create a new controller instance.
     */
    public function init()
    {
        // get the plugin settings
        $settings = craft()->plugins->getPlugin('tinyImage')->getSettings();

        // set the API key
        $this->apiKey = $settings->apiKey;
    }

    /**
     * Return all images.
     *
     * @throws HttpException
     */
    public function actionIndex()
    {
        // verify the api key exists
        $this->verifyApiKeyExists();

        // get all images from our service
        $assets = craft()->tinyImage_image->getAllImages();

        // render the template with assets variable
        $this->renderTemplate('tinyimage/_images', array(
            'assets' => $assets
        ));
    }

    /**
     * Show all sources.
     *
     * @throws HttpException
     */
    public function actionSources()
    {
        $sources = craft()->assetSources->getAllSources();

        // render the template with assets variable
        $this->renderTemplate('tinyimage/_sources', array(
            'sources' => $sources
        ));
    }

    /**
     * Show all ignored assets.
     *
     * @throws HttpException
     */
    public function actionIgnored()
    {
        // get all images from service
        $assets = craft()->tinyImage_image->allIgnoredAssets();

        // render the template with assets variable
        $this->renderTemplate('tinyimage/_ignored', array(
            'assets' => $assets
        ));
    }

    /**
     * Optimize an image.
     *
     * @throws HttpException
     */
    public function actionOptimizeImage()
    {
        // require ajax request
        $this->requireAjaxRequest();

        // determine if image param exists
        if (craft()->request->getParam('asset') != null) {
            $assetId = craft()->request->getParam('asset');

            // create a task to optimize an image
            craft()->tasks->createTask('TinyImage_Image', null, array(
                'assetId' => $assetId
            ));

            // return with no message
            $return['success'] = true;

            $this->returnJson($return);
        } else {
            $return['success'] = false;
            $return['message'] = "Missing the asset ID";

            $this->returnJson($return);
        }
    }

    /**
     * Optimize a source.
     *
     * @throws HttpException
     */
    public function actionOptimizeSource()
    {
        // require ajax request
        $this->requireAjaxRequest();

        // determine if image param exists
        if (craft()->request->getParam('source') != null) {
            $sourceId = craft()->request->getParam('source');

            // create a task to optimize a source
            craft()->tasks->createTask('TinyImage_Source', null, array(
                'sourceId' => $sourceId
            ));

            // return with no message
            $return['success'] = true;

            $this->returnJson($return);
        } else {
            $return['success'] = false;
            $return['message'] = "Missing the source ID";

            $this->returnJson($return);
        }
    }

    /**
     * Ignore an image.
     *
     * @throws HttpException
     */
    public function actionIgnore()
    {
        // require ajax request
        $this->requireAjaxRequest();

        // determine if image param exists
        if (craft()->request->getParam('asset') != null) {
            $assetId = craft()->request->getParam('asset');

            $response = craft()->tinyImage_image->ignoreImage($assetId);

            $this->handleResponse($response);
        } else {
            $this->handleResponse(false, "Missing the Asset ID");
        }
    }

    /**
     * Remove an image from ignore.
     *
     * @throws HttpException
     */
    public function actionUnignore()
    {
        // require ajax request
        $this->requireAjaxRequest();

        // determine if image param exists
        if (craft()->request->getParam('asset') != null) {
            $assetId = craft()->request->getParam('asset');

            $response = craft()->tinyImage_image->removeImageIgnore($assetId);

            $this->handleResponse($response);
        } else {
            $this->handleResponse(false, 'Missing the Asset ID');
        }
    }

    /**
     * Check if the API key is set.
     */
    protected function verifyApiKeyExists()
    {
        // if the api key is blank
        if ($this->apiKey == '') {
            // display an error
            craft()->userSession->setError('An API key for Tiny PNG is required');

            // redirect to the plugins settings
            $this->redirect(UrlHelper::getCpUrl() . '/settings/plugins/tinyimage');
        }
    }

    /**
     * Handle the servers response.
     *
     * @param $response
     * @param null $message
     */
    protected function handleResponse($response, $message = null)
    {
        if ($response) {
            $return['success'] = true;

            $this->returnJson($return);
        }

        $return['success'] = false;

        if (!is_null($message)) {
            $return['message'] = "$message";
        }

        $this->returnJson($return);
    }
}

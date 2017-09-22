<?php

namespace Craft;

class TinyImageService extends BaseApplicationComponent
{
    /**
     * API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API endpoint
     *
     * @var string
     */
    protected $endpoint = 'https://api.tinify.com/shrink';

    /**
     *  Maximum size to show.
     *
     * @var
     */
    protected $maximumSize;

    /**
     *  If we should optimize on new asset saves.
     *
     * @var
     */
    protected $optimizeOnAssetSave;

    /**
     * Which protocol to use for sending requests.
     *
     * @var
     */
    protected $protocol;

    /**
     * Should the requests use the included SSL certificate.
     *
     * @var
     */
    protected $useSsl;

    /**
     * Initialize the service.
     */
    public function init()
    {
        $settings = craft()->plugins->getPlugin('tinyImage')->getSettings();

        // get the maximum size for images
        $this->maximumSize = $settings->maximumSize;

        // should we optimize on asset save
        $this->optimizeOnAssetSave = $settings->optimizeOnAssetSave;

        // set the API protocol if empty
        if ($settings->protocol == '') {
            $this->protocol = 'fopen';
        } else {
            $this->protocol = $settings->protocol;
        }

        // set the API key
        $this->apiKey = $settings->apiKey;

        // should the requests use SSL
        $this->useSsl = $settings->useSslCert;
    }

    /**
     * Use fopen to send the request.
     *
     * @param $filePath
     * @param $asset
     * @return bool
     */
    public function sendWithFopen($filePath, $asset)
    {
        // set the options to use fopen ssl
        $fopenSslOptions = $this->setFopenSslOptions();

        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => array(
                    "Content-type: image/png",
                    "Authorization: Basic " . base64_encode("api:$this->apiKey")
                ),
                "content" => file_get_contents($filePath)
            ),
            "ssl" => $fopenSslOptions
        );

        $result = fopen($this->endpoint, "r", false, stream_context_create($options));

        if ($result) {
            /* Compression was successful, retrieve output from Location header. */
            foreach ($http_response_header as $header) {
                if (strtolower(substr($header, 0, 10)) === "location: ") {
                    file_put_contents($filePath, fopen(substr($header, 10), "rb", false));

                    // update the asset size
                    $this->updateAssetAttributes($asset, $filePath);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Set the fopen SSL options.
     *
     * @return array
     */
    protected function setFopenSslOptions()
    {
        if ($this->useSsl != '') {
            return array(
                "cafile" => CRAFT_PLUGINS_PATH . '/tinyimage/vendor/cacert.pem',
                "verify_peer" => true
            );
        } else {
            return array(
                "verify_peer" => true
            );
        }
    }

    /**
     * Use curl to send the request.
     *
     * @param $filePath
     * @param $asset
     * @return bool
     */
    protected function sendWithCurl($filePath, $asset)
    {
        $request = curl_init();

        $curlRequestOptions = $this->setCurlRequestOptions($filePath);

        curl_setopt_array($request, $curlRequestOptions);

        $response = curl_exec($request);

        if (curl_getinfo($request, CURLINFO_HTTP_CODE) === 201) {

            /* Compression was successful, retrieve output from Location header. */
            $headers = substr($response, 0, curl_getinfo($request, CURLINFO_HEADER_SIZE));

            foreach (explode("\r\n", $headers) as $header) {
                if (strtolower(substr($header, 0, 10)) === "location: ") {

                    $request = curl_init();

                    // set curl options for issues with SSL
                    $curlSuccessOptions = $this->setCurlSuccessOptions($header);

                    curl_setopt_array($request, $curlSuccessOptions);

                    file_put_contents($filePath, curl_exec($request));

                    // update the asset size
                    $this->updateAssetAttributes($asset, $filePath);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Set the request options for cURL.
     *
     * @param $filePath
     * @return array
     */
    protected function setCurlRequestOptions($filePath)
    {
        if ($this->useSsl != '') {
            return array(
                CURLOPT_URL => "$this->endpoint",
                CURLOPT_USERPWD => "api:" . $this->apiKey,
                CURLOPT_POSTFIELDS => file_get_contents($filePath),
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_CAINFO => __DIR__ . "/vendor/cacert.pem",
                CURLOPT_SSL_VERIFYPEER => true
            );
        } else {
            return array(
                CURLOPT_URL => "$this->endpoint",
                CURLOPT_USERPWD => "api:" . $this->apiKey,
                CURLOPT_POSTFIELDS => file_get_contents($filePath),
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_SSL_VERIFYPEER => true
            );
        }
    }

    /**
     * Set the success options for cURL.
     *
     * @param $header
     * @return array
     */
    protected function setCurlSuccessOptions($header)
    {
        if ($this->useSsl != '') {
            return array(
                CURLOPT_URL => substr($header, 10),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CAINFO => __DIR__ . "/vendor/cacert.pem",
                CURLOPT_SSL_VERIFYPEER => true
            );
        } else {
            return array(
                CURLOPT_URL => substr($header, 10),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true
            );
        }
    }

    /**
     * Update an assets file size and modified date.
     *
     * @param $asset
     * @param $filePath
     */
    protected function updateAssetAttributes($asset, $filePath)
    {
        // find the asset record
        $assetRecord = AssetFileRecord::model()->findById($asset->id);

        $fileSize = filesize($filePath);

        $timestamp = new DateTime();

        // has to be a string
        $assetRecord->size = "$fileSize";
        $assetRecord->dateModified = $timestamp;
        $assetRecord->dateUpdated = $timestamp;

        $assetRecord->save();
    }

    protected function removeIgnoredAssets($assets)
    {
        # code...
    }
}

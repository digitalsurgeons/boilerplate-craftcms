<?php

namespace Craft;

class TinyImage_ImageService extends TinyImageService
{
    /**
     * Optimize an image.
     *
     * @param $assetId
     * @return bool
     */
    public function optimizeImage($assetId)
    {
        // find the asset by id
        $asset = craft()->assets->getFileById($assetId);

        // find the source by source id
        $folder = craft()->assetSources->getSourceById($asset->sourceId);

        // create the full file path
        $filePath = $folder->settings['path'] . $asset->getFolder()->path . $asset->filename;

        // parse environment strings
        $parsedFilePath = craft()->config->parseEnvironmentString($filePath);

        // determine the protocol, send the image
        if ($this->protocol == 'curl') {
            return craft()->tinyImage->sendWithCurl($parsedFilePath, $asset);
        } else {
            return craft()->tinyImage->sendWithFopen($parsedFilePath, $asset);
        }
    }

    /**
     * Add a specific asset to the ignore list.
     *
     * @param $assetId
     * @return bool
     */
    public function ignoreImage($assetId)
    {
        $ignoreRecord = new TinyImage_IgnoreRecord();

        $ignoreRecord->assetId = $assetId;

        if ($ignoreRecord->validate()) {
            // It validates!
            $ignoreRecord->save();

            return true;
        }

        return false;
    }

    /**
     * Remove an image from the ignore list.
     *
     * @param $assetId
     * @return bool
     */
    public function removeImageIgnore($assetId)
    {
        // find the ignore record
        $ignoreRecord = TinyImage_IgnoreRecord::model()->findByAttributes(array(
            'assetId' => $assetId
        ));

        // delete it
        $ignoreRecord->delete();

        return true;
    }

    /**
     * Return all images.
     *
     * @return array
     * @throws Exception
     */
    public function getAllImages()
    {
        // set the criteria
        $criteria = craft()->elements->getCriteria(ElementType::Asset);

        // filter only images
        $criteria->kind = 'image';
        $criteria->order = 'size desc';
        $criteria->limit = 500;

        // find each asset
        $assets = $criteria->find();

        // get all ignored assets
        $ignoredAssets = craft()->tinyImage_image->allIgnoredAssets();

        // sort ignored from assets
        $sortedAssets = array_diff($assets, $ignoredAssets);

        if (!empty($sortedAssets)) {

            // get images that are over the size limit
            foreach ($sortedAssets as $asset) {
                if ($asset->size >= $this->maximumSize) {
                    $images[] = $asset;
                }
            }

            // if there are no offending images
            if (empty($images)) {
                $images = array();
            }

            return $images;
        }
    }

    /**
     * Return an array of all ignored assets.
     *
     * @return array
     */
    public function allIgnoredAssets()
    {
        $records = TinyImage_IgnoreRecord::model()->findAll();

        // if there are records of ignored
        if (!empty($records)) {
            foreach ($records as $record) {
                $ignoredAssets[] = craft()->assets->getFileById($record->assetId);
            }

            return $ignoredAssets;
        }

        // else there are no records, make an empty array
        $ignoredAssets = array();

        return $ignoredAssets;
    }

    /**
     * Check if an asset is ignored.
     *
     * @param $asset
     */
    public function isAssetIgnored($asset)
    {
        // find the ignore record
        $ignoreRecord = TinyImage_IgnoreRecord::model()->findByAttributes(array(
            'assetId' => $asset->id
        ));

        // if there is a record, delete
        if ($ignoreRecord) {
            $ignoreRecord->delete();
        }

        return;
    }

    /**
     * Check if we should optimize the assets on save.
     *
     * @param $asset
     * @return TinyImage_ImageTask
     */
    public function checkOptimizeOnAssetSave($asset)
    {
        if ($this->optimizeOnAssetSave == 1 && $this->isImageOverMaximumSize($asset) && $asset->kind == 'image') {
            // create a task to optimize an image
            craft()->tasks->createTask('TinyImage_Image', null, array(
                'assetId' => $asset->id
            ));
        }
    }

    /**
     * Check if the image is over the maximum size.
     *
     * @param $asset
     * @return bool
     */
    public function isImageOverMaximumSize($asset)
    {
        if ($asset->size >= $this->maximumSize) {
            return true;
        }

        return false;
    }
}

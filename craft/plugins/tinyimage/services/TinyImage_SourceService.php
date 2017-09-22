<?php

namespace Craft;

class TinyImage_SourceService extends TinyImageService
{
    /**
     * Get a list of only the local sources.
     *
     * @return $sourcesModel
     */
    public function getLocalSources()
    {
        $sourcesModel = new AssetSourceModel;

        $sourcesRecord = AssetSourceRecord::model()->findByAttributes(array(
            'type' => 'Local'
        ));

        if ($sourcesRecord) {
            $sourcesModel = AssetSourceModel::populateModel($sourcesRecord);
        }

        return $sourcesModel;
    }

    /**
     * Get all of the images over maximum size by sourceId.
     *
     * @param $sourceId
     * @return array $images
     */
    public function getImagesBySource($sourceId)
    {
        // set the criteria
        $criteria = craft()->elements->getCriteria(ElementType::Asset);

        // filter only images
        $criteria->kind = 'image';
        $criteria->order = 'size desc';
        $criteria->limit = 500;
        $criteria->sourceId = $sourceId;

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
    * Get the total count of images for a source
    *
    * @return integer
    */
    public function getImageCountBySource($sourceId)
    {
        return count($this->getImagesBySource($sourceId));
    }
}

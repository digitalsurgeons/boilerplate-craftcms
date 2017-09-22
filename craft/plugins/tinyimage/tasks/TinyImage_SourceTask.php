<?php

namespace Craft;

class TinyImage_SourceTask extends BaseTask
{
    /**
     * Return the task description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Optimizing source';
    }

    /**
     * Return the total steps.
     *
     * @return int
     */
    public function getTotalSteps()
    {
        // get settings
        $settings = $this->getSettings();

        // get all the images by the source id
        $assets = craft()->tinyImage_source->getImagesBySource($settings->sourceId);

        // return the number of assets
        return count($assets);
    }

    /**
     * Run the step.
     *
     * @param int $step
     *
     * @return bool
     */
    public function runStep($step)
    {
        // Get settings
        $settings = $this->getSettings();

        // get the assets by source
        $assets = craft()->tinyImage_source->getImagesBySource($settings->sourceId);

        // foreach asset in the source
        foreach ($assets as $asset) {

            // create a sub task
            return $this->runSubTask('TinyImage_Image', null, array(
                'assetId' => $asset->id,
            ));

        }

        // log it
        TinyImagePlugin::log("Optimized source $settings->sourceId");

        return true;
    }

    /**
     * Define the tasks settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'sourceId' => array(
                'type' => AttributeType::Number,
            ),
        );
    }
}

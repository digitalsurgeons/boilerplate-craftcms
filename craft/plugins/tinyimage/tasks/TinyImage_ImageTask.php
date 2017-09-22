<?php

namespace Craft;

class TinyImage_ImageTask extends BaseTask
{
    /**
     * Return the task description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Optimizing image';
    }

    /**
     * Return the total steps.
     *
     * @return int
     */
    public function getTotalSteps()
    {
        return 1;
    }

    /**
     * Run the step.
     *
     * @param int $step
     * @return bool
     */
    public function runStep($step)
    {
        // Get settings
        $settings = $this->getSettings();

        // do the task!
        craft()->tinyImage_image->optimizeImage($settings->assetId);

        // log it
        TinyImagePlugin::log("Optimized image with the id $settings->assetId");

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
            'assetId' => array(
                'type' => AttributeType::Number,
            )
        );
    }
}

<?php
/**
 * Job Score plugin for Craft CMS
 *
 * JobScore Task
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScoreTask extends BaseTask
{
    /**
     * @access protected
     * @return array
     */

    protected function defineSettings()
    {
        return array(
            'someSetting' => AttributeType::String,
        );
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'JobScore Tasks';
    }

    /**
     * @return int
     */
    public function getTotalSteps()
    {
        return 1;
    }

    /**
     * @param int $step
     * @return bool
     */
    public function runStep($step)
    {
        return true;
    }
}

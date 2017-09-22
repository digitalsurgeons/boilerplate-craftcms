<?php
/**
 * Job Score plugin for Craft CMS
 *
 * Job Score Variable
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScoreVariable
{
  function jobs()
  {
    return craft()->elements->getCriteria('jobscore');
  }

  function getAllJobs()
  {
    return craft()->jobScore->getAllJobs();
  }

  function getJobBySlug($slug)
  {
    return craft()->jobScore->getJobBySlug($slug);
  }
}
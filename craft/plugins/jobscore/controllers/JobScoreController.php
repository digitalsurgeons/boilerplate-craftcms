<?php
/**
 * Job Score plugin for Craft CMS
 *
 * JobScore Controller
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScoreController extends BaseController
{

  /**
   * @var    bool|array Allows anonymous access to this controller's actions.
   * @access protected
   */
  protected $allowAnonymous = array('actionIndex');

  /**
    List Jobs
   */
  public function actionIndex()
  {
    $variables['jobs'] = craft()->jobScore->getAllJobs();
    $this->renderTemplate('jobscore/index', $variables);
  }

  /**
    Sync Jobs
   */
  public function actionSyncJobs()
  {
    $setings = craft()->plugins->getPlugin('jobscore')->getSettings();
    $organizationName = $setings['organizationName'];


    if (!empty($organizationName)) {
      $url = 'http://www.jobscore.com/jobs/'.$organizationName.'/feed.json';
      $json = file_get_contents($url);
      $jobsObjects = json_decode($json);

      craft()->db->createCommand()->truncateTable(JobScoreRecord::model()->tableName());
      $jobIds = [];

      foreach ($jobsObjects->jobs as $job) {
        $slugTitle = false;
        $jobId = (string) $job->id;
        $jobEntry = new JobScoreModel();

        $string = htmlentities($job->description, null, 'utf-8');
        $source = str_replace("&nbsp;", "", $string);
        $content = html_entity_decode($source);
        $description = preg_replace('/<h3[^>]*><\\/h3[^>]*>/', '', $content);

        // Populate Model
        $jobEntry->companyName = $job->company_name;
        $jobEntry->slug = $job->title;
        $jobEntry->jobId = $job->id;
        $jobEntry->title = $job->title;
        $jobEntry->type = $job->job_type;
        $jobEntry->applyUrl = $job->apply_url;
        $jobEntry->detailUrl = $job->detail_url;
        $jobEntry->department = $job->department;
        $jobEntry->location = $job->location;
        $jobEntry->city = $job->city;
        $jobEntry->state = $job->state;
        $jobEntry->country = $job->country;
        $jobEntry->zipcode = $job->postal_code;
        $jobEntry->description = $description;
        $jobEntry->jobDateUpdated = $job->last_updated_date;
        $jobEntry->jobDateOpened = $job->opened_date;
        $jobEntry->jobDateCreate = $job->created_on;

        craft()->jobScore->saveJob($jobEntry);
      }


    } else {
      craft()->userSession->setError(Craft::t('You must set Organization Name in plugin settings.'));
    }
  }
}

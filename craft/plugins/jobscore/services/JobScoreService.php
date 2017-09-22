<?php
/**
 * Job Score plugin for Craft CMS
 *
 * JobScore Service
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScoreService extends BaseApplicationComponent
{
  private $_allJobIds;
  private $_jobsById;
  private $_fetchedAllJobs = false;

  public function saveJob(JobScoreModel $jobEntry)
  { 

    $slugTitle = $this->slugify($jobEntry->title);
    $jobRecord = new JobScoreRecord();
    $jobRecord->validate();
    $jobEntry->addErrors($jobRecord->getErrors());
    $jobRecord->companyName = $jobEntry->companyName;
    $jobRecord->slug = $slugTitle;
    $jobRecord->jobId = $jobEntry->jobId;
    $jobRecord->title = $jobEntry->title;
    $jobRecord->type = $jobEntry->type;
    $jobRecord->applyUrl = $jobEntry->applyUrl;
    $jobRecord->detailUrl = $jobEntry->detailUrl;
    $jobRecord->department = $jobEntry->department;
    $jobRecord->location = $jobEntry->location;
    $jobRecord->city = $jobEntry->city;
    $jobRecord->state = $jobEntry->state;
    $jobRecord->country = $jobEntry->country;
    $jobRecord->zipcode = $jobEntry->zipcode;
    $jobRecord->description = $jobEntry->description;
    $jobRecord->jobDateUpdated = $jobEntry->jobDateUpdated;
    $jobRecord->jobDateOpened = $jobEntry->jobDateOpened;
    $jobRecord->jobDateCreate = $jobEntry->jobDateCreate;

    if (!$jobEntry->hasErrors()) {
      $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
      try {
        $jobRecord->save(false);
        if ($transaction !== null) {
          $transaction->commit();
        }
      } catch (\Exception $e) {
        if ($transaction !== null) {
          $transaction->rollback();
        }
        throw $e;
      }
      craft()->userSession->setNotice(Craft::t('Jobs saved.'));
    } else {
      craft()->userSession->setError(Craft::t('Couldn’t save jobs.'));
    }
  }

  // Returns job by id from database.
  public function getJobById($jobId)
  {
    $entryRecord = JobScoreRecord::model()->findById($jobId);
  }

  // Returns job by slug from database.
  public function getJobBySlug($slug) {
    $jobRecord = JobScoreRecord::model()->findByAttributes(array(
      'slug' => $slug
    ));

    if ($jobRecord) {
      return JobScoreModel::populateModel($jobRecord);
    }
  }

  // Returns all jobs from database.
  public function getAllJobs($indexBy = null)
  {
    if (!$this->_fetchedAllJobs)
    {
      $jobRecords = JobScoreRecord::model()->ordered()->findAll();
      $this->_jobsById = JobScoreModel::populateModels($jobRecords, 'jobId');
      $this->_fetchedAllJobs = true;
    }

    if ($indexBy == 'jobId')
    {
      return $this->_jobsById;
    }
    else if (!$indexBy)
    {
      return array_values($this->_jobsById);
    }
    else
    {
      $jobs = array();

      foreach ($this->_jobsById as $job)
      {
        $jobs[$job->$indexBy] = $job;
      }

      return $jobs;
    }
  }

  // Sllugify Job Url
  static public function slugify($text)
  { 
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text); // replace non letter or digits by -
    $text = trim($text, '-'); // trim
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // transliterate
    $text = strtolower($text); // lowercase
    $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters

    if (empty($text))
    {
      return false;
    }
    return $text;
  }

}
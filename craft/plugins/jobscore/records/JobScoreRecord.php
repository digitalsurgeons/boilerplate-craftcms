<?php
/**
 * Job Score plugin for Craft CMS
 *
 * JobScore Record
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScoreRecord extends BaseRecord
{
  /**
   * @return string
   */
  public function getTableName()
  {
    return 'jobscore';
  }

  /**
   * @access protected
   * @return array
   */
 protected function defineAttributes()
  {
    return array(
      'companyName'     => AttributeType::String,
      'slug'            => AttributeType::String,
      'jobId'           => AttributeType::String,
      'title'           => AttributeType::String,
      'type'            => AttributeType::String,
      'applyUrl'        => AttributeType::String,
      'detailUrl'       => AttributeType::String,
      'department'      => AttributeType::String,
      'location'        => AttributeType::String,
      'city'            => AttributeType::String,
      'state'           => AttributeType::String,
      'country'         => AttributeType::String,
      'zipcode'         => AttributeType::String,
      'description'     => AttributeType::Mixed,
      'jobDateUpdated'  => AttributeType::DateTime,
      'jobDateOpened'   => AttributeType::DateTime,
      'jobDateCreate'   => AttributeType::DateTime
    );
  }

  /**
   * @return array
   */
  public function defineIndexes()
  {
    return array(
      array('columns' => array('title'), 'unique' => true),
    );
  }

  /**
   * @return array
   */
  public function scopes()
  {
    return array(
      'ordered' => array('order' => 'title'),
    );
  }
}
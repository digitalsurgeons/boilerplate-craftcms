<?php
/**
 * Job Score plugin for Craft CMS
 *
 * JobScore Model
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://roundhouseagency.com
 * @package   JobScore
 * @since     0.0.1
 */

namespace Craft;

class JobScoreModel extends BaseModel
{
  /**
   * @return array
   */
  protected function defineAttributes()
  {
    return array_merge(parent::defineAttributes(), array(
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
    ));
  }
}
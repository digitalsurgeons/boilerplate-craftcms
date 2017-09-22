<?php

/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/general.php
 */

return array(
  // Enable CSRF Protection (recommended, will be enabled by default in Craft 3)
  // Make it available to everyone as we rely on it with contact forms
  'enableCsrfProtection' => true,

  '*' => array(
    // Base site URL
    'siteUrl' => null,

    // Default Week Start Day (0 = Sunday, 1 = Monday...)
    'siteUrl' => null,

    // Default Week Start Day (0 = Sunday, 1 = Monday...)
    'defaultWeekStartDay' => 0,


    // Whether "index.php" should be visible in URLs (true, false, "auto")
    'omitScriptNameInUrls' => true,

    'defaultSearchTermOptions' => array(
      'subLeft' => true,
      'subRight' => true,
    ),

    // Dev Mode (see https://craftcms.com/support/dev-mode)
    'devMode' => false
  ),

  'boilerplate-craftcms.ads.dsdev' => array(
    'siteUrl' => 'http://boilerplate-craftcms.ads.dsdev/',
    'environmentVariables' => array(
      'baseUrl'  => 'http://boilerplate-craftcms.ads.dsdev/',
      'basePath' => '/Users/ads1018/Sites/boilerplate-craftcms/public/'
    ),
    'devMode' => true,
    'enableTemplateCaching' => false
  ),

  'boilerplate-craftcms.ac.dsdev' => array(
    'siteUrl' => '',
    'environmentVariables' => array(
      'baseUrl'  => '',
      'basePath' => ''
    ),
    'devMode' => true,
    'enableTemplateCaching' => false
  ),

  'boilerplate-craftcms.cz.dsdev' => array(
    'siteUrl' => '',
    'environmentVariables' => array(
      'baseUrl'  => '',
      'basePath' => ''
    ),
    'devMode' => true,
    'enableTemplateCaching' => false
  ),

  'boilerplate-craftcms.ds.dsdev' => array(
    'siteUrl' => '',
    'environmentVariables' => array(
      'baseUrl'  => '',
      'basePath' => ''
    ),
    'devMode' => true,
    'enableTemplateCaching' => false
  ),

  'boilerplate-craftcms.digitalsurgeonsdev.com' => array(
    'siteUrl' => 'http://boilerplate-craftcms.digitalsurgeonsdev.com/',
    'environmentVariables' => array(
      'baseUrl'  => 'http://boilerplate-craftcms.digitalsurgeonsdev.com/',
      'basePath' => '/var/www/mmi/public/'
    )
  )
);

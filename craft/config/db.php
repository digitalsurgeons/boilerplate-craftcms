<?php

/**
 * Database Configuration
 *
 * All of your system's database configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/db.php
 */

return array(

  '*' => array(
    // The database server name or IP address. Usually this is 'localhost' or '127.0.0.1'.
    'server' => 'localhost',

    // The prefix to use when naming tables. This can be no more than 5 characters.
    'tablePrefix' => 'craft',
  ),
  'boilerplate-craftcms.ac.dsdev' => array (
    'database' => 'boilerplate-craftcms',
    'user'     => 'root',
    'password' => 'root'
  ),
  'boilerplate-craftcms.ads.dsdev' => array (
    'database' => 'boilerplate-craftcms',
    'user'     => 'root',
    'password' => 'root'
  ),
  'boilerplate-craftcms.digitalsurgeonsdev.com' => array (
    'database' => '',
    'user' => '',
    'password' => ''
  )
);

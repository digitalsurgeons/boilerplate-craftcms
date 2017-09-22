<?php

/**
 * SVG Icons plugin for Craft CMS
 *
 * @author    Fyrebase
 * @copyright Copyright (c) 2016 Fyrebase
 * @link      fyrebase.com
 * @package   SvgIcons
 * @since     0.0.1
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @link      https://github.com/fyrebase/svg-icons
 */

 /**
  * Configuration file for SVG Icons
  *
  * Override this by placing a file named 'svgicons.php' inside your config folder and override variables as needed.
  * Multi-environment settings work in this file the same way as in general.php or db.php
  */

return array(
	'iconSetsPath' => $_SERVER['DOCUMENT_ROOT'] . '/svgicons/',
	'iconSetsUrl' => '/svgicons/',
);

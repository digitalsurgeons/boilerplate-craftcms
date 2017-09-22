<?php
/**
 * SVG Icons plugin for Craft CMS
 *
 * Easily access urls or inline SVG icons from a public directory
 *
 * @author    Fyrebase
 * @copyright Copyright (c) 2016 Fyrebase
 * @link      fyrebase.com
 * @package   SvgIcons
 * @since     0.0.1
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @link      https://github.com/fyrebase/svg-icons
 */

namespace Craft;

class SvgIconsPlugin extends BasePlugin
{
	/**
	 * @return mixed
	 */
	public function init()
	{
		require_once __DIR__ . '/vendor/autoload.php';

		if (craft()->request->isCpRequest()) {
			craft()->templates->includeJs('__svgicons = { loaded: [] }', true);
		}
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return Craft::t('SVG Icons');
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return Craft::t('Easily access urls or inline SVG icons from a public directory');
	}

	/**
	 * @return string
	 */
	public function getDocumentationUrl()
	{
		return 'https://github.com/fyrebase/svg-icons/blob/master/README.md';
	}

	/**
	 * @return string
	 */
	public function getReleaseFeedUrl()
	{
		return 'https://raw.githubusercontent.com/fyrebase/svg-icons/master/releases.json';
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return '0.0.6';
	}

	/**
	 * @return string
	 */
	public function getSchemaVersion()
	{
		return '0.0.1';
	}

	/**
	 * @return string
	 */
	public function getDeveloper()
	{
		return 'Fyrebase';
	}

	/**
	 * @return string
	 */
	public function getDeveloperUrl()
	{
		return 'http://fyrebase.com';
	}

	/**
	 * @return bool
	 */
	public function hasCpSection()
	{
		return false;
	}

	/**
	 */
	public function onBeforeInstall()
	{
	}

	/**
	 */
	public function onAfterInstall()
	{
	}

	/**
	 */
	public function onBeforeUninstall()
	{
	}

	/**
	 */
	public function onAfterUninstall()
	{
	}
}

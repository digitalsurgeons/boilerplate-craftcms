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

class SvgIconsVariable
{
	public function getDimensions($str)
	{
		return craft()->svgIcons->getDimensions($str);
	}

	public function setDimensions($str, $baseHeight)
	{
		return craft()->svgIcons->setDimensions($str, $baseHeight);
	}

	public function inline($icon)
	{
		return craft()->svgIcons->inline($icon);
	}

	public function getModel($icon)
	{
		return craft()->svgIcons->getModel($icon);
	}
}

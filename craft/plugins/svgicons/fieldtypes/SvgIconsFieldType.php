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

class SvgIconsFieldType extends BaseFieldType
{
	/**
	 * Returns the name of the fieldtype.
	 *
	 * @return mixed
	 */
	public function getName()
	{
		return Craft::t('SVG Icons');
	}

	/**
	 * Returns the content attribute config.
	 *
	 * @return mixed
	 */
	public function defineContentAttribute()
	{
		return AttributeType::Mixed;
	}

	/**
	 * Defines the settings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function defineSettings()
	{
		$settings['iconSets'] = AttributeType::Mixed;

		return $settings;
	}

	/**
	 * Get settings html
	 * @return string
	 */
	public function getSettingsHtml()
	{
		$iconSetsPath = craft()->config->get('iconSetsPath', 'svgicons');
		$iconSets = array();
		$errors = array();

		if (IOHelper::folderExists($iconSetsPath)) {
			$folders = IOHelper::getFolderContents($iconSetsPath, false);

			if (is_array($folders)) {
				foreach ($folders as $idx => $f)
				{
					$iconSets[IOHelper::getFolderName($f) . IOHelper::getFolderName($f, false)] = IOHelper::getFolderName($f, false);
				}
			}
		} else {
			$errors = array_merge(
				array('<p class="warning"><strong>Unable to locate SVG Icons source directory.</strong></p><p>Please ensure <code>' . $iconSetsPath . '</code> exists.</p>'),
				$errors
			);
		}

		craft()->templates->includeCssResource('svgicons/css/fields/SvgIconsFieldType_Settings.css');

		return craft()->templates->render('svgicons/fields/SvgIconsFieldType_Settings', array(
			'settings' => $this->getSettings(),
			'iconSets' => $iconSets,
			'errors' => $errors,
		));
	}

	public function prepSettings($settings)
	{
			return $settings;
	}

	/**
	 * Returns the field's input HTML.
	 *
	 * @param string $name
	 * @param mixed  $value
	 * @return string
	 */
	public function getInputHtml($name, $value)
	{
		if(!$value) $value = new SvgIconsModel();

		$settings = $this->getSettings();

		$id = craft()->templates->formatInputId($name);
		$namespacedId = craft()->templates->namespaceInputId($id);

		craft()->templates->includeCssResource('svgicons/css/fields/SvgIconsFieldType.css');
		craft()->templates->includeJsResource('svgicons/js/fields/SvgIconsFieldType.js');

		$stylesheets = IOHelper::getFolderContents(craft()->path->getPluginsPath() . 'svgicons/resources/sprites', false, '\-sprites.css$');

		if(!empty($stylesheets)) {
			foreach ($stylesheets as $sheet) {
				craft()->templates->includeCssResource('svgicons/sprites/' . IOHelper::getFilename($sheet));
			}
		}

		$svgs = IOHelper::getFolderContents(craft()->path->getPluginsPath() . 'svgicons/resources/sprites', false, '\-sprites.svg$');

		$spriteSheets = array();

		if(!empty($svgs)) {
			foreach ($svgs as $sheet) {
				$spriteSheets[] = UrlHelper::getResourceUrl('svgicons/sprites/' . IOHelper::getFilename($sheet));
			}
		}

		$jsonVars = array(
			'id' => $id,
			'inputId' => craft()->templates->namespaceInputId($id),
			'name' => $name,
			'namespace' => $namespacedId,
			'prefix' => craft()->templates->namespaceInputId(''),
			'blank' => UrlHelper::getResourceUrl('svgicons/icon-blank.svg'),
			'iconSetUrl' => craft()->config->get('iconSetsUrl', 'svgicons'),
			'spriteSheets' => $spriteSheets,
		);

		$jsonVars = json_encode($jsonVars);

		craft()->templates->includeJs('var svgIconsFieldType = new SvgIconsFieldType(' . $jsonVars . ');');

		$variables = array(
			'id' => $id,
			'name' => $name,
			'namespaceId' => $namespacedId,
			'values'  => $value,
			'options' => craft()->svgIcons->getIcons($settings->iconSets)
		);

		return craft()->templates->render('svgicons/fields/SvgIconsFieldType.twig', $variables);
	}

	/**
	 * Returns the input value as it should be saved to the database.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function prepValueFromPost($value)
	{
		if ($value['icon'] == '_blank_') $value = null;

		preg_match('#(.*)' . preg_quote(DIRECTORY_SEPARATOR) . '(svgicons-)(.{3})-(.*)#', $value['icon'], $matches, PREG_OFFSET_CAPTURE);

		if (!empty($matches)) {
			$value['sprite'] = $matches[4][0];
			$value['type'] = $matches[3][0];
			$value['icon'] = null;
			$value['resource'] = $matches[1][0];
		}

		return $value;
	}

	/**
	 * Prepares the field's value for use.
	 *
	 * @param mixed $value
	 * @return mixed
	 */

	public function prepValue($value)
	{
		if(!$value) return null;

		if(isset($value['sprite']) && !empty($value['sprite'])) {
			$value = craft()->svgIcons->getModel($value['resource'] . DIRECTORY_SEPARATOR . $value['sprite']);
		} else {
			$value = craft()->svgIcons->getModel($value['icon']);
		}

		return $value;
	}
}

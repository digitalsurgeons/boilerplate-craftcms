<?php
/**
 * SVG Icons plugin for Craft CMS
 *
 * SvgIcons FieldType
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

use SvgIcons\Utilities\FyreStringHelper as FSH;

class SvgIconsService extends BaseApplicationComponent
{
	private $sprites = array();

	public function init()
	{
		$this->createResources();

		if (IOHelper::getFolder(craft()->path->getPluginsPath() . 'svgicons/resources/sprites')) {
			foreach (IOHelper::getFolderContents(craft()->path->getPluginsPath() . 'svgicons/resources/sprites', false, '\.json$') as $json) {
				$d = JsonHelper::decode(IOHelper::getFileContents($json));
				$this->sprites[$d['resource']] = $d;
			}
		}

		parent::init();
	}

	/**
	 * Return icon sets select options
	 * @param  mixed $iconSets
	 * @return array
	 */
	public function getIcons($iconSets) {

		if(!$iconSets) return;

		$icons = array();

		if ($iconSets == '*') {
			foreach (IOHelper::getFolders(craft()->config->get('iconSetsPath', 'svgicons')) as $folder) {
				$icons = array_merge($icons, $this->_getOptions($folder));
			}
		} else {
			foreach ($iconSets as $folder) {
				$icons = array_merge($icons, $this->_getOptions($folder));
			}
		}

		return $icons;
	}

	/**
	 * Return icon model from string
	 * @param  string $icon
	 * @return array
	 */
	public function getModel($icon)
	{
		if($icon instanceof SvgIconsModel) return $icon;

		$model = new SvgIconsModel($icon);
		$model->icon = $icon;

		if (FSH::endsWith($icon, '.svg')) {
			list($model->width, $model->height) = $this->getDimensions($model);
		} else {
			preg_match('#(.*)' . preg_quote(DIRECTORY_SEPARATOR) . '(?:(svgicons-)(.{3})-)?(.*)#', $icon, $matches, PREG_OFFSET_CAPTURE);


			if (!empty($matches)) {
				$resource = $matches[1][0];

				$model->sprite = $matches[4][0];
				$model->type = $this->sprites[$resource]['type'];
				$model->resource = $resource;

				list($model->width, $model->height) = $this->getDimensions($model);
			}
		}

		return $model;
	}

	/**
	 * Return icon dimensions
	 * @param  string $icon
	 * @return array
	 */
	public function getDimensions($icon)
	{
		if($icon instanceof SvgIconsModel) {
			$model = $icon;
		} else {
			$model = $this->getModel($icon);
		}

		if ($model->resource) {
			if(isset($this->sprites[$model->resource]['classes'])) {
					return array_values($this->sprites[$model->resource]['classes'][$model->sprite]);
				}
		} else {
			return IOHelper::getFile(craft()->config->get('iconSetsPath', 'svgicons') . $model->icon) ? ImageHelper::getImageSize(craft()->config->get('iconSetsPath', 'svgicons') . $model->icon) : array(0,0);
		}

		return array(0,0);
	}

	/**
	 * Set icon dimensions maintaining aspect ratio
	 * @param string $icon
	 * @param int $baseHeight
	 */
	public function setDimensions($icon, $baseHeight)
	{
		if($icon instanceof SvgIconsModel) {
			$model = $icon;
		} else {
			$model = $this->getModel($icon);
		}

		return array(
			'width' => ceil(($model->width / $model->height) * $baseHeight),
			'height' => $baseHeight
		);
	}

	/**
	 * Return icon file contents
	 * ready for raw output
	 * @param  string $icon
	 * @return string
	 */
	public function inline($icon)
	{
		$path = craft()->config->get('iconSetsPath', 'svgicons') . $icon;

		if (!IoHelper::fileExists($path)) return '';

		return TemplateHelper::getRaw(@file_get_contents($path));
	}

	// /**
	//  * Return icon file name
	//  * @param  string $icon
	//  * @return string
	//  */
	// public function getFilename($icon)
	// {
	// 	return IOHelper::getFileName(craft()->config->get('iconSetsUrl', 'svgicons') . $icon, false);
	// }

	/**
	 * Generate sprite sheet resources
	 * @param  string $stylesheet
	 */
	private function getSpritesFromStylesheet($stylesheet)
	{
		$filename = IOHelper::getFileName($stylesheet, false);
		$lmd = IOHelper::getFile($stylesheet)->getLastTimeModified();

		$d = JsonHelper::decode(IOHelper::getFileContents(craft()->path->getPluginsPath().'svgicons/resources/sprites/' . $filename . '.json'));

		if($lmd->getTimestamp() == $d['lastModified']) return;

		$oCssParser = new \Sabberworm\CSS\Parser(IOHelper::getFileContents($stylesheet));
		$oCss = $oCssParser->parse();

		$classes = array();

		// Namespace css class
		// Store width / height
		foreach($oCss->getAllDeclarationBlocks() as $oBlock) {
			$class = str_replace('.', '', $oBlock->getSelector()[0]->getSelector());
			$oBlock->getSelector()[0]->setSelector(str_replace('.', '.svgicons-css-', $oBlock->getSelector()[0]->getSelector()));
			$classes[$class] = array();
			foreach ($oBlock->getRules() as $rule) {
				if ($rule->getRule() == 'width') {
					$classes[$class]['width'] = $rule->getValue()->getSize();
				}

				if ($rule->getRule() == 'height') {
					$classes[$class]['height'] = $rule->getValue()->getSize();
				}
			}
		}

		// Remove redundant rules
		foreach($oCss->getAllRuleSets() as $oRuleSet) {
			$oRuleSet->removeRule('width');
			$oRuleSet->removeRule('height');
		}

		$data = array(
			'resource' => $filename,
			'lastModified' => $lmd->getTimestamp(),
			'type' => 'css',
			'classes' => $classes,
		);

		IOHelper::writeToFile(craft()->path->getPluginsPath().'svgicons/resources/sprites/' . $filename . '.css', $oCss->render());
		IOHelper::writeToFile(craft()->path->getPluginsPath().'svgicons/resources/sprites/' . $filename . '.json', JsonHelper::encode($data));
	}

	private function getSpritesFromSvg($svg)
	{
		$filename = IOHelper::getFileName($svg);
		$lmd = IOHelper::getFile($svg)->getLastTimeModified();

		$d = JsonHelper::decode(IOHelper::getFileContents(craft()->path->getPluginsPath().'svgicons/resources/sprites/' . str_replace('.svg', '.json', $filename)));

		if($lmd->getTimestamp() == $d['lastModified']) return;

		$classes = array();

		$xml = simplexml_load_file($svg);
		$xml->registerXPathNamespace('svg', 'http://www.w3.org/2000/svg');
		$nodes = $xml->children();
		$type = 'sym';
		if($nodes[0]->getName() == 'defs') {
			$type = 'def';
			$nodes = $nodes[0]->children();
		}

		foreach ($nodes as $node) {
			if ($node->getName() == 'svg' || $node->getName() == 'symbol') {
				$class = '' . $node->attributes()->id;
				$classes[$class] = array();

				$viewBox = explode(' ', $node['viewBox']);
				$classes[$class]['width'] = 0 + $viewBox[2];
				$classes[$class]['height'] = 0 + $viewBox[3];
			}
		}

		$data = array(
			'resource' => str_replace('.svg', '', $filename),
			'lastModified' => $lmd->getTimestamp(),
			'type' => $type,
			'classes' => $classes,
		);

		IOHelper::copyFile($svg, craft()->path->getPluginsPath().'svgicons/resources/sprites/' . $filename);
		IOHelper::writeToFile(craft()->path->getPluginsPath().'svgicons/resources/sprites/' . str_replace('.svg', '.json', $filename), JsonHelper::encode($data));
	}

	private function createResources()
	{
		$folders = IOHelper::getFolderContents(craft()->config->get('iconSetsPath', 'svgicons'), false);

		if (is_array($folders)) {
			foreach ($folders as $idx => $f)
			{
				$iconSets[IOHelper::getFolderName($f) . IOHelper::getFolderName($f, false)] = IOHelper::getFolderName($f, false);

				// Create sprite sheet resources from Stylesheet
				$stylesheets = IOHelper::getFolderContents($f, false, '\-sprites.css');

				if(!empty($stylesheets)) {
					foreach ($stylesheets as $stylesheetIdx => $stylesheet) {
						craft()->templates->includeCss(IOHelper::getFileContents($stylesheet));
						$this->getSpritesFromStylesheet($stylesheet);
					}
				}

				// Create sprite sheet resources from SVG
				$svgs = IOHelper::getFolderContents($f, false, '\-sprites.svg');

				if(!empty($svgs)) {
					foreach ($svgs as $svgIdx => $svg) {
						$this->getSpritesFromSvg($svg);
					}
				}
			}
		}
	}

	/**
	 * Return icon set select options
	 * @param  string $folder
	 * @return array
	 */
	private function _getOptions($folder) {
		if ($spriteSheets = IOHelper::getFolderContents($folder, false, '\-sprites.')) {
			foreach ($spriteSheets as $sheet) {
				$filename = IOHelper::getFilename($sheet, false);
				$sheet = craft()->path->getPluginsPath() . 'svgicons/resources/sprites/' . $filename . '.json';
				$icons[] = array('optgroup' => str_replace('-sprites', '', IOHelper::getFileName($sheet, false)));
				$icons = array_merge($icons, $this->_getOptionsFromJson($sheet));
			}
		} else {
			$icons[] = array('optgroup' => IOHelper::getFolderName($folder, false));
			$icons = array_merge($icons, $this->_getOptionsFromFile($folder));
		}

		return $icons;
	}

	/**
	 * Return icon set select options
	 * from svg file name
	 * @param  string $folder
	 * @return array
	 */
	private function _getOptionsFromFile($folder) {
		$d = IOHelper::getFolderContents($folder, false);

		$icons = array();

		if (is_array($d)) {
			foreach ($d as $i)
			{
				$icons[] = array('value' => IOHelper::getFolderName($folder, false) . DIRECTORY_SEPARATOR . IOHelper::getFileName($i), 'label' => IOHelper::getFileName($i, false));
			}
		}

		return $icons;
	}

	/**
	 * Return icon set select options
	 * from json
	 * @param  string $jsonFile
	 * @return array
	 */
	private function _getOptionsFromJson($jsonFile) {
		$d = JsonHelper::decode(IOHelper::getFileContents($jsonFile));

		$filename = IOHelper::getFilename($jsonFile, false);

		$icons = array();
		$classes = $d['classes'];

		if (is_array($classes)) {
			foreach ($classes as $k => $v)
			{
				$icons[] = array('value' => $filename . DIRECTORY_SEPARATOR . 'svgicons-' . $d['type'] . '-' . $k, 'label' => $k);
			}
		}

		return $icons;
	}
}

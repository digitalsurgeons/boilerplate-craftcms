/**
 * SVG Icons plugin for Craft CMS
 *
 * SvgIconsFieldType JS
 *
 * @author    Fyrebase
 * @copyright Copyright (c) 2016 Fyrebase
 * @link      fyrebase.com
 * @package   SvgIcons
 * @since     0.0.1
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @link      https://github.com/fyrebase/svg-icons
 */

;(function($){
	SvgIconsFieldType = Garnish.Base.extend({
		container: null,
		init: function(options)
		{
			this.options = options;

			this.container = $("#" + options.inputId + '-field')

			this.loadSpriteSheets()

			var $sel = this.container.find('.svgicons__select')

			var opt = {
				dropdownParent: 'body',
				render: {
					item: function(item, escape) {
						if (item.value.indexOf('svgicons-css-') > -1) {

							var myRegexp = /(.*\/)(svgicons-.*)/g;
							var match = myRegexp.exec(item.value);
							var c = match[2];

							return '<div class="svgicons__si"><div class="svgicons__si__i ' + c + '"></div><span>' + escape(item.text) + '</span></div>';

						} else if (item.value.indexOf('svgicons-def-') > -1 || item.value.indexOf('svgicons-sym-') > -1) {

							var myRegexp = /(.*\/)(svgicons-.{3}-)(.*)/g;
							var match = myRegexp.exec(item.value);
							var c = match[3];

							return '<div class="svgicons__si"><div class="svgicons__si__i"><svg viewBox="0 0 1000 1000"><use xlink:href="#' + c + '" /></svg></div><span>' + escape(item.text) + '</span></div>';

						} else {
							return '<div class="svgicons__si"><div class="svgicons__si__i"><img src="' + (item.value == '_blank_' ? options.blank : options.iconSetUrl + item.value) + '" alt="" /></div><span>' + escape(item.text) + '</span></div>';
						}
					},
					option: function(item, escape) {
						if (item.value.indexOf('svgicons-css-') > -1) {

							var myRegexp = /(.*\/)(svgicons-.*)/g;
							var match = myRegexp.exec(item.value);
							var c = match[2];

							return '<div class="svgicons__i"><div class="svgicons__i__w"><div class="svgicons__i__w__i ' + c + '"></div></div><span>' + escape(item.text) + '</span></div>';

						} else if (item.value.indexOf('svgicons-def-') > -1 || item.value.indexOf('svgicons-sym-') > -1) {

							var myRegexp = /(.*\/)(svgicons-.{3}-)(.*)/g;
							var match = myRegexp.exec(item.value);
							var c = match[3];

							return '<div class="svgicons__i"><div class="svgicons__i__w"><div class="svgicons__i__w__i"><svg viewBox="0 0 1000 1000"><use xlink:href="#' + c + '" /></svg></div></div><span>' + escape(item.text) + '</span></div>';

						} else {
							return '<div class="svgicons__i"><div class="svgicons__i__w"><div class="svgicons__i__w__i"><img src="' + (item.value == '_blank_' ? options.blank : options.iconSetUrl + item.value) + '" alt="" /></div></div><span>' + escape(item.text) + '</span></div>';
						}
					}
				}
			}

			$sel.selectize(opt)
		},
		loadSpriteSheets: function() {
			var self = this;

			for (var i = 0; i < this.options.spriteSheets.length; i++) {
				var sheet = this.options.spriteSheets[i];
				if ($.inArray(sheet, __svgicons.loaded) == -1) {
					__svgicons.loaded.push(sheet)
					$.get(sheet, function(data) {
						var div = document.createElement('div')
						div.innerHTML = new XMLSerializer().serializeToString(data.documentElement)
						$svg = $(div).find('> svg')
						$svg.css('display', 'none').prependTo('body')
					});
				}
			}
		}
	});
})(jQuery);

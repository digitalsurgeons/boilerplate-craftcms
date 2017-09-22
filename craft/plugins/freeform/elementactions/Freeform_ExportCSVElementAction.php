<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2017, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Craft;

class Freeform_ExportCSVElementAction extends BaseElementAction
{
    /**
     * @inheritDoc IComponentType::getName()
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Export to CSV');
    }

    /**
     * @inheritDoc IElementAction::getTriggerHtml()
     *
     * @return string|null
     */
    public function getTriggerHtml()
    {
        $js = <<<EOT
(function()
{
	var trigger = new Craft.ElementActionTrigger({
		handle: 'Freeform_ExportCSV',
		batch: true,
		activate: function(\$selectedItems)
		{
		    var ids = [];
		    \$selectedItems.each(function() {
		        ids.push($(this).data("id"));
		    });
		    
			var form = $('<form method="post" target="_blank" action="">' +
			'<input type="hidden" name="action" value="freeform/submissions/export" />' +
			'<input type="hidden" name="submissionIds" value="' + ids.join(",") + '" />' +
			'<input type="hidden" name="{csrfName}" value="{csrfValue}" />' +
			'<input type="submit" value="Submit" />' +
			'</form>');
			
			form.appendTo('body');
			form.submit();
			form.remove();
		}
	});
})();
EOT;

        $js = str_replace("{csrfName}", craft()->config->get('csrfTokenName'), $js);
        $js = str_replace("{csrfValue}", craft()->request->getCsrfToken(), $js);

        craft()->templates->includeJs($js);
    }
}

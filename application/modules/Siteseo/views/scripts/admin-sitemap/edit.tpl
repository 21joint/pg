<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class='clear' style="min-width: 650px;">
	<div class='settings'>
		<?php echo $this->form->render($this) ?>
	</div>
</div>
<?php if($this->type != 'menu_urls' && $this->type != 'custom_pages'): ?>
	<script>
		// SHOW SUB TYPES OF SCHEMA ON CLICKING MAIN SCHEMA 
		var specificSchemaObject = '<?php echo json_encode($this->specificSchemaArray); ?>';
		var selectedValue = '<?php echo $this->selectedSpecificSchema; ?>';
		specificSchemaObject = JSON.parse(specificSchemaObject);

		en4.core.runonce.add(function() {
			var schemaSelect = $('schematype'); 
			var specificSchemaSelect = new Element('select', {
				'name':'specific_schematype',
				'id':'specific_schematype',
				'html': '<option value=" "></option>'
			}).inject(schemaSelect, 'after');
			selectSpecificSchema(schemaSelect.value);
			specificSchemaSelect.value = selectedValue;

			schemaSelect.addEvent('change', function($this) {
				selectSpecificSchema(this.value);
			})
		})

		function selectSpecificSchema(value) {
			var select = $('specific_schematype');
			if (!specificSchemaObject[value])
				return select.hide();
			select.set('html','<option value=" "></option>').show();
			optionsArray = specificSchemaObject[value];
			optionsArray.each(function(item) {
				option = new Element('option',{'value': item, 'text':item})
				option.inject(select);
			});
		}

		// SHOW APPEND META TAGS SETTINGS ONLY WHEN OVERWRITE SETTING IS ENABLED
		en4.core.runonce.add(function() {
			toggleSchematypeFields('<?php echo $this->schema ?>');
		});

		function toggleSchematypeFields(value) {
			el = $('schematype-wrapper');
			value == 0 ? el.hide() : el.show();
		}
	</script>
<?php endif; ?>

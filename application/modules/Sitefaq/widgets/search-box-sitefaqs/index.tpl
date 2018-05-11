<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
  	              . 'application/modules/Sitefaq/externals/styles/style_sitefaq.css');
?>
<?php
	$this->headScript()
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>

<script type="text/javascript">
  en4.core.runonce.add(function()
  { 
  	
  	var search_choices = new Element('ul', {
      'class':'tag-autosuggest' ,
      'styles' : {
        'width' : ($('text').getSize().x -2)+ 'px' 
      }
    }).inject($('text'),'after');
    var contentAutocomplete = new Autocompleter.Request.JSON('text', '<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'get-item', 'privacy' => $this->privacy), 'default', true) ?>', {
      'postVar' : 'text',
      'minLength': 1,
      'selectMode': 'pick',
      'autocompleteType': 'tag',
      'className': 'tag-autosuggest',
			'indicatorClass':'sitefaq-loading',
    //  'customChoices' : true,
      'customChoices' : search_choices,
      'filterSubset' : true,
      'multiple' : false,
      'injectChoice': function(token){ 
        var choice = new Element('li', {'class': 'autocompleter-choices', 'id':token.id});
        new Element('div', {'html': token.url,'class': 'autocompleter-choice'}).inject(choice);
        this.addChoiceEvents(choice).inject(this.choices);
        choice.store('autocompleteChoice', token);

      }
    });

		if(document.getElementById('text')){
				new OverText(document.getElementById('text'), {
					poll: true,
					pollInterval: 500,
					positionOptions: {
						position: ( en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft' ),
						edge: ( en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft' ),
						offset: {
							x: ( en4.orientation == 'rtl' ? -4 : 4 ),
							y: 2
						}
					}
				});
		}

    $('text').addEvent('keyup', function (e)  {  
      if (e.key == 'enter') { 
				var See_all_results_for = '<?php echo $this->string()->escapeJavascript($this->translate("See all results for ")); ?>';
				var changed_text = $('text').value.replace(See_all_results_for, '');
				$('text').value = changed_text;
				$('filter_form_search_box').submit();
      }
    });
  });
</script>

<div class="sitefaq_search">
	<form id='filter_form_search_box' method='get' action='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) ?>'>
		<?php if($this->heading): ?>
			<div class="sitefaq_search_head">
				<?php echo $this->translate("Hi %s, what do you need help with?", $this->display_name);?>
			</div>
		<?php endif; ?>
		<div class="sitefaq_search_element_wrapper">
			<input id="text" class="text" maxlength='100' name="search" type='text' placeholder="<?php echo $this->translate($this->blur_text);?>" />
		</div>
		<input class="search_button" type="submit" value="submit"  />
	</form>
</div>
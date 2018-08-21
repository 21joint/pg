<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Observer.js')
    ->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Autocompleter.js')
    ->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Autocompleter.Local.js')
    ->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Autocompleter.Request.js')
		->appendFile($this->layout()->staticBaseUrl.'application/modules/Sitefaq/externals/scripts/categories.js');
?>

<script type="text/javascript">
  en4.core.runonce.add(function()
  {
    new Autocompleter.Request.JSON('tags', '<?php echo $this->url(array('module' => 'seaocore', 'controller' => 'index', 'action' => 'tag-suggest', 'resourceType' => 'sitefaq_faq'), 'default', true) ?>', {
      'postVar' : 'text',
      'minLength': 1,
      'selectMode': 'pick',
      'autocompleteType': 'tag',
      'className': 'tag-autosuggest',
      'filterSubset' : true,
      'multiple' : true,
      'injectChoice': function(token){
        var choice = new Element('li', {'class': 'autocompleter-choices', 'value':token.label, 'id':token.id});
        new Element('div', {'html': this.markQueryValue(token.label),'class': 'autocompleter-choice'}).inject(choice);
        choice.inputValue = token;
        this.addChoiceEvents(choice).inject(this.choices);
        choice.store('autocompleteChoice', token);
      }
    });
  });

	var sub = '';
	var cat = '<?php echo $this->category_id ?>';
	if(cat != '') {
		sub = '<?php echo $this->subcategory_id; ?>';
		subcategories(cat, sub, field_id);
	}
	var sitefaq_subcategory_url = '<?php echo $this->url(array('action' => 'sub-category'), 'sitefaq_category', true);?>';
	var sitefaq_subsubcategory_url = '<?php echo $this->url(array('action' => 'subsub-category'), 'sitefaq_category', true);?>';
</script>

<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
  	              . 'application/modules/Sitefaq/externals/styles/style_sitefaq.css');
?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'application/modules/Sitefaq/externals/scripts/categories.js'); ?>

<?php if( count($this->navigation) ): ?>
	<div class="headline">
		<h2>
	  	<?php echo $this->translate('FAQs'); ?>
		</h2>
  	<div class='tabs'>
    	<?php echo $this->navigation()->setContainer($this->navigation)->menu()->render() ?>
  	</div>
	</div>  
<?php endif; ?>

<div class='global_form sitefaq_form'>
  <?php echo $this->form->render($this) ?>

	<div id="addCategoryLink" class="form-wrapper">
		<div class="form-label">&nbsp;</div>
		<div class="form-element">
			<a href="javascript: void(0);" onclick="return addAnotherOption();"><b><?php echo $this->translate("Add another category") ?></b></a>
		</div>
	</div>

	<?php if($this->languageCount > 1 && $this->multiLanguage): ?>
		<div id="multiLanguageLinkShow" class="form-wrapper">
			<div class="form-label">&nbsp;</div>
			<div class="form-element">
				<a href="javascript: void(0);" onclick="return multiLanguageOption(2);"><b><?php echo $this->translate("Create FAQ in the multiple languages supported by this website.") ?></b></a>
			</div>
		</div>

		<div id="multiLanguageLinkHide" class="form-wrapper">
			<div class="form-label">&nbsp;</div>
			<div class="form-element">
				<a href="javascript: void(0);" onclick="return multiLanguageOption(1);"><b><?php echo $this->translate("Create FAQ in the primary language of this website.") ?></b></a>
			</div>
		</div>
	<?php endif; ?>

  <script type="text/javascript">
		var maxCategories = '<?php echo $this->maxCategories; ?>';
		var category_exist = '<?php echo $this->category_exist; ?>';
		var multiLanguage = '<?php echo $this->multiLanguage;?>';
		var languageCount = '<?php echo $this->languageCount; ?>';
		var count = <?php echo $this->alreadyCreated; ?>;

		if( maxCategories <= 1 || count >= maxCategories) {
			$('addCategoryLink').destroy();
		}
		else {
			var addCategoryLinkElement = $('addCategoryLink');
		}
		
		var bodyParent = $('<?php echo $this->add_show_hide_link; ?>').getParent().getParent();
    var optionParent = $('category_id_'+count).getParent().getParent().getParent();
		var categoryElement = $('category_id_1');
		
		var addAnotherOption = window.addAnotherOption = function () {

			if (maxCategories && count >= maxCategories) {
				return !alert(new String('<?php echo $this->string()->escapeJavascript($this->translate("Maximum of %s categories are permitted.")) ?>').replace(/%s/, maxCategories));
				return false;
			}

			count = count + 1;

			// START CATEGORY CODE
			var select = document.createElement("select");

			//ADD OPTION ELEMENTS
			for(i=0; i< categoryElement.options.length; i++) {

				//CREATE A OPTION ELEMENT DYNAMICALLY
				var option = document.createElement("option");
				option.text = categoryElement.options[i].text;
				option.value = categoryElement.options[i].value;
				select.add(option, null);
			}

			select.id = 'category_id_'+count;
			select.name = 'category_id_'+count;
			select.onchange = function() { 
				subcategories(this.value, '', this.id);
			};

			var wrapperDiv = document.createElement("div");
			wrapperDiv.id = 'category_id_'+count+'-wrapper';
			wrapperDiv.setAttribute("class", "form-wrapper");

			var labelDiv = document.createElement("div");
			labelDiv.id = 'category_id_'+count+'-label';
			labelDiv.setAttribute("class", "form-label");

			var labelChieldDiv = document.createElement("label");
			labelChieldDiv.setAttribute("class", "required");

			var elementDiv = document.createElement("div");
			elementDiv.id = 'category_id_'+count+'-element';
			elementDiv.setAttribute("class", "form-element");

			var fleftDiv = document.createElement("div");
			fleftDiv.setAttribute("class", "fleft");

			select.inject(fleftDiv);
			fleftDiv.inject(elementDiv);
			labelDiv.inject(wrapperDiv);
			labelChieldDiv.inject(labelDiv);
			elementDiv.inject(wrapperDiv);
			wrapperDiv.inject(addCategoryLinkElement, 'before');
			// END CATEGORY CODE

			// START SUBCATEGORY CODE
			var selectSubCategory = document.createElement("select");

			selectSubCategory.id = 'subcategory_id_'+count;
			selectSubCategory.name = 'subcategory_id_'+count;
			selectSubCategory.onchange = function() { 
				changesubcategory(this.value, this.id);
			};

			var wrapperImageDivSub = document.createElement("div");
			wrapperImageDivSub.id = 'subcategory_id_'+count+'_backgroundimage';

			var wrapperDivSub = document.createElement("div");
			wrapperDivSub.id = 'subcategory_id_'+count+'-wrapper';
			wrapperDivSub.setAttribute("style", "display:none;");

			var elementDivSub = document.createElement("div");
			elementDivSub.id = 'subcategory_id_'+count+'-element';

			var fleftDivSub = document.createElement("div");
			fleftDivSub.setAttribute("class", "fleft");

			selectSubCategory.inject(elementDivSub);
			elementDivSub.inject(wrapperDivSub);
			wrapperDivSub.inject(fleftDivSub);
			fleftDivSub.inject(addCategoryLinkElement, 'before');
			wrapperImageDivSub.inject(wrapperDivSub, 'before');
			// END SUBCATEGORY CODE

			// START CREATE 3RD LEVEL CATEGORY ELEMENT
			var selectSubSubCategory = document.createElement("select");

			selectSubSubCategory.id = 'subsubcategory_id_'+count;
			selectSubSubCategory.name = 'subsubcategory_id_'+count;

			var wrapperImageDivSubSub = document.createElement("div");
			wrapperImageDivSubSub.id = 'subsubcategory_id_'+count+'_backgroundimage';

			var wrapperDivSubSub = document.createElement("div");
			wrapperDivSubSub.id = 'subsubcategory_id_'+count+'-wrapper';
			wrapperDivSubSub.setAttribute("style", "display:none;");

			var elementDivSubSub = document.createElement("div");
			elementDivSubSub.id = 'subsubcategory_id_'+count+'-element';

			var fleftDivSubSub = document.createElement("div");
			fleftDivSubSub.setAttribute("class", "fleft");

			selectSubSubCategory.inject(elementDivSubSub);
			elementDivSubSub.inject(wrapperDivSubSub);
			wrapperDivSubSub.inject(fleftDivSubSub);
			fleftDivSubSub.inject(addCategoryLinkElement, 'before');
			wrapperImageDivSubSub.inject(wrapperDivSubSub, 'before');
			// END CREATE 3RD LEVEL CATEGORY ELEMENT

			fleftDivSub.inject(fleftDiv, 'after');
			fleftDivSubSub.inject(fleftDivSub, 'after');

			if( maxCategories && count >= maxCategories ) {
				$('addCategoryLink').destroy();
			}
		}

		window.addEvent('domready', function() {

			if( maxCategories > 1 && count < maxCategories) {
				$('addCategoryLink').inject(optionParent, 'after');
			}

			if(multiLanguage == 1 && languageCount > 1) {
				$('multiLanguageLinkShow').inject(bodyParent, 'after');
				$('multiLanguageLinkHide').inject(bodyParent, 'after');
				multiLanguageOption(1);
			}

			if( maxCategories == 1 || category_exist != 1) {
				$('addCategoryLink').destroy();
			}
		});

		var multiLanguageOption = function (show) {
			<?php foreach($this->languageData as $value): if($this->defaultLanguage == $value) { continue; } if($value == 'en') { $value = ''; } else { $value = "_$value";}?>
				if(show == 1) { 
					$('title'+'<?php echo $value; ?>'+'-wrapper').hide();
					$('body'+'<?php echo $value; ?>'+'-wrapper').hide();
					$('multiLanguageLinkShow').setStyle('display', 'block');
					$('multiLanguageLinkHide').hide();
				}
				else {
					$('title'+'<?php echo $value; ?>'+'-wrapper').setStyle('display', 'block');
					$('body'+'<?php echo $value; ?>'+'-wrapper').setStyle('display', 'block');
					$('multiLanguageLinkShow').hide();
					$('multiLanguageLinkHide').setStyle('display', 'block');
				}
			<?php endforeach;?>
		}
  </script>
</div>

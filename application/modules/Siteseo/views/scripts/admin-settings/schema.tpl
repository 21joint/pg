<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>Ultimate SEO / Sitemaps Plugin</h2>
<?php if (count($this->navigation)): ?>
    <div class='seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<div class='clear seaocore_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?> 
  </div>
</div>

<script type="text/javascript">
	var elementsArray = [$('site_title-wrapper'), $('siteseo_schema_alternate_name-wrapper'), $('siteseo_schema_logo-wrapper'), $('social_profiles-wrapper'), $('corporate_contacts-wrapper'), $('siteseo_schema_searchbox_show-wrapper'), $('siteseo_schema_custom-wrapper')];
	var organizationElementsArray = [$('site_title-wrapper'), $('siteseo_schema_alternate_name-wrapper'), $('siteseo_schema_logo-wrapper'), $('social_profiles-wrapper'), $('corporate_contacts-wrapper'), $('siteseo_schema_searchbox_show-wrapper')];
	var websiteElementsArray = [$('site_title-wrapper'), $('siteseo_schema_alternate_name-wrapper'), $('social_profiles-wrapper'), $('siteseo_schema_searchbox_show-wrapper')];
	var customElementsArray = [$('siteseo_schema_custom-wrapper')];

	$('siteseo_schema_type').addEvent('change', function($this) {
		toggleFormElements(this.value);
	})

	en4.core.runonce.add(toggleFormElements($('siteseo_schema_type').value));
	function toggleFormElements(value) {
		elementsArray.each(function(item) { item.hide(); })
		if (value == 'custom') 
			customElementsArray.each(function(item) { item.show(); })
		else if (value == 'WebSite') 
			websiteElementsArray.each(function(item) { item.show(); })
		else  
			organizationElementsArray.each(function(item) { item.show(); })
	}
</script>
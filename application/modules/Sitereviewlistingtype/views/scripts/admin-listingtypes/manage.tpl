<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>

<h2>
  <?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewlistingtype')) { echo $this->translate('Reviews & Ratings - Multiple Listing Types Plugin'); } else { echo $this->translate('Reviews & Ratings Plugin'); }?>
</h2>

<?php if (count($this->navigation)): ?>
  <div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<div class='clear seaocore_settings_form'>
  <div class='settings'>
    <div>
      <h3><?php echo $this->translate("Manage Multiple Listing Types") ?> </h3>
      <p class="form-description">
        <?php echo $this->translate("With Multiple Listing Types Extension, you can easily create multiple listings for different types of content. This extremely power feature helps you in managing and organizing different types of listings on your site. Here, you can create many different listing types for Real Estate, Education, News, etc. and have different types of listings organized in them automatically. You can also configure all these  independent listing types such that they appear completely different from each other in terms of Layout, Features, Custom Fields and many more.<br /><br />Below, you can create a new listing type by using 'Add New Listing Type' link. You can also edit and delete listing types created by you by clicking on the links for each. You can also configure 'Member Level Settings', 'Category-Listing Profile Mapping', 'Rating Parameters' and 'Categories' for each listing type by clicking on links for each.<br /><br /><b>Visibility:</b> While setting up a new listing type on your site, you can disable the visibility of respective listing type by using the 'Visibility' option below, so that users can not see listings of that listing type during the setup. If you disable the visibility of a listing type, then the link for that listing type will not be shown in any navigation bar, drop-downs in search forms and other places.<br /><br /><b>Note:</b> If you do not create categories for any listing type, then users would not be able to post listings in that listing type. Thus, after creating a new listing type, you need to add / manage categories for that listing type from the 'Categories' section. You can not delete the default listing type available to you with this plugin.") ?>
      </p>
      <?php if(!empty($this->showListingTypeLink)): ?>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'create'), $this->translate('Add New Listing Type'), array('class' => 'buttonlink seaocore_icon_add')) ?> <br /><br />
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if(!empty($this->success)): ?>
  <ul class="form-notices" >
    <li>
      <?php echo $this->translate("Your listing type has been successfully edited."); ?>
    </li>
  </ul>
<?php endif; ?>
  
<form id='saveorder_form' method='post' action='<?php echo $this->url(array('action' => 'manage')) ?>' style="overflow:hidden;">
	<input type='hidden'  name='order' id="order" value=''/>
	<div class="seaocore_admin_order_list" style="width:100%;">
        
		<div class="list_head">     
			<div style="width:3%;">
				<?php echo $this->translate("ID") ?>
			</div>
			<div style="width:15%;">
				<?php echo $this->translate("Listing Type") ?>
			</div>
      <div style="width:10%;">
				<?php echo $this->translate("Default Photo") ?>
			</div>
			<div style="width:48%;">
				<?php echo $this->translate("Quick Links") ?>
			</div>
      <div class="admin_table_centered" style="width:5%;">
				<?php echo $this->translate("Visibility") ?>
			</div>
			<div style="width:10%;">
				<?php echo $this->translate("Options") ?>
			</div>      
		</div>
    
		<div id='order-element'>
			<ul>
				<?php foreach ( $this->listingTypes as $listingType) :?>
					<li>
						<input type='hidden'  name='order[]' value='<?php echo $listingType->listingtype_id; ?>'>
						<div style="width:3%;">
							<?php echo $listingType->listingtype_id ?>
						</div>
						<div style="width:15%;">
              <?php echo $this->translate($listingType->title_plural) ?>
						</div>
            <div style="width:10%;">
             <?php echo $this->itemPhoto($listingType, 'thumb.icon', '', array('align' => 'center'))?>
             <?php if($listingType->photo_id): ?><br/> <a href='<?php echo $this->url(array("module" => "sitereviewlistingtype", "controller" => "listingtypes", "action" => 'remove-default-icon', 'id' => $listingType->getIdentity()), "admin_default", true) ?>' class="smoothbox"><?php echo "Remove Default Photo"; ?></a>
          <?php endif;?> 
						</div>
						<div style="width:48%;">
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereview', 'controller' => 'settings', 'action' => 'level-type', 'listingtype_id' => $listingType->listingtype_id), $this->translate('Member Level Settings')) ?> | 
              
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereview', 'controller' => 'settings', 'action' => 'categories', 'listingtype_id' => $listingType->listingtype_id), $this->translate('Categories')) ?> | 

              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereview', 'controller' => 'profilemaps', 'action' => 'manage', 'listingtype_id' => $listingType->listingtype_id), $this->translate('Category-Listing Profile Mapping')) ?> | 
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereview', 'controller' => 'ratingparameters', 'action' => 'manage', 'listingtype_id' => $listingType->listingtype_id), $this->translate('Rating Parameters')) ?>
						</div>    
            
            <div class="admin_table_centered" style="width:5%;">
            	<?php if(!empty($listingType->visible)):?>
								<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'visible', 'listingtype_id' => $listingType->listingtype_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif', '', array('title' => $this->translate('Make Un-Visible at user pages')))) ?>
							<?php else: ?>
								<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'visible', 'listingtype_id' => $listingType->listingtype_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/disapproved.gif', '', array('title' => $this->translate('Make Visible at user pages')))) ?>
							<?php endif; ?>
            </div>    
            
						<div style="width:10%;">
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'edit', 'listingtype_id' => $listingType->listingtype_id), $this->translate('Edit')) ?> | 
              <?php if($listingType->listingtype_id <= 1):?>
                <?php echo $this->translate('Delete'); ?>
              <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'delete', 'listingtype_id' => $listingType->listingtype_id), $this->translate('Delete'), array('class' => 'smoothbox')) ?>
              <?php endif; ?>
						</div>               
					</li>
				<?php endforeach; ?>
	    </ul>
    </div>
  </div>
</form>
<br />

<button onClick="javascript:saveOrder(true);" type='submit' class="clear">
	<?php echo $this->translate("Save Order") ?>
</button>

<script type="text/javascript">

  var saveFlag=false;
  var origOrder;
	var changeOptionsFlag = false;

	function saveOrder(value){
		saveFlag=value;
		var finalOrder = [];
		var li = $('order-element').getElementsByTagName('li');
		for (i = 1; i <= li.length; i++)
			finalOrder.push(li[i]);
		$("order").value=finalOrder;
		$('saveorder_form').submit();
	}

  window.addEvent('domready', function(){
			var initSitereview = [];
			var li = $('order-element').getElementsByTagName('li');
			for (i = 1; i <= li.length; i++)
					initSitereview.push(li[i]);
			origOrder = initSitereview;
			var temp_array = $('order-element').getElementsByTagName('ul');
			temp_array.innerHTML = initSitereview;
			new Sortables(temp_array);
	});

	window.onbeforeunload = function(event){
		var finalOrder = [];
		var li = $('order-element').getElementsByTagName('li');
		for (i = 1; i <= li.length; i++)
			finalOrder.push(li[i]);

		for (i = 0; i <= li.length; i++){
			if(finalOrder[i]!=origOrder[i])
			{
				changeOptionsFlag = true;
				break;
			}
		}
	}
</script>

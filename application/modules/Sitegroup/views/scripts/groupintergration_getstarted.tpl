<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroup
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: groupintergration_getstarted.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
$viewer = Engine_Api::_()->user()->getViewer();
$addableCheck = Engine_Api::_()->getApi( 'settings' , 'core' )->getSetting( 'addable.integration');
$createPrivacy = 1;
$sitegroupintegrationEnabled = Engine_Api::_()->getDbtable('modules',
'core')->isModuleEnabled('sitegroupintegration');
if(!empty($sitegroupintegrationEnabled)) :
	$getHost = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
	$getGroupType = Engine_Api::_()->sitegroupintegration()->getGroupType($getHost);
	if( !empty($getGroupType)):
		$mixSettingsResults = Engine_Api::_()->getDbtable( 'mixsettings' , 'sitegroupintegration')->getIntegrationItems();

		foreach($mixSettingsResults as $modNameValue):
			if($addableCheck == 1) :
			  $Params = Engine_Api::_()->sitegroupintegration()->integrationParams($modNameValue["resource_type"], $modNameValue['listingtype_id']);
				$createPrivacy =  $Params['create_privacy'] ;
			endif;
			if (Engine_Api::_()->sitegroup()->hasPackageEnable()) :
				if($createPrivacy) :
					if (Engine_Api::_()->sitegroup()->allowPackageContent($this->subject->package_id,
					"modules", $modNameValue["resource_type"] . '_' . $modNameValue['listingtype_id'])) :  ?>
						<li> <?php $canShowMessage = false;?>
							<div class="sitegroup_getstarted_num">
								<div>
									<?php echo $i; $i++;?>
								</div>
							</div>
							<div class="sitegroup_getstarted_des">
								<?php 
									if ($modNameValue["resource_type"] == 'sitereview_listing') :
									$listingType = Engine_Api::_()->getItem('sitereview_listingtype', $modNameValue['listingtype_id'])->toarray(); 
                  $titleSinUc = ucfirst($listingType['title_singular']);
                  $titleSinLc = strtolower($listingType['title_singular']);
                  ?>
									<b><?php echo $this->translate("$titleSinUc Listings"); ?></b>
								<?php else: ?>
									<b><?php echo $this->translate($modNameValue["item_title"]); ?></b>
								<?php endif; ?>
								<p><?php $item_title = strtolower($modNameValue["item_title"]);
								if ($modNameValue["resource_type"] == 'sitereview_listing') :
									echo $this->translate("Post new $titleSinLc listings to this group."); ?><?php
								else:
									echo $this->translate("Add $item_title to this group.");
								endif;
								?></p>
								<div class="sitegroup_getstarted_btn">
									<?php if ($modNameValue["resource_type"] == 'sitereview_listing') : ?>
										<a href='<?php echo $this->url(array('action' => 'index','resource_type' => $modNameValue["resource_type"], 'group_id' => $this->group_id, 'listingtype_id' => $modNameValue["listingtype_id"] ),'sitegroupintegration_create', true) ?>'><?php echo $this->translate("Post / Manage $titleSinUc Listings"); ?></a>
									<?php else: ?>
                   <?php $item_title = $modNameValue["item_title"];?>
										<a href='<?php echo $this->url(array('action' => 'index','resource_type' => $modNameValue["resource_type"], 'group_id' => $this->group_id, 'listingtype_id' => $modNameValue["listingtype_id"] ),'sitegroupintegration_create', true) ?>'><?php echo $this->translate("Add / Manage $item_title");?></a>
									<?php endif; ?>
								</div>
							</div>
						</li>
					<?php endif; ?>
	      <?php	endif;  ?>
			<?php else : ?>
				<?php
				if($createPrivacy) :
					$isGroupOwnerAllow = Engine_Api::_()->sitegroup()->isGroupOwnerAllow($this->subject,
					$modNameValue["resource_type"] . '_' . $modNameValue['listingtype_id']);
					if (!empty($isGroupOwnerAllow)) : ?>
						<li> <?php $canShowMessage = false;?>
							<div class="sitegroup_getstarted_num">
								<div>
									<?php echo $i; $i++;?>
								</div>
							</div>
							<div class="sitegroup_getstarted_des">
								<?php 
									if ($modNameValue["resource_type"] == 'sitereview_listing') :
									$listingType = Engine_Api::_()->getItem('sitereview_listingtype', $modNameValue['listingtype_id'])->toarray(); 
                  $titleSinUc = ucfirst($listingType['title_singular']);
                  $titleSinLc = strtolower($listingType['title_singular']);
                  ?>
									<b><?php echo $this->translate("$titleSinUc Listings"); ?></b>
								<?php else: ?>
									<b><?php echo $this->translate($modNameValue["item_title"]); ?></b>
								<?php endif; ?>
								<p><?php $item_title = strtolower($modNameValue["item_title"]);
								if ($modNameValue["resource_type"] == 'sitereview_listing') :
									echo $this->translate("Post new $titleSinLc listings to this group."); ?><?php
								else:
									echo $this->translate("Add $item_title to this group.");
								endif;
								?></p>
								<div class="sitegroup_getstarted_btn">
									<?php if ($modNameValue["resource_type"] == 'sitereview_listing') : ?>
										<a href='<?php echo $this->url(array('action' => 'index','resource_type' => $modNameValue["resource_type"], 'group_id' => $this->group_id, 'listingtype_id' => $modNameValue["listingtype_id"] ),'sitegroupintegration_create', true) ?>'><?php echo $this->translate("Post / Manage $titleSinUc Listings"); ?></a>
									<?php else: ?>
                    <?php $item_title = $modNameValue["item_title"];?>
										<a href='<?php echo $this->url(array('action' => 'index','resource_type' => $modNameValue["resource_type"], 'group_id' => $this->group_id, 'listingtype_id' => $modNameValue["listingtype_id"] ),'sitegroupintegration_create', true) ?>'><?php echo $this->translate("Add / Manage $item_title");?></a>
									<?php endif; ?>
								</div>
							</div>
						</li>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif;?>
		<?php	endforeach;  ?>
	<?php endif; ?>
<?php endif;	?>
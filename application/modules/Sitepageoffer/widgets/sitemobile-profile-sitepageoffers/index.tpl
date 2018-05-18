<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $viewer_id = $this->viewer->getIdentity(); ?>
<?php if (!empty($viewer_id)): ?>
  <?php $oldTz = date_default_timezone_get(); ?>
  <?php date_default_timezone_set($this->viewer->timezone); ?>
<?php endif; ?>

<?php if($this->paginator->getTotalItemCount() > 0) :?>
	<div class="sm-content-list" id="profile_sitepageoffers" >
    <?php if ($this->can_create_offer): ?>
    <div class="profile-content-top-button" data-role="controlgroup" data-type="horizontal">
				<?php
					echo $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'create', 'page_id' => $this->sitepage->page_id, 'tab' => $this->identity), $this->translate('Add an Offer'), array(
							'class' => 'buttonlink seaocore_icon_create','data-role'=>"button", 'data-icon'=>"plus", "data-iconpos"=>"left", "data-inset" => 'false', 'data-mini'=>"true",'data-corners'=>"true",'data-shadow'=>"true"
					))
					?>
			</div>
    <?php endif;?>
		<ul data-role="listview" data-icon="arrow-r">
			<?php foreach ($this->paginator as $item): ?>
				<li>
					<a href="<?php echo $item->getHref(); ?>">
            <?php if (!empty($item->photo_id)): ?>
              <?php echo $this->itemPhoto($item, 'thumb.icon') ?>
            <?php else: ?>
              <?php echo  "<img src='" . $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />" ?>
            <?php endif; ?>
						<h3><?php echo $item->getTitle() ?></h3>
						<p>   
							<?php echo $this->translate('Created by') ?>
							<strong><?php echo $item->getOwner()->getTitle(); ?></strong>
						</p>
            <p> 
              <?php echo $this->translate('End date:'); ?>
              <?php if ($item->end_settings == 1): ?>
                <?php echo $this->translate(gmdate('M d, Y', strtotime($item->end_time))) ?>
              <?php else: ?>
              <?php echo $this->translate('Never Expires') ?>
              <?php endif; ?>
            </p>
					</a> 
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ($this->paginator->count() > 1): ?>
			<?php
			echo $this->paginationAjaxControl(
							$this->paginator, $this->identity, 'profile_sitepageoffers');
			?>
		<?php endif; ?>

	</div>

<?php else:?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No offers have been created in this Page yet.'); ?>
			<?php if ($this->can_create_offer): ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="' . $this->url(array('action' => 'create', 'page_id' => $this->sitepage->page_id, 'tab' => $this->identity), 'sitepageoffer_general') . '">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>

<?php if (!empty($viewer_id)): ?>
  <?php date_default_timezone_set($oldTz); ?>
<?php endif; ?>
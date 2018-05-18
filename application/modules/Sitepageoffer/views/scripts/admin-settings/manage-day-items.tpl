<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: offer-of-day.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?><?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Offers Extension') ?></h2>
<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<h3>
  <?php echo $this->translate("Offer of the Day") ?>
</h3>
<p>
  <?php echo $this->translate('Below you can manage the entries for "Offer of the Day" widget. To mark an offer, please click on the "Add Offer of the Day" link below and select the dates. If more than one offers of the day are found for a date then randomly one will be displayed.') ?>
</p>
<br />
<div class="tip"> <span> <?php echo $this->translate('You should only make those offers as "Offer of the Day" which have their view privacy set as \'Everyone\' or \'All Registered Members\'.'); ?> </span> </div>
<br />
<div>
  <a href="<?php echo $this->url(array('action' =>'add-offer-of-day')) ?>" class="smoothbox buttonlink seaocore_icon_add" title="<?php echo $this->translate('Add  Offer of the Day');?>"><?php echo $this->translate('Add  Offer of the Day');?></a>
</div>
<br />
<div>
<?php echo $this->translate(array('%s result found.', '%s results found.', $this->offerOfDaysList->getTotalItemCount()), $this->locale()->toNumber($this->offerOfDaysList->getTotalItemCount())) ?>
</div>
<br />
<div>
	<?php if ($this->offerOfDaysList->getTotalItemCount() > 0): ?>
		<div class='admin_search'>
			<?php echo $this->formFilter->render($this) ?>
		</div>
	  <form id='multidelete_form' method="post" action="<?php echo $this->url(array('module' => 'sitepageoffer', 'controller' => 'settings', 'action' => 'multi-delete-offer'), 'admin_default'); ?>" onSubmit="return multiDelete()">
		  <table class='admin_table' width="100%">
		    <thead>
		      <tr>
						<th style='width: 1%;' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox'></th>
						<?php $class = ( $this->order == 'engine4_sitepageoffer_offers.title' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
						<th width="24%" align="left" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('engine4_sitepageoffer_offers.title', 'DESC');"><?php echo $this->translate("Offer") ?></a></th>
            <th width="24%" align="left"><?php echo $this->translate("Page Title") ?></th>
						<?php $class = ( $this->order == 'start_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
						<th width="24%" align="left" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('start_date', 'DESC');"><?php echo $this->translate("Start Date") ?></a></th>
						<?php //Start End date work  ?>
						<?php $class = ( $this->order == 'end_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
						<th width="24%" align="left" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('end_date', 'DESC');"><?php echo $this->translate("End Date") ?></a></th>
						<th width="24%" align="left"><?php echo $this->translate("Options");?></th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php foreach ($this->offerOfDaysList as $offer): ?>
            <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepage.photos-sitepage', $offer->page_id, $layout);?>
            <?php $sitepageoffer_object = Engine_Api::_()->getItem('sitepageoffer_offer', $offer->resource_id);?>
						<td width="1%"><input name='delete_<?php echo $offer->itemoftheday_id; ?>' type='checkbox' class='checkbox' value="<?php echo $offer->itemoftheday_id ?>"/></td>
            <?php if($offer->photo_id):?>
							<td width="24%" class=""><?php echo $this->htmlLink($sitepageoffer_object->getHref(array('tab' => $tab_id)),$this->itemPhoto($sitepageoffer_object, 'thumb.normal'),array('title'=>$sitepageoffer_object->getTitle())); ?></td>
            <?php else:?>
              <td width="24%" class=""><?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepageoffer_object->owner_id, 'offer_id' =>  $sitepageoffer_object->offer_id,'tab' => $tab_id,'slug' => $sitepageoffer_object->getOfferSlug($sitepageoffer_object->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />",array('title' => $sitepageoffer_object->getTitle())) ?></td>
            <?php endif;?>
            <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $offer->page_id);?>
            <?php             
             	$truncation_limit = 13;
							$tmpBodytitle = strip_tags($sitepage_object->title);
							$item_sitepagetitle = ( Engine_String::strlen($tmpBodytitle) > $truncation_limit ? Engine_String::substr($tmpBodytitle, 0, $truncation_limit) . '..' : $tmpBodytitle );             
            ?>          
              
						<td width="18%" class='admin_table_bold'><?php echo $this->htmlLink($sitepage_object->getHref(), $item_sitepagetitle, array('title' => $sitepage_object->title, 'target' => '_blank')) ?></td>
						<td width="24%"> <?php echo $this->translate(gmdate('M d,Y',strtotime($offer->start_date)))?></td>
						<td width="24%"> <?php echo $this->translate(gmdate('M d,Y',strtotime($offer->end_date)))?></td>
						<td width="24%">
						<a href='<?php echo $this->url(array('action' => 'delete-offer-of-day', 'id' => $offer->itemoftheday_id)) ?>' class="smoothbox" title="<?php echo $this->translate("delete") ?>">
						<?php echo $this->translate("delete") ?>
						</a>
						</td>
		      </tr>
		      <?php endforeach;?>
		    </tbody>
		  </table>
		  <br />
		  <div class='buttons'>
		  	<button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
		  </div>
		</form>
  <?php else: ?>
		<div class="tip"><span><?php echo $this->translate("No offers have been marked as Offer of the Day."); ?></span> </div>
  <?php endif;?>
	<br />
	<?php echo $this->paginationControl($this->offerOfDaysList); ?>
</div>
<script type="text/javascript">

  function multiDelete()
  {
    return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected offer ?")) ?>');
  }

  function selectAll()
  {
    var i;
    var multidelete_form = $('multidelete_form');
    var inputs = multidelete_form.elements;
    for (i = 1; i < inputs.length - 1; i++) {
      if (!inputs[i].disabled) {
        inputs[i].checked = inputs[0].checked;
      }
    }
  }
</script>

<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){

    if( order == currentOrder ) {
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }
</script>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: helpful-report.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin')?></h2>

<div class='seaocore_admin_tabs'>
  <?php
  echo $this->navigation()
          ->menu()
          ->setContainer($this->navigation)
          ->render();
  ?>
</div>
<?php if( count($this->subNavigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->subNavigation)->render()
    ?>
  </div>
<?php endif; ?>

<?php echo $this->translate('This page lists all of the reports your users have sent in regarding inappropriate FAQs. Below, you can view the FAQ for each report by using the ‘view’ option, and edit the FAQ using the ‘edit’ option.');?>

<br/><br/>

<div class='admin_members_results'>
  <?php 
  	if( !empty($this->paginator) ) {
  		$counter=$this->paginator->getTotalItemCount(); 
  	}
  	if(!empty($counter)): 
  
  ?>
		<div class="">
			<?php  echo $this->translate(array('%s FAQ reason found.', '%s FAQ reasons found.', $counter), $this->locale()->toNumber($counter)) ?>
		</div>
  <?php else:?>
		<div class="tip"><span>
			<?php  echo $this->translate("No results were found.") ?></span>
		</div>
  <?php endif; ?>
</div>

<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

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

<br />

<?php  if( !empty($counter)):?>
  <table class='admin_table'>
		<thead>
			<tr>
				<th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('faq_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>
				<th style='width: 10%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'DESC');"><?php echo $this->translate('FAQ Title'); ?></a></th>
				<th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('owner_id', 'DESC');"><?php echo $this->translate('Reporter'); ?></a></th>
				<th style='width: 10%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('reason', 'DESC');"><?php echo $this->translate('Reasons'); ?></a></th>
        <th style='width: 10%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('modified_date', 'DESC');"><?php echo $this->translate('Date'); ?></a></th>
				<th style='width: 1%;' class='admin_table_options'><?php echo $this->translate('Options'); ?></th>
			</tr>
		</thead>
    <tbody>
      <?php  if( !empty($counter)):?>
        <?php foreach( $this->paginator as $item ): ?>
          <?php $sitefaq_object = Engine_Api::_()->getItem('sitefaq_faq', $item->faq_id);?>
          <?php $owner_name = $this->user($item->owner_id)->getTitle(); ?>
          <?php $truncate_owner_name = $this->sitefaq_api->truncateText($owner_name, 10);?>
          <?php $sitefaq_reason = $this->sitefaq_api->truncateText($item->reason, 40);?> 
          <?php $sitefaq_title = $this->sitefaq_api->truncateText($item->title, 30);?>  
          <tr>
            <td><?php echo $item->faq_id ?></td>
            <td class='admin_table_bold' title="<?php echo $item->title;?>"><?php echo $this->htmlLink($sitefaq_object->getHref(), $sitefaq_title, array('title' => $item->title, 'target' => '_blank')) ?></td>
            <td class='admin_table_bold'><?php  echo $this->htmlLink($this->item('user', $item->owner_id)->getHref(), $truncate_owner_name, array('title' => $owner_name, 'target' => '_blank')) ?></td>
            <td  title="<?php echo $item->reason;?>"><?php  echo $sitefaq_reason; ?></td>
            <td><?php echo $item->modified_date ?></td>
            <td class='admin_table_options'>
						<?php echo $this->htmlLink($sitefaq_object->getHref(), 'View FAQ', array('target' => '_blank')) ?>
						|
						<?php echo $this->htmlLink(array('route' => 'sitefaq_specific','action' => 'edit', 'faq_id' => $item->faq_id), $this->translate('Edit FAQ'), array('target' => '_blank')) ?> 
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <br />
  <?php  echo $this->paginationControl($this->paginator); ?><br  />
<?php endif; ?>
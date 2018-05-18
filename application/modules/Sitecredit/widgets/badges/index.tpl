<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sitecredit/externals/styles/style_sitecredit.css');
?>
<div class="my_badge">
<?php  $count=0; if( count($this->result)): 
	foreach ($this->result as $item): $count++; ?>
	<div class=<?php echo $count > 1 ? "others_badge" : "first_badge" ; ?> >
	  <?php 
echo $this->htmlLink(array('route' => 'credit_general', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'view-detail', 'id' => $item->badge_id), $this->itemPhoto($item, 'thumb.profile'),array('class' => 'smoothbox'));
?>
  <div class="badge_title"><?php echo $this->translate($item->title) ?></div>
  </div> 
<?php endforeach; ?>
<?php endif;?>
</div>

	

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
<div>
<?php if($this->showBalance) : ?>
	<?php if(!empty($this->credits)) :?>
    <div class="user_current_credits">
      <div id="showBalance_current_credit_1" ><?php echo vsprintf($this->translate("Current ".ucfirst($GLOBALS['credits'])." : %s "), array(
            $this->credits
         ));
  ?></div>
    </div>
  <?php endif; ?>
<?php endif; ?>

<?php if($this->allowBadge):?>
<?php  $changeclass=true; if($this->showRank) : ?>
<?php if( count($this->result)): ?>    
<?php if($this->showNextRank) : ?>
<?php if(!empty($this->nextRank)): ?>
<?php $changeclass=false; ?>
<?php endif;endif;?>
<?php endif;endif;?> 
<div class="badge_box">
<?php if($this->showRank) : ?>
<?php if( count($this->result)): ?>
<?php foreach ($this->result as $item): ?>
  <div class="photo" <?php echo ($changeclass)?'style="display:block;width:100%;"':''; ?>>
   	  <?php echo $this->htmlLink(array('route' => 'credit_general', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'view-detail', 'id' => $item->badge_id), $this->itemPhoto($item, 'thumb.icon'),array('class' => 'smoothbox')); ?>
 <div class='badge_title'> <?php echo $this->translate($item->title); ?></div>
</div>
<?php endforeach; ?>
<?php endif;?>
<?php endif;?>

<?php if($this->showRank) : ?>
<?php if( count($this->result)): ?>    
<?php if($this->showNextRank) : ?>
<?php if(!empty($this->nextRank)): ?>
<div class="right_arw"> 
<?php echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/arrow_right.png'); ?>
</div>  
<?php endif;endif;?>
<?php endif;endif;?> 

<?php if($this->showNextRank) : ?>
<?php if(!empty($this->nextRank)): ?>
<div class="photo" <?php echo ($changeclass)?'style="display:block;width:100%;"':'';?> >
	  <?php 
echo $this->htmlLink(array('route' => 'credit_general', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'view-detail', 'id' => $this->nextRank->badge_id), $this->itemPhoto($this->nextRank, 'thumb.icon'),array('class' => 'smoothbox'));
?>
<div class="badge_title"><?php echo $this->translate($this->nextRank->title) ?></div>
<?php $creditNeeded=$this->nextRank->credit_count;?>
</div> 
<?php else:?>
<?php if(count($this->result)): ?>
<?php $creditNeeded=0;?>
<div><?php echo $this->translate('Congratulation! You have achieved the highest badge.');?></div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
</div>
<?php if(count($this->nextRank) && $this->showNextRank) : ?>
<div class="badge_progressbar">
<div id="myProgress">
  <div id="myBar"></div>
</div>
<div id="showBalance_current_credit_box"><div id="showBalance_current_credit" class="showBalance_current_credit"></div></div>
<div id="showBalance_target_credit" class="showBalance_target_credit"></div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if($this->showlimit && !empty($this->creditLevelLimit)) : ?>
<div class="badge_progressbar"> 
<div id="myProgress2">
<div id="myBar2"></div>
</div>
<div id="showLimit_creditDiffrence"></div>
</div>
<?php endif; ?>
</div>	
<script type="text/javascript">


window.addEvent('domready',function () {
 
var perc1=100;
var perc2=100;
if($("myProgress"))
	{   if(<?php echo empty($creditNeeded)?0:$creditNeeded?>){
		 perc1=((<?php echo $this->credits?>) / (<?php echo empty($creditNeeded)?0:$creditNeeded?>))*100;
	} 		
		$("myBar").setStyle('width', perc1+'%');
    $("showBalance_current_credit").setStyle('width', perc1+'%');
		$("showBalance_current_credit").innerHTML="<?php echo $this->credits ?>";
		$("showBalance_target_credit").innerHTML="<?php echo $creditNeeded ?>";
	}

if($("myProgress2")){
   
if(<?php echo $this->nolimit?>)
		{
		  $("showLimit_creditDiffrence").innerHTML="Your "+<?php echo $GLOBALS['credits'] ?>+" balance for today is"+<?php echo $this->creditEarnedDay ?>;
		} else {
			var perc2 = ((<?php echo $this->creditEarnedDay?>) / (<?php echo $this->creditLimit?>))*100;
	      $("myBar2").setStyle('width', perc2+'%');
          if(<?php echo $this->creditEarnedDay?>==<?php echo $this->creditLimit?>)
          {
			$("showLimit_creditDiffrence").innerHTML="You can not earn any more "+<?php echo $GLOBALS['credit'] ?>+" today.";
          } else {
        	$("showLimit_creditDiffrence").innerHTML="You can earn <?php echo $this->creditDifference ?> "+<?php echo $GLOBALS['credits']?>+" more today.";
          }
		
		}
 }
});
</script>	

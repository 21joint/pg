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
  <?php if($this->target=="link") : ?>
   <?php if(!empty($this->AffiliateLinkPermission)) : ?>
     <div class="next_target_btnbox">
      <?php if($this->showReffralLink) : ?>
      <input type="text" name="Affiliate_link" id="Affiliate_link" value="<?php echo $this->link ?>" readonly>
      <?php endif; ?>  
        <div class="invite_friends mtop15">

        <?php echo $this->htmlLink(array('route'=>'default','module'=>'sitecredit','controller'=>'invite','action'=>'index','format'=>'smoothbox'), $this->translate('Invite Friends'), array(
        'class'=>'smoothbox link_button',
        )) ?>
      </div>
    </div>
    <?php if(!empty($this->link_credit)): ?>
      <div><?php   echo vsprintf($this->translate("You can earn %s ".$GLOBALS['credits']." per referral."), array(
            $this->link_credit
         ));?></div>
    <?php endif;?>
  <?php endif; ?>	
<?php endif; ?>	

<?php if($this->target=="badge") :  ?>
<?php $changeclass=true;?>
    <?php if(count($this->result)):  ?>
    <?php if( count($this->nextRank)): ?>
    <div class="right_arw">
      <?php $changeclass=false;?>
    </div>
    <?php endif;?>      
    <?php endif;?>

	<div class="badge_box">
    <?php if(count($this->result)):  ?>
      <?php foreach ($this->result as $item): ?>
       <div class="photo" <?php echo ($changeclass)?'style="display:block; width:100%;"':'';?>>
         <?php 
echo $this->htmlLink(array('route' => 'credit_general', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'view-detail', 'id' => $item->badge_id), $this->itemPhoto($item, 'thumb.icon'),array('class' => 'smoothbox'));
?>
         <div class="badge_title"><?php echo $item->title;?></div>  
       </div> 
     <?php endforeach; ?>
   <?php endif;?>

    <?php if(count($this->result)):  ?>
    <?php if( count($this->nextRank)): ?>
    <div class="right_arw">
      <?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/arrow_right.png'." />";?>
    </div>
    <?php endif;?>	    
    <?php endif;?>

     <?php if( count($this->nextRank)): ?>
    <div class="photo" <?php echo ($changeclass)?'style="display:block; width: 100%;"':'';?>>
    <?php 
echo $this->htmlLink(array('route' => 'credit_general', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'view-detail', 'id' => $this->nextRank->badge_id), $this->itemPhoto($this->nextRank, 'thumb.icon'),array('class' => 'smoothbox'));
?>
    <div class="badge_title"><?php echo $this->nextRank->title;?></div> 
    </div> 
   
    <?php $this->creditNeeded=$this->nextRank->credit_count;?>
    <?php else:?>
    <?php if(count($this->result)): ?>
      <?php $this->creditNeeded=0;?>
      <div><?php echo $this->translate("Congratulation! You have achieved the highest badge.");?></div>
    <?php endif; ?>
  <?php endif; ?>
</div>
  

<?php if(count($this->nextRank)): ?>
<div class="badge_progressbar">
    <div id="myProgressbar">
      <div id="myBarblock">
      </div>
    </div>
    <div id="current_credit_box"><div id="current_credit" class="current_credit"></div></div>
    <div id="target_credit" class="target_credit"></div><br/>
    <span id="show_text"></span>
</div>
<?php endif; ?>

<?php endif; ?>	
</div>

<script type="text/javascript">
if(document.getElementById("Affiliate_link")){
  document.getElementById("Affiliate_link").onclick = function() {
    this.select();
    document.execCommand("copy");
  } 
}
window.addEvent('domready',function () {
 
var percent=100;
if($("myProgressbar"))
  {   if(<?php echo empty($this->creditNeeded)?0:$this->creditNeeded?>){
     percent=((<?php echo empty($this->credits)?0:$this->credits?>) / (<?php echo empty($this->creditNeeded)?0:$this->creditNeeded?>))*100;
  }     
    $("myBarblock").setStyle('width', percent+'%');
    $("current_credit").setStyle('width', percent+'%');
    $("current_credit").innerHTML="<?php echo $this->credits ?>";
    $("target_credit").innerHTML="<?php echo $this->creditNeeded ?>";
    if(<?php echo empty($this->creditNeeded)?0:$this->creditNeeded?>){
      $("show_text").innerHTML= "Hey! You need just "+<?php echo $this->creditNeeded-$this->credits ?>+" "+<?php echo $GLOBALS['credits'] ?>+" to achieve your next badge.";     
    }
}});
</script> 



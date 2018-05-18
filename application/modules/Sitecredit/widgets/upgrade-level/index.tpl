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
 <style type="text/css">
#upgrade_level-element::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #F5F5F5;
}

#upgrade_level-element::-webkit-scrollbar
{
    width: 8px;
    background-color: #F5F5F5;
}

#upgrade_level-element::-webkit-scrollbar-thumb
{
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
 </style>
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sitecredit/externals/styles/style_sitecredit.css');
?>

<div class="sitecredit_upgrade_level">
    <?php if($this->superadmin): ?>
     <div class="sitecredit_upgrade_level_desc"><?php echo $this->translate("As you are Super Admin so you cannot upgrade your member level to any other member level."); ?></div>

	  <?php else : ?>

     <div class="level1 sitecredit_current_level txt_center"> 
      <span><?php echo Engine_Api::_()->getItem('authorization_level', $this->viewer->level_id)->title;?></span>
      <span><?php echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/enable.png');?></span>
     </div>

      <?php if($this->showlevel) { ?>

			<?php if(count($this->result)) { ?>
			<div>
			<?php echo "Upgrade request sent for ".$this->level." level." ?>
			</div>
			<?php } else { ?> 
        <?php if(!empty($this->creditNeeded)):?>
				<?php if(!empty($this->needCredits)) {?>
        <div class="indicator_arrow"><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/down_arw.png'." />";?></div>
				<div class="level1">
				<?php echo $this->level." level."; ?>
				<?php echo $this->creditNeeded.$this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/credit.png'); ?>      
				</div>
        <div class="mtop15 txt_center"><a href="javascript:void(0)" class="disable_button"><?php echo $this->translate('Upgrade Level'); ?></a></div>
				<?php } else {?>
        <div class="indicator_arrow"><i class="fa fa-arrow-down"></i></div>
				<div class="level1">
        <?php echo $this->level." level."; ?>
        <?php echo $this->creditNeeded.$this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/credit.png'); ?>
				</div>
        <div class="mtop15"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'upgrade','level_id'=>$this->level_id), $this->translate('Upgrade Level'), array('class' => 'smoothbox link_button')); ?></div>
				<?php } ?>
        <?php else: ?>
        <div class="sitecredit_upgrade_level_desc"><?php echo $this->translate("No member level available as you are already at highest member level."); ?></div>
        <?php endif;?>
			<?php } ?>

		<?php } else{ ?>

			<div>
			<?php if(count($this->result)) { ?>
			<div>
			<?php  echo vsprintf($this->translate("Upgrade request sent for %s level."), array(
            Engine_Api::_()->getItem('authorization_level', $this->result->requested_level)->title
         )); ?>
			</div>
			<?php } else { ?>
      
      <form>
      <?php if(count($this->levels)): ?>
      <div class="indicator_arrow"><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/down_arw.png'." />";?></div>
      <?php endif; ?>
      <div id="upgrade_level-wrapper" class="form-wrapper">
      <div id="upgrade_level-element" class="form-element">
      <ul class="form-options-wrapper">
      
      <?php if(count($this->result)) { ?>
        <div>
          <?php 
           
          echo vsprintf($this->translate("Upgrade request sent for %s level."), array(
           Engine_Api::_()->getItem('authorization_level', $this->result->requested_level)->title
         ));
       ?>
        </div>
        <?php } else {
        if(count($this->levels)) { 
        ?>
        <?php
        foreach ($this->levels as $item) { ?>
          <?php if(empty($item->credit_point)) continue; ?>
        <div>
          <?php if($this->currentCredits): 
          $needCredits=$item->credit_point-$this->currentCredits;
          if($needCredits<=0): ?>   
          <li>
            <input type="radio" name="level_upgrade_button" id="level_upgrade_button" value="<?php echo $item->level_id ?>" onchange="onUpgradeLevelChange(this.value);" >
            <label ><?php echo Engine_Api::_()->getItem('authorization_level', $item->level_id)->title." Level"; ?></label>
            <p><?php echo $item->credit_point; ?> credits <?php echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/credit.png'); ?></p>
          </li>
		      <?php  else : ?>
          <li>
          <input type="radio" name="level_upgrade_button" id="level_upgrade_button" value="<?php echo $item->level_id ?>" disabled >
          <label ><?php echo Engine_Api::_()->getItem('authorization_level', $item->level_id)->title." Level"; ?></label>
            <p><?php echo $item->credit_point; ?> credits <?php echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/credit.png'); ?></p>
           </li> 
          <?php endif; ?>
          <?php endif; ?>
			  </div>
		  <?php } } else { ?>
       <div class="sitecredit_upgrade_level_desc"><?php echo $this->translate("You are already on highest level you can't upgrade any further."); ?></div>      
      <?php } ?> 
       <?php } ?>
		</ul>
		</div>
	</div>
  <div id="submmit_button_div" class="mtop15">
  
  </div>
  </form>
      <?php } ?>	
 </div>
 <?php } ?>	
<?php endif; ?>	
</div>


<script type="text/javascript">
var href;
function onUpgradeLevelChange(level_id)
{  href = en4.core.baseUrl+'sitecredit/index/upgrade/level_id/'+level_id;
 document.getElementById('submmit_button_div').innerHTML='<a  id="level_upgrade_link_button" class="link_button">Upgrade Level </a>';
 document.getElementById("level_upgrade_link_button").addEvent('click',function () {
      Smoothbox.open(href);
 }); 
}
</script>
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
<div class="sitecredit_recent_activities">
<?php if(count($this->result)) : ?>
<ul>
  <?php foreach($this->result as $data) : ?>
	<li class="sitecredit_recent_activities_list">
  <?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/activity.png'." />";
    $column='language_'.$this->language;
     if(empty($data->$column)){
      $activity_type = $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($data->activity_type));
      if(!empty($activity_type)){
      $activity_type = str_replace("(subject)","",$activity_type);
      $activity_type = str_replace("(object)","",$activity_type);
      echo $activity_type;
      }
     }
    else {
    echo $data->$column;
    }
   ?>
  </li>
  <li class="sitecredit_recent_activities_list_points">
    <div class="sitecredit_recent_activities_credits">
      <span class="sitecredit_recent_activities_credits_block"><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/credit.png'." />"; ?><strong><?php echo $data->credit_point; ?></strong></span>
      <span class="fright"><i class="seaocore_icon_date"></i> <?php echo date('dS M', strtotime($item->creation_date));?></span>
    </div>
  </li>
  
  <?php endforeach; ?>
</ul>

<?php else : ?>
<div class="tip">
	<?php echo $this->translate('No activity performed yet.'); ?>
</div>
<?php endif; ?>
</div>

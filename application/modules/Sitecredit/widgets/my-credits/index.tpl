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
<div class="my_credits">
<?php if($this->totalCredits) : ?>
<ul class="credits_validity">
  <li>
    <div id="sitecredit_total_credits">
      <?php echo vsprintf($this->translate("<strong>Total ".ucfirst($GLOBALS['credits'])." :</strong> %s"), array(
            $this->totalCredits
         )); ?>
    </div>
  </li>
  <li>
    <div id="sitecredit_validity_date">
    <?php if($this->validityDate && $this->totalCredits) 
          echo vsprintf($this->translate("<strong>Validity Date :</strong> %s"), array(
            date('dS F Y ', strtotime($this->validityDate))
         )); 
    ?>
    </div>
  </li>
</ul>
<?php else : ?>
  <div class="tip">
    <span><?php echo $this->translate("You have Zero ".ucfirst($GLOBALS['credit'])." Balance");?></span>
  </div>
<?php endif;?>
<?php if($this->validityDays && $this->totalCredits) { if($this->validityDays <= 60 ): ?>
<div id="sitecredit_validity_days" class="sitecredit_validity_days">
  <?php echo vsprintf($this->translate("Your ".$GLOBALS['credits']." validity will expire in %s Days."), array(
            $this->validityDays
         )); ?>
</div>
<?php endif; } ?>

<div id="sitecredit_credit_account" class="sitecredit_credit_account_table">
<?php if(count($this->result)) : ?>
 <table width="90%">
  <thead>
    <tr>
      <th width="50%" rowspan="2"><?php echo  $this->translate(ucfirst($GLOBALS['credit'])." Type")?></th>
      <th width="50%" colspan="2"><?php echo  $this->translate(ucfirst($GLOBALS['credit'])." Values")?></th>
    </tr>
    <tr>
      <th><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/addition.png'." />";?></th>
      <th><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/deduction.png'." />";?></th>
    </tr>
   </thead> 
    <tbody>
    <?php foreach($this->result as $item) : ?>
    <?php if($item->credit > 0) : ?>
    <tr>
    <?php else : ?>
    <tr>
    <?php endif; ?>
      <td>
        <?php if(!empty($this->creditTypeArray[$item->type])) 
          echo $this->translate($this->creditTypeArray[$item->type]); 
          else  
          echo $item->type;
        ?>
      </td>
      <?php if($item->credit >0 ) : ?>
      <td><?php echo $item->credit ?></td>
      <td></td>
      <?php else : ?>
      <td></td>
      <td><?php echo (abs($item->credit)); ?></td>
      <?php endif; ?>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
</div>
</div>

<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view-activity.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div style="width:400px;height:300px;">
  <br/>
  <br/>
  <br/>
  <div class="slider_reveal" id="tips_slider_content1">
    <table width ="100%">
     <thead><tr><td width = "50%"><b><?php echo $this->translate('Activity Type') ?></b></td><td width = "50%"><b><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?></b></td></tr></thead> 
     <?php foreach($this->rawData as $item) : ?>
       <tr>
         <td>
          <?php $activity=Engine_Api::_()->getDbtable('activitycredits','sitecredit')->fetchRow(array('activitycredit_id = ?' => $item->type_id));

          $column='language_'.$this->language;

          if(empty($activity->$column)):
            $activity_type = $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($activity->activity_type));
          if(!empty($activity_type)){
            $activity_type = str_replace("(subject)","",$activity_type);
            $activity_type = str_replace("(object)","",$activity_type);
            echo $activity_type;
          }
          else :
            echo $activity->$column;
          endif; 
          ?>
        </td>
        <td><?php echo $item->credit ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<br/>
<br/>
<br/>
<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
  <?php echo $this->translate("close") ?></a>
</div>
<?php if( @$this->closeSmoothbox ): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>
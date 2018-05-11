<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<style>
  .table2{
    width: <?php echo (count($this->languageNameList)>1)? 150 : 100 ?>%;
  }
</style>
<h2 class="fleft">
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>
<?php if( count($this->navigation) ): ?>

  <div class='seaocore_admin_tabs clr'>
    <?php
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<?php if( count($this->navigationSubMenu) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    echo $this->navigation()->menu()->setContainer($this->navigationSubMenu)->render()
    ?>
  </div>
<?php endif; ?>
<script type="text/javascript">
  var onModuleChange =function(level_id){
    window.location.href= en4.core.baseUrl+'admin/sitecredit/credit/manage/id/'+$('member_level').value+/module_id/+$('module_id').value;
  }
</script>
<?php 
echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'credit', 'action' => 'index'), $this->translate('Set Credit Values for New Activities.'));
?>
<form enctype="application/x-www-form-urlencoded" class="credit_form" action="" method="post" onsubmit="return validateForm()">
  <div>
    <div class="manage_activities">
      <h3>Manage Activities</h3>
      <p class="form-description">Here, you can manage credit value of various activities from different modules as per member level available on your website. You can also enable / disable the various activities.</p>
      <div class="form-elements">
        <div id="member_level-wrapper" class="form-wrapper">
          <div id="member_level-label" class="form-label">
            <label for="member_level" class="optional">Member Level</label>
          </div>
          <div id="member_level-element" class="form-element">
            <select name="member_level" id="member_level" onchange="javascript:onModuleChange(this.value);" >
              <?php  foreach ($this->levelOptions as $key => $value): ?>
                <?php if($key==$this->level_id) : ?>
                  <option value="<?php echo $key?>" selected="selected" > <?php echo $value ?> </option>
                <?php else : ?>
                  <option value="<?php echo $key?>"> <?php echo $value ?> </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div id="module_id-wrapper" class="form-wrapper">
          <div id="module_id-label" class="form-label">
            <label for="module_id" class="optional">Modules</label>
          </div>
          <div id="module_id-element" class="form-element">
            <select name="module_id" id="module_id" onchange="javascript:onModuleChange(this.value);" >
              <?php if($this->module == 'all_module'): ?>
                <option value="all_modules" selected="selected" >All modules </option>
              <?php else: ?>
                <option value="all_modules"  >All modules </option>
              <?php endif; ?>
              <?php  foreach ($this->modules as $key => $value):
                if(empty($value['module']))
                  continue;?>
                <?php if($value['module']==$this->module) :  ?>
                <option value="<?php echo $value['module']?>"  selected="selected"> <?php echo $value['title'];?> </option> 
                <?php else : ?>
                <option value="<?php echo $value['module']?>" > <?php echo $value['title'];?> </option>
                <?php endif; ?>  
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="manage_table">
    <?php if(empty($this->data)) :  ?>
      <div class="tip"> <span> <?php echo $this->translate('You have not set the credits for selected member level and module. To set the credits, go to ‘Manage Credits’ --> ‘Assign Credit Values’ section.');?> </span> </div>
    <?php else :?>
      <table class="table2">
        <thead>
          <tr>
            <th width="3%" align="center" rowspan="2">Activity Type</th>
            <th width="1%" align="center" rowspan="2">Status</th>
            <th width="1%" align="center" colspan="4">Credit Values</th>
            <?php foreach($this->languageNameList as $key => $value): ?>
              <th width="1%" align="center" rowspan="2"><?php echo $value; ?></th>
            <?php endforeach; ?>
          </tr>
          <tr>
            <th>First Activity</th>
            <th>Next Activities</th>
            <th>Max/Per Day</th>
            <th>Deduction</th>              
          </tr>
        </thead>
        <tbody>
          <?php $diffModule;
          foreach ($this->values as $actionType):
            if (!in_array($actionType->activity_type, $this->activityNotShown)):
              ?>
            <?php $module = $actionType->module;
            if($module!=$diffModule): 
              $diffModule=$module;
              $title=$actionType->title; ?>
            <tr class="credits_activity">
             
              <td align="center" colspan="<?php echo count($this->languageNameList)+6; ?>"><?php if(empty($title)) echo $diffModule; else echo $title; ?></td></tr>
            <?php endif;?>
            <tr>
              <td>
                <?php $data =  $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($actionType->activity_type));
                if(!empty($data)){
                  $data = str_replace("(subject)","",$data);
                  $data = str_replace("(object)","",$data);
                  echo $data ;
                }

                ?></td>
                <td align="center">
                  <div id='<?php echo $actionType->activity_type?>_status-wrapper' class="form-wrapper" style="display: block;">
                    <div class='admin_table_centered'>
                      <?php echo ($actionType->status == "enabled" ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'credit', 'action' => 'disable-status','status'=>0, 'activity_id' => $actionType->activitycredit_id ,'id'=> $this->level_id ,'module_id'=> $this->module), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/enabled1.gif', '', array('title' => $this->translate('Disable credits for this activity'))), array())  :  $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'credit', 'action' => 'disable-status','status'=>1, 'activity_id' => $actionType->activitycredit_id ,'id'=> $this->level_id,'module_id'=>$this->module), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/enabled0.gif', '', array('title' => $this->translate('Enable credits for this activity'))))) ?>
                    </div>
                    <div id="error_<?php echo $actionType->activity_type?>" style="color:red;"></div>
                  </div>
                </td>
                <td>
                  <div id='<?php echo $actionType->activity_type?>_first-wrapper' class="form-wrapper" style="display: block;">
                    <div id='<?php echo $actionType->activity_type?>_first-element' class="form-element">
                      <input type="text" name='<?php echo $actionType->activity_type?>_first' id='<?php echo $actionType->activity_type?>_first' size="4" value="<?php echo $actionType->credit_point_first ?>" onkeypress="return isNumberKey(event)" style="width: 3em">
                    </div>
                  </div>
                </td>
                <td>
                  <div id='<?php echo $actionType->activity_type?>_other-wrapper' class="form-wrapper" style="display: block;">
                    <div id='<?php echo $actionType->activity_type?>_other-element' class="form-element">
                      <input type="text" name='<?php echo $actionType->activity_type?>_other' id='<?php echo $actionType->activity_type?>_other' size="4" value="<?php echo $actionType->credit_point_other ?>" onkeypress="return isNumberKey(event)" style="width: 3em" >
                    </div>
                  </div>
                </td>
                <td>
                  <div id='<?php echo $actionType->activity_type?>_limit-wrapper' class="form-wrapper" style="display: block;">
                    <div id='<?php echo $actionType->activity_type?>_limit-element' class="form-element">
                      <input type="text" name='<?php echo $actionType->activity_type?>_limit' id='<?php echo $actionType->activity_type?>_limit' size="4" onkeypress="return isNumberKey(event)" value="<?php echo $actionType->limit_per_day ?>" style="width: 3em">
                    </div>
                  </div>
                </td>
                <?php if (!in_array($actionType->activity_type, $this->activityNotShownDeletion)): ?>
                  <td>
                    <div id='<?php echo $actionType->activity_type?>_delete-wrapper' class="form-wrapper" style="display: block;">
                      <div id='<?php echo $actionType->activity_type?>_delete-element' class="form-element">
                        <input type="text" name='<?php echo $actionType->activity_type?>_delete' id='<?php echo $actionType->activity_type?>_delete' size="4" onkeypress="return isNumberKey(event)" value="<?php echo $actionType->deduction ?>"  style="width: 3em">
                      </div>
                    </div>
                  </td>
                <?php else: ?>
                  <td>
                    <div id='<?php echo $actionType->activity_type?>_delete-wrapper' class="form-wrapper" style="display: block;">
                      <div id='<?php echo $actionType->activity_type?>_delete-element' class="form-element">
                        <input type="text" name='<?php echo $actionType->activity_type?>_delete' id='<?php echo $actionType->activity_type?>_delete' size="4"  value="" placeholder="N/A" readonly  style="width: 3em">
                      </div>
                    </div>
                  </td>
                <?php endif; ?>
                <?php foreach($this->languageNameList as $key => $value): $column="language_".$key;?>
                  <td>
                    <div id='<?php echo $actionType->activity_type?>_<?php echo $column?>-wrapper' class="form-wrapper" style="display: block;">
                      <div id='<?php echo $actionType->activity_type?>_<?php echo $column?>-element' class="form-element">
                        <textarea name='<?php echo $actionType->activity_type?>_<?php echo $column?>' id='<?php echo $actionType->activity_type?>_<?php echo $column?>' placeholder="Enter text here.." rows="1" cols="3"><?php echo $actionType->$column ?></textarea>
                      </div>
                    </div>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endif;
            endforeach;
            ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
    <center>
      <div id="submit-wrapper" class="form-wrapper">
        <div id="submit-label" class="form-label">&nbsp;</div>
        <div id="submit-element" class="form-element">
          <button name="submit" id="submit" type="submit">Save Changes</button>
        </div>
      </div>
    </center>
  </div>
</form>
<script type="text/javascript">

  function validateForm(){
    var count=0;
    <?php  foreach ($this->values as $actionType): ?>
    <?php if (in_array($actionType->activity_type, $this->activityNotShown)) continue; ?>
    var first= document.getElementById("<?php echo $actionType->activity_type?>_first").value;
    var other=document.getElementById("<?php echo $actionType->activity_type?>_other").value;
    var deduction=document.getElementById("<?php echo $actionType->activity_type?>_delete").value;
    var limit_per_day=document.getElementById("<?php echo $actionType->activity_type?>_limit").value;
    
    if(!((first==''||parseInt(first)==0) && (other==''|| parseInt(other)==0) && (deduction==''|| parseInt(deduction)==0) && (limit_per_day==''|| parseInt(limit_per_day)==0)))
    {
      if((first==''|| parseInt(first)==0) && ( deduction!=='' || limit_per_day!=='' || parseInt(deduction)!==0 || parseInt(limit_per_day)!==0 )){
        document.getElementById("error_<?php echo $actionType->activity_type?>").style.display = "block";
        document.getElementById("error_<?php echo $actionType->activity_type?>").innerHTML='<?php  echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/error.png', '', array('title' => $this->translate('Please enter credit values in Credits for First time')))?>';  
        count = count+1;
      }  
      if((other!=='' && parseInt(other)!==0) && (first=='' || parseInt(first)==0))
      {
        document.getElementById("error_<?php echo $actionType->activity_type?>").style.display = "block";
        document.getElementById("error_<?php echo $actionType->activity_type?>").innerHTML='<?php  echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/error.png', '', array('title' => $this->translate('Please enter credit values in Credits for First time')))?>'; 
        count = count+1;
      }
    }
    
    if( (parseInt(limit_per_day)!==0 && limit_per_day!=='') && (parseInt(limit_per_day) < parseInt(first)))
    {
      document.getElementById("error_<?php echo $actionType->activity_type?>").style.display = "block";
      document.getElementById("error_<?php echo $actionType->activity_type?>").innerHTML='<?php  echo $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/icons/error.png', '', array('title' => $this->translate('Credit values in Limit Per Day field should be greater or equal to cedit points for performing activity first time.')))?>'; 
      count = count+1;
    }
    
  <?php endforeach; ?>
  if(count>0) {
    return false;
  } else {
    document.getElementById("error_<?php echo $actionType->activity_type?>").style.display="none";
    return true;
  }
  
}

function isNumberKey(evt) { 
  var charCode = (evt.charCode) ? evt.which : event.keyCode
  
  if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
    return false; 
  
  return true; 
} 
</script> 
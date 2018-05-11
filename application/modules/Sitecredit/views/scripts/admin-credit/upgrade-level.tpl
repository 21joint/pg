<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: upgrade-level.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2 class="fleft">
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<?php if( count($this->navigationSubMenu) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigationSubMenu)->render()    ?>
  </div>
<?php endif; ?>
<div>
  <form enctype="application/x-www-form-urlencoded" class="credit_form" method="post">
    <div>
      <div>
        <div id="success_massage" style="color:green;"><?php echo $this->success_massage ?></div>
        <br/>
        <h4 class="description">You can can assign credits to each member level which will be required by a user to upgrade from his current member level to that member level. [ Note : If you don't want any member level to upgrade, then you can set that value to 0. ]</h4>
        <div class="form-elements">
          <?php foreach( $this->levels as $level ): ?>
            <?php if($level->type == 'public' || ($level->type=='admin' && $level->flag=='superadmin')) 
            continue; 
            $permissionsTable = Engine_Api::_()->getDbtable('levels', 'sitecredit');
            $select=$permissionsTable->select()->where('level_id =?',$level->level_id);
            $result=$permissionsTable->fetchRow($select); ?>
            <br/>
            <div id="credit_point-wrapper" class="form-wrapper">
              <div id="credit_point-label" class="form-label" style="float:left; clear:left; width:150px;">
                <label for="credit_point" class="optional"><?php echo $level->getTitle()?></label>
              </div>
              <div id="credit_point-element" class="form-element">
                <input type="text" name="credit_point_<?php echo $level->level_id ?>" id="credit_point_<?php echo $level->level_id ?>" onkeypress="return isNumberKey(event)" value="<?php echo $result->credit_point ?>">
              </div>
            </div> 

          <?php endforeach; ?>
          <div id="submit-wrapper" class="form-wrapper">
            <div id="submit-label" class="form-label">&nbsp;</div>
            <div id="submit-element" class="form-element">
              <button name="submit" id="submit" type="submit">Save Changes</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  function isNumberKey(evt) { 
    var charCode = (evt.charCode) ? evt.which : event.keyCode

    if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
      return false; 
    
    return true; 
  } 
</script>
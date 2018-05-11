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
<div class='seaocore_settings_form'>
    <div class='settings'>
        <?php echo $this->form->render($this); ?>
    </div>
</div>

<script type="text/javascript">
  window.addEvent('domready',function () {
   
    $("sitecredit_year_validity-wrapper").style.display="inline-block";
    $("sitecredit_month_validity-wrapper").style.display="inline-block";
    creditValidityChange();
    
  });
  function creditValidityChange()
  {
   if ($("sitecredit_validity-wrapper")) {
    if ($("sitecredit_validity-0").checked) {
     if ($("sitecredit_month_validity-wrapper")) {
      $("sitecredit_month_validity-wrapper").hide();
    }
    if ($("sitecredit_year_validity-wrapper")) {
      $("sitecredit_year_validity-wrapper").hide();
    } 
  } else {
    if ($("sitecredit_month_validity-wrapper")) {
      $("sitecredit_month_validity-wrapper").show();
    }
    if ($("sitecredit_year_validity-wrapper")) {
      $("sitecredit_year_validity-wrapper").show();
    } 
  }
}

}

</script>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>
<h2 class="fleft">
  Credits, Reward Points and Virtual Currency - User Engagement Plugin
</h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>

<?php endif; ?>

<?php if( count($this->navigationSubMenu) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigationSubMenu)->render()
    ?>
  </div>

<?php endif; ?>

<div class='clear seaocore_settings_form'>
  <div class='settings'>

    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="text/javascript">
    window.addEvent('domready',function () {

  if ($("sitecredit_badge_enable-0").checked) {
   if ($("sitecredit_ranking-wrapper")) {
    $("sitecredit_ranking-wrapper").hide();
  } 
} else {
  if ($("sitecredit_ranking-wrapper")) {
    $("sitecredit_ranking-wrapper").show();
  } 
}
    onBadgeSettingChange();
    
  });
 function onBadgeSettingChange()
 {
  
  if ($("sitecredit_badge_enable-0").checked) {
   if ($("sitecredit_ranking-wrapper")) {
    $("sitecredit_ranking-wrapper").hide();
  } 
} else {
  if ($("sitecredit_ranking-wrapper")) {
    $("sitecredit_ranking-wrapper").show();
  } 
}

}
</script>
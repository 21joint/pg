<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
 ?>
 <style type='text/css'>
#listingtype_id-wrapper {
    display:none !important;
}     
 </style>
<h2><?php echo $this->translate("Parental Guidance Customizations") ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='sd_layout_left'>
    <?php if( count($this->navigation2) ): ?>
        <div class='tabs_left'>
            <?php
                // Render the menu
                //->setUlClass()
                echo $this->navigation()->menu()->setContainer($this->navigation2)->render()
            ?>
        </div>
    <?php endif; ?>
</div>
<div class="admin_table_form sd_layout_middle">
    <div class='seaocore_settings_form'>
        <div class='settings'>
          <?php echo $this->form->render($this) ?>
        </div>
    </div>
</div>


<script type="text/javascript">
var fetchLevelSettings = function(level_id){
    window.location.href= en4.core.baseUrl+'admin/sdparentalguide/permission/index/id/'+level_id;
}
</script>

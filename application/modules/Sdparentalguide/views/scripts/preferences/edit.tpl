<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<div class="headline">
  <h2>
    <?php if ($this->viewer->isSelf($this->user)):?>
      <?php echo $this->translate('Update My Preferences');?>
    <?php else:?>
      <?php echo $this->translate('Update %1$s\'s Preferences', $this->htmlLink($this->user->getHref(), $this->user->getTitle()));?>
    <?php endif;?>
  </h2>
  <div class="tabs">
    <?php
      // Render the menu
      echo $this->navigation()
        ->menu()
        ->setContainer($this->navigation)
        ->render();
    ?>
  </div>
</div>

<?php echo $this->form->render(); ?>
<script type="text/javascript">
function showAllCategories(element){
    var value = $(element).checked;
    $$(".sd_listing_category").set("checked",value);
}  
</script>
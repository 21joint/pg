<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>
<?= $this->form->render($this) ?>

<script type="text/javascript">
function showAllCategories(element){
    var value = $(element).checked;
    $$(".sd_listing_category").set("checked",value);
}  
</script>
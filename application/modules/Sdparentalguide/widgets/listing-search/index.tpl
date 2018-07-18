<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<?php
  if (APPLICATION_ENV == 'production')
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.min.js');
  else
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>

<?= $this->form->render(); ?>

<?php include_once APPLICATION_PATH.'/application/modules/Sdparentalguide/views/scripts/listingSearchJs.tpl'; ?>

<style type="text/css">
.sd_listing_search .sd_inline_field {
    display: inline-block;
    vertical-align: middle;
}    
.sd_listing_search .sd_inline_field label.optional {
    display: none;
}
.sd_listing_search .sd_inline_field label.heading_label {
    display: block;
}
#fieldset-grp2 br {
    display: none;
}
.sd_listing_search .form-options-wrapper li{
    display: inline-block;
    vertical-align: middle;
}
.sd_listing_search .form-options-wrapper label {
    float: left;
}
.sd_listing_search .sd_inline_field.include {
    margin-right: 15px;
}
.sd_listing_search .sd_inline_field.subcategory ,
.sd_listing_search .sd_inline_field.category {
    display: none;
}
.sd_listing_search .sd_loader{
    position: absolute;
    margin-left: -20px;
    margin-top: 3px;    
}
</style>
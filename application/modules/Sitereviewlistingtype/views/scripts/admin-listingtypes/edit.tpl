<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.tpl 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>

<h2>
  <?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewlistingtype')) { echo $this->translate('Reviews & Ratings - Multiple Listing Types Plugin'); } else { echo $this->translate('Reviews & Ratings Plugin'); }?>
</h2>

<?php if (count($this->navigation)): ?>
  <div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitereview/externals/images/back.png" class="icon" />
<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'manage'), $this->translate('Back to Manage Listing Types'), array('class'=> 'buttonlink', 'style'=> 'padding-left:0px;')) ?>
<br /><br />

<div class='seaocore_settings_form'>
  <div class='settings'> <?php echo $this->form->render($this) ?> </div>
</div>

<script type="text/javascript">

  window.addEvent('domready', function() {
    toogleLaguagePhase('none');
    showOverviewText('<?php echo $this->listingType->overview;?>');
    hideOwnerReviews('<?php echo $this->listingType->reviews;?>');
    showDescription('<?php echo $this->listingType->body_allow;?>');
  
    showUiOption('<?php echo $this->package; ?>');
    showclaim('<?php echo $this->claimlink ?>');
    showApplication('<?php echo $this->listingType->allow_apply ?>');
    
     var expiry=0;
      if($('expiry-2').checked){
        expiry=2;
      }
      showExpiryDuration(expiry);
  });

  function showOverviewText(option) {

    if(option == 1) {
      $('overview_creation-wrapper').style.display = "block";
    } else {
      $('overview_creation-wrapper').style.display = "none";
    }

  }

  function showclaim(option) 
  {
    if($('claim_show_menu-wrapper')) {
      if(option == 1) { 
        $('claim_show_menu-wrapper').style.display='block';	
      }
      else{
        $('claim_show_menu-wrapper').style.display='none';
      }		
    }
    if($('claim_email-wrapper')) {
      if(option == 1) { 
        $('claim_email-wrapper').style.display='block';	
      }
      else{
        $('claim_email-wrapper').style.display='none';
      }		
    }
  }
  
  function showApplication(option) 
  {
    if($('show_application-wrapper')) {
      if(option == 1) { 
        $('show_application-wrapper').style.display='block';	
      }
      else{
        $('show_application-wrapper').style.display='none';
      }		
    }
  }
  
  function showUiOption(option) 
  {
    if($('package_view-wrapper')) {
      if(option == 1) { 
        $('package_view-wrapper').style.display='block';	
      }
      else{
        $('package_view-wrapper').style.display='none';
      }		
    }
    if($('package_description-wrapper')) {
      if(option == 1) { 
        $('package_description-wrapper').style.display='block';	
      }
      else{
        $('package_description-wrapper').style.display='none';
      }		
    }
    if($('expiry-wrapper')) {
      if(option == 1) { 
        $('expiry-wrapper').style.display='none';	
      }
      else{
        $('expiry-wrapper').style.display='block';
      }		
    }
  }
  
  function hideOwnerReviews(option) {
    if($('allow_owner_review-wrapper')) {
      if(option == 2 || option == 3) {
        $('allow_owner_review-wrapper').style.display='block';
        $('allow_review-wrapper').style.display='block';
      }else{
        $('allow_owner_review-wrapper').style.display='none';
        $('allow_review-wrapper').style.display='none';
      }
    }
  }

  function showExpiryDuration(option) {
    if($('admin_expiry_duration-wrapper')) {
      if(option == 2) {
        $('admin_expiry_duration-wrapper').style.display='block';
      }else{
        $('admin_expiry_duration-wrapper').style.display='none';
      }
    }
  }
  
  function showUpdateWarning(){
    if( $('translation_file').checked){
      var r=confirm("Are you sure that you want to replace language files for this listing type in all languages folders?");
      if (r==false)
      {
        $('translation_file').checked=false;
      }
    }
    
    if($('translation_file').checked)
      toogleLaguagePhase('block');
    else
      toogleLaguagePhase('none');
  }
  
  function toogleLaguagePhase(display){
    <?php  $elements = Engine_Api::_()->getApi('language', 'sitereview')->getDataWithoutKeyPhase();
    foreach($elements as $key=>$element):?>
        $('<?php echo $key ?>-wrapper').style.display=display;
    <?php endforeach; ?>
    if($('sitereview_language_phrases-wrapper'))
      $('sitereview_language_phrases-wrapper').style.display = display;
  }

  function showDescription(option) {
    if($('body_required-wrapper')) {
      if(option == 1) {
        $('body_required-wrapper').style.display='block';
      } else{
        $('body_required-wrapper').style.display='none';
      }
    }
  }
</script>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
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
<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitereviewlistingtype', 'controller' => 'listingtypes', 'action' => 'manage'), $this->translate('Back to Manage Listing Types'), array('class' => 'buttonlink', 'style' => 'padding-left:0px;')) ?>
<br /><br />

<?php  echo "<div class='tip'><span>" . $this->translate('Note: After creating a new Listing Type, you need to flush the cache to reflect language phrase changes for this Listing Type by <a href="admin/core/settings/performance">clicking here</a>.') . "</span></div>";?>

<?php if(!$this->hasLanguageDirectoryPermissions):?>
<div class="tip">
  <span>
    <?php echo $this->translate("Language file for this listing type could not be created because you do not have write permission chmod -R 777 recursively to the directory '/application/languages/'. Please login in over your Cpanel or FTP and give the recursively write permission to this directory and try again for adding a new listing type.") ?>
  </span>
</div>
<?php else:?>
<div class='seaocore_settings_form'>
  <div class='settings'> 
    <?php $this->form->setDescription($this->translate("Below, you will be able to configure and customize your listing type based on various parameters like Allowing Reviews on Listings, Writing Overviews for Listings, Price Information, and a lot more.<br/><br/><b>Note:</b> If you do not create categories for this listing type, then users would not be able to post listings in this listing type. Thus, after configuring this listing type, you need to add / manage categories for this listing type from the 'Categories' section."));
  $this->form->getDecorator('Description')->setOption('escape', false); ?>  
    <?php echo $this->form->render($this) ?> 
  </div>
</div>

<script type="text/javascript">
  
  window.addEvent('domready', function() {
    var expiry=0;
    if($('expiry-2').checked){
      expiry=2;
    }
    showExpiryDuration(expiry);
    showUiOption(0);
    showclaim(0);
    showApplication(0);
  });
  
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
  
  function showOverviewText(option) {

    if(option == 1) {
      $('overview_creation-wrapper').style.display = "block";
    } else {
      $('overview_creation-wrapper').style.display = "none";
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

  function hideOwnerReviews(option) {
    if($('allow_owner_review-wrapper')) {
      if(option == 2 || option == 3) {
        $('allow_owner_review-wrapper').style.display='block';
        $('allow_review-wrapper').style.display='block';
      } else{
        $('allow_owner_review-wrapper').style.display='none';
        $('allow_review-wrapper').style.display='none';
      }
    }
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
  
  
 function setListingTypeValues(listing_type) {

   if(listing_type == 'default_type') {
     $('pinboard_layout-wrapper').style.display = 'block';
   }
   else {
     $('pinboard_layout-wrapper').style.display = 'none';
   }
   
   sitereviewpaidlistingEnabled = '<?php echo Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting'); ?>';
   
   switch (listing_type) {
     case 'default_type':
       $('title_singular').value = '';
       $('title_plural').value = '';
       $('slug_singular').value = '';
       $('slug_plural').value = '';        
       $('compare-1').checked = 1; 
       $('reviews-3').checked = 1; 
       hideOwnerReviews(3);
       $('allow_owner_review-0').checked = 1;        
       $('body_allow-0').checked = 1;
       showDescription(0);
       $('body_required-0').checked = 1;         
       $('location-1').checked = 1;      
       $('navigation_tabs').value = 6;
       $('profile_tab-1').checked = 1;       
       $('expiry-0').checked = 1;
       showExpiryDuration(0);
       //$('subscription-0').checked = 1;    
       $('where_to_buy-2').checked = 1;       
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1; 
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
       $('photo_type-listing').checked = 1;        
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Places';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';       
       break; 
     case 'tourism':
       $('title_singular').value = 'Tourism';
       $('title_plural').value = 'Tourism';
       $('slug_singular').value = 'tourism';
       $('slug_plural').value = 'tourisms';        
       $('compare-1').checked = 1; 
       $('reviews-3').checked = 1; 
       hideOwnerReviews(3);
       $('allow_owner_review-0').checked = 1;        
       $('body_allow-0').checked = 1;
       showDescription(0);
       $('body_required-0').checked = 1;         
       $('location-1').checked = 1;      
       $('navigation_tabs').value = 6;
       $('profile_tab-1').checked = 1;       
       $('expiry-1').checked = 1;
       showExpiryDuration(1);
       //$('subscription-0').checked = 1;     
       $('where_to_buy-2').checked = 1;       
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-1').checked = 1; 
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
       $('photo_type-listing').checked = 1;        
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Book';
      $('language_phrases-text_tags').value = 'Places';
      $('language_phrases-text_listings').value = 'listings';
      $('language_phrases-text_listing').value = 'listing';
      $('language_phrases-text_posted').value = 'posted';
      $('language_phrases-text_post').value = 'post';
      $('language_phrases-text_stores').value = 'Websites';
      $('language_phrases-text_store').value = 'Website';       
       break;
     case 'fashion':
       $('title_singular').value = 'Fashion';
       $('title_plural').value = 'Fashion';
       $('slug_singular').value = 'fashion';
       $('slug_plural').value = 'fashiontrends';                  
       $('compare-1').checked = 1;       
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-1').checked = 1;         
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-0').checked = 1;         
       $('location-0').checked = 1;
       $('navigation_tabs').value = 6;
       $('profile_tab-0').value = 1;
       $('expiry-1').checked = 1;
       showExpiryDuration(1);
       //$('subscription-0').checked = 1;        
       $('expiry-1').checked = 1; 
       $('where_to_buy-2').checked = 1;    
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1;   
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1; 
       $('contact_detail-phone').checked = 0;
       $('contact_detail-website').checked = 0;
       $('contact_detail-email').checked = 0;     
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Items';
      $('language_phrases-text_listing').value = 'Item';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';       
       break;      
     case 'electronic':
       $('title_singular').value = 'Electronic';
       $('title_plural').value = 'Electronics';
       $('slug_singular').value = 'electronic';
       $('slug_plural').value = 'electronics';              
       $('compare-1').checked = 1;       
       $('reviews-3').checked = 1; 
       hideOwnerReviews(3);
       $('allow_owner_review-0').checked = 1;                
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-0').checked = 1;             
       $('location-0').checked = 1;
       $('navigation_tabs').value = 6;
       $('profile_tab-1').checked = 1;         
       $('expiry-0').checked = 1;
       showExpiryDuration(0);  
       //$('subscription-0').checked = 1;
       $('expiry-1').checked = 1;      
       $('where_to_buy-2').checked = 1;  
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1;    
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;       
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;     
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';       
       break;        
     case 'sport':
       $('title_singular').value = 'Sport';
       $('title_plural').value = 'Sports';
       $('slug_singular').value = 'sport';
       $('slug_plural').value = 'sports';                
       $('compare-1').checked = 1;       
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-1').checked = 1;           
       $('body_allow-0').checked = 1;
       showDescription(0);
       $('body_required-1').checked = 1;                
       $('location-0').checked = 1;
       $('navigation_tabs').value = 6;  
       $('profile_tab-1').checked = 1;    
       $('expiry-0').checked = 1;
       showExpiryDuration(0);
       //$('subscription-0').checked = 1;  
       $('expiry-1').checked = 1;       
       $('where_to_buy-2').checked = 1; 
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-1').checked = 1; 
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;       
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;      
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';         
       break;        
     case 'blog':
       $('title_singular').value = 'Blog';
       $('title_plural').value = 'Blogs';
       $('slug_singular').value = 'blog';
       $('slug_plural').value = 'blogs';             
       $('compare-0').checked = 1;       
       $('reviews-0').checked = 1;  
       hideOwnerReviews(0);
       $('allow_owner_review-0').checked = 1;         
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-1').checked = 1;         
       $('location-1').checked = 1;
       $('navigation_tabs').value = 6;
       $('profile_tab-1').checked = 1;         
       $('expiry-0').checked = 1;
       showExpiryDuration(0); 
       //$('subscription-1').checked = 1;       
       $('expiry-0').checked = 1;
       $('where_to_buy-1').checked = 1;       
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-1').checked = 1; 
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;  
       $('contact_detail-phone').checked = 0;
       $('contact_detail-website').checked = 0;
       $('contact_detail-email').checked = 0;     
      $('language_phrases-text_overview').value = 'Body';
      $('language_phrases-text_Where_to_Buy').value = 'References';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Entries';
      $('language_phrases-text_listing').value = 'Entry';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Sites';
      $('language_phrases-text_store').value = 'Site';       
       break;
     case 'property':
       $('title_singular').value = 'Property';
       $('title_plural').value = 'Properties';
       $('slug_singular').value = 'property';
       $('slug_plural').value = 'properties';           
       $('compare-1').checked = 1;       
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-0').checked = 1;          
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-0').checked = 1;             
       $('location-1').checked = 1;
       $('navigation_tabs').value = 6;
       $('profile_tab-1').checked = 1;                
       $('expiry-1').checked = 1;
       showExpiryDuration(1);    
       //$('subscription-0').checked = 1;        
       $('expiry-1').checked = 1;  
       $('where_to_buy-1').checked = 1;   
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1; 
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;       
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;    
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';        
       break;
     case 'food':
       $('title_singular').value = 'Food';
       $('title_plural').value = 'Food';
       $('slug_singular').value = 'fooditem';
       $('slug_plural').value = 'fooditems';           
       $('compare-1').checked = 1;       
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-0').checked = 1;        
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-0').checked = 1;       
       $('location-0').checked = 1;
       $('navigation_tabs').value = 7;
       $('profile_tab-1').checked = 1;  
       $('expiry-0').checked = 1;
       showExpiryDuration(0);        
       //$('subscription-1').checked = 1;
       $('expiry-1').checked = 1;         
       $('where_to_buy-2').checked = 1; 
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-1').checked = 1;
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;  
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;    
      $('language_phrases-text_overview').value = 'Preparation';
      $('language_phrases-text_Where_to_Buy').value = 'Buy Ingredients';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'items';
      $('language_phrases-text_listing').value = 'item';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';       
       break;
     case 'classified':
       $('title_singular').value = 'Classified';
       $('title_plural').value = 'Classifieds';
       $('slug_singular').value = 'classified';
       $('slug_plural').value = 'classifieds';         
       $('compare-1').checked = 1;       
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-0').checked = 1;           
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-0').checked = 1;           
       $('location-1').checked = 1;
       $('navigation_tabs').value = 6;
       $('profile_tab-1').checked = 1;         
       $('expiry-1').checked = 1;
       showExpiryDuration(1);  
       //$('subscription-0').checked = 1; 
       $('expiry-1').checked = 1;  
       $('where_to_buy-2').checked = 1;        
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-1').checked = 1; 
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;    
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;        
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Sites';
      $('language_phrases-text_store').value = 'Site';       
       break;
     case 'entertainment':
       $('title_singular').value = 'Entertainment';
       $('title_plural').value = 'Entertainments';
       $('slug_singular').value = 'entertainment';
       $('slug_plural').value = 'entertainments';        
       $('compare-1').checked = 1;       
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-0').checked = 1;    
       $('profile_tab-1').checked = 1;                
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-1').checked = 1;                 
       $('location-0').checked = 1;
       $('navigation_tabs').value = 6;  
       $('expiry-0').checked = 1;
       showExpiryDuration(0);   
       $('admin_expiry_duration-text').value = 1;
       $('admin_expiry_duration-select').value = 'week';
       //$('subscription-0').checked = 1;   
       $('expiry-1').checked = 1;       
       $('where_to_buy-2').checked = 1; 
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1;       
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
        $('photo_type-listing').checked = 1;     
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;      
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'Where to Buy';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Stores';
      $('language_phrases-text_store').value = 'Store';       
       break;  
     case 'article':
       $('title_singular').value = 'Article';
       $('title_plural').value = 'Articles';
       $('slug_singular').value = 'article';
       $('slug_plural').value = 'articles';        
       $('compare-0').checked = 1;  
       $('price-0').checked = 1;
       $('edit_creationdate-1').checked = 1;
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('allow_owner_review-0').checked = 1;
       $('allow_review-0').checked = 1;
       $('profile_tab-0').checked = 1;
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-1').checked = 1;
       $('location-0').checked = 1;
       $('navigation_tabs').value = 6;
       $('expiry-0').checked = 1;
       showExpiryDuration(0);   
       $('admin_expiry_duration-text').value = 1;
       $('admin_expiry_duration-select').value = 'week';
       //$('subscription-1').checked = 1;         
       $('where_to_buy-1').checked = 1; 
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1;
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
       $('photo_type-listing').checked = 1;     
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;      
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'References';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Sites';
      $('language_phrases-text_store').value = 'Site';       
       break;    
     case 'job':
       $('title_singular').value = 'Job';
       $('title_plural').value = 'Jobs';
       $('slug_singular').value = 'job';
       $('slug_plural').value = 'jobs';
       $('allow_apply-1').checked = 1;
       $('compare-0').checked = 1;  
       $('price-0').checked = 1;
       $('edit_creationdate-1').checked = 1;
       $('reviews-2').checked = 1; 
       hideOwnerReviews(2);
       $('show_editor-1').checked = 1;
       $('allow_owner_review-0').checked = 1;
       $('allow_review-0').checked = 1;
       $('profile_tab-1').checked = 1;
       $('body_allow-1').checked = 1;
       showDescription(1);
       $('body_required-0').checked = 1;
       $('location-1').checked = 1;
       $('navigation_tabs').value = 8;
       $('expiry-1').checked = 1;
       showExpiryDuration(1);   
       $('admin_expiry_duration-text').value = 1;
       $('admin_expiry_duration-select').value = 'week';
       //$('subscription-0').checked = 1;   
       $('where_to_buy-1').checked = 1; 
       $('overview-1').checked = 1;
       showOverviewText(1);
       $('overview_creation-0').checked = 1;
       $('category_edit-1').checked = 1; 
       $('wishlist-1').checked = 1;
       $('featured-1').checked = 1;
       showfeatured(1);
       $('featured_color').value = '#30a7ff';
       $('sponsored-1').checked = 1;
       showsponsored(1);
       $('sponsored_color').value = '#FC0505';
       $('metakeyword-1').checked = 1;
       $('photo_type-listing').checked = 1;     
       $('contact_detail-phone').checked = 1;
       $('contact_detail-website').checked = 1;
       $('contact_detail-email').checked = 1;      
      $('language_phrases-text_overview').value = 'Overview';
      $('language_phrases-text_Where_to_Buy').value = 'References';
      $('language_phrases-text_tags').value = 'Tags';
      $('language_phrases-text_listings').value = 'Listings';
      $('language_phrases-text_listing').value = 'Listing';
      $('language_phrases-text_posted').value = 'Posted';
      $('language_phrases-text_post').value = 'Post';
      $('language_phrases-text_stores').value = 'Sites';
      $('language_phrases-text_store').value = 'Site';  
      if(sitereviewpaidlistingEnabled == '1') {
        $('package-1').checked = 1;
        showUiOption(1);
        $('package_view-1').checked = 1;
      }    
       break;           
   }
 }
  
</script>
<?php endif; ?>
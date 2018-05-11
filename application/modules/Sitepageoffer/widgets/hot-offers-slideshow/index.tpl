<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
      $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/_class.noobSlide.packed.js');
?>

<?php
// Starting work for "Slide Show".
$image_text_var = '';
$title_link_var = '';

$title_link_var = "new Element('h4').set('html',";
if ($this->show_link == 'true')
  $title_link_var .= "'<a href=" . '"' . "'+currentItem.link+'" . '"' . ">link</a>'";
if ($this->title == 'true')
  $title_link_var .= "+currentItem.title";
$title_link_var .= ").inject(im_info);";

$image_count = 1;

$viewer_id = $this->viewer->getIdentity();
if(!empty($viewer_id)) {
	$oldTz = date_default_timezone_get();
	date_default_timezone_set($this->viewer->timezone);
}

foreach ($this->show_slideshow_object as $type => $offer) {
  $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $offer->page_id);
  $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
  $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $offer->page_id, $layout);
  if($offer->photo_id == 0) {
		$offerPhoto = $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $offer->owner_id, 'offer_id' =>  $offer->offer_id,'tab' => $tab_id,'slug' => $offer->getOfferSlug($offer->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_normal.png' alt='' />",array('title' => $offer->getTitle()));
  }
  else {
    $offerPhoto = $this->htmlLink($offer->getHref(),$this->itemPhoto($offer, 'thumb.normal', $offer->getTitle()));
  }
														
  $today = date("Y-m-d H:i:s");
	$claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$offer->offer_id,$offer->page_id);
	if($offer->claim_count == -1 && ($offer->end_time > $today || $offer->end_settings == 0)) {
		$show_offer_claim = 1;
	}
	elseif($offer->claim_count > 0 && ($offer->end_time > $today || $offer->end_settings == 0)) {
		$show_offer_claim = 1;
	}
	else {
		$show_offer_claim = 0;
	}
					

  $content_info = null;
  
  if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)) {
  $content_info .= '<div class="sitepage_offer_date seaocore_txt_light" style="margin-top:2px;">';
  if(!empty($show_offer_claim) && empty($claim_value)) {
    $request = Zend_Controller_Front::getInstance()->getRequest();
		$urlO = $request->getRequestUri();
		$request_url = explode('/',$urlO);
		$param = 1;
		if(empty($request_url['2'])) {
			$param = 0;
		}
		$return_url = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://":"http://";
		$currentUrl = urlencode($urlO);
        
    if(!empty($this->viewer_id)) {
      $content_info .= '<span><img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />';
			$content_info .= $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $offer->offer_id),Zend_Registry::get('Zend_Translate')->_('Get Offer'),array('onclick' => 'owner(this);return false')). '</span>';
    }
    else {
      $offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $offer->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
			$title = $this->translate('Get Offer');
			$content_info .= '<span><img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>".'</span>';
    }
	}
		elseif(!empty($claim_value) && !empty($show_offer_claim) || ($offer->claim_count == 0 && $offer->end_time > $today && !empty($claim_value))) {
			$content_info .= '<span><img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />';
			$content_info .= $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $offer->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('onclick' => 'owner(this);return false')) . '</span>';
		}
		else {
			$content_info .= "<span><b>";
      $content_info .= $this->translate('Expired');
      $content_info .= "</b></span>";
		}
	  $content_info .= '<span><b>&middot;</b></span><span>' .$offer->claimed.' '.$this->translate('claimed') . '</span>';
		if($offer->claim_count != -1) {
		$content_info .= "<span><b>&middot;</b></span>";
		$content_info .= '<span>' . $offer->claim_count.' '.$this->translate('claims left') . '</span>';
	}

  $content_info .= '</div>';
}
  $description = strip_tags($offer->description);

  $content_link = $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $this->translate('View Offer &raquo;'), array('class' => 'featured_slideshow_view_link'));

  $image_text_var .= "<div class='featured_slidebox'>";
  $image_text_var .= "<div class='featured_slidshow_img'>" . $offerPhoto . "</div>";


  if (!empty($content_info)) {
    $image_text_var .= "<div class='featured_slidshow_content'>";
  }
  if (!empty($offer->title)) {

    $title = $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($offer->getTitle(), 45), 10),array('title' => $offer->getTitle()));

    $image_text_var .='<h5>' . $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $offer->title) . '</h5>';
		$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
		$tmpBody = strip_tags($sitepage_object->title);
		$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
    $image_text_var .= "<div class='featured_slidshow_info'>";
    $image_text_var .= $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($offer->page_id, $offer->owner_id, $offer->getSlug()),  $page_title,array('title' => $sitepage_object->title)); 
    $image_text_var .= "</div>";

  }

  if (!empty($content_link)) {
    $image_text_var .= "<h3 style='display:none'><span>" . $image_count++ . '_caption_title:' . $title . '_caption_link:' . $content_link . '</span>' . "</h3>";
  }

  if (!empty($content_info)) {
    $image_text_var .= "<span class='featured_slidshow_info'>" . $content_info . "</span>";
  }

  if (!empty($description)) {
    $truncate_description = ( Engine_String::strlen($description) > 253 ? Engine_String::substr($description, 0, 250) . '...' : $description );
    $image_text_var .= "<p>" . $truncate_description . " " . $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $this->translate('More &raquo;')) . "</p>";
  }

  $image_text_var .= "</div></div>";
}
if (!empty($this->num_of_slideshow)) {
?>
  <script type="text/javascript">
    window.addEvent('domready',function(){
      
      if (document.getElementsByClassName == undefined) {
        document.getElementsByClassName = function(className)
        {
          var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
          var allElements = document.getElementsByTagName("*");
          var results = [];

          var element;
          for (var i = 0; (element = allElements[i]) != null; i++) {
            var elementClass = element.className;
            if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
              results.push(element);
          }

          return results;
        }
      }

      var width=$('global_content').getElement(".featured_slideshow_wrapper").clientWidth;
      $('global_content').getElement(".featured_slideshow_mask").style.width= (width-10)+"px";
      var divElements=document.getElementsByClassName('featured_slidebox');   
     for(var i=0;i < divElements.length;i++)
      divElements[i].style.width= (width-10)+"px";
  
      var handles8_more = $$('#handles8_more span');
      var num_of_slidehsow = "<?php echo $this->num_of_slideshow; ?>";
      var nS8 = new noobSlide({
        box: $('sitepageoffer_featured_offer_im_te_advanced_box'),
        items: $$('#sitepageoffer_featured_offer_im_te_advanced_box h3'),
        size: (width-10),
        handles: $$('#handles8 span'),
        addButtons: {previous: $('sitepageoffer_featured_offer_prev8'), stop: $('sitepageoffer_featured_offer_stop8'), play: $('sitepageoffer_featured_offer_play8'), next: $('sitepageoffer_featured_offer_next8') },
        interval: 5000,
        fxOptions: {
          duration: 500,
          transition: '',
          wait: false
        },
        autoPlay: true,
        mode: 'horizontal',
        onWalk: function(currentItem,currentHandle){

          //		// Finding the current number of index.
          var current_index = this.items[this.currentIndex].innerHTML;
          var current_start_title_index = current_index.indexOf(">");
          var current_last_title_index = current_index.indexOf("</span>");
          // This variable containe "Index number" and "Title" and we are finding index.
          var current_title = current_index.slice(current_start_title_index + 1, current_last_title_index);
          // Find out the current index id.
          var current_index = current_title.indexOf("_");
          // "current_index" is the current index.
          current_index = current_title.substr(0, current_index);

          // Find out the caption title.
          var current_caption_title = current_title.indexOf("_caption_title:") + 15;
          var current_caption_link = current_title.indexOf("_caption_link:");
          // "current_caption_title" is the caption title.
          current_caption_title = current_title.slice(current_caption_title, current_caption_link);
          var caption_title = current_caption_title;
          // "current_caption_link" is the caption title.
          current_caption_link = current_title.slice(current_caption_link + 14);


          var caption_title_lenght = current_caption_title.length;
          if( caption_title_lenght > 30 )
          {
            current_caption_title = current_caption_title.substr(0, 30) + '..';
          }

          if( current_caption_title != null && current_caption_link!= null )
          {
            $('sitepageoffer_featured_offer_caption').innerHTML =   current_caption_link;
          }
          else {
            $('sitepageoffer_featured_offer_caption').innerHTML =  '';
          }


          $('sitepageoffer_featured_offer_current_numbering').innerHTML =  current_index + '/' + num_of_slidehsow ;
        }
      });

      //more handle buttons
      nS8.addHandleButtons(handles8_more);
      //walk to item 3 witouth fx
      nS8.walk(0,false,true);
    });
  </script>
<?php } ?>

<script type="text/javascript" >
	function owner(thisobj) {
		var Obj_Url = thisobj.href ;
		Smoothbox.open(Obj_Url);
	}
</script>

<div class="featured_slideshow_wrapper">
  <div class="featured_slideshow_mask">
    <div id="sitepageoffer_featured_offer_im_te_advanced_box" class="featured_slideshow_advanced_box">
      <?php echo $image_text_var ?>
    </div>
  </div>

  <div class="featured_slideshow_option_bar">
    <div>
      <p class="buttons">
        <span id="sitepageoffer_featured_offer_prev8" class="featured_slideshow_controllers-prev featured_slideshow_controllers prev" title=<?php echo $this->translate("Previous") ?> ></span>
        <span id="sitepageoffer_featured_offer_stop8" class="featured_slideshow_controllers-stop featured_slideshow_controllers" title=<?php echo $this->translate("Stop") ?> ></span>
        <span id="sitepageoffer_featured_offer_play8" class="featured_slideshow_controllers-play featured_slideshow_controllers" title=<?php echo $this->translate("Play") ?> ></span>
        <span id="sitepageoffer_featured_offer_next8" class="featured_slideshow_controllers-next featured_slideshow_controllers" title=<?php echo $this->translate("Next") ?> ></span>
      </p>
    </div>
    <span id="sitepageoffer_featured_offer_caption"></span>
    <span id="sitepageoffer_featured_offer_current_numbering" class="featured_slideshow_pagination"></span>
  </div>
</div>  

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

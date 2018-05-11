<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: preview.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()
	  ->appendStylesheet($this->layout()->staticBaseUrl
	    . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
?>

<div class="sitepage_offer_priview_popup">      
	<div class="sitepage_offer_priview_popup_head">
		<b><?php echo $this->translate('Preview') ?></b>
	</div>
	<div class="sitepage_offer_block">
		<div class="sitepage_offer_photo">
			<img src='<?php echo $this->image_path;?>' alt='' />
		</div>
		<div class="sitepage_offer_details">
			<div id='title' class="sitepage_offer_title"></div>
      <div id='date' class="sitepage_offer_stats"></div>
      <div id='coupon_code' class="sitepage_offer_stats"></div>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
        <div class="sitepage_offer_date seaocore_txt_light">
          <span>
            <a href='javascript:void(0);'>
              <img src="<?php echo $this->layout()->staticBaseUrl?>application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />	
              <?php echo $this->translate('Get Offer');?>
            </a>
          </span>	
          <span><b>&middot;</b></span><span id="claim"></span>
        </div>
      <?php endif; ?>
		</div>		
	</div>
	<button onclick="parent.window.post()" type="submit_button"><?php echo $this->translate("Post");?></button>&nbsp;&nbsp;<?php echo $this->translate("or");?>&nbsp;
	<a onclick="javascript:parent.Smoothbox.close();" href="javascript:void(0);" type="button" id="cancel" name="cancel"><?php echo $this->translate('Edit'); ?></a>
</div>	

<script type="text/javascript">
  
  var mydate = new Date(parent.window.$('calendar_output_span_end_time-date').innerHTML);

  var day = mydate.getDate();

	var month = ["January", "February", "March", "April", "May", "June",
	"July", "August", "September", "October", "November", "December"][mydate.getMonth()];
	var str = day + ' ' + month + ' ' + mydate.getFullYear();
  var url = parent.window.$('url').value;
  var myTruncatedUrl = url.substring(0,30);
  var end_date = parent.window.$('end_settings-0').checked;
  var final_url = '<a href = "' + url + '" target="_blank" title= "' + url + '" >' +  myTruncatedUrl + '</a>';

  if(parent.window.$('url').value) {
		if (end_date) {
			$('date').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('End date: Never Expires'));?>' + ' ' + '|' + ' ' + 'URL:' + ' ' + final_url;
		}
		else{
			$('date').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('End date:'));?>' + ' ' + str + ' ' + '|' + ' ' + 'URL:' + ' ' +final_url;
		}
  }
  else {
		if(end_date) {
			$('date').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('End date: Never Expires'));?>';
		}
		else {
			$('date').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('End date:'));?>' + ' ' + str;
		}
  }
  
  $('title').innerHTML = parent.window.$('title').value;
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
    if(parent.window.$('claim_count').value == 0) {
      $('claim').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Unlimited claims'));?>';
    }
    else {
      if(parent.window.$('claim_count').value > 1) {
        $('claim').innerHTML = parent.window.$('claim_count').value + ' ' + '<?php echo $this->string()->escapeJavascript($this->translate('Claims left'));?>';
      }
      else {   
        $('claim').innerHTML = parent.window.$('claim_count').value + ' ' + '<?php echo $this->string()->escapeJavascript($this->translate('Claim left'));?>';
      }
    }
  <?php endif; ?>

  var coupon_code = parent.window.$('coupon_code').value;
  if(coupon_code != '') {
    $('coupon_code').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Coupon Code:'));?>'+ ' ' + parent.window.$('coupon_code').value;
  }

</script>
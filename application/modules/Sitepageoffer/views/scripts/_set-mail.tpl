<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _set-mail.tpl 6590 2012-06-20 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
	$offer_photo = 'http://' . $_SERVER['HTTP_HOST']. $this->layout()->staticBaseUrl . $this->offer_photo_path;
	$page_photo = 'http://' . $_SERVER['HTTP_HOST']. $this->layout()->staticBaseUrl . $this->page_photo_path;
?>

<?php if(!$this->enable_mailtemplate):?>
	<table border="0" cellpadding="10" cellspacing="0"><tbody><tr><td bgcolor="#f7f7f7"><table border="0" cellpadding="0" cellspacing="0" align="center" style="width:600px"><tbody><tr><td align="left" style="background-color:#79b4d4;padding:10px;font-family:tahoma,verdana,arial,sans-serif;vertical-align:middle;font-size:17px;font-weight:bold;color:#fff;"><?php echo $this->site_title;?></td></tr><tr><td colspan="0" style="font-family:tahoma,verdana,arial,sans-serif;padding:10px;border:1px solid #cccccc;" valign="top">
<?php endif;?>

<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin-top:10px" width="100%"><tbody><tr><td valign="top" style="padding-right:10px;font-size:0px"><img src= '<?php echo $page_photo;?>' /></td><td valign="top">	<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;" width="100%"><tbody><tr><td style="font-size:13px;font-family:tahoma,verdana,arial,sans-serif;font-weight:bold;color:#3b5998;margin-bottom:10px;"><?php echo $this->page_title;?></td></tr><tr><td style="font-size:11px;font-family:tahoma,verdana,arial,sans-serif;padding:10px 0">To redeem your offer, take this email to <?php echo $this->page_title;?> and show it to the staff from your phone or in print.</td></tr><tr><td style="font-size:11px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><div style="border:1px solid #CCCCCC;padding:10px;overflow:hidden;"><div style="float:left;margin-right:10px;"><img src= '<?php echo $offer_photo;?>' /></div><div style="overflow:hidden;font-family:tahoma,verdana,
arial,sans-serif;"><div style="font-weight:bold;font-size:13px;margin-bottom:5px;"><?php echo $this->offer_title;?></div><div style="margin-bottom:5px;"><?php if($this->offer_time_setting):?><?php echo $this->translate('Expires: ').$this->offer_time;?><?php else:?><?php echo $this->translate('Expires: Never Expires');?><?php endif;?><?php if(!empty($this->offer_url)):?><?php echo ' | '.'URL: '.$this->offer_url?><?php endif;?></div><div style="margin-bottom:5px;"><?php if($this->coupon_code):?><?php echo $this->translate('Coupon Code: ').$this->coupon_code;?><?php endif;?></div><div style="margin-bottom:10px;"><?php echo $this->translate('Claimed by ').$this->claim_owner_name;?></div></div></div></td></tr><tr><td style="font-size:11px;font-family:tahoma,verdana,arial,sans-serif;"></td></tr><tr><td style="font-size:11px;font-family:tahoma,verdana,arial,sans-serif;padding:10px 0 20px 0;color:gray;"><div><?php echo $this->offer_description;?></div></td></tr></tbody></table></td></tr><tr><td colspan="2" style="
padding:5px 10px;width:552px;"><table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border-top:1px solid #ccc;border-bottom:1px solid #ccc;background-color:#eeeeee"><tbody><tr><td style="font-size:11px;font-family:tahoma,verdana,arial,sans-serif;padding:6px 0 6px 60px;width:90px"><table cellspacing="0" cellpadding="0" style="border-collapse:collapse"><tbody><tr><td style="border:1px solid #5c98b8;background-color:#79B4D4"><table cellspacing="0" cellpadding="0" style="border-collapse:collapse"><tbody><tr><td style="font-size:11px;font-family:tahoma,verdana,arial,sans-serif;padding:2px 6px 4px;border-top:1px solid #8a9cc2"><?php echo $this->share_offer;?></td></tr></tbody></table></td></tr></tbody></table></td><td style="font-size:11px;font-family:tahoma,verdana,arial,sans-serif;padding:6px 0">&nbsp;&nbsp;<?php echo $this->like_page;?><?php echo $this->pagehome_offer;?></td></tr></tbody></table></td></tr></tbody></table>

<?php if(!$this->enable_mailtemplate):?>
	</td></tr></tbody></table></td></tr></tbody></table>          
<?php endif;?>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: invitepreview.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepageinvite/externals/styles/style_sitepageinvite.css');
?>
<div class="global_form_popup">
  <h3><?php echo $this->translate("Invite to your Page") ?></h3>


  <div class="sitepageinvite_popup_heading"><?php echo $this->translate("Preview your invitation") ?></div>


  <?php if (!empty($this->is_suggenabled)) : ?>

    <?php echo $this->translate("To the invitees who are already on %s, a suggestion for your Page will be sent.", $this->site_title) ?>
    <?php echo '<div class="sitepageinvite_popup_suggestion"><span class="sitepage_notification"><a href="javascript:void(0);" >' . $this->viewer_name . '</a>' . $this->translate(" has suggested you to visit and explore the Page: ") . '<a href="javascript:void(0);" >' . $this->sitepage->title . '</a>.</span></div>'; ?>
    <div class="sitepageinvite_popup_suggestion_preview">
      <ul class="requests">
        <li style="margin-bottom:0px;">
          <?php echo $this->itemPhoto($this->sitepage, 'thumb.icon', ''); ?> 
          <div>
            <div>
              <?php echo '<a href="javascript:void(0);">' . $this->viewer_name . '</a>' . $this->translate(" has sent you a page suggestion:") ?> <a href="javascript:void(0);"><?php echo $this->sitepage->title; ?> </a>
            </div>
            <div>
              <button type="submit">
                <?php echo $this->translate('View this Page'); ?>      
              </button>
              <?php echo $this->translate('or'); ?> <a href="javascript:void(0);"> <?php echo $this->translate('ignore request'); ?>  </a>
            </div>
          </div>	
        </li>
      </ul>
    </div>	
  <?php else : ?>

    <?php echo $this->translate("To the invitees who are already on %s, a suggestion notification for your Page will be sent.", $this->site_title) ?>
    <?php echo '<div class="sitepageinvite_popup_suggestion"><span class="sitepage_notification"><a href="javascript:void(0);" >' . $this->viewer_name . '</a>' . $this->translate(" has suggested you to visit and explore the Page: ") . '<a href="javascript:void(0);" >' . $this->sitepage->title . '</a>.</span></div>'; ?>

  <?php endif; ?>
  <br />
  <?php echo $this->translate("Additionally, an email will also be sent to the invitees who are not on %s.", $this->site_title) ?>
  <div class="sitepageinvite_popup_email_preview">
    <?php echo $this->bodyHtmlTemplate; ?>
  </div>
  <div class="buttons" >
    <button name="invitepreview"  id="invitepreview" onclick="parent.inviteFriends('<?php echo $this->servicetype;?>');" ><?php echo $this->translate("Send"); ?></button> 
    <?php echo $this->translate(" or"); ?>  <a onclick="parent.Smoothbox.close();" href="javascript:void(0);" id="cancel" name="cancel"><?php echo $this->translate("cancel"); ?></a>
  </div>
</div>	

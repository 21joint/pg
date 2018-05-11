<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: takeaction.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="sitepagebadge_request_detail">
	<div class="settings">
	  <form class="global_form" method="POST">
	    <div>
	      <?php
	      $url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($this->sitepage_id)), 'sitepage_entry_view');
	      $sitepage_title = "<a href='$url' target='_blank'>$this->sitepage_title</a>";
	      ?>
	
	      <?php if ($this->badgerequest->status == 1): ?>
	        <h3><?php echo $this->translate("Details"); ?></h3>
	        <p><?php echo $this->translate("Below are the details of the badge request that was approved.") ?></p><br />
	      <?php elseif ($this->badgerequest->status == 2): ?>
	        <h3><?php echo $this->translate("Details"); ?></h3>
	        <p><?php echo $this->translate("Below are the details of the badge request that was declined.") ?></p><br />
	      <?php else: ?>	
	        <h3><?php echo $this->translate("Take an Action"); ?></h3>
	        <p><?php echo $this->translate("Please take an appropriate action on badge request for this page:") ?> <?php echo $sitepage_title; ?></p>
	        <p><?php echo $this->translate("Once you save this form, an email will be send to the requester.") ?></p><br />
	      <?php endif; ?>
	
	
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Badge:") ?></label>
	        </div>
	        <div class="form-element">
	          <b><?php echo $this->badge->title; ?></b>
	          <br />
	          <?php echo $this->badge_image; ?>
	        </div>
	      </div>
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Member Id:") ?></label>
	        </div>
	        <div class="form-element">
	          <?php echo $this->member->user_id; ?>
	        </div>
	      </div>
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Requester Name:") ?></label>
	        </div>
	        <div class="form-element">
	          <?php echo $this->member->toString(); ?>
	        </div>
	      </div>				
	
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Email:") ?></label>
	        </div>
	        <div class="form-element">
	          <?php echo $this->member->email; ?>
	        </div>
	      </div>
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Requested Date:") ?></label>
	        </div>
	        <div class="form-element">
	          <?php echo $this->badgerequest->creation_date; ?>
	        </div>
	      </div>
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Last Action Taken:") ?></label>
	        </div>
	        <div class="form-element">
	          <?php echo $this->badgerequest->modified_date; ?>
	        </div>
	      </div>
	
	      <?php if (!empty($this->badgerequest->contactno)): ?>			
	        <div class="form-wrapper">
	          <div class="form-label">
	            <label><?php echo $this->translate("Contact Number:") ?></label>
	          </div>
	          <div class="form-element">
	            <?php echo $this->badgerequest->contactno; ?>
	          </div>
	        </div>
	      <?php endif; ?>
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("User Comments:") ?></label>
	        </div>
	        <div class="form-element">
	          <?php echo $this->badgerequest->user_comment; ?>
	        </div>
	      </div>				
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Status:") ?> </label>
	        </div>
	        <div class="form-element">
	          <?php if ($this->badgerequest->status == 1) : ?>
	            <?php echo $this->translate("Approved") ?>
	          <?php elseif ($this->badgerequest->status == 2) : ?>
	            <?php echo $this->translate("Declined") ?>
	          <?php else: ?>
	            <p><?php echo $this->translate("Take an action on this request by choosing a status below. An email to the badge requester will be sent accordingly."); ?></p><br />
	            <select id="" name="status">
	              <option value="1" <?php if ($this->badgerequest->status == 1): ?><?php echo "selected"; ?><?php endif; ?>><?php echo $this->translate("Approved") ?></option>
	              <option value="2" <?php if ($this->badgerequest->status == 2): ?><?php echo "selected"; ?><?php endif; ?>><?php echo $this->translate("Declined") ?></option>
	              <option value="4" <?php if ($this->badgerequest->status == 4): ?><?php echo "selected"; ?><?php endif; ?>><?php echo $this->translate("Hold") ?></option>
	            </select>
	          <?php endif; ?>
	        </div>
	      </div>
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label><?php echo $this->translate("Admin's Comments:") ?> </label>
	        </div>
	        <div class="form-element">
	          <?php if ($this->badgerequest->status == 1 || $this->badgerequest->status == 2) : ?>
	            <?php if (!empty($this->badgerequest->admin_comment)): ?>
	              <?php echo $this->badgerequest->admin_comment; ?>
	            <?php else: ?>
	              <?php echo '---'; ?>
	            <?php endif; ?>
	          <?php elseif ($this->badgerequest->status == 3 || $this->badgerequest->status == 4): ?>
	            <textarea name="admin_comment"><?php echo $this->badgerequest->admin_comment; ?></textarea>
	          <?php endif; ?>
	        </div>
	      </div>		
	
	      <div class="form-wrapper">
	        <div class="form-label">
	          <label>&nbsp;</label>
	        </div>
	        <div class="form-element">
	          <?php if ($this->badgerequest->status == 1) : ?>
	            <button onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate("Close") ?></button>
	          <?php elseif ($this->badgerequest->status == 2) : ?>
	            <button onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate("Close") ?></button>
	          <?php else: ?>
	            <button type='submit'><?php echo $this->translate('Save'); ?></button>
	            <?php echo $this->translate(" or ") ?> 
	            <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
	              <?php echo $this->translate("cancel") ?></a>
	          <?php endif; ?>
	        </div>
	      </div>
	    </div>
	  </form>
	</div>
</div>
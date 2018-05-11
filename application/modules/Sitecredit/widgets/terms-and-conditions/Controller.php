<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_TermsAndConditionsController extends Engine_Content_Widget_Abstract
{
  	public function indexAction() {
   		$viewer = Engine_Api::_()->user()->getViewer();
   		if (!$viewer)
      		return $this->setNoRender();
   		$this->view->terms=$terms=Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.terms','<p>1. Do not delete the activities which you have performed to earn credits. If you delete those activities, you will loose your earned credits.</p>
<p>2. Credits are more for activity when it is performed for the first time and credit value is same when it is performed second time onwards.</p>
<p>3. You can only upgrade your member level or switch to member level with same credit value as of your current member level. You cannot switch back to your previous member level even if you have required credits.</p>
<p>4. You can redeem your credits on the checkout page while purchase Event tickets or Store products.</p>
<p>5. Credits redeemed on checkout page will not be refunded even if you have cancelled the Event ticket or returned the Store product.</p>
<p>6. Your credit value will set to &lsquo;0&rsquo; once your credit validity expires.</p>');

   		if (empty($terms)) {
      		return $this->setNoRender();
  		} 

	}

}

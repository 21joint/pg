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
class Sitecredit_Widget_EarnCreditsController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer) {
            return $this->setNoRender();
        }
        $this->view->instructions=$instructions=Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.instruction','<p>1. <strong>Performing Activities</strong></p>
<p>&nbsp;&nbsp;&nbsp;Earn credits by performing various activities: liking a post, by commenting on a post, content creation etc.</p>
<p><strong>&nbsp;</strong></p>
<p>2. <strong>Referral Signups</strong></p>
<p>&nbsp;&nbsp;&nbsp;Invite your friends via referral link to join your community and earn considerable credits.</p>
<p><strong>&nbsp;</strong></p>
<p>3. <strong>Send to Friends</strong></p>
<p>&nbsp;&nbsp;&nbsp;You can send credits to your community friends as a gift.</p>
<p><strong>&nbsp;</strong></p>
<p>4. <strong>Bonus</strong></p>
<p>&nbsp;&nbsp;&nbsp;Community Admin can provide you credits as bonus for your active participation or on some special occasion.</p>
<p><strong>&nbsp;</strong></p>
<p>5. <strong>Buy Credits</strong></p>
<p>&nbsp;&nbsp;&nbsp;You can buy credits with an ongoing offer or exact credit value you need to get more credits.</p>
<p><br><br></p>');

        $sitecreditEarnCredit = Zend_Registry::isRegistered('sitecreditEarnCredit') ? Zend_Registry::get('sitecreditEarnCredit') : null;
        if (empty($sitecreditEarnCredit))
            return $this->setNoRender();

        if (empty($instructions)) {
            return $this->setNoRender();
        } 

    }

}

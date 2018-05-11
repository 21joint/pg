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
class Sitecredit_Widget_BadgesController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $user_id = Engine_Api::_()->core()->getSubject()->getIdentity();
        if (!$user_id)
            return $this->setNoRender();
        if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.badge.enable', 0))
            return $this->setNoRender();
        $param=array();
        $param['user_id']=$user_id;
        $param['basedon']= Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.ranking', 0);

        $sitecreditBadge = Zend_Registry::isRegistered('sitecreditBadge') ? Zend_Registry::get('sitecreditBadge') : null;
        if (empty($sitecreditBadge))
            return $this->setNoRender();
  
        if ($this->_getParam('countbadge', 1)<=5) {
            $this->view->count = $param['count']= $this->_getParam('countbadge', 1);
        }else {
            $this->view->count = $param['count']= 1;
        }
        $this->view->result=$result = Engine_Api::_()->getDbtable('Badges','sitecredit')->getBadge($param);

        if (count($result)==0) {
            return $this->setNoRender();
        }

    }

}

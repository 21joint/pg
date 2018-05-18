<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: core.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitemetatag_Api_Core extends Core_Api_Abstract {

    protected $_pageInfo;

    // RETURNS PAGE INFORMATION OF CURRENT RENDERING PAGE
    public function getCurrentPageinfo() {
        if (isset($this->_pageInfo)) 
            return $this->_pageInfo;
        $params = array();
        $params['content'] =  Zend_Registry::isRegistered('sitemeta_content_name') ? Zend_Registry::get('sitemeta_content_name') : false;
        $this->_pageInfo = $params['content'] ? Engine_Api::_()->getDbtable('pageinfo', 'sitemetatag')->getPageinfo($params) : false;
        return $this->_pageInfo;
    }
}
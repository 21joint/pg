<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 20007-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @author     SocialEngineAddOns
 */
class Siteseo_Controller_Action_Helper_MetatagPage extends Zend_Controller_Action_Helper_Abstract
{
  function postDispatch()
  {
    $contentHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Content');
    if (Zend_Registry::isRegistered('sitemeta_content_name') && Zend_Registry::get('sitemeta_content_name', false)) {
    	return;
    }
    if( $contentHelper->getEnabled() ) {
      Zend_Registry::set('sitemeta_content_name', $contentHelper->getContentName());
    } else {
      Zend_Registry::set('sitemeta_content_name', false);
    }
  }

}

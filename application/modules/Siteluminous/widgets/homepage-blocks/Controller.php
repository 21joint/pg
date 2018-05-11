<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Widget_HomepageBlocksController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
    $islanguage = $this->view->translate()->getLocale();
    if(!strstr($islanguage, '_')){
              $islanguage = $islanguage.'_default';
        }
    $keyForSettings = str_replace('_', '.', $islanguage);
    $siteluminousLendingBlockValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.lending.block.languages.'.$keyForSettings, null);
    
    if(empty($siteluminousLendingBlockValue)){
      $siteluminousLendingBlockValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.lending.block', null);
    }
    
    if(!empty($siteluminousLendingBlockValue))
      $this->view->siteluminousLendingBlockValue = @base64_decode($siteluminousLendingBlockValue);
    
    $siteluminous_landing_page_block = Zend_Registry::isRegistered('siteluminous_landing_page_block') ? Zend_Registry::get('siteluminous_landing_page_block') : null;
    if(empty($siteluminous_landing_page_block))
      return $this->setNoRender();
  }
}
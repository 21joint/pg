<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: core.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Api_Core extends Core_Api_Abstract
{
  public function sendEmailToUser($value = null,$email_template = null) {
    $coresetting=Engine_Api::_()->getApi('settings', 'core');
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if(empty($value) || empty($email_template))
      return;
    $user = Engine_Api::_()->user()->getUser($value['user_id']);
    $data = array();
    $data['site_title'] = $coresetting->getSetting('core.general.site.title', '');
    $data['recipient_title'] = $user->getTitle();
    $data['credits'] = $value['credit_point'];
    if($email_template=="credits_sent_to_users")
    {
        $data['message'] = $value['message'];
    }
    if($email_template=="credits_sent_to_friend")
    {   
        $sender=Engine_Api::_()->user()->getUser($value['type_id']);
        $data['message'] = $value['reason'];
        $data['sender_title'] = $sender->getTitle();
    }
    
   $data['queue'] = false;

   Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, $email_template,$data);
  }

  public function getCurrencySymbol() {

    $localeObject = Zend_Registry::get('Locale');
    $currencyCode = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
    $currencySymbol = Zend_Locale_Data::getContent($localeObject, 'currencysymbol', $currencyCode);
    return $currencySymbol;
  }
  public function getPriceWithCurrency($price) {  
    $coresetting=Engine_Api::_()->getApi('settings', 'core');
      if (empty($price)) {
          return $price;
      } 

        $defaultParams = array();
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      if (empty($viewer_id)) {
          $defaultParams['locale'] = $coresetting->getSetting('core.locale.locale', 'auto');
      } 
        $currency = $coresetting->getSetting('payment.currency', 'USD');
        $defaultParams['precision'] = 2;
        $price = (float) $price;
        $priceStr = Zend_Registry::get('Zend_View')->locale()->toCurrency($price, $currency, $defaultParams); 
    
        return $priceStr;
  }
  public function getModuleEditorArray() {
        $data = array();

        $getEnabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
        // Get menu items
        $moduleItemsTable = Engine_Api::_()->getDbtable('modulelists', 'sitecredit');
        $moduleItemsSelect = $moduleItemsTable->select()
                ->order('order_id');
        if (!empty($getEnabledModuleNames)) {
            $moduleItemsSelect->where('name IN(?)', $getEnabledModuleNames);
        }
        $modules = $moduleItemsTable->fetchAll($moduleItemsSelect);

      return $modules;
  }

    public function buildParent($buildData,$parent) { 
      $child=array();
      try {
        
      if (empty($buildData) || empty($parent)) {
        return $child;
      }
      $sd = $buildData->toArray();
      
      foreach ($sd as $data) {
       if($data['parent_id']==$parent->modulelist_id){
        $child[]=$data;
       }
      }
    }catch(Exception $x){

      echo $x->getMessage();
      die;
    }
    return $child;
    }
}
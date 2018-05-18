<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SiteformController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_SiteformController extends Sitepageform_Controller_Abstract {

  protected $_requireProfileType = true;
  
  public function editTabAction() {
    
		$page_id = $this->_getParam('page_id');
		$layout_type = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
		$contenPageTable = Engine_Api::_()->getDbtable('content', 'sitepage');
		$adminContentPageTable = Engine_Api::_()->getDbtable('admincontent', 'sitepage');
		if(empty($layout_type)) {
			$sitepageformtable = Engine_Api::_()->getDbtable('sitepageforms', 'sitepageform');
			$offerWidgetName = $sitepageformtable->select()
									->from($sitepageformtable->info('name'),'offer_tab_name')
									->where('page_id = ?', $page_id)
									->query()->fetchColumn();
			if( !empty($offerWidgetName) ) {
				$this->view->offer_tab_name = $offerWidgetName;
			}
			else {
				$tablecontent = Engine_Api::_()->getDbtable('content', 'core');
				$params = $tablecontent->select()
										->from($tablecontent->info('name'),'params')
										->where('name = ?', 'sitepageform.sitepage-viewform')
										->query()->fetchColumn();
				$decodedParam = !empty($params) ? Zend_Json::decode($params) : array();
				$tabName = $decodedParam['title'];
				$this->view->offer_tab_name = $tabName;
			}	
		}
		else {
		  $userContent = $contenPageTable->checkWidgetExist($page_id,'sitepageform.sitepage-viewform');
		  if(!empty($userContent)) {
		    $decodedParam = Zend_Json::decode($userContent);
				$tabName = $decodedParam['title'];
				$this->view->offer_tab_name = $tabName;
		  }
		  else {
		    $adminContent = $adminContentPageTable->checkAdminWidgetExist('sitepageform.sitepage-viewform');
		    $decodedParam = !empty($adminContent) ? Zend_Json::decode($adminContent) : array();
				$tabName = $decodedParam['title'];
				$this->view->offer_tab_name = $tabName;
		  }
		
		}
		
		$this->view->success = 0;
		if ($this->getRequest()->isPost()) {
		  $userContent = $contenPageTable->checkWidgetExist($page_id,'sitepageform.sitepage-viewform');
		  $tab_name = $_POST['tab_name'];
		  $setParam = "{\"title\":\"$tab_name\",\"titleCount\":false}";
		  if(!empty($userContent) && !empty($layout_type)) {
		    $contenPageTable->update(array('params' => $setParam), array('contentpage_id = ?' => $page_id,'name	 = ?' => 'sitepageform.sitepage-viewform'));
		  }
		  elseif(!empty($layout_type)) {
				$adminContentPageTable->update(array('params' => $setParam), array('name	 = ?' => 'sitepageform.sitepage-viewform'));
		  }
		  else {
		    $sitepageformtable->update(array('offer_tab_name' => $_POST['tab_name']), array('page_id = ?' => $page_id));
		  }
			$this->view->success = 1;
		}
  }

}
?>
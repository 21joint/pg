<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    IndexController.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_IndexController extends Core_Controller_Action_Standard {

    public function indexAction() {

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $search = $request->getParam('search');
        if (empty($search)) {
            return $this->_forward('notfound', 'error', 'core');
        }
        // Render
        $this->_helper->content
                //->setNoRender()
                ->setEnabled()
        ;
    }
    
     public function getHashtagAction() {
            
        $hashtags = Engine_Api::_()->getDbtable('tags', 'sitehashtag')->getItems($this->_getParam('text'), $this->_getParam('limit'), $this->_getParam('search_criteria'));
        $data = array();
        $view = Zend_Registry::get('Zend_View');
       
            foreach ($hashtags as $hashtag) {
                
                $url=  $view->baseUrl()."/hashtag?search=".urlencode($hashtag->text);
                $data[] = array(
                    'id' => $hashtag->tag_id,
                    'label' => $hashtag->text,
                    'hashtag_url' => $url,
                );
            }


        return $this->_helper->json($data);
    }

}

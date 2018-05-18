<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Widget_NavigationController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $this->view->padding_top = 0;
        $layoutValue = Engine_Api::_()->captivate()->getWidgetizedPageLayoutValue(array('name' => 'core_index_index'));
        if ($params['module'] == 'core' && $params['controller'] == 'index' && $params['action'] == 'index' && ($layoutValue == 'default' || $layoutValue == '')) {
            $this->view->padding_top = 1;
        }
    }

}

<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_Guide extends Core_Model_Item_Abstract
{
    public function getHref($params = array())
    {
        $params = array_merge(array(
          'route' => 'sdparentalguide_guides',
          'reset' => true,
          'action' => 'view',
          'id' => $this->getIdentity(),
        ), $params);
        $route = $params['route'];
        $reset = $params['reset'];
        unset($params['route']);
        unset($params['reset']);
        return Zend_Controller_Front::getInstance()->getRouter()
          ->assemble($params, $route, $reset);
    }
    public function getTopic(){
        return Engine_Api::_()->getItem('sdparentalguide_topic', $this->topic_id);
    }
} 

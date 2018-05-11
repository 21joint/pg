<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitetagcheckin
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: GeoSitetagCheckin.php 2012-08-20 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitetagcheckin_View_Helper_GetSitetagCheckinMap extends Zend_View_Helper_Abstract {

    /**
     * Assembles action string
     * 
     * @return Map
     */
    public function getSitetagCheckinMap($action,$params = array()) {
        if(!$action || empty($params['checkin'])){
            return;
        }
        $view = Zend_Registry::get('Zend_View');
        $view->clearVars();
        $view->addScriptPath('application/modules/Sitetagcheckin/views/scripts/');
        $view->action = $action; 
        $RESOURCE_TYPE = $action->getType();
        
        return $view->render('_mapView.tpl');
    }
}


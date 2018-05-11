<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteluminous_Plugin_Core extends Zend_Controller_Plugin_Abstract {
    
     public function onRenderLayoutDefault($event) {

        $view = $event->getPayload();

        $view->headLink()->appendStylesheet('https://fonts.googleapis.com/css?family=Ubuntu:300,700')
                ->appendStylesheet('https://fonts.googleapis.com/css?family=Open+Sans:400,300,700')
                ->appendStylesheet('https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300');
    }

    public function onRenderLayoutDefaultSimple($event) {
        // Forward
        return $this->onRenderLayoutDefault($event, 'simple');
    }


}

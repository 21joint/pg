<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Mail.php 6590 2012-06-20 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_View_Helper_Pageoffermail extends Zend_View_Helper_Abstract {

  public function pageoffermail($data = array()) {

    return $this->view->partial(
                    '_set-mail.tpl', 'sitepageoffer',$data);
  }
}
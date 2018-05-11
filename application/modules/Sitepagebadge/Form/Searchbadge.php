<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Searchbadge.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Form_Searchbadge extends Engine_Form {

  public function init() {
    $this
            ->setAttribs(array(
                'id' => 'filter_form_badge',
                'class' => 'global_form_badge',
            ))
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index'), 'sitepage_general'));

    $this->addElement('Hidden', 'badge_id', array(
        'order' => 200
    ));
  }

}
?>
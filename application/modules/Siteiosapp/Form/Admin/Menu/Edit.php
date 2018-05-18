<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Edit.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteiosapp_Form_Admin_Menu_Edit extends Siteiosapp_Form_Admin_Menu_Add {

    protected $_menu;

    public function getMenu() {
        return $this->_menu;
    }

    public function setMenu($menu) {
        $this->_menu = $menu;
        return $this;
    }

    public function init() {

        if ((isset($this->_menu)) && !empty($this->_menu)) {
            if (isset($this->_menu->params) && !empty($this->_menu->params)) {
                $params = @unserialize($this->_menu->params);
                if (isset($params) && !empty($params) && isset($params['listingtype_id']) && !empty($params['listingtype_id'])) {
                    $listingtypeId = $params['listingtype_id'];
                    if (isset($params['header_label_singular']) && !empty($params['header_label_singular'])) {
                        $title_singular = $params['header_label_singular'];
                    } else {
                        $title_singular = Engine_Api::_()->getItem('sitereview_listingtype', $listingtypeId)->title_singular;
                    }
                    if (isset($listingtypeId) && !empty($listingtypeId) && isset($title_singular) && !empty($title_singular)) {
                        $listingType = $this->addElement('Text', 'header_label_singular', array(
                            'label' => 'Header Label (Singular)',
                            'required' => true,
                            'order' => 801,
                            'value' => $title_singular
                        ));
                    }
                }
            }
        }

        parent::init();

        if ((isset($this->_menu)) && !empty($this->_menu)) {
            if (isset($this->_menu->params) && !empty($this->_menu->params)) {
                $params = @unserialize($this->_menu->params);
                if (isset($params) && !empty($params) && isset($params['listingtype_id']) && !empty($params['listingtype_id'])) {
                    $this->header_label->setLabel('Header Label (Plural)');
                }
            }
        }

        $this->removeElement('status');
    }

}

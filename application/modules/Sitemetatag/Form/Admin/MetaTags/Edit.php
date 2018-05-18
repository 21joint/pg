<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitemetatag_Form_Admin_MetaTags_Edit extends Engine_Form {

	protected $_page;

	public function getPage() {
		return $this->_page;
	}

	public function setPage(Core_Model_Item_Abstract $page) {
		$this->_page = $page;
		return $this;
	}

	public function init() {
		$page = $this->getPage();
        $this->setTitle($page->displayname);
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        // $provideSubject = strstr($page->provides, "subject=") ? true : false;

        $this->addElement('Text', 'title', array(
            'label' => 'Page Title',
            'description' => 'Enter the title of the page. This will be shown in title tag of the page.',
            'allowEmpty' => false,
            'required' => true,
            'value' => $page->getTitle(),
            ));

        $this->addElement('Textarea', 'description', array(
            'label' => 'Meta Description',
            'description' => 'Enter the meta description of the page. (This will be shown in meta description tag of the page.)',
            'allowEmpty' => false,
            'required' => true,
            'value' => $page->getDescription(),
            ));


        $params = array('page_id' => $page->getIdentity());
        $pageInfo = Engine_Api::_()->getDbtable('pageinfo','sitemetatag')->getPageinfo($params);

        if ($pageInfo->photo_id) {
            $this->addElement('dummy', 'preview', array(
                'decorators' => array(
                    array('ViewScript', array(
                        'viewScript' => '_formCustomImagePreview.tpl',
                        'class' => 'form element'
                        ))
                    )
                ));
            $this->addElement('checkbox', 'remove_photo', array(
                'label' => 'Remove Photo'
                ));
        }
        $this->addElement('File', 'photo', array(
            'label' => 'Meta Image',
            'description' => 'Add a meta image for this page. (If this page is profile page/view page, you can skip this step)',
        ));

        $this->addElement('Select', 'enable_opengraph', array(
            'label' => 'Enable Open Graph Meta Tags',
            'description' => 'Do you want to enable Open graph meta tags for this page .',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
                ),
            'value' => $pageInfo ? $pageInfo->enable_opengraph : 1,
            ));

        $this->addElement('Select', 'enable_twittercards', array(
            'label' => 'Enable Twitter cards Meta Tags',
            'description' => 'Do you want to enable Twitter cards meta tags for this page .',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
                ),
            'value' => $pageInfo ? $pageInfo->enable_twittercards : 1,
            ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
                ),
        ));
        
        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'onclick' => "javascript:parent.Smoothbox.close();",
            'href' => "javascript:void(0);",
            'decorators' => array(
                'ViewHelper',
                ),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array(
            'submit',
            'cancel',
            ), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper'
                ),
        ));
        $button_group = $this->getDisplayGroup('buttons');
        $button_group->setOrder('999');
    }
}
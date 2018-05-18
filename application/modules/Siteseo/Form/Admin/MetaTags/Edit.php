<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Form_Admin_MetaTags_Edit extends Engine_Form {

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
        $description = 'Here You can edit the meta tags for the selected page.';
        $this->setTitle($page->displayname);
        $this->setDescription($description);
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
            'description' => 'Enter the meta description of the page. This will be shown in meta description tag of the page.',
            'allowEmpty' => false,
            'required' => true,
            'value' => $page->getDescription(),
            ));

        $keywordTemplate = '[subject_keywords], [subject_category],[subject_subcategory],[subject_subsubcategory],[subject_type],[subject_location]';
        if(empty($page->keywords) && strpos($page->name, '_view') !== false)
            $keywords = $page->keywords . $keywordTemplate; 
        else 
            $keywords = $page->keywords;

        $this->addElement('Textarea', 'keywords', array(
            'label' => 'Meta Keywords',
            'description' => 'Enter the meta keywords of the page separated by comma. (This will be used in meta keywords tag of the page. Also, You can use variable [subject_keywords], [subject_category], [subject_subcategory], [subject_subsubcategory], [subject_type], [subject_location] .)',
            'value' => $keywords,
            ));
        $params = array('page_id' => $page->getIdentity());
        $pageInfo = Engine_Api::_()->getDbtable('pageinfo','siteseo')->getPageinfo($params);

        $this->addElement('Select', 'meta_robot', array(
            'label' => 'Meta Robot Tag',
            'description' => 'The index property will allow Google bot to index this page and follow property will allow to follow the links on this page. [Note: Meta Robot tag is used to provide search engines with instructions on how web pages should be crawled, indexed, and presented in search results.]',
            'multiOptions' => array(
                0 => 'Index , Follow',
                1 => 'Index , Nofollow',
                2 => 'Noindex , Follow',
                3 => 'Noindex , Nofollow'
                ),
            'value' => $pageInfo ? $pageInfo->meta_robot : 0,
            ));

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
            'label' => 'Custom Image',
            'description' => 'Add a custom image for this page. (If this page is profile page/view page, you can skip this step)',
        ));

        // DISPLAY OPEN GRAPH AND TWITTER CARD FIELDS ONLY IF SITEMETATAG IS INSTALLED
        if (Engine_Api::_()->hasModuleBootstrap('sitemetatag')) {
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
        }

        $this->addElement('Textarea', 'custom_metatags', array(
            'label' => 'Additional Meta Tags',
            'description' => 'Mention the additional meta tags you want to add for this page. Here you can add meta tags of your choice other than meta description or meta keywords tag for this particular page. It will be shown as it is in the web page. Example : <meta name="copyright" content="name of owner">',
            'value' => $pageInfo ? $pageInfo->custom_metatags : '',
            ));

        $clickHere = '<a href="http://schema.org/docs/faq.html" target="_blank"> Click here </a>';
        $this->addElement('Textarea', 'schema', array(
            'label' => 'Custom Schema Markup',
            'description' => "Enter the Custom Schema Markup you want to enter for this page in json-id format. This will overwrite the default schema markup. $clickHere to know more about schema markup. [Note: You need not to include script tags, you can just add the json code.]",
            'value' => $pageInfo ? $pageInfo->schema : '',
            ));

        $this->getElement('schema')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

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
<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: filter.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Form_Admin_MetaTags_Contentfilter extends Engine_Form {

    public function init() {

        $this->clearDecorators()
        ->addDecorator('FormElements')->addDecorator('Form')
        ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search siteseo_search'));

        $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))
        ->setMethod('GET');

        $contentType = new Zend_Form_Element_Select('type', array('label' => 'Content Type'));
        $contentType->setMultiOptions(array(
            '' => 'Everything',));

        $searchApi = Engine_Api::_()->getApi('search', 'core');
        $availableTypes = $searchApi->getAvailableTypes();
        if( is_array($availableTypes) && count($availableTypes) > 0 ) {
            $options = array();
            foreach( $availableTypes as $index => $type )
                $options[$type] = strtoupper('ITEM_TYPE_' . $type);
            $contentType->addMultiOptions($options);
        }

        $title = new Zend_Form_Element_Text('title', array('label' => 'Meta Title'));
        $description = new Zend_Form_Element_Text('description', array('label' => 'Meta Description'));
        $keywords = new Zend_Form_Element_Text('keywords', array('label' => 'Meta Keywords'));
        $submit = new Zend_Form_Element_Button('search', array('type' => 'submit'));

        $elements = array($contentType, $title, $description, $keywords, $submit);
        foreach ($elements as $element) {
            $element->clearDecorators()->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));
        }

        $submit->removeDecorator('Label')->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons sm_search_button'))
        ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clr'));

        $this->addElement('Hidden', 'order', array('order' => 10001));
        $this->addElement('Hidden', 'order_direction', array('order' => 10002));
        $this->addElement('Hidden', 'page', array('order' => 10003));
        $this->addElements($elements);

        // Set default action without URL-specified params
        $params = array();
        foreach (array_keys($this->getValues()) as $key) {
            $params[$key] = null;
        }
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
    }
}
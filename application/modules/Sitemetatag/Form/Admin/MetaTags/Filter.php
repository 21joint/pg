<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: filter.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitemetatag_Form_Admin_MetaTags_Filter extends Engine_Form {

    public function init() {
        $this->clearDecorators()
        ->addDecorator('FormElements')->addDecorator('Form')
        ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search meta_search'));

        $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box meta_search_form'))
        ->setMethod('GET');

        $moduleTable = Engine_Api::_()->getDbtable('modules', 'core');
        $moduleTableName = $moduleTable->info('name');
        $corePageTable = Engine_Api::_()->getDbtable('pages','core');
        $corePageTableName = $corePageTable->info('name');
        $select = $moduleTable->select()
            ->from($moduleTableName, array('name', 'title'))->where("$moduleTableName.enabled = ?", 1)
            ->setIntegrityCheck(false)
            ->joinLeft($corePageTableName, "1", array())
            ->where("$corePageTableName.name LIKE CONCAT('%', $moduleTableName.name ,'_%')")
            ->distinct();
        $rows = $moduleTable->fetchAll($select);

        $enabledModules = array();
        $enabledModules[''] = 'All Modules';
        $enabledModules['custom'] = 'Custom Pages';
        foreach ($rows as $row) {
            // $enabledModules[$row->name] = strlen($row->title) > 20 ? substr($row->title, 0, 23) . '..' : $row->title;
            $enabledModules[$row->name] = $row->title;
        }

        $plugin = new Zend_Form_Element_Select('plugin', array('label' => 'Select Pages',
            'multiOptions' => $enabledModules,
            ));
        $displayname = new Zend_Form_Element_Text('displayname', array('label' => 'Display Name'));
        $title = new Zend_Form_Element_Text('title', array('label' => 'Page Title'));
        $description = new Zend_Form_Element_Text('description', array('label' => 'Meta Description'));
        $opengraph = new Zend_Form_Element_Select('enable_opengraph', array( 'label' => 'Open Graph', 
            'multiOptions' => array(
                    '' => '',
                    '0' => 'Disabled',
                    '1' => 'Enabled',
                    )
                ));
        $twittercard = new Zend_Form_Element_Select('enable_twittercards', array( 'label' => 'Twitter Cards', 
            'multiOptions' => array(
                    '' => '',
                    '0' => 'Disabled',
                    '1' => 'Enabled',
                    )
                ));

        $submit = new Zend_Form_Element_Button('search', array('type' => 'submit', array('label' => 'Search')));
        $elements = array($plugin, $displayname, $title, $description, $opengraph, $twittercard, $submit);
        foreach ($elements as $element) {
            $element->clearDecorators()->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));
        }

        $submit->removeDecorator('Label')->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons sm_search_button'))
        ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clr'));

        $this->addElement('Hidden', 'order', array('order' => 10001));
        $this->addElement('Hidden', 'order_direction', array('order' => 10002));
        $this->addElement('Hidden', 'page_id', array('order' => 10003));
        $this->addElement('Hidden', 'page', array('order' => 10004));


        $this->addElements($elements);

        // Set default action without URL-specified params
        $params = array();
        foreach (array_keys($this->getValues()) as $key) {
            $params[$key] = null;
        }
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
    }
}
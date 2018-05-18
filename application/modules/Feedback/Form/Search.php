<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Search.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Search extends Fields_Form_Search {

    protected $_fieldType = 'feedback';
    protected $_searchForm;

    public function init() {
        parent::init();

        $this->loadDefaultDecorators();

        $this
                ->setAttribs(array(
                    'id' => 'filter_form',
                    'class' => 'feedbacks_browse_filters field_search_criteria',
                ))
                ->setMethod('GET')
                ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
                ->getDecorator('HtmlTag')
                ->setOption('class', 'browsemembers_criteria feedbacks_browse_filters');

        // Add custom elements
        $this->getAdditionalOptionsElement();
    }

    public function getAdditionalOptionsElement() {

        $i = -5000;
        $this->addElement('Hidden', 'page', array(
            'order' => 200,
        ));

        $this->_searchForm = Engine_Api::_()->getDbTable('searchformsetting', 'seaocore');
        $row = $this->_searchForm->getFieldsOptions('feedback', 'orderby');
        if (!empty($row) && !empty($row->display)) {
            $this->addElement('Select', 'orderby', array(
                'label' => 'Browse By',
                'multiOptions' => array(
                    'feedback_id' => 'Most Recent',
                    'total_votes' => 'Most Voted',
                    'views' => 'Most Viewed',
                    'comment_count' => 'Most Commented',
                    'featured' => 'Featured',
                ),
                'onchange' => 'this.form.submit();',
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));
        }

        $row = $this->_searchForm->getFieldsOptions('feedback', 'stat');
        if (!empty($row) && !empty($row->display)) {
            $this->addElement('Select', 'stat', array(
                'label' => 'Status',
                'multiOptions' => array(
                    '0' => 'All Status',
                ),
                'onchange' => 'this.form.submit();',
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));

            $status = Engine_Api::_()->getDbtable('status', 'feedback')->getStatus();
            foreach ($status as $stat) {
                $this->stat->addMultiOption($stat->stat_id, $stat->stat_name);
            }
        } else {
            $this->addElement('Hidden', 'stat', array(
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    //array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));
        }

        $row = $this->_searchForm->getFieldsOptions('feedback', 'category');
        if (!empty($row) && !empty($row->display)) {
            $this->addElement('Select', 'category', array(
                'label' => 'Category',
                'multiOptions' => array(
                    '0' => 'All Categories',
                ),
                'onchange' => 'this.form.submit();',
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));

            $categories = Engine_Api::_()->getDbtable('categories', 'feedback')->getCategories();
            foreach ($categories as $category) {
                $this->category->addMultiOption($category->category_id, $category->category_name);
            }
        } else {
            $this->addElement('Hidden', 'category', array(
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    //array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),                
            ));
        }

        $row = $this->_searchForm->getFieldsOptions('feedback', 'search');
        if (!empty($row) && !empty($row->display)) {
            $this->addElement('Text', 'search', array(
                'label' => 'Search Feedback',
                'onchange' => 'this.form.submit();',
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));
        }

        $this->addElement('Hidden', 'tag', array(
            'order' => $i--,
        ));

        $this->addElement('Button', 'done', array(
            'label' => 'Search',
            'order' => 10000,
            'onclick' => 'this.form.submit();',
            'decorators' => array(
                'ViewHelper',
                //array('Label', array('tag' => 'span')),
                array('HtmlTag', array('tag' => 'li'))
            ),
        ));
    }

}

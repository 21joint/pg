<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Search.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Form_Search extends Fields_Form_Search
{
    protected $_searchForm;
    protected $_fieldType = 'sitefaq_faq';

  public function init()
  {
        $this
                ->setAttribs(array(
                    'id' => 'filter_form',
                    'class' => 'sitefaqs_browse_filters field_search_criteria',
                ))
                ->setMethod('GET');

        $this->getAdditionalOptionsElement();

        parent::init();

        $this->loadDefaultDecorators();
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

        $front = Zend_Controller_Front::getInstance();
        $module = $front->getRequest()->getModuleName();
        $controller = $front->getRequest()->getControllerName();
        $action = $front->getRequest()->getActionName();

        if ($module == 'sitefaq' && $controller == 'index' && $action == 'manage') {
            $this->setAction($view->url(array('action' => 'manage'), 'sitefaq_general', true))->getDecorator('HtmlTag')->setOption('class', 'browsemembers_criteria');
        } else {
            $this->setAction($view->url(array('action' => 'browse'), 'sitefaq_general', true))->getDecorator('HtmlTag')->setOption('class', 'browsemembers_criteria');
        }
    }

    public function getAdditionalOptionsElement() {
        $order = 99990;

        $this->addElement('Hidden', 'page', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'tag', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'tag_id', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'category', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'subcategory', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'subsubcategory', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'categoryname', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'subcategoryname', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'subsubcategoryname', array(
            'order' => $order++,
        ));

        $this->addElement('Hidden', 'search_form', array(
            'order' => $order++,
            'value' => 1
        ));

        $this->_searchForm = Engine_Api::_()->getDbTable('searchformsetting', 'seaocore');

        $row = $this->_searchForm->getFieldsOptions('sitefaq', 'search');
        if (!empty($row) && !empty($row->display)) {
            $this->addElement('Text', 'search', array(
                'label' => 'Search FAQs',
                'order' => $row->order,
                'decorators' => array(
                    'ViewHelper',
                    array('Label', array('tag' => 'span')),
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));
        }

        $row = $this->_searchForm->getFieldsOptions('sitefaq', 'orderby');
        if (!empty($row) && !empty($row->display)) {
            $this->addElement('Select', 'orderby', array(
                'label' => 'Browse By',
                'multiOptions' => array(
                    'weight' => '',
                    'faq_id' => 'Most Recent',
                    'view_count' => 'Most Viewed',
                    'comment_count' => 'Most Commented',
                    'like_count' => 'Most Liked',
                    'helpful' => 'Most Helpful',
                    'title' => 'Alphabetical',
                    'rating' => 'Highest Rated'
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

        $row = $this->_searchForm->getFieldsOptions('sitefaq', 'category_id');
        if (!empty($row) && !empty($row->display)) {
            $categories = Engine_Api::_()->getDbTable('categories', 'sitefaq')->getCategories(null);
            if (count($categories) != 0) {
                $categories_prepared[0] = "";
                foreach ($categories as $category) {
                    $categories_prepared[$category->category_id] = $category->category_name;
                }

                if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
                    $onChangeEvent = "subcategories(this.value, '', '');";
                    $categoryFiles = 'application/modules/Sitefaq/views/scripts/_Subcategory.tpl';
                } else {
                    $onChangeEvent = "sm4.core.category.set(this.value, 'subcategory');";
                    $categoryFiles = 'application/modules/Sitefaq/views/sitemobile/scripts/_Subcategory.tpl';
                }

                $this->addElement('Select', 'category_id', array(
                    'label' => 'Category',
                    'order' => $row->order,
                    'multiOptions' => $categories_prepared,
                    'onchange' => $onChangeEvent,
                    'decorators' => array(
                        'ViewHelper',
                        array('Label', array('tag' => 'span')),
                        array('HtmlTag', array('tag' => 'li'))
                    ),
                ));

                $this->addElement('Select', 'subcategory_id', array(
                    'RegisterInArrayValidator' => false,
                    'order' => $row->order + 1,
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => $categoryFiles,
                                'class' => 'form element')))
                ));
            }
        }

        if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            $this->addElement('Button', 'done', array(
                'label' => 'Search',
                'order' => 999999999,
                'onclick' => 'this.form.submit();',
                'decorators' => array(
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'li'))
                ),
            ));
        } else { 
            //SITEMOBILE SUBMIT TYPE BUTTON IN SEARCH FORM
            $this->addElement('Button', 'done', array(
                'label' => 'Search',
                'type' => 'submit',
                'order' => 999999999,
                'ignore' => true,
            ));
        }
    }

}
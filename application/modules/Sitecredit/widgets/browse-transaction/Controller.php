<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_BrowseTransactionController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
    
        $viewer=Engine_Api::_()->user()->getViewer();
        if (!$viewer) 
            return $this->setNoRender();
        $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
   
        if (isset($params['page']) && !empty($params['page']))
            $this->view->page = $page = $params['page'];
        else
            $this->view->page = $page = 1;

        $this->view->showContent = $params['show_content'] = $this->_getParam('show_content', 2);
        $this->view->itemCountPerPage = $params['itemCountPerPage'] = $this->_getParam('itemCount', 12);
        $this->view->is_ajax = $params['is_ajax'] = $this->_getParam('is_ajax', false);
        $this->view->textTruncation = $params['truncationActivity'] = $this->_getParam('truncationActivity', 100);
        $this->view->creditTypeArray = $GLOBALS['sitecredit_creditType'];

        $this->view->language=Zend_Registry::get('Locale')->getLanguage();
        $sitecreditTransactions = Zend_Registry::isRegistered('sitecreditTransactions') ? Zend_Registry::get('sitecreditTransactions') : null;
        if (empty($sitecreditTransactions))
            return $this->setNoRender();
        $this->view->formFilter = $formFilter = new Sitecredit_Form_Admin_Filter();
        $this->view->params = $params;
        $values = array();
        if ($formFilter->isValid($this->_getAllParams())) {
            $values = $formFilter->getValues();
        }

        foreach ($values as $key => $value) {
            if (null === $value) {
                unset($values[$key]);
            }
        }
        $now = date('Y-m-d h:m:s');
        $now = strtotime($now);
        $raw = date('Y-m-d',$now);
  
        $viewer=Engine_Api::_()->user()->getViewer()->getIdentity();
        $creditTable=Engine_Api::_()->getItemTable('credit');
        $creditTableName = $creditTable->info('name');
  
        $selectQuery=$creditTable->select()->group('type');
        $this->view->result=$result=$creditTable->fetchAll($selectQuery);

        $validity=Engine_Api::_()->getDbtable('credits','sitecredit')->validityCheck();
  
        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');
        $select = $creditTable->select()->setIntegrityCheck(false);

        $select->from($creditTableName )->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id',array("$validityTableName.start_date"));
        $select->where("$creditTableName.user_id = ?",$viewer);
        $select ->where('DATE_ADD(start_date, INTERVAL '.$validity.' MONTH) >'.$creditTableName.'.creation_date')
        ->where($creditTableName.'.creation_date > start_date');

  
        if (!empty($params['show_time'])) {
            $show_time = $params['show_time'];
        }elseif (!empty($params['show_time']) && !isset($params['post_search'])) {
            $show_time = $params['show_time'];
        } else {
            $show_time = '';
        }

        if (!empty($params['credit_type'])) {
            $credit_type = $params['credit_type'];
        }elseif (!empty($params['credit_type']) && !isset($params['post_search'])) {
            $credit_type = $params['credit_type'];
        }else {
            $credit_type = '';
        }

        if (!empty($params['starttime']) && !empty($params['starttime']['date'])) {
            $creation_date_start = $params['starttime']['date'];
        }elseif (!empty($params['starttime']['date']) && !isset($params['post_search'])) {
            $creation_date_start = $params['starttime']['date'];
        }else {
            $creation_date_start = '';
        }

        if (!empty($params['endtime']) && !empty($params['endtime']['date'])) {
            $creation_date_end = $params['endtime']['date'];
        }elseif (!empty($params['endtime']['date']) && !isset($params['post_search'])) {
            $creation_date_end = $params['endtime']['date'];
        }else {
            $creation_date_end = '';
        }

        if (!empty($params['from'])) {  
            $creation_date_start = $params['from'];
        }elseif (!empty($params['from']) && !isset($params['post_search'])) {
            $creation_date_start = $params['from'];
        }
  
        if (!empty($params['to'])) {  
            $creation_date_end = $params['to'];
        }elseif (!empty($params['to']) && !isset($params['post_search'])) {
            $creation_date_end = $params['to'];
        }       

        if (isset($params['order_min_amount']) && $params['order_min_amount'] != '') {
            $order_min_amount = $params['order_min_amount'];
        }elseif (!empty($params['order_min_amount']) && !isset($params['post_search'])) {
            $order_min_amount = $params['order_min_amount'];
        }else {
            $order_min_amount = '';
        }

        if (isset($params['order_max_amount']) && $params['order_max_amount'] != '') {
            $order_max_amount = $params['order_max_amount'];
        }elseif (!empty($params['order_max_amount']) && !isset($params['post_search'])) {
            $order_max_amount = $params['order_max_amount'];
        }else {
            $order_max_amount = '';
        }

    // searching
        $this->view->show_time = $values['show_time'] = $show_time;
        $this->view->credit_type = $values['credit_type'] = $credit_type;
        $this->view->starttime = $values['from']= $creation_date_start;
        $this->view->endtime=$values['to'] = $creation_date_end;
        $this->view->order_min_amount = $values['order_min_amount'] = $order_min_amount;
        $this->view->order_max_amount = $values['order_max_amount'] = $order_max_amount;

        if (!empty($this->view->starttime)) {
            $values['from'] = $this->view->starttime . ' 00:00:00';
        }
        if (!empty($credit_type)) {
            $select->where($creditTableName . '.type  LIKE ?', '%' . trim($credit_type) . '%');
        }
        if (!empty($show_time)) {
            switch ($show_time) {
                case 'day':  $select->where("CAST($creditTableName.creation_date AS DATE)=?",$raw);
                break;
                case 'weekly':    $select->where("$creditTableName.creation_date >= DATE(NOW()) - INTERVAL 7 DAY");
                break;
                case 'range':   if (!empty($creation_date_start)) {
                      $select->where("CAST($creditTableName.creation_date AS DATE) >=?", trim($creation_date_start));
                        }
                                if (!empty($creation_date_end)) {
                      $select->where("CAST($creditTableName.creation_date AS DATE) <=?", trim($creation_date_end));
                        } 
                break;
            }
        }


        if ($order_min_amount != '') {
            $select->where("$creditTableName.credit_point >=?", trim($order_min_amount));
        }

        if ($order_max_amount != '') {
            $select->where("$creditTableName.credit_point <=?", trim($order_max_amount));
        }

        $values = array_merge(array(
          'order' => 'creation_date',
          'order_direction' => 'DESC',
        ), $values);


        $this->view->formValues = array_filter($values); 
        $this->view->assign($values);


        $select->order((!empty($values['order']) ? $values['order'] : 'creation_date' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
    //MAKE PAGINATOR
        $this->view->paginator = $paginator= Zend_Paginator::factory($select);
        $this->view->paginator->setItemCountPerPage($params['itemCountPerPage']);
        $this->view->paginator = $this->view->paginator->setCurrentPageNumber($page);

        $this->view->totalCount = $paginator->getTotalItemCount();
  
    }

}


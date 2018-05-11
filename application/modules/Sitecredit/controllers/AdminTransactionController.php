<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminTransactionController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_AdminTransactionController extends Core_Controller_Action_Admin {

    public function init() {
        $this->view->creditTypeArray = $GLOBALS['sitecredit_creditType'];
    }

    public function indexAction() {
        // show all transaction carried out related to caredits 
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_transaction');
        $this->view->navigationSubMenu = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main_transaction', array(), 'sitecredit_admin_main_transaction_allmembers');
        $this->view->formFilter = $formFilter = new Sitecredit_Form_Admin_Filter();
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
        $raw = date('Y-m-d', $now);

        $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
        $this->view->pageno = $page = $this->_getParam('page', 1);
        $creditTable = Engine_Api::_()->getItemTable('credit');
        $creditTableName = $creditTable->info('name');

        $selectQuery = $creditTable->select()->group('type');
        $this->view->result = $result = $creditTable->fetchAll($selectQuery);

        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');

        $validity = Engine_Api::_()->getDbtable('credits', 'sitecredit')->validityCheck();

        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');

        $select = $creditTable->select()->setIntegrityCheck(false);
        $select->from($creditTableName)
                ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));
        $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id', array("$userTableName.displayname"));

        $select->where('DATE_ADD(start_date, INTERVAL ' . $validity . ' MONTH) >' . $creditTableName . '.creation_date')
                ->where($creditTableName . '.creation_date > start_date');

        if (!empty($_POST['show_time'])) {
            $show_time = $_POST['show_time'];
        } elseif (!empty($_GET['show_time']) && !isset($_POST['post_search'])) {
            $show_time = $_GET['show_time'];
        } else {
            $show_time = '';
        }

        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (!empty($_GET['username']) && !isset($_POST['post_search'])) {
            $username = $_GET['username'];
        } else {
            $username = '';
        }

        if (!empty($_POST['credit_type'])) {
            $credit_type = $_POST['credit_type'];
        } elseif (!empty($_GET['credit_type']) && !isset($_POST['post_search'])) {
            $credit_type = $_GET['credit_type'];
        } else {
            $credit_type = '';
        }
        if (!empty($_POST['starttime']) && !empty($_POST['starttime']['date'])) {
            $creation_date_start = $_POST['starttime']['date'];
        } elseif (!empty($_GET['starttime']['date']) && !isset($_POST['post_search'])) {
            $creation_date_start = $_GET['starttime']['date'];
        } else {
            $creation_date_start = '';
        }

        if (!empty($_POST['endtime']) && !empty($_POST['endtime']['date'])) {
            $creation_date_end = $_POST['endtime']['date'];
        } elseif (!empty($_GET['endtime']['date']) && !isset($_POST['post_search'])) {
            $creation_date_end = $_GET['endtime']['date'];
        } else {
            $creation_date_end = '';
        }

        if (!empty($_POST['from'])) {
            $creation_date_start = $_POST['from'];
        } elseif (!empty($_GET['from']) && !isset($_POST['post_search'])) {
            $creation_date_start = $_GET['from'];
        }

        if (!empty($_POST['to'])) {
            $creation_date_end = $_POST['to'];
        } elseif (!empty($_GET['to']) && !isset($_POST['post_search'])) {
            $creation_date_end = $_GET['to'];
        }

        if (isset($_POST['order_min_amount']) && $_POST['order_min_amount'] != '') {
            $order_min_amount = $_POST['order_min_amount'];
        } elseif (!empty($_GET['order_min_amount']) && !isset($_POST['post_search'])) {
            $order_min_amount = $_GET['order_min_amount'];
        } else {
            $order_min_amount = '';
        }

        if (isset($_POST['order_max_amount']) && $_POST['order_max_amount'] != '') {
            $order_max_amount = $_POST['order_max_amount'];
        } elseif (!empty($_GET['order_max_amount']) && !isset($_POST['post_search'])) {
            $order_max_amount = $_GET['order_max_amount'];
        } else {
            $order_max_amount = '';
        }

        // searching
        $this->view->show_time = $values['show_time'] = $show_time;
        $this->view->username = $values['username'] = $username;
        $this->view->credit_type = $values['credit_type'] = $credit_type;
        $this->view->starttime = $values['from'] = $creation_date_start;
        $this->view->endtime = $values['to'] = $creation_date_end;
        $this->view->order_min_amount = $values['order_min_amount'] = $order_min_amount;
        $this->view->order_max_amount = $values['order_max_amount'] = $order_max_amount;
        if (!empty($username)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($username) . '%');
        }
        if (!empty($credit_type)) {
            $select->where($creditTableName . '.type  LIKE ?', '%' . trim($credit_type) . '%');
        }

        if (!empty($show_time)) {
            switch ($show_time) {
                case 'day': $select->where("CAST($creditTableName.creation_date AS DATE)=?", $raw);
                    break;
                case 'weekly': $select->where("$creditTableName.creation_date >= DATE(NOW()) - INTERVAL 7 DAY");
                    break;
                case 'range': if (!empty($creation_date_start)) {
                        $select->where("CAST($creditTableName.creation_date AS DATE) >=?", trim($creation_date_start));
                    }
                    if (!empty($creation_date_end)) {
                        $select->where("CAST($creditTableName.creation_date AS DATE) <=?", trim($creation_date_end));
                    } break;
            }
        }
        if ($order_min_amount != '') {
            $select->where("$creditTableName.credit_point >=?", trim($order_min_amount));
        }

        if ($order_max_amount != '') {
            $select->where("$creditTableName.credit_point <=?", trim($order_max_amount));
        }
        $values = array_merge(array(
            'order' => 'credit_id',
            'order_direction' => 'DESC',
                ), $values);

        $this->view->assign($values);

        $select->order((!empty($values['order']) ? $values['order'] : 'credit_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        $this->view->formValues = array_filter($values);

        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function viewAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');
        // Check post
        $this->view->result = $credit = Engine_Api::_()->getItem('credit', $id);
    }

}

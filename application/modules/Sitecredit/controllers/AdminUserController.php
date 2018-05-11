<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminUserController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_AdminUserController extends Core_Controller_Action_Admin {

    public function init() {
        $this->view->creditTypeArray = $GLOBALS['sitecredit_creditType'];
    }

    public function indexAction() {
        // display credit details of all users
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_transaction');
        $this->view->navigationSubMenu = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main_transaction', array(), 'sitecredit_admin_main_transaction_specificmembers');
        $this->view->pageno = $page = $this->_getParam('page', 1);

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
        if (!empty($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
        } elseif (!empty($_GET['user_id']) && !isset($_POST['post_search'])) {
            $user_id = $_GET['user_id'];
        } else {
            $user_id = '';
        }

        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (!empty($_GET['username']) && !isset($_POST['post_search'])) {
            $username = $_GET['username'];
        } else {
            $username = '';
        }

        $this->view->username = $values['username'] = $username;

        $creditTable = Engine_Api::_()->getDbtable('credits', 'sitecredit');
        $this->view->validity = $validity = $creditTable->validityCheck();

        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');

        $creditTableName = $creditTable->info('name');

        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');
        $select = $creditTable->select()->setIntegrityCheck(false);

        $select->from($creditTableName, array('COUNT(' . $creditTableName . '.credit_id) as activities', "$creditTableName.user_id", 'SUM(' . $creditTableName . '.credit_point) as credit',))
                ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));
        $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id', array("$userTableName.displayname"));

        $select->where('DATE_ADD(start_date, INTERVAL ' . $validity . ' MONTH) >' . $creditTableName . '.creation_date')
                ->where($creditTableName . '.creation_date > start_date');

        $select->group("$creditTableName.user_id");

        if (!empty($username)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($username) . '%');
        }
        if (!empty($user_id)) {
            $select->where("$creditTableName.user_id = ? ", $user_id);
        }

        $values = array_merge(array(
            'order' => 'credit',
            'order_direction' => 'DESC',
                ), $values);

        $this->view->assign($values);
        $select->order((!empty($values['order']) ? $values['order'] : 'credit' ) . " " . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

        $this->view->formValues = array_filter($values);
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function sendAction() {
// display detail of credits sent to user by admin
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_user');

        $values = array();
        $this->view->formFilter = $formFilter = new Sitecredit_Form_Admin_Filter();
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
        $page = $this->_getParam('page', 1);
        $bonusTable = Engine_Api::_()->getDbtable('bonuses', 'sitecredit');
        $bonusTableName = $bonusTable->info('name');
        $select = $bonusTable->select()->setIntegrityCheck(false);

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
        if (!empty($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
        } elseif (!empty($_GET['user_id']) && !isset($_POST['post_search'])) {
            $user_id = $_GET['user_id'];
        } else {
            $user_id = '';
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
        $this->view->starttime = $values['from'] = $creation_date_start;
        $this->view->endtime = $values['to'] = $creation_date_end;
        $this->view->order_min_amount = $values['order_min_amount'] = $order_min_amount;
        $this->view->order_max_amount = $values['order_max_amount'] = $order_max_amount;

        if (!empty($username)) {
            $userTable = Engine_Api::_()->getDbTable('users', 'user');
            $userTableName = $userTable->info('name');
            $select->from($bonusTableName)->join($userTableName, $userTableName . '.user_id = ' . $bonusTableName . '.user_id', array("$userTableName.displayname"));
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($username) . '%');
        }

        if (!empty($show_time)) {
            switch ($show_time) {
                case 'day': $select->where("CAST($bonusTableName.creation_date AS DATE)=?", $raw);
                    break;
                case 'weekly': $select->where("$bonusTableName.creation_date >= DATE(NOW()) - INTERVAL 7 DAY");
                    break;
                case 'range': if (!empty($creation_date_start)) {
                        $select->where("CAST($bonusTableName.creation_date AS DATE) >=?", trim($creation_date_start));
                    }
                    if (!empty($creation_date_end)) {
                        $select->where("CAST($bonusTableName.creation_date AS DATE) <=?", trim($creation_date_end));
                    } break;
            }
        }

        if ($order_min_amount != '') {
            $select->where("$bonusTableName.credit_point >=?", trim($order_min_amount));
        }

        if ($order_max_amount != '') {
            $select->where("$bonusTableName.credit_point <=?", trim($order_max_amount));
        }
        if (!empty($user_id)) {
            $select->where("$bonusTableName.user_id = ? ", $user_id);
        }

        $values = array_merge(array(
            'order' => 'bonus_id',
            'order_direction' => 'DESC',
                ), $values);

        $this->view->assign($values);
        $select->order((!empty($values['order']) ? $values['order'] : 'bonus_id') . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        $this->view->formValues = array_filter($values);
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function creditSendAction() { // send credits to user
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer)
            return;

        $this->view->form = $form = new Sitecredit_Form_Admin_Credits_Send();

        //check post request
        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $value = $form->getValues();

            $values = array_merge($value, array(
                'owner_id' => $viewer->getIdentity(),
            ));

            if ($values['member'] == 1) {
                $values['user_id'] = 0;
            }
            $values['creation_date'] = new Zend_Db_Expr('NOW()');
            // Create blog
            try {
                $row = Engine_Api::_()->getDbtable('bonuses', 'sitecredit')->createRow();
                $row->setFromArray($values);
                $row->save();
                $values['type_id'] = $row->bonus_id;
                Engine_Api::_()->getDbtable('credits', 'sitecredit')->insertBonusData($values);

                $form->addNotice('credit transaction is done');
            } catch (Exception $e) {
                throw $e;
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
    }

    public function getallusersAction() {
        // for suggestions of users
        $text = $this->_getParam('search');
        $user_ids = $this->_getParam('user_ids', null);
        $levelid = $this->_getParam('level_id', null);

        $limit = $this->_getParam('limit', 40);
        $tableName = Engine_Api::_()->getDbTable('users', 'user');
        try {

            $select = $tableName->select()
                    ->where('displayname  LIKE ? ', '%' . $text . '%');
            if (!empty($levelid)) {
                $select->where("level_id=?", $levelid);
            }

            if (!empty($user_ids)) {

                $select->where("user_id NOT IN ($user_ids)");
            }
            $select->order('displayname ASC')
                    ->limit($limit);

            $userObjects = $tableName->fetchAll($select);

            $data = array();
            //FETCH RESULTS

            foreach ($userObjects as $users) {
                $data[] = array(
                    'id' => $users->user_id,
                    'label' => $users->getTitle(),
                    'photo' => $this->view->itemPhoto($users, 'thumb.icon'),
                );
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $this->_helper->json($data);
    }

    public function viewAction() {  // show credit details of each user individually
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $user_id = $this->_getParam('id');
        // Check post

        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $selectUser = $userTable->select()->where('user_id=?', $user_id);
        $this->view->user = $userTable->fetchRow($selectUser);

        $param['user_id'] = $user_id;
        $param['basedon'] = 0;
        $param['count'] = 1;
        $credits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param);
        $totalCredits = $credits->credit;

        $this->view->totalCredits = $totalCredits;

        $checkValidity = Engine_Api::_()->getDbtable('credits', 'sitecredit')->validityCheck();

        $validityTable = Engine_Api::_()->getDbTable('validities', 'sitecredit');
        $select = $validityTable->select()->where('user_id=?', $user_id);
        $validityuser = $validityTable->fetchRow($select);

        if (empty($validityuser)) {
            Engine_Api::_()->getDbtable('validities', 'sitecredit')->insertvalidity();
            $validityuser = $validityTable->fetchRow($select);
            $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
        } else {
            $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
            while ($validityDate < date('Y-m-d h:m:s')) {
                Engine_Api::_()->getDbtable('validities', 'sitecredit')->updateValidity($validityDate);
                $validityuser = $validityTable->fetchRow($select);
                $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
            }
        }
        $this->view->validityDate = $validityDate;
        // calculate remaining days for validity if less than 60 days show message to user.
        $now = time(); // or your date as well
        $your_date = strtotime($validityDate);
        $datediff = $your_date - $now;
        $this->view->validityDays = $validityDays = floor($datediff / (60 * 60 * 24));

        $this->view->result = $result = Engine_Api::_()->getDbtable('credits', 'sitecredit')->CreditsActivityType($param);
        $this->view->rawData = Engine_Api::_()->getDbtable('credits', 'sitecredit')->getCreditByTypeID($param);
    }

    public function reasonViewAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $bonus_id = $this->_getParam('id');
        // Check post
        $bonusTable = Engine_Api::_()->getDbtable('bonuses', 'sitecredit');
        $select = $bonusTable->select()->where('bonus_id = ?', $bonus_id);
        $this->view->result = $result = $bonusTable->fetchRow($select);
    }

}

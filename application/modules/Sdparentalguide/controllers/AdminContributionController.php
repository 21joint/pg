<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminContributionController extends Core_Controller_Action_Admin
{
    public function init() {
        $this->view->creditTypeArray = $GLOBALS['sitecredit_creditType'];
    }
    public function indexAction() {
        // show all transaction carried out related to caredits 
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_contribution');
        $this->view->navigationSubMenu = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_contribution', array(), 'sdparentalguide_admin_main_contribution_transaction');

        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Contribution_FilterTransaction();
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
        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');

        $select = $creditTable->select()->setIntegrityCheck(false);
        $select->from($creditTableName);
        $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id', array("$userTableName.displayname","$userTableName.username","$userTableName.email"
                ,"$userTableName.level_id"));
        
        //Calculate only when validity is enabled. It would increase speed.
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        if($coreSettings->getSetting('sitecredit.validity',0)){
            $validity = Engine_Api::_()->getDbtable('credits', 'sitecredit')->validityCheck();
            $select->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));
            $select->where('DATE_ADD(start_date, INTERVAL ' . $validity . ' MONTH) >' . $creditTableName . '.creation_date')
                    ->where($creditTableName . '.creation_date > start_date');
        }
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
        
        if (!empty($_POST['firstname'])) {
            $firstname = $_POST['firstname'];
        } elseif (!empty($_GET['firstname']) && !isset($_POST['post_search'])) {
            $firstname = $_GET['firstname'];
        } else {
            $firstname = '';
        }

        if (!empty($_POST['lastname'])) {
            $lastname = $_POST['lastname'];
        } elseif (!empty($_GET['lastname']) && !isset($_POST['post_search'])) {
            $lastname = $_GET['lastname'];
        } else {
            $lastname = '';
        }
        
        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
        } elseif (!empty($_GET['email']) && !isset($_POST['post_search'])) {
            $email = $_GET['email'];
        } else {
            $email = '';
        }
        
        if (!empty($_POST['memberlevel'])) {
            $memberlevel = $_POST['memberlevel'];
        } elseif (!empty($_GET['memberlevel']) && !isset($_POST['post_search'])) {
            $memberlevel = $_GET['memberlevel'];
        } else {
            $memberlevel = '';
        }
        
        if (!empty($_POST['credit_type'])) {
            $credit_type = $_POST['credit_type'];
        } elseif (!empty($_GET['credit_type']) && !isset($_POST['post_search'])) {
            $credit_type = $_GET['credit_type'];
        } else {
            $credit_type = '';
        }
        
        if (!empty($_POST['topic'])) {
            $topic = $_POST['topic'];
        } elseif (!empty($_GET['topic']) && !isset($_POST['post_search'])) {
            $topic = $_GET['topic'];
        } else {
            $topic = '';
        }
        
        if (!empty($_POST['starttime']) && !empty($_POST['starttime']['date'])) {
//            $creation_date_start = $_POST['starttime']['date'];
            $creation_date_start = date('Y-m-d',strtotime($_POST['starttime']['date']));
        } elseif (!empty($_GET['starttime']['date']) && !isset($_POST['post_search'])) {
//            $creation_date_start = $_GET['starttime']['date'];
            $creation_date_start = date('Y-m-d',strtotime($_POST['starttime']['date']));
        } else {
            $creation_date_start = '';
        }

        if (!empty($_POST['endtime']) && !empty($_POST['endtime']['date'])) {
//            $creation_date_end = $_POST['endtime']['date'];
            $creation_date_end = date('Y-m-d',strtotime($_POST['endtime']['date']));
        } elseif (!empty($_GET['endtime']['date']) && !isset($_POST['post_search'])) {
//            $creation_date_end = $_GET['endtime']['date'];
            $creation_date_end = date('Y-m-d',strtotime($_POST['endtime']['date']));
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

        // searching
        $this->view->show_time = $values['show_time'] = $show_time;
        $this->view->username = $values['username'] = $username;
        $this->view->firstname = $values['firstname'] = $firstname;
        $this->view->lastname = $values['lastname'] = $lastname;
        $this->view->email = $values['email'] = $email;
        $this->view->memberlevel = $values['memberlevel'] = $memberlevel;
        $this->view->topic = $values['topic'] = $topic;
        $this->view->credit_type = $values['credit_type'] = $credit_type;
        $this->view->starttime = $values['from'] = $creation_date_start;
        $this->view->endtime = $values['to'] = $creation_date_end;

        if (!empty($username)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($username) . '%');
        }
        if (!empty($firstname)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($firstname) . '%');
        }
        if (!empty($lastname)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($lastname) . '%');
        }
        if (!empty($email)) {
            $select->where($userTableName . '.email  = ?', $email);
        }
        if (!empty($memberlevel)) {
            $select->where($userTableName . '.level_id  = ?', $memberlevel);
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

        $values = array_merge(array(
            'order' => 'credit_id',
            'order_direction' => 'DESC',
                ), $values);
        
        $this->view->assign($values);
        if($values['order'] == 'lastname'){
            $select->order(SUBSTR("displayname, INSTR(displayname, ' ') ".(!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' )));
        }else{
            $select->order((!empty($values['order']) ? $values['order'] : 'credit_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        }
        
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);

        $this->view->formValues = array_filter($values);

//        include_once APPLICATION_PATH . '/application/modules/Sdparentalguide/controllers/license/license2.php';
    }

    public function viewAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');
        // Check post
        $this->view->result = $credit = Engine_Api::_()->getItem('credit', $id);
    }
    
    public function membersAction() {
        // display credit details of all users
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_contribution');
        $this->view->navigationSubMenu = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_contribution', array(), 'sdparentalguide_admin_main_contribution_members');
        $this->view->pageno = $page = $this->_getParam('page', 1);

        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Contribution_FilterMemberTransaction();
        $values = array();
        if ($formFilter->isValid($this->_getAllParams())) {
            $values = $formFilter->getValues();
        }

        foreach ($values as $key => $value) {
            if (null === $value) {
                unset($values[$key]);
            }
        }
        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (!empty($_GET['username']) && !isset($_POST['post_search'])) {
            $username = $_GET['username'];
        } else {
            $username = '';
        }
        
        if (!empty($_POST['firstname'])) {
            $firstname = $_POST['firstname'];
        } elseif (!empty($_GET['firstname']) && !isset($_POST['post_search'])) {
            $firstname = $_GET['firstname'];
        } else {
            $firstname = '';
        }

        if (!empty($_POST['lastname'])) {
            $lastname = $_POST['lastname'];
        } elseif (!empty($_GET['lastname']) && !isset($_POST['post_search'])) {
            $lastname = $_GET['lastname'];
        } else {
            $lastname = '';
        }
        
        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
        } elseif (!empty($_GET['email']) && !isset($_POST['post_search'])) {
            $email = $_GET['email'];
        } else {
            $email = '';
        }
        
        if (!empty($_POST['memberlevel'])) {
            $memberlevel = $_POST['memberlevel'];
        } elseif (!empty($_GET['memberlevel']) && !isset($_POST['post_search'])) {
            $memberlevel = $_GET['memberlevel'];
        } else {
            $memberlevel = '';
        }
        
        if (!empty($_POST['topic'])) {
            $topic = $_POST['topic'];
        } elseif (!empty($_GET['topic']) && !isset($_POST['post_search'])) {
            $topic = $_GET['topic'];
        } else {
            $topic = '';
        }
        
        $this->view->username = $values['username'] = $username;
        $this->view->firstname = $values['firstname'] = $firstname;
        $this->view->lastname = $values['lastname'] = $lastname;
        $this->view->email = $values['email'] = $email;
        $this->view->memberlevel = $values['memberlevel'] = $memberlevel;
        $this->view->topic = $values['topic'] = $topic;
        
        $creditTable = Engine_Api::_()->getDbtable('credits', 'sitecredit');
        $this->view->validity = $validity = $creditTable->validityCheck();

        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');

        $creditTableName = $creditTable->info('name');

        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');
        $select = $creditTable->select()->setIntegrityCheck(false);

//        $select->from($creditTableName, array('COUNT(' . $creditTableName . '.credit_id) as activities', "$creditTableName.user_id", 'SUM(' . $creditTableName . '.credit_point) as credit',))
//                ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));

        $select->from($creditTableName, array("$creditTableName.user_id",))
                ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));
        
        $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id', array("$userTableName.displayname","$userTableName.username", "$userTableName.email" ,"$userTableName.level_id"));
        
        $select->where('DATE_ADD(start_date, INTERVAL ' . $validity . ' MONTH) >' . $creditTableName . '.creation_date')
                ->where($creditTableName . '.creation_date > start_date');
        
        $select->group("$creditTableName.user_id");
        
        if (!empty($username)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($username) . '%');
        }
        
        if (!empty($firstname)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($firstname) . '%');
        }
        if (!empty($lastname)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($lastname) . '%');
        }
        if (!empty($email)) {
            $select->where($userTableName . '.email  = ?', $email);
        }
        if (!empty($memberlevel)) {
            $select->where($userTableName . '.level_id  = ?', $memberlevel);
        }

        $values = array_merge(array(
            'order' => 'credit_id',
            'order_direction' => 'DESC',
                ), $values);
        
        $this->view->assign($values);
        if($values['order'] == 'lastname'){
            $select->order(SUBSTR("displayname, INSTR(displayname, ' ') ".(!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' )));
        }elseif($values['order'] == 'credit'){
            $select->order('credit_id'. " " . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        }else{
            $select->order((!empty($values['order']) ? $values['order'] : 'credit_id' ) . " " . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        }

        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        
        $this->view->formValues = array_filter($values);
//        include_once APPLICATION_PATH . '/application/modules/Sdparentalguide/controllers/license/license2.php';
    }
}
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminLevelController extends Core_Controller_Action_Admin {
    public function manageAction() {
        // get all badges 
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_levels');

//        $this->view->navigationSubMenu = $navigation = Engine_Api::_()->getApi('menus', 'core')
//                ->getNavigation('sitecredit_admin_main_badge', array(), 'sitecredit_admin_badge_manage');
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
        //get data from table
        $badgeEntry = Engine_Api::_()->getDbtable('badges', 'sitecredit')->fetchAll();
        $this->view->badgeAvailable = $badgeEntry->toArray();

        $page = $this->_getParam('page', 5);
        $BadgeTable = Engine_Api::_()->getItemTable('badge');
        $BadgeTableName = $BadgeTable->info('name');
        $select = $BadgeTable->select();

        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (!empty($_GET['username']) && !isset($_POST['post_search'])) {
            $username = $_GET['username'];
        } else {
            $username = '';
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

        $this->view->username = $values['username'] = $username;
        $this->view->order_min_amount = $values['order_min_amount'] = $order_min_amount;
        $this->view->order_max_amount = $values['order_max_amount'] = $order_max_amount;
        if (!empty($username)) {
            $select->where($BadgeTableName . '.title  LIKE ?', '%' . trim($username) . '%');
        }

        if ($order_min_amount != '') {
            $select->where("$BadgeTableName.credit_count >=?", trim($order_min_amount));
        }

        if ($order_max_amount != '') {
            $select->where("$BadgeTableName.credit_count <=?", trim($order_max_amount));
        }
        $values = array_merge(array(
            'order' => 'badge_id',
            'order_direction' => 'DESC',
                ), $values);
        $this->view->formValues = array_filter($values);
        $this->view->assign($values);
        $select->order((!empty($values['order']) ? $values['order'] : 'badge_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(500);
    }

    public function addBadgeAction() {
        // add badge 
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Badge_Addbadge();

        if (!$this->_helper->requireUser()->checkRequire()) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Max file size limit exceeded (probably).");
            return;
        }
        // check post request
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $photo = $values['photo'];

            if (empty($_POST['credit_count']) || (!empty($_POST['credit_count']) && $_POST['credit_count'] < 1 )) {
                $error = $this->view->translate('Please enter valid credit values');
                $error = Zend_Registry::get('Zend_Translate')->_($error);
                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);
                return;
            }

            $values['file_id'] = Engine_Api::_()->getItemTable('badge')->setPhoto($form->photo);

            $table = Engine_Api::_()->getDbtable('badges', 'sitecredit');
            $values['creation_date'] = date('Y-m-d H : i : s');
            $values['modified_date'] = date('Y-m-d H : i : s');
            try {
                $row = $table->createRow();
                $row->setFromArray($values);
                $row->save();
                $form->addNotice('Badge has been saved');
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

    public function deleteAction() {
        //delete a badge
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');
        $this->view->badge_id = $id;
        // Check post
        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $offer = Engine_Api::_()->getItem('badge', $id);
                // delete the blog entry into the database
                $offer->delete();
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
    }

    public function editAction() {
        // edit badge details
        if (!$this->_helper->requireUser()->isValid())
            return;

        $viewer = Engine_Api::_()->user()->getViewer();
        $badge = Engine_Api::_()->getItem('badge', $this->_getParam('id'));

        $this->view->form = $form = new Sdparentalguide_Form_Admin_Badge_Addbadge();

        $form->setTitle('Edit Badge')->setDescription('Here, admin can edit Badge');
        // get uploaded image
        $ImageValue = "";
        if ($badge) {
            $url = Engine_Api::_()->storage()->get($badge->file_id, '')->getPhotoUrl();
            $urlArr = explode("/", $url);
            $Image_name = explode("?c=", $urlArr[count($urlArr) - 1]);
            $ImageValue = "[ Uploaded file " . $Image_name[0] . " ] <br /><img src='$url' width=70px height=70px />";
        }
        $form->photo->setDescription($ImageValue);
        $form->photo->getDecorator('Description')->setOption('escape', false);
        $form->photo->setOptions(array('required' => false, 'allowEmpty' => true));
        $form->submit->setOptions(array('label' => 'Edit Badge'));
        // Populate form
        $formbadge = $badge->toArray();
        $form->populate($formbadge);

        $this->view->item = $badge;

        // Check post/form
        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        if (empty($_POST['credit_count']) || (!empty($_POST['credit_count']) && $_POST['credit_count'] < 1 )) {
            $error = $this->view->translate('Please enter valid credit values');
            $error = Zend_Registry::get('Zend_Translate')->_($error);
            $form->getDecorator('errors')->setOption('escape', false);
            $form->addError($error);
            return;
        }
        // Process
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            $values = $form->getValues();

            $photo = $values['photo'];
            if (!empty($values['photo']))
                $values['file_id'] = Engine_Api::_()->getItemTable('badge')->setPhoto($form->photo);

            $badge->setFromArray($values);
            $badge->modified_date = date('Y-m-d H:i:s');
            $badge->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
    }

    public function viewDetailAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');

        $this->view->badge = $badge = Engine_Api::_()->getItem('badge', $id);
    }

}

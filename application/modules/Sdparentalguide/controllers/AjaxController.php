<?php

class Sdparentalguide_AjaxController extends Core_Controller_Action_Standard
{

    public function init() {

        $type = $this->_getParam('type');
        $identity = $this->_getParam('id');
        $action = $this->_getParam('action');

        if( $type && $identity && $action) {
            $item = Engine_Api::_()->getItem($type, $identity);
            if( $item instanceof Core_Model_Item_Abstract ) {
                if( !Engine_Api::_()->core()->hasSubject() ) {
                    Engine_Api::_()->core()->setSubject($item);
                }
            }
        }
        $this->_helper->requireUser();
    }

    public function userPrivacyAction() {
        
        $viewer = Engine_Api::_()->user()->getViewer();
        $subject = Engine_Api::_()->core()->getSubject();

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }

        if(!$subject->isSelf($viewer)) return;

        $type = $this->_getParam('type', null);
        $user_id = $this->_getParam('id', null);

        // setup privacy for public
        $searchPrivacy = $subject->search;
        ($searchPrivacy == 1) ? $subject->search = 0 : $subject->search = 1;

        $subject->save();

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('User have been saved.');

    }

    public function passwordAction() {

        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();

        if( !$this->getRequest()->isPost() ){
            return;
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        
        // setup form
        $form = new User_Form_Settings_Password();

        // setup values
        $values = $request->getParam('values', null);
        foreach($values as $key => $value) {
            $form->getElement($key)->setValue($value);
        }

        // if form is valid
        if( !$form->isValid( $values ) ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Something went wrong.');
            return;
        }

        // Check conf
        if( $form->getValue('passwordConfirm') !== $form->getValue('password') ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Passwords did not match.');
            return;
        }
        
        // Process form
        $userTable = Engine_Api::_()->getItemTable('user');
        $db = $userTable->getAdapter();

        // Check old password
        $salt = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.secret', 'staticSalt');
        $select = $userTable->select()
            ->from($userTable, new Zend_Db_Expr('TRUE'))
            ->where('user_id = ?', $viewer->getIdentity())
            ->where('password = ?', new Zend_Db_Expr(sprintf('MD5(CONCAT(%s, %s, salt))', $db->quote($salt), $db->quote($form->getValue('oldPassword')))))
            ->limit(1)
        ;
        $valid = $select
            ->query()
            ->fetchColumn()
        ;

        if( !$valid ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Old password did not match.');
            return;
        }

        // Save
        $db->beginTransaction();

        try {

            $viewer->setFromArray($form->getValues());
            $viewer->save();

            $db->commit();
        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
        }

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Settings were successfully saved.');

    }

    public function deleteAction() {

        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }

        if( !$this->_helper->requireAuth()->setAuthParams($viewer, null, 'delete')->isValid() ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('You are not able to delete your account.');
            return;
        };

        if( $viewer->level_id === 1 ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Super Admins can\'t be deleted.');
            return;
        }
        
        // Process
        $db = Engine_Api::_()->getDbtable('users', 'user')->getAdapter();
        $db->beginTransaction();

        try {
            $viewer->delete();
        
            $db->commit();
        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
        }

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your account have been deleted.');

        // Unset viewer, remove auth, clear session
        Engine_Api::_()->user()->setViewer(null);

    }

    public function editProfileAction() {

        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        
        // General form w/o profile type
        $aliasedFields = $viewer->fields()->getFieldsObjectsByAlias();
        $this->view->topLevelId = $topLevelId = 0;
        $this->view->topLevelValue = $topLevelValue = null;
        if (isset($aliasedFields['profile_type'])) {
            $aliasedFieldValue = $aliasedFields['profile_type']->getValue($viewer);
            $topLevelId = $aliasedFields['profile_type']->field_id;
            $topLevelValue = (is_object($aliasedFieldValue) ? $aliasedFieldValue->value : null);
            if (!$topLevelId || !$topLevelValue) {
                $topLevelId = null;
                $topLevelValue = null;
            }
            $this->view->topLevelId = $topLevelId;
            $this->view->topLevelValue = $topLevelValue;
        }

        // Get form
        $form = new Fields_Form_Standard(array(
            'item' => $viewer,
            'topLevelId' => $topLevelId,
            'topLevelValue' => $topLevelValue,
            'hasPrivacy' => true,
            'privacyValues' => $this->getRequest()->getParam('privacy'),
        ));

        // setup values
        $values = $request->getParam('values', null);
        foreach($values as $key => $value) {
            if($key != 'submit') {
                $element = $form->getElement($key);
                if($element) {
                    $element->setValue($value);
                }
            }
        }

        if ( $form->isValid($values) ) {
            
            $form->saveValues();

            // Update display name
            $aliasValues = Engine_Api::_()->fields()->getFieldsValuesByAlias($viewer);
            $viewer->setDisplayName($aliasValues);
            $viewer->save();

            // update networks
            Engine_Api::_()->network()->recalculate($viewer);

            $this->view->status = true;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Profile have been updated.');
            return;
            
        }

        $this->view->status = false;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Something went wrong.');

    }

    public function notificationsAction() {

        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();

        // Build the different notification types
        $modules = Engine_Api::_()->getDbtable('modules', 'core')->getModulesAssoc();
        $notificationTypes = Engine_Api::_()->getDbtable('notificationTypes', 'activity')->getNotificationTypes();
        $notificationSettings = Engine_Api::_()->getDbtable('notificationSettings', 'activity')->getEnabledNotifications($viewer);

        $notificationTypesAssoc = array();
        $notificationSettingsAssoc = array();
        foreach( $notificationTypes as $type ) {
            if( isset($modules[$type->module]) ) {
                $category = 'ACTIVITY_CATEGORY_TYPE_' . strtoupper($type->module);
                $translateCategory = Zend_Registry::get('Zend_Translate')->_($category);
                if( $translateCategory === $category ) {
                    $elementName = preg_replace('/[^a-zA-Z0-9]+/', '_', $type->module);
                    $category = $modules[$type->module]->title;
                } else {
                    $elementName = preg_replace('/[^a-zA-Z0-9]+/', '_', strtolower($translateCategory));
                }
            } else {
                $elementName = 'misc';
                $category = 'Misc';
            }

            $notificationTypesAssoc[$elementName]['category'] = $category;
            $notificationTypesAssoc[$elementName]['types'][$type->type] = 'ACTIVITY_TYPE_' . strtoupper($type->type);

            if( in_array($type->type, $notificationSettings) ) {
                $notificationSettingsAssoc[$elementName][] = $type->type;
            }
        }

        ksort($notificationTypesAssoc);

        $notificationTypesAssoc = array_filter(array_merge(array(
            'general' => array(),
            'misc' => array(),
        ), $notificationTypesAssoc));

        // Make form
        $form = new Engine_Form(array(
            'title' => 'Notification Settings',
            'description' => 'Which of the these do you want to receive email alerts about?',
        ));

        foreach( $notificationTypesAssoc as $elementName => $info ) {
            $form->addElement('MultiCheckbox', $elementName, array(
                'label' => $info['category'],
                'multiOptions' => $info['types'],
                'value' => (array) @$notificationSettingsAssoc[$elementName],
            ));
        }

        // setup values
        $values = $request->getParam('values', null);
       
        $publishTypes = array();

        foreach($values as $key => $value) {
            if($value['value'] === 'true') {
                array_push($publishTypes, $value['name']);
            }
        }

        // Set notification setting
        Engine_Api::_()->getDbtable('notificationSettings', 'activity')
        ->setEnabledNotifications($viewer, $publishTypes);

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Profile have been updated.');
        

    }

    public function privacyAction() {

        $this->_helper->ViewRenderer->setNoRender(true);
        $viewer = Engine_Api::_()->user()->getViewer();
        $request = Zend_Controller_Front::getInstance()->getRequest();

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }

        // setup values
        $values = $request->getParam('values', null);
        $publishTypes = array(
            'publishTypes' => array()
        );

        foreach($values as $key => $value) {
            if($value['key'] == 'publishTypes[]' && $value['value'] === 'true') {
                array_push($publishTypes['publishTypes'], $value['name']);
            } else if( $value['key'] != 'publishTypes[]') {
                if($value['key'] == 'search')
                    $publishTypes[$value['key']] = $value['value'] == 'true' ? '1' : '0';
                else
                    $publishTypes[$value['key']] = $value['name'];
            }
        }

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $auth = Engine_Api::_()->authorization()->context;

        $this->view->form = $form = new User_Form_Settings_Privacy(array(
            'item' => $viewer,
        ));

        // Populate form
        $form->populate( $viewer->toArray() );

        // Set up activity options
        $defaultPublishTypes = array('post', 'signup', 'status');
        if( $form->getElement('publishTypes') ) {
            $actionTypes = Engine_Api::_()->getDbtable('actionTypes', 'activity')->getEnabledActionTypesAssoc();
        foreach( $defaultPublishTypes as $key ) {
            unset($actionTypes[$key]);
        }

        foreach( array_keys($actionTypes) as $key ) {
            if( substr($key, 0, 5) == 'post_' ) {
                $defaultPublishTypes[] = $key;
                unset($actionTypes[$key]);
            }
        }

        $form->publishTypes->setMultiOptions($actionTypes);
        $actionTypesEnabled = Engine_Api::_()->getDbtable('actionSettings', 'activity')                    ->getEnabledActions($viewer);
            $form->publishTypes->setValue($actionTypesEnabled);
        }

        foreach($publishTypes as $key => $value) {
            if( $key != 'publishTypes[]' && $element = $form->getElement($key) )
                $element->setValue($value);
        }
        
        $form->save();

        $values = $form->getValues();
        $viewer->search = $values['search'];
        $viewer->save();


        // Update notification settings
        if( $form->getElement('publishTypes') ) {
            $publishTypes = array_merge($form->publishTypes->getValue(), $defaultPublishTypes);
            Engine_Api::_()->getDbtable('actionSettings', 'activity')->setEnabledActions($viewer, (array) $publishTypes);
        }
        
        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Profile have been updated.');
        

    }

    public function preferenceAction(){

        
        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();
    
        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $form = new Sdparentalguide_Form_Signup_Interests();

        $values = $request->getParam('values', null);

        $publishTypes = array();
        
        $i = 0;
        foreach($values as $key => $value) {
            $publishTypes['categories'][$i] = $value['name'];
            $i++;
        }
        
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            
            $prefTable = Engine_Api::_()->getDbTable("preferences","sdparentalguide");
            $catTable = Engine_Api::_()->getDbTable("categories","sitereview");

            $categories = $publishTypes['categories'];
            $categories = $catTable->fetchAll($catTable->select()->where('category_id IN (?)',$categories));
            if(count($categories) <= 0){
                return;
            }

            $prefTable->delete(array(
                'user_id = ?' => $viewer->getIdentity(),
                'category_id NOT IN(?)' => $categories
            ));
            foreach($categories as $category){
                $prefParams = array(
                    'user_id' => $viewer->getIdentity(),
                    'listingtype_id' => $category->listingtype_id,
                    'category_id' => $category->category_id
                );
                $prefRow = $prefTable->fetchRow($prefTable->select()->where('user_id = ?',$viewer->getIdentity())
                        ->where("listingtype_id = ?",$category->listingtype_id)->where("category_id = ?",$category->category_id));
                if(empty($prefRow)){
                    $prefRow = $prefTable->createRow();
                }                
                $prefRow->setFromArray($prefParams);
                $prefRow->save();        
            }

            $this->view->status = true;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Profile have been updated.');
            
            $db->commit();

        }catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }

        
    }

}

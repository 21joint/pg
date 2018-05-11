<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_PreferencesController extends Core_Controller_Action_Standard
{
    public function init(){
        if (!Engine_Api::_()->core()->hasSubject()) {
            // Can specifiy custom id
            $id = $this->_getParam('id', null);
            $subject = null;
            if (null === $id) {
                $subject = Engine_Api::_()->user()->getViewer();
                Engine_Api::_()->core()->setSubject($subject);
            } else {
                $subject = Engine_Api::_()->getItem('user', $id);
                Engine_Api::_()->core()->setSubject($subject);
            }
        }
        
        if (!empty($id)) {
            $params = array('id' => $id);
        } else {
            $params = array();
        }
        // Set up navigation
        $this->view->navigation = $navigation = Engine_Api::_()
            ->getApi('menus', 'core')
            ->getNavigation('user_edit', array('params' => $params));
        
        $this->_helper->requireUser();
        $this->_helper->requireSubject('user');
    }
    public function editAction(){
        $this->view->user = $user = Engine_Api::_()->core()->getSubject();
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        
        $this->view->form = $form = new Sdparentalguide_Form_Signup_Interests();
        $form->removeElement("skip-link");
        $form->removeDisplayGroup("buttons");
        $form->getElement("continue")->setLabel("Save Changes");
        if(!$this->getRequest()->isPost()){
            return;
        }
                
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try{
            
            $prefTable = Engine_Api::_()->getDbTable("preferences","sdparentalguide");
            $data = $form->getValues();
            if(empty($data['categories']) || count($data['categories']) <= 0){
                return;
            }
            $categories = $data['categories'];
            $catTable = Engine_Api::_()->getDbTable("categories","sitereview");
            $categories = $catTable->fetchAll($catTable->select()->where('category_id IN (?)',$categories));
            if(count($categories) <= 0){
                return;
            }
            $prefTable->delete(array(
                'user_id = ?' => $user->getIdentity(),
                'category_id NOT IN(?)' => $categories
            ));
            foreach($categories as $category){
                $prefParams = array(
                    'user_id' => $user->getIdentity(),
                    'listingtype_id' => $category->listingtype_id,
                    'category_id' => $category->category_id
                );
                $prefRow = $prefTable->fetchRow($prefTable->select()->where('user_id = ?',$user->getIdentity())
                        ->where("listingtype_id = ?",$category->listingtype_id)->where("category_id = ?",$category->category_id));
                if(empty($prefRow)){
                    $prefRow = $prefTable->createRow();
                }                
                $prefRow->setFromArray($prefParams);
                $prefRow->save();        
            }
            
            $db->commit();
            $this->view->form = $form = new Sdparentalguide_Form_Signup_Interests();
            $form->addNotice(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved.'));
            
        } catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }
        
    }
}

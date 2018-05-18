<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Plugin_Signup_Interests extends Core_Plugin_FormSequence_Abstract
{
  protected $_name = 'fields';

  protected $_formClass = 'Sdparentalguide_Form_Signup_Interests';

  protected $_script = array('signup/form/interests.tpl', 'sdparentalguide');

  protected $_adminFormClass = 'Sdparentalguide_Form_Admin_Signup_Interests';

  protected $_adminScript = array('admin-signup/interests.tpl', 'sdparentalguide');

  public function onSubmit(Zend_Controller_Request_Abstract $request)
  {
    // Form was valid
    $skip = $request->getParam("skip");
    // do this if the form value for "skip" was not set
    // if it is set, $this->setActive(false); $this->onsubmisvalue and return true.
    if( $skip == "skipForm" ) {
      $this->setActive(false);
      $this->onSubmitIsValid();
      $this->getSession()->skip = true;
      $this->_skip = true;
      return true;
    } else {
      parent::onSubmit($request);
    }
  }
  
  public function onProcess()
  {
    // In this case, the step was placed before the account step.
    // Register a hook to this method for onUserCreateAfter
    if( !$this->_registry->user ) {
      // Register temporary hook
      Engine_Hooks_Dispatcher::getInstance()->addEvent('onUserCreateAfter', array(
        'callback' => array($this, 'onProcess'),
      ));
      return;
    }
    $user = $this->_registry->user;
    
    $prefTable = Engine_Api::_()->getDbTable("preferences","sdparentalguide");
    $data = $this->getSession()->data;
    if(empty($data['categories']) || count($data['categories']) <= 0){
        return;
    }
    $categories = $data['categories'];
    $catTable = Engine_Api::_()->getDbTable("categories","sitereview");
    $categories = $catTable->fetchAll($catTable->select()->where('category_id IN (?)',$categories));
    if(count($categories) <= 0){
        return;
    }
    foreach($categories as $category){
        $prefParams = array(
            'user_id' => $user->getIdentity(),
            'listingtype_id' => $category->listingtype_id,
            'category_id' => $category->category_id
        );
        $prefRow = $prefTable->createRow();
        $prefRow->setFromArray($prefParams);
        $prefRow->save();        
    }
    
  }

  public function onAdminProcess($form)
  {
    $step_table = Engine_Api::_()->getDbtable('signup', 'user');
    $step_row = $step_table->fetchRow($step_table->select()->where('class = ?', 'Sdparentalguide_Plugin_Signup_Interests'));
    $step_row->enable =  $form->getValue('enable');
    $step_row->save();

    $form->addNotice('Your changes have been saved.');
  }
  
}

<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Plugin_Signup_Family extends Core_Plugin_FormSequence_Abstract
{
  protected $_name = 'fields';

  protected $_formClass = 'Sdparentalguide_Form_Signup_Family';

  protected $_script = array('signup/form/family.tpl', 'sdparentalguide');

  protected $_adminFormClass = 'Sdparentalguide_Form_Admin_Signup_Family';

  protected $_adminScript = array('admin-signup/family.tpl', 'sdparentalguide');

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
 
    $data = $this->getSession()->data;

    
    // if(empty($data['members']) || count($data['members']) <= 0){
    //     return;
    // }
    


    $gender = $data['profile_gender'];
    $age = $data['profile_age_range'];

    if(empty($gender)){
      $gender = 3;
    }

    // Save values for gender and age
    $user->gg_gender = $gender;
    $user->gg_age_range = $age;
    
    
  
    //Meta for Gender
    $fieldsMeta = Engine_Api::_()->fields()->getTable('user', 'meta');
    $select = $fieldsMeta->select()
                ->where('type = ?' ,'sex')
                ->orwhere('type = ?', 'age_range');
    
    $rowMetaGender = $fieldsMeta->fetchAll($select);

    $ageId = $rowMetaGender[0]->field_id;
    $genderId = $rowMetaGender[1]->field_id;
    
    
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

    try {

        // Fields table for gender
        $table = Engine_Api::_()->fields()->getTable('user', 'values');
        $valuesGender = $table->createRow();
        $valuesGender->field_id = $genderId;
        $valuesGender->item_id = $user->getIdentity();
        $valuesGender->value = $gender;
        $valuesGender->save();
  
        // Fields table for Age
        $valueAge = $table->createRow();
        $valueAge->field_id = $ageId;
        $valueAge->item_id =$user->getIdentity();
        $valueAge->value = $age; 
        $valueAge->save();


        // User table gender
       
    } catch (Exception $ex) {
      $db->rollBack();
      throw $ex;
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

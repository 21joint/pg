<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Edit Answer
 */

class Ggcommunity_View_Helper_EditAnswer extends Zend_View_Helper_Abstract
{
  protected $_composePartials = array();

  public function editAnswer($answer)
  {
    if(!$answer) return;

    $type = $answer->getType();
    $form = new Ggcommunity_Form_Answer_Edit();
    if($type == 'ggcommunity_comment') {
      $form = new Ggcommunity_Form_Comment_Edit();
    }
    $answerValues = $answer->toArray();

    $form->body->setAttrib('id','edit_'.$type.'_body_'.$answer->getIdentity());
    $form->setAttrib('id', 'form_edit_'.$type.'_'.$answer->getIdentity());
    $form->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
      'module' => 'ggcommunity', 'controller' => 'answer', 'action' => 'edit'), 'default', true));
    $form->populate($answerValues);
    
    return $this->view->partial(
      '_edit_box.tpl', 'ggcommunity', array(
      'answer' => $answer,
      'form' => $form,
      'content' => $answer->body,
      'composePartials' => $this->getComposePartials(),
      )
    );
  }

  private function getComposePartials()
  {
    if( $this->_composePartials ) {
      return $this->_composePartials;
    }
    // Assign the composing values
    $composePartials = array();
    foreach( Zend_Registry::get('Engine_Manifest') as $data ) {
      if( empty($data['composer']) ) {
        continue;
      }
      foreach( $data['composer'] as $config ) {
        if( empty($config['allowEdit']) ) {
          continue;
        }
        if( !empty($config['auth']) && !Engine_Api::_()->authorization()->isAllowed($config['auth'][0], null, $config['auth'][1]) ) {
          continue;
        }
        $composePartials[] = $config['script'];
      }
    }
    $this->_composePartials = $composePartials;
    return $this->_composePartials;
  }
}

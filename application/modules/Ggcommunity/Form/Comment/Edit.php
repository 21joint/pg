<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Create Comment
 */
class Ggcommunity_Form_Comment_Edit extends Engine_Form
{
  
    public function init() {
        $this
            ->setAttrib('id', 'create_comment_form')
            ->setAttrib('class','global_form_front extfox-form')
        ;
        $viewer = Engine_Api::_()->user()->getViewer();
        $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);
        
        /* comment body */
        $this->addElement('Textarea', 'body', array(
            'rows' => 1,
            'allowEmpty' => false,
            'required' => true,
            'class' => 'comment_text',
            'id' =>'comment_body',
            'filters' => array(
                new Engine_Filter_Censor(),
                new Engine_Filter_Html(array('AllowedTags' => 'br'))
            ),
            'placeholder' => "Leave a comment...",
            'autofocus' => 'autofocus',
        ));

        // Element: submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Comment',
            'type' => 'submit',
            'class' => 'submit-comment  btn primary small active',
            'id' => 'add_comment',
            'decorators' => array('ViewHelper'),
        ));
  
    }
  
}

<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteforum
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 6590 2015-12-23 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteforum_Form_Topic_Create extends Engine_Form {

    public function init() {
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        $this->setMethod("POST");
        $this->setAttrib('name', 'siteforum_post_create');
        $this->addElement('Text', 'title', array(
            'label' => 'Topic Title',
            'allowEmpty' => false,
            'required' => true,
            'filters' => array(
                new Engine_Filter_Censor(),
                'StripTags',
            ),
            'validators' => array(
                array('StringLength', true, array(1, 64)),
            ),
        ));
        $this->addElement('Text', 'tags', array(
            'label' => 'Tags (Keywords)',
            'autocomplete' => 'off',
            'description' => 'Separate tags with commas.',
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
        ));
        $this->tags->getDecorator("Description")->setOption("placement", "append");
        $viewer = Engine_Api::_()->user()->getViewer();

        $allowHtml = (bool) $settings->getSetting('siteforum.html', 1);
        $allowBbcode = (bool) $settings->getSetting('siteforum.bbcode', 0);

        if (!$allowHtml) {
            $filter = new Engine_Filter_HtmlSpecialChars();
        } else {
            $filter = new Engine_Filter_Html();
            $filter->setForbiddenTags();
            $allowed_tags = array_map('trim', explode(',', Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'forum', 'commentHtml')));
            $filter->setAllowedTags($allowed_tags);
        }

        if ($allowHtml || $allowBbcode) {
            $upload_url = "";

            if (Engine_Api::_()->authorization()->isAllowed('album', $viewer, 'create')) {
                $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'upload-photo'), 'siteforum_general', true);
            }

            $options = array(
                'bbcode' => $settings->getSetting('siteforum.bbcode', 0),
                'html' => $settings->getSetting('siteforum.html', 1)
            );

            $editorOptions = array_merge($options, Engine_Api::_()->seaocore()->tinymceEditorOptions($upload_url));
             $editorOptions['height'] = '400px';
            $this->addElement('TinyMce', 'body', array(
                'disableLoadDefaultDecorators' => true,
                'required' => true,
                'editorOptions' => $editorOptions,
                'allowEmpty' => false,
                'decorators' => array('ViewHelper'),
                'filters' => array(
                    $filter,
                    new Engine_Filter_Censor(),
                )
            ));
        } else {
            $this->addElement('textarea', 'body', array(
                'required' => true,
                'attribs' => array('rows' => 24, 'cols' => 80, 'style' => 'width:553px; max-width:553px;height:158px;'),
                'allowEmpty' => false,
                'filters' => array(
                    'StripTags',
                    $filter,
                    new Engine_Filter_Censor(),
                ),
            ));
        }

        $this->addElement('Checkbox', 'watch', array(
            'label' => 'Send me notifications when other members reply to this topic.',
            'value' => '1',
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Post Topic',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
        $button_group = $this->getDisplayGroup('buttons');
        $button_group->addDecorator('DivDivDivWrapper');
    }

}

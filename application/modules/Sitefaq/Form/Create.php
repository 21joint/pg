<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Create extends Engine_Form
{
  public $_error = array();

  public function init()
  {  
		$this
		->setTitle('Create New FAQ')
		->setDescription("Create your new FAQ by filling the information below, then click ‘Submit’.")
		->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
		
		$question = Zend_Controller_Front::getInstance()->getRequest()->getParam('question', '');
		$question = urldecode($question);

		$viewer = Engine_Api::_()->user()->getViewer();
		$level_id = $viewer->level_id;

		$sitefaq_api = Engine_Api::_()->sitefaq();

		$settings = Engine_Api::_()->getApi('settings', 'core');

		$albumEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album');

		$upload_url = "";
    if(Engine_Api::_()->authorization()->isAllowed('album', $viewer, 'create') && $albumEnabled){
      $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'upload-photo'), 'sitefaq_general', true);
    }

		$editorOptions = Engine_Api::_()->seaocore()->tinymceEditorOptions($upload_url);

		$filter = new Engine_Filter_Html();
		$editorAllow = $settings->getSetting('sitefaq.editor', 1);

		$multilanguage_allow = $settings->getSetting('sitefaq.multilanguage', 0);
		$defaultLanguage = $settings->getSetting('core.locale.locale', 'en');
		$languages = $settings->getSetting('sitefaq.languages');
		$total_allowed_languages = Count($languages);

		if(empty($total_allowed_languages)) {
			$languages[$defaultLanguage] = Zend_Registry::get('Zend_Translate')->_('English');
		}

		//GET EXISTING LANGUAGES ARRAY
		$localeMultiOptions = $sitefaq_api->getLanguageArray();
		$order = -9996;
		foreach($languages as $label) {

			$lang_name = $label;
			if(isset($localeMultiOptions[$label])){
				$lang_name = $localeMultiOptions[$label];
			}

			$required_title_body = false;
			$allowEmpty_title_body = true;
			if($defaultLanguage == $label || empty($multilanguage_allow) || $total_allowed_languages == 1) {
				$required_title_body = true;
				$allowEmpty_title_body = false;
			}

			$title_field = "title_$label";
			$body_field = "body_$label";
			$title_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Question in %s"), $lang_name);
			$body_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Answer in %s"), $lang_name);

			if(empty($multilanguage_allow) || (!empty($multilanguage_allow) && $total_allowed_languages <= 1)) {
				$title_field = "title";
				$body_field = "body";
				$title_label = "Question";
				$body_label = "Answer";
			}
			elseif($label == 'en' && $total_allowed_languages > 1) {
				$title_field = "title";
				$body_field = "body";
			}

			$title_order = $order++;
			$body_order = $order++;
			if(((!empty($multilanguage_allow) && $total_allowed_languages > 1 && $defaultLanguage == $label)) || ($title_field == 'title' && !in_array($defaultLanguage, $languages))) {
				$title_order = -9999;
				$body_order = -9998;
			}

			$this->addElement('Text', "$title_field", array(
				'label' => $title_label,
				'required' => $required_title_body,
				'allowEmpty' => $allowEmpty_title_body,
				'order' => $title_order,
				'value' => $question,
				'attribs' => array('style'=>'width:646px;')
			));

			if($editorAllow) {
				$this->addElement('TinyMce', "$body_field", array(
					'label' => $body_label,
					'required' => $required_title_body,
					'allowEmpty' => $allowEmpty_title_body,
					'order' => $body_order,
					'filters' => array( new Engine_Filter_Censor(), $filter),
					'editorOptions' => $editorOptions,
				));
			}
			else
			{
				$this->addElement('Textarea', "$body_field", array(
					'label' => $body_label,
					'required' => $required_title_body,
					'allowEmpty' => $allowEmpty_title_body,
					'order' => $body_order,
					'attribs' => array('rows'=>24, 'cols'=>80, 'style'=>'width:646px; max-width:646px;height:250px;'),
					'filters' => array($filter, new Engine_Filter_Censor()),
				));
			}
		}

		if($settings->getSetting('sitefaq.tag', 1)) {
			$this->addElement('Text', 'tags',array(
				'label'=>'Tags (Keywords)',
				'autocomplete' => 'off',
				'description' => 'Separate tags with commas.',
				'filters' => array(
					new Engine_Filter_Censor(),
				),
			));
			$this->tags->getDecorator("Description")->setOption("placement", "append");
		}

		//CUSTOM FIELD WORK
    if( !$this->_item ) {
      $customFields = new Sitefaq_Form_Custom_Fields();
    } else {
      $customFields = new Sitefaq_Form_Custom_Fields(array(
        'item' => $this->getItem()
      ));
    }
    if( get_class($this) == 'Sitefaq_Form_Create' ) {
      $customFields->setIsCreation(true);
    }

    $this->addSubForms(array(
      'fields' => $customFields
    ));
		//END CUSTOM FIELD WORK

    $categories = Engine_Api::_()->getDbTable('categories', 'sitefaq')->getCategories(null);
    if (count($categories) != 0) {

			$this->addElement('Select', 'category_id_1', array(
					'RegisterInArrayValidator' => false,
					'allowEmpty' => true,
					'required' => false,
					'decorators' => array(array('ViewScript', array(
											'viewScript' => 'application/modules/Sitefaq/views/scripts/_formCategory.tpl',
											'class' => 'form element')))
			));
		}

    $this->addElement('Select', 'draft', array(
      'label' => 'Status',
      'multiOptions' => array("0"=>"Published", "1"=>"Saved As Draft"),
      'description' => 'If this entry is published, it cannot be switched back to draft mode.'
    ));
    $this->draft->getDecorator('Description')->setOption('placement', 'append');

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'memberlevels')) {

			$levels_prepared = $sitefaq_api->getMemberLevels();

			$this->addElement('Multiselect', 'member_levels', array(
					'label' => 'Member Levels',
					'description' => 'Select the member level to which this FAQ should be available. Use CTRL-click to select or deselect multiple Member Levels.',
					'multiOptions' => $levels_prepared,
					'value' => 0,
			));
		}

		$topLevelOptions = $sitefaq_api->getProfileTypes(0);
		$topLevelOptionsCount = Count($topLevelOptions);

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'profiletypes') && $topLevelOptionsCount > 2) {

			$this->addElement('Multiselect', 'profile_types', array(
					'label' => 'Profile Types',
					'description' => 'Select the profile type to which this FAQ should be available. Use CTRL-click to select or deselect multiple Profile Types.',
					'multiOptions' => $topLevelOptions,
					'value' => 0,
			));
		}

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'networks')) {

			$networksOptions = $sitefaq_api->getNetworks();

			$this->addElement('Multiselect', 'networks', array(
					'label' => 'Networks Selection',
					'description' => 'Select the networks, members of which should be able to see your FAQ. Use CTRL-click to select or deselect multiple Networks.',
					'multiOptions' => $networksOptions,
					'value' => 0,
			));
		}

		if($settings->getSetting('sitefaq.search', 1)) {
			$this->addElement('Checkbox', 'search', array(
				'label' => "Show this FAQ in search results.",
				'value' => 1
			));
		}

    $this->addElement('Button', 'submit', array(
      'label' => 'Submit',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array(
        'ViewHelper',
      ),
    ));

		if(empty($question)) {
			$this->addElement('Cancel', 'cancel', array(
				'label' => 'cancel',
				'link' => true,
				'prependText' => ' or ',
				'decorators' => array(
					'ViewHelper',
				),
			));
		}

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
  }

}

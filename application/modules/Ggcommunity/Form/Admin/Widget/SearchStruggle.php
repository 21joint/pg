<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity Search Struggle Admin Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_SearchStruggle extends Engine_Form
{
  public function init()
  {
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_searchstruggle_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
    'label' => 'Title',
    'value' => 'PARENTAL GUIDANCE',
    ));

    $logoOptions = array('' => 'Text-only (No logo)');
    $imageExtensions = array('gif', 'jpg', 'jpeg', 'png','svg');

    $it = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');

    foreach( $it as $file ) {
      if( $file->isDot() || !$file->isFile() ) continue;
      $basename = basename($file->getFilename());
      if( !($pos = strrpos($basename, '.')) ) continue;
      $ext = strtolower(ltrim(substr($basename, $pos), '.'));
      if( !in_array($ext, $imageExtensions) ) continue;
      $logoOptions['public/admin/' . $basename] = $basename;
      
    }

    $this->addElement('Select', 'select', array(
      'label' => 'Select Logo',
      'multiOptions' => $logoOptions
    ));

    $this->addElement('Textarea', 'description', array(
      'label' => 'Description',
      'value' => 'Share Your Struggle, Provide Your Theories, and Gain Advice',
    ));
    
  }

}

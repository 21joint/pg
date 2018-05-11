<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity Review Product Admin Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_ReviewProduct extends Engine_Form
{
  

  public function init()
  {
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
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_reviewproduct_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
    'label' => 'Title',
    'value' => '"I have finally have all my diaper bag essential in one place and organized"',
    ));

    $this->addElement('Select', 'select_image', array(
      'label' => 'Select Image',
      'multiOptions' => $logoOptions,
      'value' => 0,
    ));
    
  }

}

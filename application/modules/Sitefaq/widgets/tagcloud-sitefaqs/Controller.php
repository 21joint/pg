<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Widget_TagcloudSitefaqsController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  { 
    $this->view->owner_id = $owner_id = 0;
		if(Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      $faq = Engine_Api::_()->core()->getSubject('sitefaq_faq');
			$this->view->owner_id = $owner_id = $faq->owner_id;
      $this->view->owner = $faq->getOwner();
		}
    
    $this->view->loaded_by_ajax = $is_ajax_load = $this->_getParam('loaded_by_ajax', true);
    $this->view->isajax = $isajax = $this->_getParam('isajax', false);  
    $this->view->allParams = $allParams  = array('isajax' => 1, 'loaded_by_ajax' => 1);    

		//HOW MANY TAGS WE HAVE TO SHOW
		$total_tags = $this->_getParam('itemCount', 100);

  	//CONSTRUCTING TAG CLOUD
		$tag_array = array();
		$sitefaq_api = Engine_Api::_()->sitefaq();
		$this->view->count_only = $sitefaq_api->getTags($owner_id, 0, 1);
		if($this->view->count_only <= 0) {
			return $this->setNoRender();
		}

    if (!$is_ajax_load || ($is_ajax_load && $isajax)) {    
        //FETCH TAGS
        $tag_cloud_array = $sitefaq_api->getTags($owner_id, $total_tags, 0);

        foreach($tag_cloud_array as $vales)	{
          $tag_array[$vales['text']] = $vales['Frequency'];		
          $tag_id_array[$vales['text']] = $vales['tag_id'];	
        }

        if(!empty($tag_array)) {
          $max_font_size = 18;
          $min_font_size = 12;
          $max_frequency = max(array_values($tag_array));
          $min_frequency = min(array_values($tag_array));
          $spread = $max_frequency - $min_frequency;
          if($spread == 0) {
            $spread = 1;
          }
          $step = ($max_font_size - $min_font_size) / ($spread);

          $tag_data = array('min_font_size' => $min_font_size, 'max_font_size' => $max_font_size, 'max_frequency' => $max_frequency, 'min_frequency' => $min_frequency, 'step' => $step);
          $this->view->tag_data = $tag_data;
          $this->view->tag_id_array = $tag_id_array;
        }
        $this->view->tag_array = $tag_array;

        if(empty($this->view->tag_array)) {
          return $this->setNoRender();
        }
        $this->view->showContent = true;
    }
  }

}
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

class Sitefaq_Widget_SocialshareSitefaqsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		//DONT RENDER THIS IF NOT AUTHORIZED
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return $this->setNoRender();
    }

		$social_share_default_code = '<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_preferred_5"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript">
		var addthis_config = {
							services_compact: "facebook, linkedin, google, digg, more",
							services_exclude: "print, email"
		}
		</script>
		<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js"></script>';

		//GET CODE FROM CORE SETTING
    $this->view->code = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.code.share', $social_share_default_code);

		//GET FAQ SUBJECT
		$sitefaq = Engine_Api::_()->core()->getSubject();

		//DONT RENDER THIS IF NOT AUTHORIZED
		if($sitefaq->draft == 1 ||$sitefaq->approved != 1 || $sitefaq->search != 1) {
      return $this->setNoRender();
		}

		//DONT RENDER THIS IF NOT AUTHORIZED
    if (empty($this->view->code)) {
      return $this->setNoRender();
    }
  }

}
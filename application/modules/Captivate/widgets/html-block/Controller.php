<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Widget_HtmlBlockController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $islanguage = $this->view->translate()->getLocale();
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        if (!strstr($islanguage, '_')) {
            $islanguage = $islanguage . '_default';
        }

        $keyForSettings = str_replace('_', '.', $islanguage);
        $captivateLendingBlockValue = $coreSettings->getSetting('captivate.home.lending.block.languages.' . $keyForSettings, null);

        $captivateLendingBlockTitleValue = $coreSettings->getSetting('captivate.home.lending.block.title.languages.' . $keyForSettings, null);
        if (empty($captivateLendingBlockValue)) {
            $captivateLendingBlockValue = $coreSettings->getSetting('captivate.home.lending.block', null);
        }
        if (empty($captivateLendingBlockTitleValue)) {
            $captivateLendingBlockTitleValue = $coreSettings->getSetting('captivate.home.lending.block.title', null);
        }

        if (!empty($captivateLendingBlockValue))
            $this->view->captivateLendingBlockValue = @base64_decode($captivateLendingBlockValue);
        if (!empty($captivateLendingBlockTitleValue))
            $this->view->captivateLendingBlockTitleValue = @base64_decode($captivateLendingBlockTitleValue);
        
    }

}

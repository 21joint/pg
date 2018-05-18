<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Controller.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitehashtag_Widget_HashtagsController extends Engine_Content_Widget_Abstract {

    public function indexAction() {


        $limit = $this->_getParam('tag_count', 10);
        $duration = $this->_getParam('duration', 30);
        $hashtagMapTable = Engine_Api::_()->getDbtable('tags', 'sitehashtag');
        
        $hashtagNames = $hashtagMapTable->getTopTrends($limit,$duration);
        if (empty($hashtagNames)){
            return $this->setNoRender();
        }

        $this->view->hashtags = $hashtagNames;
    }

}

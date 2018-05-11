<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitevideo
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2016-3-3 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitevideo_Widget_PeopleWhoLikeController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        //DONT RENDER IF VEWER IS EMPTY
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        if (empty($viewer_id)) {
            return $this->setNoRender();
        }

        //DONT RENDER IF SUBJECT IS NOT SET
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->setNoRender();
        }

        //GET LIST SUBJECT
        $subject = Engine_Api::_()->core()->getSubject();
        $this->view->resource_type = $resource_type = $subject->getType();
        $this->view->resource_id = $resource_id = $subject->getIdentity();
        $this->view->limit = $limit = $this->_getParam('itemCount', 9);
        $peopleLikeResults = Engine_Api::_()->getApi('like', 'seaocore')->peopleLike($resource_type, $resource_id, $limit);
        $this->view->like_count = $like_count = Engine_Api::_()->getApi('like', 'seaocore')->likeCount($resource_type, $resource_id);
        $this->view->detail = 0;
        $this->view->results = array();

        if (!empty($peopleLikeResults)) {

            foreach ($peopleLikeResults as $peopleLikeResult) {
                $like_user_object[] = Engine_Api::_()->getItem('user', $peopleLikeResult['poster_id']);
            }

            $this->view->results = $like_user_object;

            if (!empty($like_count) && $like_count > $limit) {
                $this->view->detail = 1;
            }
        } else {
            return $this->setNoRender();
        }
    }

}

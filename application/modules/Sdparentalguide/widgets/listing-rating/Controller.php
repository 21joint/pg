<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Widget_ListingRatingController extends Engine_Content_Widget_Abstract {
  public function indexAction() {

    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject() || !$viewer->getIdentity()) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject();
    if (!$subject->authorization()->isAllowed($viewer, 'view')) {
      return $this->setNoRender();
    }
    
    $table = Engine_Api::_()->getDbTable("listingRatings","sdparentalguide");
    $this->view->userRating = $userRating = $table->getRating($subject,$viewer);
    
    $request = Zend_Controller_Front::getInstance()->getRequest();
    if($request->isPost()){
        $ratingType = $request->getParam("type");
        $rating = $request->getParam("rating");
        header("Content-Type: application/json");
        if(empty($ratingType) || empty($rating)){
            echo json_encode(array('status' => false));exit;
            return;
        }
        
        if(empty($userRating)){
            $userRating = $table->createRow();
            $userRating->user_id = $viewer->getIdentity();
            $userRating->listing_id = $subject->getIdentity();
        }
        $userRating->{$ratingType} = $rating;
        $userRating->save();
        echo json_encode(array('status' => true));exit;
    }   
    
  }
}
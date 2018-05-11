<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_Widget_ListingSearchController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $this->view->form = $form = new Sdparentalguide_Form_Search();
    }
}

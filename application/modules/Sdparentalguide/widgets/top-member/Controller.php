<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_Widget_TopMemberController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $this->view->friendrequest=$this->_getParam('showFriendRequest',1);
        $param = Array();
        $this->view->basedon = $param['basedon']= $this->_getParam('topmember', 'activities');
        $this->view->page = $page = Zend_Controller_Front::getInstance()->getRequest()->getParam("page",1);
    
        $param['count'] = $this->_getParam('count',10); 
        $this->view->perPage = $param['count'];
        
        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $select = $usersTable->select()
            ->where("search = ?", 1)
            ->where("enabled = ?", 1)
            ;
        
        if($param['basedon'] == 'activities'){
            $select->order("gg_activities DESC");
        }else{
            $select->order("gg_contribution DESC");
        }
        $this->view->paginator= $paginator = Zend_Paginator::factory($select);
        if ($paginator->getTotalItemCount() <= 0) {
            return $this->setNoRender();
        }
        $paginator->setItemCountPerPage($param['count']);
        $paginator->setCurrentPageNumber($page);
    }
}

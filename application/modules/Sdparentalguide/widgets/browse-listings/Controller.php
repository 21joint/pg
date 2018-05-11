<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_Widget_BrowseListingsController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $params = array();
        $this->view->truncation = $params['truncation'] = $this->_getParam('truncation', 32);
        $this->view->truncationDescription = $params['truncationDescription'] = $this->_getParam('truncationDescription', 1024);
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $page = $request->getParam("page",1);
        $formValues = $request->getParams();
        unset($formValues['action']);
        unset($formValues['module']);
        unset($formValues['controller']);
        unset($formValues['rewrite']);
        unset($formValues['content_id']);
        unset($formValues['format']);
        
        $table = Engine_Api::_()->getDbTable('listings', 'sitereview');
        $listingTableName = $table->info("name");
        $select = $table->select()
                ->where($listingTableName . '.closed = ?', '0')
//                ->where($listingTableName . '.approved = ?', '1')
                ->where($listingTableName . '.search = ?', '1')
                ->where($listingTableName . '.draft = ?', '0')
                ->where($listingTableName . '.creation_date <= ?', date('Y-m-d H:i:s'));
        
        if(!empty($formValues['user_id'])){
            $select->where("$listingTableName.owner_id = ?",$formValues['user_id']);
        }
        if(!empty($formValues['listing_type'])){
            $select->where("$listingTableName.listingtype_id = ?",$formValues['listing_type']);
        }
        if(!empty($formValues['category_id'])){
            $select->where("$listingTableName.category_id = ?",$formValues['category_id']);
        }
        if(!empty($formValues['subcategory_id'])){
            $select->where("$listingTableName.subcategory_id = ?",$formValues['subcategory_id']);
        }        
        if(!empty($formValues['sort'])){
            if($formValues['sort'] == 'creation_date'){
                $select->order("$listingTableName.".$formValues['sort']." ASC");
            }else{
                $select->order("$listingTableName.".$formValues['sort']." DESC");
            }            
        }else{
            $select->order("$listingTableName.listing_id DESC");
        }
        
        if(!empty($formValues['complete']) && $formValues['complete'] != 'all'){
            if($formValues['complete'] == 'complete'){
                $select->where("$listingTableName.gg_grading_complete = ?",1);
            }else{
                $select->where("$listingTableName.gg_grading_complete = ?",0);
            }            
        }
        
        if(!empty($formValues['include']) && $formValues['include'] != 'all'){
            if($formValues['include'] == 'approved'){
                $select->where("$listingTableName.approved = ?",1);
            }else{
                $select->where("$listingTableName.approved = ?",0);
            }            
        }
        
        $this->view->formValues = $formValues;
                
        $this->view->listings = $paginator = Zend_Paginator::factory($select);
        $this->view->totalCount = $paginator->getTotalItemCount();

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_getParam('itemCount', 10));
        
        if(!isset($formValues['forms']) || count($formValues['forms']) <= 0){
            return;
        }
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        foreach($formValues['forms'] as $formData){
            if(empty($formData['listing_id'])){
                continue;
            }
            $sitereview = Engine_Api::_()->getItem("sitereview_listing",$formData['listing_id']);
            if(empty($sitereview)){
                continue;
            }
            $sitereview->gg_grading_picquality = (int)$formData['picture_quality'];
            $sitereview->gg_grading_picquantity = (int)$formData['picture_quantity'];
            $sitereview->gg_grading_description = (int)$formData['description'];
            $sitereview->gg_grading_grammar = (int)$formData['spelling'];
            $sitereview->gg_grading_categorization = (int)$formData['categorization'];
            $sitereview->gg_grading_disclosure = (int)$formData['disclosure'];
            $sitereview->gg_grading_safetyguidelines = (int)$formData['safety'];
            $sitereview->gg_graded_by_comments = $formData['notes'];
            $sitereview->gg_grading_complete = (int)$formData['complete'];
            $sitereview->approved = (int)$formData['approved'];
            $sitereview->gg_graded_by = $viewer_id;
            $sitereview->save();
        }
        
    }
}

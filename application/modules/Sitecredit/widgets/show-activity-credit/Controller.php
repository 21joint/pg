<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_ShowActivityCreditController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {

        $this->view->viewer=$viewer=Engine_Api::_()->user()->getViewer();
        if (!$viewer) 
            return $this->setNoRender();
        $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
   
        if (isset($params['page']) && !empty($params['page']))
            $this->view->page = $page = $params['page'];
        else
            $this->view->page = $page = 1;

        $this->view->showContent = $params['show_content_credit'] = $this->_getParam('show_content_credit', 2);
        $this->view->itemCountPerPage = $params['itemCountPerPage'] = $this->_getParam('itemCountPage', 12);
        $this->view->is_ajax = $params['is_ajax'] = $this->_getParam('is_ajax', false);
        $this->view->textTruncation = $params['truncationActivity'] = $this->_getParam('truncationActivity', 100);
        $this->view->id = $params['id'] = $this->_getParam('identity'); 
  
        $this->view->params=$params;  
        $this->view->formFilter = $formFilter = new Sitecredit_Form_Admin_Filter();
        $values = array();
        if ($formFilter->isValid($this->_getAllParams())) {
            $values = $formFilter->getValues();
        }

        foreach ($values as $key => $value) {
            if (null === $value) {
                unset($values[$key]);
            }
        }
        $this->view->language=Zend_Registry::get('Locale')->getLanguage();
  
        $sitecreditShowActivity = Zend_Registry::isRegistered('sitecreditShowActivity') ? Zend_Registry::get('sitecreditShowActivity') : null;
        if (empty($sitecreditShowActivity))
            return $this->setNoRender();
        
        $permissiontable=Engine_Api::_()->getDbtable('permissions', 'authorization');
        
        $select=$permissiontable->select()->where('level_id=?',$viewer->level_id)->where('type=?','sitecredit_credit')->where('name=?','max_perday');
        
        $this->view->levelcreditlimit=$permissiontable->fetchRow($select);
        
        /*$coreModuletable=Engine_Api::_()->getDbtable('modules', 'core');
        $coreModuletableName = $coreModuletable->info('name');
*/
        $creditTable=Engine_Api::_()->getDbtable('activitycredits','sitecredit');
        $creditTableName = $creditTable->info('name');

        $getEnabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
        // Get menu items
        $moduleItemsTable = Engine_Api::_()->getDbtable('modulelists', 'sitecredit');
        $moduleItemsSelect = $moduleItemsTable->select()
                ->order('order_id')->where('enabled = ?',1);
        if (!empty($getEnabledModuleNames)) {
            $moduleItemsSelect->where('name IN(?)', $getEnabledModuleNames);
        }
        $moduleItemsSelect->where('parent_id = ?', 0);
        $modules = $moduleItemsTable->fetchAll($moduleItemsSelect);
       
        $this->view->modules=$modules;
        $select=$creditTable->select()->setIntegrityCheck(false);
        $select->where($creditTableName .'.member_level=?',$viewer->level_id)->where($creditTableName .'.status = "enabled"');
        
        $modulelist=Engine_Api::_()->sitecredit()->getModuleEditorArray();
        $modulelist=$modulelist->toArray();

        $moduleLabels=array();

        foreach( $modules as $key => $modules) {
            $moduleLabels[$modules->name]=$modules->label;
            $parent_id=$modules->modulelist_id;
            foreach ($modulelist as $data) {
                if($data['parent_id']==$parent_id){
                    $moduleLabels[$data['name']]=$modules->label;
                }
            }
        }
        $this->view->moduleLabels=$moduleLabels;
            
        if (!empty($params['module_id'])) {
            $module = $params['module_id'];
        }elseif (!empty($params['module_id']) && !isset($params['post_search'])) {
            $module = $params['module_id'];
        }else {
            $module = '';
        }
        $this->view->selectedModule=$module;
        if(empty($module)){
            $string="FIELD(module";
            foreach ($modulelist as $key => $value) {
                if($value['enabled']==1){
                $string.=",'".$value['name']."'";
                $moduleListAray[]=$value['name'];
                }
            }    
            $string.=")";
            $select->order(new Zend_Db_Expr($string));
            $select->where('module IN(?)',$moduleListAray);
        }else{
            $count=0;
            $moduleListArray=array();
            $string;
            foreach ($modulelist as $key => $value) {
                if($value['enabled']==1){
                    if($value['name']==$module)
                    $parent_id=$value['modulelist_id'];
                }                
            }
            if(!empty($parent_id)){
                $string="FIELD(module,'".$module."'";
                $moduleListArray[]=$module;
                foreach ($modulelist as $data) {
                    if($data['enabled']==1){
                    if($data['parent_id']==$parent_id){
                        $string.=",'".$data['name']."'";
                        $moduleListArray[]=$data['name'];
                        ++$count;
                    }
                    }
                }
                $string.=")";
            }
            if($count>0){
               $select->where('module IN(?)',$moduleListArray);
               $select->order(new Zend_Db_Expr($string)); 
            }else {
                $select->where('module = ?',$module);
            }
        }
 
        $values = array_merge(array(
            'order' => 'activity_type',
            'order_direction' => 'DESC',
        ), $values);

        $this->view->formValues = array_filter($values); 
        $this->view->assign($values);
    //MAKE PAGINATOR

        $this->view->paginator = $paginator=Zend_Paginator::factory($select);
        $this->view->paginator->setItemCountPerPage($params['itemCountPerPage']);
        $this->view->paginator = $this->view->paginator->setCurrentPageNumber($page);

        $this->view->totalCount = $paginator->getTotalItemCount();
    

    }

}


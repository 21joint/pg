<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sdparentalguide_Api_Install extends Core_Api_Abstract{
    public function getPageByName($name){
        $db = Engine_Db_Table::getDefaultAdapter();
        $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', $name)
            ->limit(1)
            ->query()
            ->fetchColumn();
        return $page_id;
    }
    public function addCredibilityPage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_index_index");
        if(!empty($customPageId)){
            return;
        }
        $clonePageId = $this->getPageByName("sitecredit_index_index");

        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_index_index',
            'displayname' => 'Credits - My Credit Page (Custom)',
            'title' => 'My Credits',
            'description' => 'This page allows user to get details of their earned credits.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();
        
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();
        
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'right',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
        ));
        $main_right_id = $db->lastInsertId();
        
        $contentTable = Engine_Api::_()->getDbTable('content','core');
        $rightColumnId = $this->getColumnId("container", 'right', $clonePageId);
        if($rightColumnId){
            $rightContents = $contentTable->fetchAll($contentTable->select()->where('parent_content_id = ?',$rightColumnId));
            foreach($rightContents as $row){
                $rowParams = $row->toArray();
                $newRow = $contentTable->createRow();
                unset($rowParams['content_id']);
                $rowParams['page_id'] = $page_id;
                $rowParams['parent_content_id'] = $main_right_id;
                $newRow->setFromArray($rowParams);
                $newRow->save();
            }
        }
        
        $middleColumnId = $this->getColumnId("container", 'middle', $clonePageId);
        if($middleColumnId){
            $middleContents = $contentTable->fetchAll($contentTable->select()->where('parent_content_id = ?',$middleColumnId));
            foreach($middleContents as $row){
                $rowParams = $row->toArray();
                unset($rowParams['content_id']);
                $newRow = $contentTable->createRow();
                if($row->name == 'sitecredit.top-member'){
                    $rowParams['name'] = "sdparentalguide.top-member";
                }
                $rowParams['page_id'] = $page_id;
                $rowParams['parent_content_id'] = $main_middle_id;
                $newRow->setFromArray($rowParams);
                $newRow->save();
            }
        }
    }

    public function addListingPage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_index_listings");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_index_listings',
            'displayname' => 'Sdparentalguide - Listing Grades',
            'title' => 'Listing Grades',
            'description' => 'This page allow certain users to search for listings and grade/approve them',
            'custom' => 1,
        ));
        $page_id = $db->lastInsertId();
        
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
        ));
        $main_middle_id = $db->lastInsertId();
        
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sdparentalguide.listing-search',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sdparentalguide.browse-listings',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 2,
        ));
        
    }
    public function getColumnId($type,$name,$clonePageId){
        $db = Engine_Db_Table::getDefaultAdapter();
        $containerId = $db->select()
            ->from('engine4_core_content', 'content_id')
            ->where('type = ?', $type)
            ->where('name = ?', $name)
            ->where("page_id = ?",$clonePageId)
            ->limit(1)
            ->query()
            ->fetchColumn();
        return $containerId;
    }
}

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
    
    public function addCommunityHomePage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_community_home");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_community_home',
            'displayname' => 'Sdparentalguide - Community Home',
            'title' => 'Community Home',
            'description' => 'This page allow users to browse community.',
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
            'name' => 'sdparentalguide.community-home',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addLeaderboardPage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_community_leaderboard");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_community_leaderboard',
            'displayname' => 'Sdparentalguide - Community Leaderboard',
            'title' => 'Community Leaderboard',
            'description' => 'This page allow users to search for questions and answers.',
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
            'name' => 'sdparentalguide.community-leaderboard',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addReviewsHomePage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_reviews_home");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_reviews_home',
            'displayname' => 'Sdparentalguide - Reviews Home',
            'title' => 'Reviews Home',
            'description' => 'This page allow users to browse for reviews.',
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
            'name' => 'sdparentalguide.reviews-home',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addReviewsCreatePage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_reviews_create");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_reviews_create',
            'displayname' => 'Sdparentalguide - Reviews Create',
            'title' => 'Reviews Create',
            'description' => 'This page allow users to create reviews.',
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
            'name' => 'sdparentalguide.reviews-create',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addReviewsViewPage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_reviews_view");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_reviews_view',
            'displayname' => 'Sdparentalguide - Reviews View',
            'title' => 'Reviews View',
            'description' => 'This page allow users to view reviews.',
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
            'name' => 'sdparentalguide.reviews-view',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addGuidesHomePage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_guides_home");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_guides_home',
            'displayname' => 'Sdparentalguide - Guides Home',
            'title' => 'Guides Home',
            'description' => 'This page allow users to browse for guides.',
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
            'name' => 'sdparentalguide.guides-home',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addGuidesCreatePage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_guides_create");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_guides_create',
            'displayname' => 'Sdparentalguide - Guides Create',
            'title' => 'Guides Create',
            'description' => 'This page allow users to create guides.',
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
            'name' => 'sdparentalguide.guides-create',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addGuidesViewPage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_guides_view");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_guides_view',
            'displayname' => 'Sdparentalguide - Guides View',
            'title' => 'Guides View',
            'description' => 'This page allow users to view guides.',
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
            'name' => 'sdparentalguide.guides-view',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addSearchPage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_search_index");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_search_index',
            'displayname' => 'Sdparentalguide - Search',
            'title' => 'Search',
            'description' => 'This page allow users to search',
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
            'name' => 'sdparentalguide.search',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
    
    public function addTopicsHomePage(){
        $db = Engine_Db_Table::getDefaultAdapter();
        $customPageId = $this->getPageByName("sdparentalguide_topics_home");
        if(!empty($customPageId)){
            return;
        }
        
        $db->insert('engine4_core_pages', array(
            'name' => 'sdparentalguide_topics_home',
            'displayname' => 'Sdparentalguide - Topics Home',
            'title' => 'Topics Home',
            'description' => 'This page allow users to browse for topics.',
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
            'name' => 'sdparentalguide.topics-home',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        
    }
}

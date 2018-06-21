<?php

class Sdparentalguide_ApiController extends Sdparentalguide_Controller_Action_Api
{

    public function indexAction(){
        
    }
    public function searchAction(){
        $dataString = $this->getParam("request");
        $dataString = urldecode(@utf8_decode($dataString));
        $requestParams = (array)@json_decode($dataString);
        
        $table = Engine_Api::_()->getDbtable('search', 'core');
        $db = $table->getAdapter();
        $select = $table->select();
        $text = isset($requestParams['string'])?$requestParams['string']:'';
        $limit = isset($requestParams['limit'])?$requestParams['limit']:'10';
        $page = isset($requestParams['page'])?$requestParams['page']:'1';
        $types = isset($requestParams['types'])?$requestParams['types']:null;
        if(!empty($text)){
            $select->where("(`title` LIKE  '%$text%' OR `description` LIKE  '%$text%' OR `keywords` LIKE  '%$text%' OR `hidden` LIKE  '%$text%')")
            ->order(new Zend_Db_Expr($db->quoteInto('MATCH(`title`, `description`, `keywords`, `hidden`) AGAINST (?) DESC', $text)));
        }
        
        $availableTypes = Engine_Api::_()->getItemTypes();
        if( $types ) {
            $typesArray = @explode(",",$types);
            if(count($typesArray) > 0){
                $select->where('type IN (?)', $typesArray);
            }            
        } else {
            $select->where('type IN(?)', $availableTypes);
        }
                
        $response = array();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);
        
        foreach($paginator as $item){
            $isItemTypeAvailable = Engine_Api::_()->hasItemType($item->type);
            if (empty($isItemTypeAvailable))
                continue;

            //@todo classified_album get href issue
            if ($item->type == 'classified_album')
                continue;
            
            $item = Engine_Api::_()->getItem($item->type, $item->id);
            if (!$item)
                continue;
            
            $itemArray = $item->toArray();
            if(!empty($itemArray['description'])){
                $itemArray['description'] = substr(strip_tags($itemArray['description']));
            }
            if(!empty($itemArray['body'])){
                $itemArray['body'] = substr(strip_tags($itemArray['body']),0,255);
            }
            unset($itemArray['creation_ip']);
            unset($itemArray['lastlogin_ip']);
            unset($itemArray['gg_ip_lastmodified']);
            $itemArray['href'] = $item->getHref();
            
            $response[] = $itemArray;
        }
        $this->respondWithSuccess($response);
    }
    
    public function rankingServiceAction(){
        $contributionRangeType = $this->getParam("contributionRangeType","Overall");
        $orderBy = $this->getParam("orderBy","contributionPoints");
        
        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $select = $usersTable->select()
            ->where("search = ?", 1)
            ->where("enabled = ?", 1)
            ;
        
        //Contribution Range
        if(strtolower($contributionRangeType) == "week" || strtolower($contributionRangeType) == "month"){
//            $creditsTable = Engine_Api::_()->getDbtable('credits','sitecredit');
//            $creditsTableName = $creditsTable->info("name");
            
        }
        //Sort data
        //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
        if($orderBy == 'contributionPoints'){
            $select->order("gg_contribution DESC");
        }elseif($orderBy == 'reviewCount'){
            $select->order("gg_review_count DESC");
        }elseif($orderBy == 'questionCount'){
            $select->order("gg_question_count DESC");
        }elseif($orderBy == 'followers'){
            $select->order("gg_followers_count DESC");
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->getParam("limit",10));
        $paginator->setCurrentPageNumber($this->getParam("page",1));
        
        $response = array(
            'contributionRangeType' => $contributionRangeType,
            'orderBy' => $orderBy,
            'contributions' => array(),
        );
        $api = Engine_Api::_()->sdparentalguide();
        foreach($paginator as $user){
            $temp = array(
                'contributorID' => $user->getIdentity(),
                'contributionPoints' => $user->gg_contribution,
                'reviewCount' => $user->gg_review_count,
                'questionCount' => $user->gg_question_count,
                'answerCount' => 0, //Don't have answers count in users table for now.
                'followers' => $user->gg_followers_count,
                'title' => $user->getTitle(),
            );
            $contentImages = $api->getContentImage($user);
            $temp = array_merge($temp,$contentImages);
            $response['contributions'][] = $temp;
        }
        $this->respondWithSuccess($response);
    }
}

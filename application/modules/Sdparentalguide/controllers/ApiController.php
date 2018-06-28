<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

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
    
}

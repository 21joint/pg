<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_SearchTerms extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_SearchTerm";
    protected $_name = 'gg_search_terms';
    
    public function getSearchAliases($searchText){
        if(empty($searchText)){
            return array();
        }
        $searchTerm = $this->fetchRow($this->select()->where('name LIKE ?',"%".$searchText."%"));
        if(empty($searchTerm)){
            return array();
        }
        
        $aliasTable = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
        $aliases = $aliasTable->fetchAll($aliasTable->select()->where('searchterm_id = ?',$searchTerm->searchterm_id));
        if(count($aliases) <= 0 || empty($aliases)){
            return;
        }
        
        $searchAliases = array($searchText);
        foreach($aliases as $alias){
            $searchAliases[] = $alias->name;
        }
        return $searchAliases;
    }
} 





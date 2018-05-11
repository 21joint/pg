<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Votes
 */

class Ggcommunity_Model_DbTable_Votes extends Core_Model_Item_DbTable_Abstract 
{
  protected $_rowClass = "Ggcommunity_Model_Vote";

  // Delete vote for this parent_id
  public function deleteVote($id) {
    
    $vote_table = Engine_Api::_()->getDbTable('votes', 'ggcommunity');
            
    $vote_db = $vote_table->getAdapter();
    $vote_db->beginTransaction();
    try {

      $select = $vote_table->select()->where('parent_id = ?', $id);
      $votes = $vote_table->fetchAll($select);
      if(count($votes)>0) {
        foreach($votes as $vote) {
            $vote->delete();
        }
        $vote_db->commit();
      }

    } catch( Exception $e ) {
        $vote_db->rollBack();
        return;
    }
    return true;

  }
  
}
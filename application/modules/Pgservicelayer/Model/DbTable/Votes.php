<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */
class Pgservicelayer_Model_DbTable_Votes extends Engine_Db_Table {

    protected $_rowClass = 'Ggcommunity_Model_Vote';
    protected $_name = 'ggcommunity_votes';
    protected $_custom = false;

    public function __construct($config = array()) {
        if (get_class($this) !== 'Pgservicelayer_Model_DbTable_Votes') {
            $this->_custom = true;
        }

        parent::__construct($config);
    }

    public function getVoteTable() {
        return $this;
    }

    public function addVote(Core_Model_Item_Abstract $resource, Core_Model_Item_Abstract $poster,$voteType = 1) {
        $row = $this->getVote($resource, $poster);
        if (null !== $row) {
            throw new Core_Model_Exception('Already voted');
        }

        $table = $this->getVoteTable();
        $row = $table->createRow();

        if (isset($row->parent_type)) {
            $row->parent_type = $resource->getType();
        }

        $row->parent_id = $resource->getIdentity();
        $row->user_id = $poster->getIdentity();
        $row->vote_type = $voteType;
        $row->save();

        if (isset($resource->up_vote_count) && $voteType == 1) {
            $resource->up_vote_count++;
            $resource->save();
        }
        
        if (isset($resource->down_vote_count) && $voteType == 0) {
            $resource->down_vote_count++;
            $resource->save();
        }

        return $row;
    }

    public function removeVote(Core_Model_Item_Abstract $resource, Core_Model_Item_Abstract $poster,$voteType = 1) {
        $row = $this->getVote($resource, $poster,$voteType);
        if (null === $row) {
            throw new Core_Model_Exception('No vote to remove');
        }

        $row->delete();

        if (isset($resource->up_vote_count) && $voteType == 1) {
            $resource->up_vote_count--;
            $resource->save();
        }
        
        if (isset($resource->down_vote_count) && $voteType == 0) {
            $resource->down_vote_count--;
            $resource->save();
        }

        return $this;
    }

    public function isVote(Core_Model_Item_Abstract $resource, Core_Model_Item_Abstract $poster,$voteType = 1) {
        return ( null !== $this->getVote($resource, $poster,$voteType) );
    }

    public function getVote(Core_Model_Item_Abstract $resource, Core_Model_Item_Abstract $poster,$voteType = 1) {
        $table = $this->getVoteTable();
        $select = $this->getVoteSelect($resource,$voteType)
                ->where('user_id = ?', $poster->getIdentity())
                ->where("vote_type = ?",(int)$voteType)
                ->limit(1);

        return $table->fetchRow($select);
    }

    public function getVoteSelect(Core_Model_Item_Abstract $resource,$voteType = 1) {
        $select = $this->getVoteTable()->select();

        $select->where('parent_type = ?', $resource->getType());

        $select
                ->where('parent_id = ?', $resource->getIdentity())
                ->order('vote_id ASC');
        $select->where("vote_type = ?",$voteType);

        return $select;
    }

    public function getVotePaginator(Core_Model_Item_Abstract $resource,$voteType = 1) {
        $paginator = Zend_Paginator::factory($this->getVoteSelect($resource,$voteType));
        $paginator->setItemCountPerPage(3);
        $paginator->count();
        $pages = $paginator->getPageRange();
        $paginator->setCurrentPageNumber($pages);
        return $paginator;
    }

    public function getVoteCount(Core_Model_Item_Abstract $resource,$voteType = 1) {
        if (isset($resource->up_vote_count) && $voteType == 1) {
            return $resource->up_vote_count;
        }
        if (isset($resource->down_vote_count) && $voteType == 0) {
            return $resource->down_vote_count;
        }
        $select = new Zend_Db_Select($this->getVoteTable()->getAdapter());
        $select
                ->from($this->getVoteTable()->info('name'), new Zend_Db_Expr('COUNT(1) as count'));

        $select->where('parent_type = ?', $resource->getType());
        $select->where('parent_id = ?', $resource->getIdentity());
        $select->where("vote_type = ?",$voteType);
        $data = $select->query()->fetchAll();
        return (int) $data[0]['count'];
    }

    public function getAllVotes(Core_Model_Item_Abstract $resource,$voteType = 1) {
        return $this->getVoteTable()->fetchAll($this->getVoteSelect($resource,$voteType));
    }

    public function getAllVotesUsers(Core_Model_Item_Abstract $resource,$voteType = 1) {
        $table = $this->getVoteTable();
        $select = new Zend_Db_Select($table->getAdapter());
        $select->from($table->info('name'), array('user_id'));

        $select->where('parent_type = ?', $resource->getType());
        $select->where('parent_id = ?', $resource->getIdentity());
        $select->where("vote_type = ?",$voteType);

        $users = array();
        foreach ($select->query()->fetchAll() as $data) {
            $users[] = $data['user_id'];
        }
        $users = array_values(array_unique($users));

        return Engine_Api::_()->getItemMulti('user', $users);
    }

    /**
     * Gets a proxy object for the like handler
     *
     * @return Engine_ProxyObject
     * */
    public function votes($subject) {
        return new Engine_ProxyObject($subject, $this);
    }

}

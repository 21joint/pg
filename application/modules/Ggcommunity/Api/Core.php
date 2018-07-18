<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Ggcommunity
 */
class Ggcommunity_Api_Core extends Core_Api_Abstract
{
   

    public function getPermission($user) {
        $level_id = !empty($user->getIdentity()) ? $user->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $permissionTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $select = $permissionTable->select()
          ->where('level_id = ?', $level_id)
          ->where('type = ?', 'ggcommunity')
        ;
        $rows = $permissionTable->fetchAll($select);

        $permissions = [];
        foreach($rows as $row) {
            $permissions[$row->name] =  $row->value;
        }
        return $permissions;
    }

    public function handleTopics($topics)
    {
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $select = $table->select();
        $all_topics = $table->fetchAll($select);

        $topics_id = [];
        
        foreach( $topics as $topic ) {
            $topic = htmlspecialchars((trim($topic)));
            if( !in_array($topic, $all_topics) && $topic !="" ) {
                $topics_id[] = $this->checkTopic($topic);  
            }
        }

    
        return $topics_id;
    }

    public function checkTopic($text)
    {
        
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $select = $table->select()->where('name = ?', $text);

        $results = $table->fetchRow($select);
    
        $topic_id = "";
        if( $results ) $topic_id = $results->topic_id;
        return $topic_id;
    }

    // get Topics for specific text added
    public function getTopicsByText($text = null, $limit = 10)
    {
        
        $table = Engine_APi::_()->getDbtable('topics', 'sdparentalguide');
        $select = $table->select()
            ->order('name ASC')
            ->limit($limit)
        ;

        if($text) {
            $select->where('name LIKE ?', $text.'%');
        }

        return $table->fetchAll($select);
    }

    //get Featured Topics
    public function getFeaturedTopics($limit) {

        $table = Engine_APi::_()->getDbtable('topics', 'sdparentalguide');
        $select = $table->select()
            ->where('featured = ?', 1)
            ->limit($limit)
        ;
        return $table->fetchAll($select);
    }

    //for each topic create new row in ggcommunity_topicmaps table 
    public function addTopicMap($question, $topic_ids) {
        if(!$question) return;
        //go through all ids and create row for each of them
       
        foreach($topic_ids as $topic_id) {
            $table = Engine_Api::_()->getDbTable('topicmaps', 'ggcommunity');
            $db = $table->getAdapter();
            $db->beginTransaction();
            
            try {
                $row = $table->createRow();
                $row->parent_type = $question->getType();
                $row->parent_id = $question->getIdentity();
                $row->topic_id = $topic_id;
                $row->save();
               
                $db->commit();
                
            } catch( Exception $e ) {
                $db->rollBack();
                throw $e;
               
            }

        }
        return true;
    }

    // conver to x time ago from timestamp
    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    // function that return object of vote for specific subject and viewer
    public function getVote($subject, $viewer) {

        if(!$subject || !$viewer) return;

        $table = Engine_Api::_()->getDbTable('votes', 'ggcommunity');
        $select = $table->select()
            ->where('parent_type = ?', $subject->getType())
            ->where('parent_id = ?', $subject->getIdentity())
            ->where('user_id = ?', $viewer->getIdentity())
            ->order('vote_id DESC')
        ;
        $row = $table->fetchRow($select);

        return $row;


    }


    // function that return object of vote for specific subject and viewer
    public function addVote($subject, $viewer, $type) {

        if(!$subject || !$viewer) return;

        $table = Engine_Api::_()->getDbTable('votes', 'ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $row = $table->createRow();
            $row->vote_type = $type;
            $row->parent_type = $subject->getType();
            $row->parent_id = $subject->getIdentity();
            $row->user_id = $viewer->getidentity();
            $row->save();
            $db->commit();

        }catch( Exception $e ) {
		  $db->rollBack();
		  throw $e;
		}

        return $row;

    }

    // function that update vote from up to down and from down to up
    public function updateVote($vote) {

        if(!$vote) return;
        if($vote->vote_type == 1 ) {
            $vote->vote_type = 0;
        } else {
            $vote->vote_type = 1;
        }
        $vote->save();

        return $vote;

    }

    // function for udpdating up_vote_count and down_vote_count
    public function updateVoteCount($subject, $vote_type, $vote) {

        if(!$vote) {
            if($vote_type == 1 ) {
                $subject->up_vote_count = $subject->up_vote_count+1;
            } else {
                $subject->down_vote_count = $subject->down_vote_count-1;
            }     
        } else {

            if($vote_type == 1) {
                $subject->up_vote_count = $subject->up_vote_count + 1;
                if( $subject->down_vote_count == 1 ) {
                    $subject->down_vote_count = 0; 
                } else {
                    $subject->down_vote_count = $subject->down_vote_count - 1; 
                } 
            } else {
                if( $subject->up_vote_count == 1 ) {
                    $subject->up_vote_count = 0;
                } else {
                    $subject->up_vote_count = $subject->up_vote_count - 1;
                }  
                $subject->down_vote_count = $subject->down_vote_count + 1;
            }
        }
        $subject->save();

    }
}

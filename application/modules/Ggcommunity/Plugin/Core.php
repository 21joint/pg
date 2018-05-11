<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Ggcommunity
 */
class Ggcommunity_Plugin_Core extends Zend_Controller_Plugin_Abstract
{
    /** EXTFOX
        * Check event, which item is being deleted
        * If it is Question make sure that you will also delete all
        * - Answers about that question
        * - Comments about that question
        * - Votes for that Question

        * If it is Answer make sure that you will delete all
        * - Comments about that answer
        * - Votes for that Answer

        * If it is Comment, make sure that you will delete 
        * - Votes for that Comment
    */

    public function onItemDeleteBefore($event)  
    {
        $payload = $event->getPayload(); 

        if( $payload instanceof Ggcommunity_Model_Question ) {

            // Delete answers
            $answer_table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
            $answer_select = $answer_table->select()->where('parent_id = ?', $payload->getIdentity());
            if( count($answer_table->fetchAll($answer_select)) > 0 ) {
                foreach( $answer_table->fetchAll($answer_select) as $answer ) {
                    $answer->delete();
                }
            }

            // Delete topicmaps
            $topicmaps_table = Engine_Api::_()->getDbtable('topicmaps', 'ggcommunity');
            $topicmap_select = $topicmaps_table->select()->where('parent_id = ?', $payload->getIdentity());
            if( count($topicmaps_table->fetchAll($topicmap_select)) > 0 ) {
                foreach( $topicmaps_table->fetchAll($topicmap_select) as $topicmap ) {
                    $topicmap->delete();
                }
            }
            
          
            //delete Votes for this question
            $delete = Engine_Api::_()->getItemTable('ggcommunity_vote')->deleteVote($payload->getIdentity());

        }
        else if($payload instanceof Ggcommunity_Model_Answer ) {

            // Delete comments
            $comment_table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
            $comment_select = $comment_table->select()->where('parent_id = ?', $payload->getIdentity());
            if(count($comment_table->fetchAll($comment_select)) > 0) {
                foreach( $comment_table->fetchAll($comment_select) as $comment ) {
                    $comment->delete(); 
                }
            }

            // Delete votes for this answer
            $delete = Engine_Api::_()->getItemTable('ggcommunity_vote')->deleteVote($payload->getIdentity());

        }
        else if($payload instanceof Ggcommunity_Model_Comment) {

            // Delete votes for this comment
            $delete = Engine_Api::_()->getItemTable('ggcommunity_vote')->deleteVote($payload->getIdentity());

        }
        
    }

    /** EXTFOX
        * Every Time when user is being deleted make sure that you will delete all
        * - Questions that are leaved by this user
        * - Answers that are leaved by this user
        * - Comments that are leaved by this user
        * - Votes made by this user
    */
    public function onUserDeleteBefore($event) {
        
        $payload = $event->getPayload();

        // if deleted item is not user don't do nothing
        if(!$payload instanceof User_Model_User) return;

        //if it is user delete everthing from this user
        $user_id = $payload->user_id;

        // delete questions from this user
        $question_table = Engine_Api::_()->getDbtable('questions', 'ggcommunity');
        $question_select = $question_table->select()->where('user_id = ?', $user_id);
        if( count($question_table->fetchAll($question_select)) > 0 ) {
            foreach( $question_table->fetchAll($question_select) as $question ) {
                $question->delete();
            }
        }

        // delete answers from this user
        $answer_table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
        $answer_select = $answer_table->select()->where('user_id = ?', $user_id);
        if( count($answer_table->fetchAll($answer_select)) > 0 ) {
            foreach( $answer_table->fetchAll($answer_select) as $answer ) {
                $answer->delete();
            }
        }

        // delete comments from this user
        $comment_table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
        $comment_select = $comment_table->select()->where('user_id = ?', $user_id);
        if( count($comment_table->fetchAll($comment_select)) > 0 ) {
            foreach( $comment_table->fetchAll($comment_select) as $comment ) {
                $comment->delete();
            }
        }
        
        // delete comments from this user
        $vote_table = Engine_Api::_()->getDbtable('votes', 'ggcommunity');
        $vote_select = $vote_table->select()->where('user_id = ?', $user_id);
        if( count($vote_table->fetchAll($vote_select)) > 0 ) {
            foreach( $vote_table->fetchAll($vote_select) as $vote ) {
                $vote->delete();
            }
        }
        
        
    }

    /** EXTFOX
        * Every Time when answer is being created 
    */
    public function onAnswerCreateBefore($event) {

    }
    

    /** EXTFOX
        * Every Time when comment is being created 
    */
    public function onCommentCreateBefore($event) {

    }

    
    /** EXTFOX
        * Every Time when Item is being edited 
    */
    public function onItemEditBefore($event) {

    }

    public function onRenderLayoutDefault($event) {

        $view = $event->getPayload();

        $view->headLink()->appendStylesheet('https://fonts.googleapis.com/css?family=Nunito:400,600,700|Open+Sans:300,400,600,700');
    }

    public function onRenderLayoutDefaultSimple($event) {
        // Forward
        return $this->onRenderLayoutDefault($event, 'simple');
    }
}
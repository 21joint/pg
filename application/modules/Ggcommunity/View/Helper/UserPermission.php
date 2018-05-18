<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    UserPermission
 */

class Ggcommunity_View_Helper_UserPermission extends Zend_View_Helper_Abstract
{

    public function userPermission($permission, $subject, $viewer = null) {

       
        if( null === $viewer ) {
            $viewer = Engine_Api::_()->user()->getViewer();
        }

        $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);

        // check some important stafs abut subject (in this case question/answer/comment)
        $type = $subject->getType();
        $id = $subject->getIdentity();
       
        if($type == 'ggcommunity_answer') {
            if($subject->accepted == 1 ? $class='black' : $class='');
        }
        // take correct controller when deleting items
        switch($type) {
            case 'ggcommunity_question':
                $controller = 'question-profile';
                $item_id = 'question_id';
                break;
            case 'ggcommunity_answer':
                $controller = 'answer-profile';
                $item_id = 'answer_id';
                break;
            case 'ggcommunity_comment':
                $controller = 'comment-profile';
                $item_id = 'comment_id';
            break;
        }
     
        switch ($permission) {
            case 'edit_question':
                if($permissions[$permission] == 0) {
                    return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Edit"), array("class" => "btn answer small blue", "disabled" => "disabled"));
                } else {
                    return $this->view->htmlLink(array("route" => "question_options","action" => "edit", "question_id"=> $subject->getIdentity()), $this->view->translate("Edit"), array("class" => "btn answer small blue"));
                }
                break;
            case 'edit_answer' :
                if( $subject->getOwner()->isSelf($viewer) || $viewer->isAdmin() ) {
                    
                    return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Edit"), array("class" => "btn answer small $class", "onclick" => "en4.ggcommunity.answer.edit('$type',$id)"));
                } else {
                    return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Edit"), array("class" => "btn answer small $class", "disabled" => "disabled"));
                }
                break;
            case 'comment_question' :
                if($permissions[$permission] == 0) {
                    if($subject->comment_count > 0) {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate(array("Comment | %s", "Comments | %s", $subject->comment_count),$this->view->locale()->toNumber($subject->comment_count)), array("class" => "btnanswer small blue", "id" => "count_question_comments", "onclick" => "switchTab('comment',$id)"));
                    } else {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Comment"), array("class" => "btn answer small blue","id" => "count_question_comments"));
                    }
                } else {
                    if($subject->comment_count > 0) {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate(array("Comment | %s", "Comments | %s", $subject->comment_count),$this->view->locale()->toNumber($subject->comment_count)), array("class" => "btn answer small blue", "id" => "count_question_comments", "onclick" => "switchTab('comment',$id)"));
                    } else {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Comment"), array("class" => "btn answer small blue", "id" => "count_question_comments", "onclick" => "switchTab('comment',$id)"));
                    }

                }
            case 'comment_answer' :
                if($permissions[$permission] == 0) {
                    if($subject->comment_count > 0) {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate(array("Comment | %s", "Comments | %s", $subject->comment_count),$this->view->locale()->toNumber($subject->comment_count)), array("class" => "btn answer small $class", "id" => "comment_counter_$id", "onclick" => "en4.ggcommunity.answer.comment('$type',$id)"));
                    } else {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Comment"), array("class" => "btn answer small $class", "id" => "comment_counter_$id"));
                    }
                } else {
                    if($subject->comment_count > 0) {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate(array("Comment | %s", "Comments | %s", $subject->comment_count),$this->view->locale()->toNumber($subject->comment_count)), array("class" => "btn answer small $class", "id" => "comment_counter_$id", "onclick" => "en4.ggcommunity.answer.comment('$type',$id)"));
                    } else {
                        return $this->view->htmlLink("javascript:void(0)", $this->view->translate("Comment"), array("class" => "btn answer small $class", "id" => "comment_counter_$id","onclick" => "en4.ggcommunity.answer.comment('$type',$id)"));
                    }
                }
                break;
            case 'edit_comment' :
                if($subject->getOwner()->isSelf($viewer) || $viewer->isAdmin()) {
                    return $this->view->htmlLink("javascript:void(0)", '<svg aria-hidden="true" data-prefix="fal" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>' . $this->view->translate("Edit"), array("class" => "edit-item option-item display-flex", "onclick" => "en4.ggcommunity.comment.edit('$type',$id)"));
                } else {
                    return;
                }
                break;
            case 'best_answer' :
                if($permissions[$permission] == 0) {
                    return $this->view->htmlLink("javascript:void(0)", '<svg style="margin-right:5px"  xmlns="http://www.w3.org/2000/svg"  xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="13px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"/><stop offset="1" stop-color="#5bc6cd"/></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"/><stop offset="1" stop-color="#51b2b6"/></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"/><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"/><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"/></svg>'. $this->view->translate("Chose Theory"), array("class" => "mark-best primary", "disabled" => "disabled"));
                } else {
                    return $this->view->htmlLink(array("route"=>"default", "module"=>"ggcommunity", "controller"=>"answer-profile","action"=>"best", "answer_id"=>$id), '<svg style="margin-right:5px" width="13px" height="13px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"/><stop offset="1" stop-color="#5bc6cd"/></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"/><stop offset="1" stop-color="#51b2b6"/></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"/><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"/><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"/></svg>'. $this->view->translate("Chose Theory"), array("class" => "mark-best primary smoothbox"));
                }
                break;
            case "delete_$type" :
                if($subject->getOwner()->isSelf($viewer) || $viewer->isAdmin()) {
                    return $this->view->htmlLink(array("route"=>"default", "module"=>"ggcommunity", "controller"=>$controller,"action"=>"delete",$item_id=>$id), '<svg aria-hidden="true" data-prefix="fal" data-icon="times-circle" role="img" width="19px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 464c-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216zm94.8-285.3L281.5 256l69.3 69.3c4.7 4.7 4.7 12.3 0 17l-8.5 8.5c-4.7 4.7-12.3 4.7-17 0L256 281.5l-69.3 69.3c-4.7 4.7-12.3 4.7-17 0l-8.5-8.5c-4.7-4.7-4.7-12.3 0-17l69.3-69.3-69.3-69.3c-4.7-4.7-4.7-12.3 0-17l8.5-8.5c4.7-4.7 12.3-4.7 17 0l69.3 69.3 69.3-69.3c4.7-4.7 12.3-4.7 17 0l8.5 8.5c4.6 4.7 4.6 12.3 0 17z" ></path></svg>'.$this->view->translate("Delete"), array("class"=>"delete-item smoothbox option-item display-flex" ) );
                
                } else {
                    return;
                }
                break;
         
                
        }
       
    }


}

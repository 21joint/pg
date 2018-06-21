<?php
    $item = $this->item;
    $subject = $this->subject;
?>

<ul class="topic_holder d-flex">
    <!-- right side -->
    <?php $question = Engine_Api::_()->getItem('ggcommunity_question', $item->parent_id); ?>
    <!-- Question side -->
    <li class="struggle_holder left-side d-block d-sm-flex px-xl-4 py-xl-3 px-lg-4 py-lg-3 px-1 py-3 mr-4  mb-0">
        <div class="struggle_box_left">
            <div class="struggle_left_side d-none d-sm-block">
                <a href="<?php $question->getOwner()->getHref();?>" class="struggle_owner_image">
                    <?php echo $this->itemPhoto($question->getOwner(), 'thumb.icon', array('class'=> 'owner_thumb')) ?>
                </a>
            </div>

            <div class="struggle_right-side w-sm-100 m-0">
                <a href="<?php echo $question->getHref();?>" class="struggle_title">
                <?php echo $question->body;?></a>
                <ul class="struggle_info d-flex">
                    <li class="struggle_time_created pr-2">
                        <?php echo 'asked '. Engine_Api::_()->ggcommunity()->time_elapsed_string($question->creation_date);?>
                    </li>
                    <li class="pr-2">᛫</li>
                    <li class="struggle_owner_name pr-2">
                        <?php echo $question->getOwner(); ?>
                    </li>
                    <li class="pr-2">᛫</li>

                    <li class="list-inline edit-list-item pr-4">
                        Mealtime
                    </li>
                </ul>
            </div>
        </div> 

        <div class="struggle_box_right col-xl-3 col-lg-3 col-5 d-none d-sm-block">
            <ul class="struggle_count_info <?php echo ($question->open==1 ? 'primary' : 'count_close_closed');?>">
                <li class="count_info" id="vote_count">
                    <span class="count votes counter <?php echo ($question->open==1 ? 'primary' : 'count_close');?>"><?php echo $question->up_vote_count;?></span>
                    <p class="count_title <?php echo ($question->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('votes');?></p>
                </li>
                <li class="count_info" id="answer_count">
                    <span class="count answers counter <?php echo ($question->open==1 ? 'primary' : 'count_close');?> <?php echo ( (($question->open == 1) && ($question->accepted_answer==1)) ? 'open_accepted_answer' : '');?> <?php echo ( (($question->open == 0) && ($question->accepted_answer==1)) ? 'closed_accepted_answer' : '');?>"><?php echo $question->answer_count;?></span>
                    <p class="count_title title_active"><?php echo $this->translate('theories');?></p>
                </li>
                <li class="count_info" id="comment_count">
                    <span class="count comments counter <?php echo ($question->open==1 ? 'primary' : 'count_close');?>"><?php echo $question->comment_count;?></span>
                    <p class="count_title <?php echo ($question->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('comments');?></p>
                </li>
            </ul>
        </div> <!--End of struggle right box-->

        <!-- for Mobile -->
        <div class="struggle-box_right-mobile d-block d-sm-none pt-1">
            <div class="holder-bottom-mobile d-flex justify-content-between">
                <ul class="d-flex  align-items-center col-3 justify-content-between left-side mx-0 px-0 ">
                    <!-- vote -->
                    <li class="count_info d-flex mr-3  align-items-center" id="vote_count">
                        <?php if($question->open==1):?>
                            <svg class="pr-2" width="15"aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" class=""></path></svg>
                        <?php  else:?>
                            <svg style="width:13px;padding-right:5px" aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#B9CFD1" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" class=""></path></svg>
                        <?php endif;?>
                        <span class="count votes counter  <?php echo ($question->open==1 ? 'primary' : 'count_close');?>"><?php echo $question->up_vote_count;?></span>
                    </li>
                    <!-- answer count -->
                    <li class="count_info d-flex  align-items-center mr-3 <?php echo ($question->open==1 ? 'primary' : 'count_close');?> <?php echo ( (($question->open == 1) && ($question->accepted_answer == 1)) ? 'svg-icons-one' : '');?> <?php echo ( (($question->open == 0) && ($question->accepted_answer==1)) ? 'svg-icons-two' : '');?> " id="answer_count">

                        <div class="first-icon">
                            <?php echo ($question->open == 1 ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.98 13.34"  width="13"><title>star_unanswered_open</title><path d="M6.67,2.15,8.09,3.94l.4.5.6-.15,2.37-.57L10.22,5.38,9.75,6l.49.61,1.47,1.83-2.62-1-.79-.3-.43.72L6.19,10.72l.18-3.16V6.77l-.76-.22L3,5.77l2.82-.68.72-.17V4.18l.11-2M6.09,0a.31.31,0,0,0-.31.3L5.56,4.12.23,5.4a.31.31,0,0,0,0,.6L5.39,7.5l-.3,5.34a.46.46,0,0,0,.43.49h0A.45.45,0,0,0,6,13.1L8.76,8.37l4.7,1.82h.14a.39.39,0,0,0,.3-.63L11,6l2.44-3.2a.31.31,0,0,0-.25-.5h-.12l-4.25,1L6.33.12A.31.31,0,0,0,6.09,0Z" transform="translate(0 0)" fill="#5cc7cd"/></svg>'  : '<svg  width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.98 13.33"><title>star_unanswered_closed</title><path d="M6.67,2.15,8.09,3.94l.4.5.6-.15,2.37-.57L10.22,5.38,9.75,6l.49.61,1.47,1.83-2.62-1-.79-.3-.43.72L6.19,10.72l.18-3.16V6.77l-.76-.22L3,5.77l2.82-.68.72-.17V4.18l.11-2M6.09,0a.31.31,0,0,0-.31.3L5.56,4.12.23,5.4a.31.31,0,0,0,0,.6L5.39,7.5l-.3,5.34a.46.46,0,0,0,.43.49h0A.45.45,0,0,0,6,13.1L8.76,8.37l4.7,1.82h.14a.39.39,0,0,0,.3-.63L11,6l2.44-3.2a.31.31,0,0,0-.25-.5h-.12l-4.25,1L6.33.12A.31.31,0,0,0,6.09,0Z" transform="translate(0 0)" fill="#b9cfd1"/></svg>');?>
                        </div>

                        <div class="second-icon">
                            <?php echo ( (($question->open == 1) && ($question->accepted_answer == 1)) ? '<svg xmlns="http://www.w3.org/2000/svg" width="13" viewBox="0 0 13.97 13.33"><title>star_answered_open</title><path d="M13.61,10.22h-.15L8.75,8.37,5.93,13.1a.46.46,0,0,1-.4.23.45.45,0,0,1-.44-.46v0l.29-5.33L.22,6a.32.32,0,0,1,0-.61L5.56,4.12,5.76.3A.31.31,0,0,1,6.08,0h0a.3.3,0,0,1,.24.12l2.56,3.2,4.25-1h.07a.31.31,0,0,1,.25.5L11,6l2.88,3.61a.38.38,0,0,1-.28.61Z" transform="translate(0 0)" fill="#5cc7cd"/></svg>' : '');?>
                        </div>
                        
                        <div class="third-icon">
                            <?php echo ( (($question->open == 0) && ($question->accepted_answer==1)) ? '<svg xmlns="http://www.w3.org/2000/svg"  width="13" viewBox="0 0 13.97 13.33"><title>star_answered_closed</title><path d="M13.61,10.22h-.15L8.75,8.37,5.93,13.1a.46.46,0,0,1-.4.23.45.45,0,0,1-.44-.46v0l.29-5.33L.22,6a.32.32,0,0,1,0-.61L5.56,4.12,5.76.3A.31.31,0,0,1,6.08,0h0a.3.3,0,0,1,.24.12l2.56,3.2,4.25-1h.07a.31.31,0,0,1,.25.5L11,6l2.88,3.61a.38.38,0,0,1-.28.61Z" transform="translate(0 0)" fill="#b9cfd1"/></svg>' : '');?>
                        </div>

                        <div class="holder-counter-number pl-1 <?php echo ($question->open==1 ? 'primary' : 'count_close');?>">
                            <?php echo $question->answer_count;?>
                        </div>

                    </li>
                    <!-- comment -->
                    <li class="count_info d-flex  align-items-center  <?php echo ($question->open==1 ? 'primary' : 'count_close');?>" id="comment_count">
                        
                        <?php if($question->open == 1):?>
                                <svg style="width:13px;padding-right:5px" aria-hidden="true" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#5CC7CE" d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z" class=""></path></svg>
                        <?php else:?>
                                <svg style="width:13px;padding-right:5px" aria-hidden="true" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#B9CFD1" d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z" class=""></path></svg>
                        <?php endif;?>
                        <?php echo $question->comment_count;?>
                    </li>
                </ul>
            </div>
        </div>

    </li>
    
    <!-- Answer side -->
    <!-- left side -->
    <li class="struggle_holder right-side d-block d-sm-flex px-lg-4 py-3 mb-0 px-sm-0">
        <div class="struggle_box_left w-100">
            <div class="struggle_left_side">
                <a href="<?php echo $item->getOwner()->getHref();?>" class="struggle_owner_image">
                    <?php echo $this->itemPhoto($item->getOwner(), 'thumb.icon', array('class'=> 'owner_thumb')) ?>
                </a>
            </div>

            <div class="struggle_right-side">
                <ul class="struggle_info d-flex">
                    <li class="struggle_time_created">
                        <div class="top-holder">
                            <a class="struggle_title" href="javascript:void(0);"><?php echo $item->body;?></a>
                        </div>
                        <div class="bottom-holder-comments d-flex align-items-center">
                            <div class="vote d-flex align-items-center mr-3">
                                <svg style="width:13px;padding-right:5px" aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" class=""></path></svg>
                                <?php echo $item->up_vote_count;?>
                            </div>
                            <div class="comment d-flex align-items-center">
                                <svg style="width:13px;padding-right:5px" aria-hidden="true" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#5CC7CE" d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"></path></svg>
                                <?php echo $item->comment_count; ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div> 
    </li>
</ul>
        
  

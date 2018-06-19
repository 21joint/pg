<div class="browse_content_holder">

    <div class="browse_top_holder">
        <div class="browse_holder">
            <div class="search_description">
                <p><?php echo $this->translate('Search');?></p>
                <?php if($this->query):?>
                    <p class="bold_text">
                        <?php echo $this->translate('results found containing ');?>
                        <span><?php echo $this->query; ?></span>
                    </p>
                <?php endif; ?>
            </div>
            <div class="search_form">
                <?php echo $this->form->render($this); ?>
            </div>
        </div>
    </div> <!-- End of browse_top_holder-->

    <div class="browse_main_holder">

        <div class="main_top_box">
            <div class="main_top_left">
                <?php echo $this->translate(array("%s  result", "%s results", $this->paginator->getTotalItemCount()),
                   $this->locale()->toNumber($this->paginator->getTotalItemCount())); ?>
            </div>
            <div class="main_top_right large-6 small-12">
                <ul class="top_tabs">
                    <?php $uri=$_SERVER['REQUEST_URI'];
                        if(strpos($uri, 'submit') !== false ? $rest ='&param=' : $rest ='?param=');
                        // make sure to delete previous param and add a new one
                        if(strpos($uri, 'param') !== false) {
                            $param = substr($uri, strpos($uri, 'param=')-1);
                            $only_param = substr($param, strpos($param, '=')+1);
                            $uri = str_replace($param, "", $uri);
                        } 
                    ?>
                    
                    <li class="tab" id="latest">
                        <a href="<?php echo $uri .$rest .'latest';?>" class="<?php echo ($only_param == 'latest' ? 'border': '');?>"><?php echo $this->translate('latest');?></a>
                    </li>
                    <li class="tab" id="trending">
                        <a href="<?php echo $uri .$rest .'trending';?>" class="<?php echo ($only_param == 'trending' ? 'border': '');?>"><?php echo $this->translate('trending');?></a>
                    </li>
                    <li class="tab" id="unanswered">
                        <a href="<?php echo $uri .$rest .'unanswered';?>" class="<?php echo ($only_param == 'unanswered' ? 'border': '');?>"><?php echo $this->translate('unanswered');?></a>
                    </li>
                    <li class="tab" id="active">
                        <a href="<?php echo $uri .$rest .'active';?>" class="<?php echo ($only_param == 'active' ? 'border': '');?>"><?php echo $this->translate('active');?></a>
                    </li>
                    <li class="tab" id="closed">
                        <a href="<?php echo $uri .$rest .'closed';?>" class="<?php echo ($only_param == 'closed' ? 'border': '');?>"><?php echo $this->translate('closed');?></a>
                    </li>
                </ul>
            </div>
        </div> <!-- End of main_top_box-->

        <div class="main_box">
        <?php if($this->paginator->getTotalItemCount() > 0):?>
            <ul class="topic_holder">
                <?php foreach($this->paginator as $item):?>
                    <li class="struggle_holder">
                        <div class="struggle_box_left">
                            <div class="struggle_left_side">
                                <a href="<?php echo $item->getOwner()->getHref();?>" class="struggle_owner_image">
                                    <?php echo $this->itemPhoto($item->getOwner(), 'thumb.icon', array('class'=> 'owner_thumb')) ?>
                                </a>
                            </div>

                            <?php $item_type = $item->getType(); ?>
                            <div class="struggle_right-side">
                                <a href="<?php echo $item->getHref();?>" class="struggle_title">
                                <?php echo $item->getTitle();?></a>
                                <ul class="struggle_info">
                                    <li class="struggle_time_created">
                                        <?php echo 'asked '. Engine_Api::_()->ggcommunity()->time_elapsed_string($item->creation_date);?>
                                    </li>
                                    <li>᛫</li>
                                    <li class="struggle_owner_name">
                                        <?php echo $item->getOwner(); ?>
                                    </li>
                                    <li class="list-inline edit-list-item">
                                        <?php echo $this->htmlLink(array("route" => "question_options","action" => "edit", "question_id"=> $item->getIdentity()), '<svg aria-hidden="true" data-prefix="fas" data-icon="edit" role="img" width="12px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-edit fa-w-18 fa-9x"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z" class=""></path></svg>' . $this->translate("Edit"), array("class" => "edit-item option-item display-flex"));?>
                                    </li>
                                    <li class="list-inline delete-list-item">
                                        <?php echo $this->userPermission("delete_$item_type", $item);?>
                                    </li>
                                </ul>
                            </div>
                        </div> <!-- End of struggle left box-->

                        <div class="struggle_box_right large-3 medium-3 small-5">
                            <ul class="struggle_count_info large-10 large-offset-2 <?php echo ($item->open==1 ? 'primary' : 'count_close_closed');?>">
                                <li class="count_info" id="vote_count">
                                    <span class="count votes counter <?php echo ($item->open==1 ? 'primary' : 'count_close');?>"><?php echo $item->up_vote_count;?></span>
                                    <p class="count_title <?php echo ($item->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('votes');?></p>
                                </li>
                                <li class="count_info" id="answer_count">
                                    <span class="count answers counter <?php echo ($item->open==1 ? 'primary' : 'count_close');?> <?php echo ( (($item->open == 1) && ($item->accepted_answer==1)) ? 'open_accepted_answer' : '');?> <?php echo ( (($item->open == 0) && ($item->accepted_answer==1)) ? 'closed_accepted_answer' : '');?>"><?php echo $item->answer_count;?></span>
                                    <p class="count_title <?php echo ($item->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('theories');?></p>
                                </li>
                                <li class="count_info" id="comment_count">
                                    <span class="count comments counter <?php echo ($item->open==1 ? 'primary' : 'count_close');?>"><?php echo $item->comment_count;?></span>
                                    <p class="count_title <?php echo ($item->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('comments');?></p>
                                </li>
                            </ul>
                        </div> <!--End of struggle right box-->
                        

                    </li> <!-- End of struggle holder-->

                    


                <?php endforeach;?>
            </ul>

            <?php else: ?>
               <div class="tip_message">
                   <span class="no_results"><?php echo $this->translate('No results found for this criteria');?></span>
               </div>
           <?php endif;?>

        </div> <!-- End of main_box-->

        <!-- pagination -->
        <div>
            <?php echo $this->paginationControl($this->paginator, null, null, array(
                'pageAsQuery' => true,
                'query' => $this->params
            )); ?>
        </div>
    </div> <!-- End of browse_main_holder-->
</div>

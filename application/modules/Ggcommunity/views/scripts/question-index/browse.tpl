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
                    <li class="struggle_holder d-flex flex-wrap">
                        <div class="struggle_box_left large-9 medium-12 small-12">
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
                                        <?php echo 'asked '. Engine_Api::_()->ggcommunity()->time_elapsed_string($item->creation_date); ?>
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
                                    <svg class="d-lg-none" aria-hidden="true" data-prefix="fas" width="12px" style="margin-right:3px;" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
<!-- Check for primary or closed and adjust fill to match -->
                                        <path fill="#17becb" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" ></path></svg>
                                    <span class="count votes counter <?php echo ($item->open==1 ? 'primary' : 'count_close');?>"><?php echo $item->up_vote_count;?></span>
                                    <p class="count_title <?php echo ($item->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('votes');?></p>
                                </li>
                                <li class="count_info" id="answer_count">
                                    <!-- Make only visible for smaller than lg screens -->
                                    <?php if($item->accepted_answer==1) { ?>
                                    <svg class="d-lg-none" xmlns="http://www.w3.org/2000/svg" style="margin-right:3px;" width="13px" height="13px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 38"><defs><linearGradient id="z" x1="-173.22" y1="1009.42" x2="-172.4" y2="1010.06" gradientTransform="matrix(13.72, 0, 0, -11.03, 2403.25, 11146.77)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5BC7CE"/><stop offset="1" stop-color="#5BC7CE"/></linearGradient><linearGradient id="x" x1="-304.1" y1="1050.64" x2="-303.1" y2="1050.64" gradientTransform="matrix(16.54, 0, 0, -10.11, 5029.61, 10635.32)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5BC7CE"/><stop offset="1" stop-color="#5BC7CE"/></linearGradient></defs><title>star_pg</title><path d="M38.29,8.11l-7.17,9.44L25,9.36,36.9,6.64C38.65,6.23,39.15,6.93,38.29,8.11Z" fill="url(#z)"/><path d="M16.54,11.4.87,15.33c-1,.23-1.22,1.29-.3,1.7L15.86,21.5Z" fill="url(#x)"/><path d="M14.56,36.53l2-35.61c0-1,1-1.25,1.68-.43L39.58,27.06c.79,1,.57,2.58-1.63,1.72L25.08,23.86,17,37.19a1.3,1.3,0,0,1-2.49-.66Z" fill="#5BC7CE"/></svg>
                                    <?php }else{ ?>
                                    <svg class="d-lg-none" viewBox="0 0 42 40" width="13px" height="13px"
                                    preserveAspectRatio="xMidYMid meet" x="0" y="0" xmlns="http://www.w3.org/2000/svg"><path d="M32.52 18.024l8.06 10.036c.79 1 .57 2.58-1.63 1.72l-12.87-4.92L18 38.19a1.3 1.3 0 0 1-2.49-.66h.05l.852-15.161L1.57 18.03c-.92-.41-.7-1.47.3-1.7l15.093-3.785.597-10.625c0-1 1-1.25 1.68-.43l7.068 8.8L37.9 7.64c1.75-.41 2.25.29 1.39 1.47l-6.77 8.914z" fill-rule="nonzero" stroke="#5BC7CE" stroke-width="4" fill="none"/></svg>
                                    <?php } ?>
                                    <!-- Check for answered/unanswered - filled/outline --> 
                                    <span class="count answers counter <?php echo ($item->open==1 ? 'primary' : 'count_close');?> <?php echo ( (($item->open == 1) && ($item->accepted_answer==1)) ? 'open_accepted_answer' : '');?> <?php echo ( (($item->open == 0) && ($item->accepted_answer==1)) ? 'closed_accepted_answer' : '');?>"><?php echo $item->answer_count;?></span>
                                    <p class="count_title <?php echo ($item->open==1 ? 'title_active' : 'title_close');?>"><?php echo $this->translate('theories');?></p>
                                </li>
                                <li class="count_info" id="comment_count">
                                    <svg class="d-lg-none" aria-hidden="true" width="12px"  style="margin-right:3px;"  data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-comments fa-w-18 fa-9x"><path fill="#17becb" d="M224 358.857c-37.599 0-73.027-6.763-104.143-18.7-31.375 24.549-69.869 39.508-110.764 43.796a8.632 8.632 0 0 1-.89.047c-3.736 0-7.111-2.498-8.017-6.061-.98-3.961 2.088-6.399 5.126-9.305 15.017-14.439 33.222-25.79 40.342-74.297C17.015 266.886 0 232.622 0 195.429 0 105.16 100.297 32 224 32s224 73.159 224 163.429c-.001 90.332-100.297 163.428-224 163.428zm347.067 107.174c-13.944-13.127-30.849-23.446-37.46-67.543 68.808-64.568 52.171-156.935-37.674-207.065.031 1.334.066 2.667.066 4.006 0 122.493-129.583 216.394-284.252 211.222 38.121 30.961 93.989 50.492 156.252 50.492 34.914 0 67.811-6.148 96.704-17 29.134 22.317 64.878 35.916 102.853 39.814 3.786.395 7.363-1.973 8.27-5.467.911-3.601-1.938-5.817-4.759-8.459z" class=""></path></svg>
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

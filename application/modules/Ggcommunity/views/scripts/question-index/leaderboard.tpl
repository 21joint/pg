<div class="leaderboard_content browse_content_holder">

    <div class="browse_top_holder" id="leaderboard_top">
        <div class="browse_holder" id="leaderboard_holder">
            <div class="leaderboard_top_left large-3 medium-3">
                <p class="leaderboard_title"><?php echo $this->translate('Leaderboard');?></p>
            </div>
            <div class="leaderboard_top_right large-6 medium-6 small-7">
               <a href="javascript:void(0);" class="leaderboard_overall">
                   <?php echo $this->translate('Overall');?>
               </a>
               <a href="javascript:void(0);" class="leaderboard_week">
                   <?php echo $this->translate('Week');?>
               </a>
               <a href="javascript:void(0);" class="leaderboard_month">
                   <?php echo $this->translate('Month');?>
               </a>
            </div>
        </div>
    </div> <!-- End of browse_top_holder-->

    <div class="browse_main_holder" id="leaderboard_main_box">

        <div class="main_top_box" id="leaderboard_main_top_box">
            <div class="main_top_left large-2 medium-2 small-4">
                <ul class="leaderboard_main_top_left">
                    <li class="leaderboard_item" id="rank"><?php echo $this->translate('Rank');?></li>
                    <li class="leaderboard_item" id="leader"><?php echo $this->translate('Leader');?></li>
                </ul>
            </div>
            <div class="main_top_right large-5 medium-5" id="leaderboard_main_top_right">
                <ul class="top_tab" id="leaderboard_tabs">
                    <li class="tab" id="overall">
                        <a href="javascript:void(0);"><?php echo $this->translate('Points');?></a>
                    </li>
                    <li class="tab" id="week">
                        <a href="javascript:void(0);"><?php echo $this->translate('Answers');?></a>
                    </li>
                    <li class="tab" id="month">
                        <a href="javascript:void(0);"><?php echo $this->translate('Questions');?></a>
                    </li>
                </ul>
            </div>
        </div> <!-- End of main_top_box-->

        <div class="main_box">
        <?php if($this->viewer->getIdentity() > 0):?>
            <?php $x = 1;?>
            <ul class="topic_holder">
                <?php for($i=0; $i< 10; $i++):?>
                    <li class="struggle_holder">
                        <div class="holder-all">
                            <div class="struggle_box_left large-6 medium-6 small-8">
                                <div class="struggle_left_side large-12 medium-12">
                                    <div class="counter-number large-1 medium-1 small-2">
                                        <?php echo $x++;?>
                                    </div>
                                    <a href="<?php echo $this->viewer->getOwner()->getHref();?>" class="struggle_owner_image">
                                        <?php echo $this->itemPhoto($this->viewer->getOwner(), 'thumb.icon', array('class'=> 'owner_thumb')) ?>
                                        <div class="owner_level">1
                                            <?php //echo $item->getOwner()->level_id;?>
                                        </div>
                                    </a>
                                    <a href="<?php //echo $item->getOwner()->getHref();?>" class="struggle_owner_name"> Kim Barnes
                                        <?php //echo $item->getOwner()->getTitle();?> 
                                    </a>
                                </div>
                            </div> <!-- End of struggle left box-->

                            <div class="struggle_box_right large-5 medium-5">
                                <ul class="struggle_count_info large-11 large-offset-1">
                                    <li class="count_info" id="points">
                                        <svg style="margin-right:5px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"/><stop offset="1" stop-color="#5bc6cd"/></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"/><stop offset="1" stop-color="#51b2b6"/></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"/><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"/><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"/></svg>
                                        <span class="count votes counter" id="points_count">36103</span>
                                    </li>
                                    <li class="count_info" id="answers">
                                        <span class="count answers counter" id="answers_count">2378</span>
                                    </li>
                                    <li class="count_info" id="questions">
                                        <span class="count comments counter" id="questions_count">82</p>
                                    </li>
                                </ul>
                            </div> <!--End of struggle right box-->
                        </div>
                    </li> <!-- End of struggle holder-->

                <?php endfor;?>
            </ul>

            <?php else: ?>
               <div class="tip_message">
                   <span class="no_results"><?php echo $this->translate('Noone has achieve leader title yet');?></span>
               </div>
           <?php endif;?>

        </div> <!-- End of main_box-->

        <!-- pagination -->
        <div>
            <?php //echo $this->paginationControl($this->paginator, null, null, array(
                //'pageAsQuery' => true,
                //'query' => $this->params
            //)); ?>
        </div>
    </div> <!-- End of browse_main_holder-->
</div>

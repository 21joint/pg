<div class="answer_holder_box" id="answer_holder_box_<?php echo $this->answer->getIdentity(); ?>">
    <div class="holder-box <?php echo ($this->answer->accepted == 1 ? 'green' : 'white')?>" id="item_main_box_<?php echo $this->answer->getIdentity()?>">
        <div class="item-main-description">
            <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                    'item' => $this->answer,
                    'viewer' =>$this->viewer,
            )); ?>
        </div>
    </div>
    <?php
        $params = array(
            'type' => 'ggcommunity_answer',
            'id' => $this->answer->getIdentity()
        );
        $paginator = Engine_Api::_()->getItemTable('ggcommunity_comment')->getCommentsPaginator($params);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber(1);
    ?>
        
    <!-- Box with form for new comment and all comments listed for that answer -->
    <div class="holder-box white large-11 medium-11 large-offset-1 medium-offset-1 comments-holder none" id="comment_holder_<?php echo $this->answer->getType();?>_<?php echo $this->answer->getIdentity();?>">

        <!-- Render form for new comment(for answer) -->
        <?php if($this->permissions['comment_answer'] != 0) :?>
            <div class="comment_form border-bottom" id="comment_holder_form">
                <?php echo $this->form_comment->render($this) ?>
            </div>
        <?php endif; ?>
        <div class="comments_container" id="comments_box_<?php echo $this->answer->getIdentity();?>">
            <?php if(count($paginator) > 0):?>
            
                <!-- Display all comments one by one for this answer -->
                <?php foreach($paginator as $item):?>
                    <div class="item-main-description border-bottom p-10 comment_holder_box" id="comment_<?php echo $item->getIdentity();?>">
                        <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                                'item' => $item,
                                'viewer' =>$this->viewer,
                        )); ?>
                    </div>
                <?php endforeach; ?>

                <div>
                    <?php echo $this->paginationControl($paginator, null, null, array(
                    'pageAsQuery' => true,
                    'query' => $params,
                    )); ?>
                </div>

            <?php else: ?>
                <p class="tip_msg" id="no_comments_tip">
                    <?php echo $this->translate('There are no comments to show for this answer'); ?> 
                </p>
            <?php endif; ?>
        </div>

    </div> <!-- End of comment box-->
</div>
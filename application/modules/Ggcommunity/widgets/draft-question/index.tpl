<div class="draft_widget">
    <!-- draft holder -->
    <div class="draft_holder">
        <div class="draft_white_frame">
            <div class="draft_main_holder">
                <div class="holder-drafts">
                    <!-- title holder -->
                    <h1 class="dw_title"><?php echo $this->title;?></h1>
                    <hr class="large-4">
                    <!-- description holder -->
                    <div class="description-holder large-11">
                        <p class="dw_content"><?php echo $this->content;?></p>
                    </div>
                    <!-- holder button -->
                    <div class="holder-button large-11">

                        <?php echo $this->userPermission('edit_question', $this->subject);?>
                    
                        <a href="<?php echo $this->url(array('module'=>'ggcommunity','controller'=>'question-profile','action' => 'publish', 'question_id'=>$this->subject->getIdentity()), 'default', true); ?>" class="btn  primary active smoothbox">
                            <?php echo $this->translate('Publish');?>
                        </a>
                        
                    </div>
                </div>
                   
            </div>
        </div>
    </div>

    
</div>
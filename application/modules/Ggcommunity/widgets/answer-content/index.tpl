<div class="sort_items_box" id="item_container_<?php echo $this->subject->getIdentity();?>">
    <div class="holder-box sort-box display-flex">
        <div class="left">
           
        </div>
        <div class="right display-flex">
            <!-- Render form here for sorting answers/comments -->
        </div>
    </div> <!-- End of sorting box-->
    
    <!-- Answer/Comment Box -->
    <div class="answer_full_box" id="answer-full-box">
        <!-- <?php echo $this->nextid;?> -->
        <script type="text/javascript" id="first" >
            en4.core.runonce.add(function() {

                var answer_viewmore = document.getElementById('answer_viewmore');
                var answer_loading = document.getElementById('answer_loading');
                var answer_viewmore_link = document.getElementById('answer_viewmore_link');

                var subject_id = <?php echo sprintf('%d', $this->subject->getIdentity()) ?>;
                var next_id = <?php echo sprintf('%d', $this->nextid) ?>;
                var subject_guid = '<?php echo $this->subjectGuid ?>';
                var endOfAnswer = <?php echo ( $this->endOfAnswer ? 'true' : 'false' ) ?>;
                var anchor = document.getElementById('answers_box');
                
                var ViewMore = window.ViewMore = function(next_id, subject_guid) {


                    var url = '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'answer-index', 'action' => 'index','answer_id' => $this->nextid, 'subject_id' => $this->subject->getIdentity(), 'shown_ids'=>$this->shown_ids), 'default', true) ?>';    

                    if(!answer_viewmore.classList.contains('none')) {
                        answer_viewmore.classList.add('none');
                    }
                    if(answer_loading.classList.contains('none')) {
                        answer_loading.classList.remove('none');
                        answer_loading.classList.add('block');
                    }
                    
                    en4.core.request.send(new Request.HTML({
                        url : url,
                        data : {
                            format : 'html',
                            'maxid' : next_id,
                            'subject' : subject_guid
                        },
                        onComplete: function(responseHTML) {
                         
                            var all = $$('div.answer_holder_box');
                            var last = all[all.length -1];
                            last.parentNode.insertBefore(responseHTML[1], last.nextSibling);
                            Smoothbox.bind(anchor);
                        }
                    }) )
 
                }

                

                if( next_id > 0 && !endOfAnswer ) {
                    
                    if(answer_viewmore.classList.contains('none')) {
                        answer_viewmore.classList.remove('none');
                    }

                    if(!answer_loading.classList.contains('none')) {
                        answer_loading.classList.add('none');
                    }
                    
                
                    answer_viewmore_link.removeEvents('click').addEvent('click', function(){
                
                        ViewMore(next_id, subject_guid);
                    });
                } else {

                    if(!answer_viewmore.classList.contains('none')) {
                        answer_viewmore.classList.add('none');
                    }

                    if(!answer_loading.classList.contains('none')) {
                        answer_loading.classList.add('none');
                    }
                
                }
            
            });
            
        
        </script>


        
        <div id="answers_box">
            <?php if($this->best):?>
            <?php $answer = $this->best;?>
            <div class="answer_holder_box" id="answer_holder_box_<?php echo $answer->getIdentity(); ?>">
                    <div class="holder-box green" id="item_main_box_<?php echo $answer->getIdentity(); ?>">
                        <div class="item-main-description">
                            <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                                'item' => $answer,
                                'viewer' => $this->viewer,
                            )); ?>
                            
                            <div class="item_edit_content large-9 medium-9 large-offset-2 medium-offset-2 columns none" id="edit_item_<?php echo $answer->getIdentity();?>">
                                <?php echo $this->form_answer_edit->render($this) ?>
                            </div>
                        
                        </div>
                    </div>
                    <?php 
                        if( !empty($answer) ) {
                            $answer_guid = $answer->getGuid(false);
                        }

                        $all_comments = $answer->comment_count;
                      
                        $table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
                        $limit = 3;
                    
                        $select = $table->select()
                            ->where('parent_type = ?', $answer->getType())
                            ->where('parent_id = ?', $answer->getIdentity())
                            ->order('comment_id DESC')
                            ->limit($limit)
                        ;
                        $comments = $table->fetchAll($select);
                          
                      
                        // Parametars for view more
                        $nextid = null;
                        $endOfComment = false;
                    
                        // Are we at the end?
                        if( count($comments) < $limit ) {
                            $endOfComment = true;
                            $nextid = 0;
                        } else {
                            $nextid =  $comments[$limit-1]->comment_id;  
                        
                        }
                        
                        if($answer->comment_count - $limit < 1) {
                            $nextid = 0;
                        } 
                          
                    ?>
                    <script type="text/javascript" id="comments" >
                        
                        en4.core.runonce.add(function() {

                            var subject_id = <?php echo sprintf('%d', $answer->getIdentity()) ?>;
                            var subject_type = '<?php echo $answer->getType(); ?>';
                            var next_id = <?php echo sprintf('%d', $nextid) ?>;
                            var subject_guid = '<?php echo $answer_guid ?>';
                            var endOfComment = <?php echo ( $endOfComment ? 'true' : 'false' ) ?>;
                            var anchor = document.getElementById('comments_box_'+ subject_id);
                            

                            var comment_viewmore = document.getElementById('comment_viewmore_'+ subject_type + '_' + subject_id);
                            var comment_loading = document.getElementById('comment_loading_'+ subject_type + '_' + subject_id);
                            var comment_viewmore_link = document.getElementById('comment_viewmore_link_'+ subject_type + '_' + subject_id);

                            var ViewMore = window.ViewMore = function(next_id, subject_guid) {


                                var url = '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'comment-index', 'action' => 'index','comment_id' => $nextid, 'subject_id' => $answer->getIdentity(), 'subject_type'=>$answer->getType()), 'default', true) ?>';    

                                if(!comment_viewmore.classList.contains('none')) {
                                    comment_viewmore.classList.add('none');
                                }
                                if(comment_loading.classList.contains('none')) {
                                    comment_loading.classList.remove('none');
                                    comment_loading.classList.add('block');
                                }
                                
                                en4.core.request.send(new Request.HTML({
                                    url : url,
                                    data : {
                                        format : 'html',
                                        'maxid' : next_id,
                                        'subject' : subject_guid
                                    },
                                    onComplete: function(responseHTML) {
                                        var all = $$('div#comment_holder_' + subject_type + '_'+subject_id+ ' div.comment_holder_box');
                                        var last = all[all.length -1];
                                        last.parentNode.insertBefore(responseHTML[1], last.nextSibling);
                                        Smoothbox.bind(anchor);
                                    }
                                }) )
            
                            }


                            if( next_id > 0 && !endOfComment ) {
                                
                                if(comment_viewmore.classList.contains('none')) {
                                    comment_viewmore.classList.remove('none');
                                }

                                if(!comment_loading.classList.contains('none')) {
                                    comment_loading.classList.add('none');
                                }
                                
                                comment_viewmore_link.removeEvents('click').addEvent('click', function(){
                            
                                    ViewMore(next_id, subject_guid);
                                });
                            } else {

                                if(!comment_viewmore.classList.contains('none')) {
                                    comment_viewmore.classList.add('none');
                                }

                                if(!comment_loading.classList.contains('none')) {
                                    comment_loading.classList.add('none');
                                }
                            
                            }
                        
                        });
                    
                    </script>

                    <!-- Box with form for new comment and all comments listed for that answer -->
                    <div class="holder-box holder-width-two white large-11 medium-11 large-offset-1 medium-offset-1  comments-holder none" id="comment_holder_<?php echo $answer->getType();?>_<?php echo $answer->getIdentity();?>">
                        <!-- Render form for new comment(for answer) -->
                        
                        <?php if($this->permissions['comment_answer'] != 0) :?>
                            <div class="comment_form border-bottom" id="comment_holder_form">
                                <?php echo $this->form_comment->render($this) ?>
                            </div>
                        <?php endif; ?>
                        <div class="comments_container" id="comments_box_<?php echo $answer->getIdentity();?>">
                            <?php if(count($comments) > 0):?>
                            
                                <!-- Display all comments one by one for this answer-->
                                <?php foreach($comments as $item):?>
                                    <div class="item-main-description border-bottom p-10 comment_holder_box" id="comment_<?php echo $item->getIdentity(); ?>">
                                        <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                                                'item' => $item,
                                                'viewer' =>$this->viewer,
                                        )); ?>
                                        <div class="item_edit_content large-9 medium-9 large-offset-2 medium-offset-2 columns none" id="edit_item_<?php echo $item->getIdentity();?>">
                                            <?php echo $this->form_answer_edit->render($this) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?> <!-- end of comments fro this answer-->
                                <!-- This should be redefinte in view more  -->
                                <div id="ajax_responses" style="display:none;">
                                    <script type="text/javascript" id="response"></script>
                                </div>
                            
                                <div class="comment_viewmore none" id="comment_viewmore_<?php echo $answer->getType() . '_' . $answer->getIdentity()?>">
                                    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
                                        'id' => "comment_viewmore_link_". $answer->getType() . '_' .$answer->getIdentity() ,
                                        'class' => 'buttonlink icon_viewmore'
                                    )) ?>
                                </div> 

                                <div class="comment_viewmore none" id="comment_loading_<?php echo $answer->getType() . '_' .$answer->getIdentity()?>">
                                    <i class="fa-spinner fa-spin fa"></i>
                                    <?php echo $this->translate("Loading ...") ?>
                                </div>
                            
                            <?php else: ?>
                                <p class="tip_msg" id="no_comments_tip"> There are no comments to show for this answer</p>
                            <?php endif; ?>
                        </div>
                    
                    </div> <!-- End of comment box--> 
                    
                </div>
            <?php endif; ?>
            <?php foreach($this->paginator as $answer):?>
                <div class="answer_holder_box" id="answer_holder_box_<?php echo $answer->getIdentity(); ?>">
                    <div class="holder-box <?php echo ($answer->accepted == 1 ? 'green' : 'white')?>" id="item_main_box_<?php echo $answer->getIdentity(); ?>">
                        <div class="item-main-description">
                            <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                                'item' => $answer,
                                'viewer' => $this->viewer,
                            )); ?>
                            
                            <div class="item_edit_content large-9 medium-9 large-offset-2 medium-offset-2 columns none" id="edit_item_<?php echo $answer->getIdentity();?>">
                                <?php echo $this->form_answer_edit->render($this) ?>
                            </div>
                        
                        </div>
                    </div>
                    <?php 
                        if( !empty($answer) ) {
                            $answer_guid = $answer->getGuid(false);
                        }

                        $all_comments = $answer->comment_count;
                      
                        $table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
                        $limit = 3;
                    
                        $select = $table->select()
                            ->where('parent_type = ?', $answer->getType())
                            ->where('parent_id = ?', $answer->getIdentity())
                            ->order('comment_id DESC')
                            ->limit($limit)
                        ;
                        $comments = $table->fetchAll($select);
                          
                      
                        // Parametars for view more
                        $nextid = null;
                        $endOfComment = false;
                    
                        // Are we at the end?
                        if( count($comments) < $limit ) {
                            $endOfComment = true;
                            $nextid = 0;
                        } else {
                            $nextid =  $comments[$limit-1]->comment_id;  
                        
                        }
                        
                        if($answer->comment_count - $limit < 1) {
                            $nextid = 0;
                        } 
                          
                    ?>
                    <script type="text/javascript" id="comments" >
                        
                        en4.core.runonce.add(function() {

                            var subject_id = <?php echo sprintf('%d', $answer->getIdentity()) ?>;
                            var subject_type = '<?php echo $answer->getType(); ?>';
                            var next_id = <?php echo sprintf('%d', $nextid) ?>;
                            var subject_guid = '<?php echo $answer_guid ?>';
                            var endOfComment = <?php echo ( $endOfComment ? 'true' : 'false' ) ?>;
                            var anchor = document.getElementById('comments_box_'+ subject_id);
                            

                            var comment_viewmore = document.getElementById('comment_viewmore_'+ subject_type + '_' + subject_id);
                            var comment_loading = document.getElementById('comment_loading_'+ subject_type + '_' + subject_id);
                            var comment_viewmore_link = document.getElementById('comment_viewmore_link_'+ subject_type + '_' + subject_id);

                            var ViewMore = window.ViewMore = function(next_id, subject_guid) {


                                var url = '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'comment-index', 'action' => 'index','comment_id' => $nextid, 'subject_id' => $answer->getIdentity(), 'subject_type'=>$answer->getType()), 'default', true) ?>';    

                                if(!comment_viewmore.classList.contains('none')) {
                                    comment_viewmore.classList.add('none');
                                }
                                if(comment_loading.classList.contains('none')) {
                                    comment_loading.classList.remove('none');
                                    comment_loading.classList.add('block');
                                }
                                
                                en4.core.request.send(new Request.HTML({
                                    url : url,
                                    data : {
                                        format : 'html',
                                        'maxid' : next_id,
                                        'subject' : subject_guid
                                    },
                                    onComplete: function(responseHTML) {
                                        var all = $$('div#comment_holder_' + subject_type + '_'+subject_id+ ' div.comment_holder_box');
                                        var last = all[all.length -1];
                                        last.parentNode.insertBefore(responseHTML[1], last.nextSibling);
                                        Smoothbox.bind(anchor);
                                    }
                                }) )
            
                            }

                            

                            if( next_id > 0 && !endOfComment ) {
                                
                                if(comment_viewmore.classList.contains('none')) {
                                    comment_viewmore.classList.remove('none');
                                }

                                if(!comment_loading.classList.contains('none')) {
                                    comment_loading.classList.add('none');
                                }
                                
                                comment_viewmore_link.removeEvents('click').addEvent('click', function(){
                            
                                    ViewMore(next_id, subject_guid);
                                });
                            } else {

                                if(!comment_viewmore.classList.contains('none')) {
                                    comment_viewmore.classList.add('none');
                                }

                                if(!comment_loading.classList.contains('none')) {
                                    comment_loading.classList.add('none');
                                }
                            
                            }
                        
                        });

                       
                       
                    
                        
                    
                    </script>
                    <!-- Box with form for new comment and all comments listed for that answer -->
                    <div class="holder-box holder-width-two white large-11 medium-11 large-offset-1 medium-offset-1  comments-holder none" id="comment_holder_<?php echo $answer->getType();?>_<?php echo $answer->getIdentity();?>">
                        <!-- Render form for new comment(for answer) -->
                        
                        <?php if($this->permissions['comment_answer'] != 0) :?>
                            <div class="comment_form border-bottom" id="comment_holder_form">
                                <?php echo $this->form_comment->render($this) ?>
                            </div>
                        <?php endif; ?>
                        <div class="comments_container" id="comments_box_<?php echo $answer->getIdentity();?>">
                            <?php if(count($comments) > 0):?>
                            
                                <!-- Display all comments one by one for this answer-->
                                <?php foreach($comments as $item):?>
                                    <div class="item-main-description border-bottom p-10 comment_holder_box" id="comment_<?php echo $item->getIdentity(); ?>">
                                        <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                                                'item' => $item,
                                                'viewer' =>$this->viewer,
                                        )); ?>
                                        <div class="item_edit_content large-9 medium-9 large-offset-2 medium-offset-2 columns none" id="edit_item_<?php echo $item->getIdentity();?>">
                                            <?php echo $this->form_answer_edit->render($this) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?> <!-- end of comments fro this answer-->
                                <!-- This should be redefinte in view more  -->
                                <div id="ajax_responses" style="display:none;">
                                    <script type="text/javascript" id="response"></script>
                                </div>
                            
                                <div class="comment_viewmore none" id="comment_viewmore_<?php echo $answer->getType() . '_' . $answer->getIdentity()?>">
                                    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
                                        'id' => "comment_viewmore_link_". $answer->getType() . '_' .$answer->getIdentity() ,
                                        'class' => 'buttonlink icon_viewmore'
                                    )) ?>
                                </div> 

                                <div class="comment_viewmore none" id="comment_loading_<?php echo $answer->getType() . '_' .$answer->getIdentity()?>">
                                    <i class="fa-spinner fa-spin fa"></i>
                                    <?php echo $this->translate("Loading ...") ?>
                                </div>
                            
                            <?php else: ?>
                                <p class="tip_msg" id="no_comments_tip"> There are no comments to show for this answer</p>
                            <?php endif; ?>
                        </div>
                    
                    </div> <!-- End of comment box--> 
                    
                </div>
            <?php endforeach; ?>
            
        </div>
        <div id="ajax_responses" style="display:none;">
            <script type="text/javascript" id="response"></script>
        </div>
       
        <div class="answer_viewmore none" id="answer_viewmore">
            <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
                'id' => 'answer_viewmore_link',
                'class' => 'buttonlink icon_viewmore'
            )) ?>
        </div> 

        <div class="answer_viewmore none" id="answer_loading">
            <i class="fa-spinner fa-spin fa"></i>
            <?php echo $this->translate("Loading ...") ?>
        </div>
        
        <?php if($this->permissions['answer_question'] != 0):?>
            <div class="answer_form">
                <div class="holder-title-textarea">
                    <h5><?php echo $this->translate('Your New Theory');?></h5>
                </div>
                <?php echo $this->form_answer->render($this) ?>
            </div>
            <?php 
                // this should be function getAnswers
                $answer_table = Engine_Api::_()->getDbTable('answers', 'ggcommunity');
                $select = $answer_table->select()
                ->where('parent_type = ?', $this->subject->getType())
                ->where('parent_id = ?', $this->subject->getIdentity())
                ;
                $answers = $answer_table->fetchAll($select);
                if(count($answers) > 0)
                {
                    $last_answer = count($answers);
                    $last_answer_id = $answers[$last_answer-1]->getIdentity();
                } else {
                    $last_answer_id = 0;
                }
            ?>
            <script>
               
                

                
                var last_answer_id = <?php echo $last_answer_id;?>;
                var form = document.getElementById('create-answer-form');
               
                form.addEventListener("submit", function(e) {

                    e.preventDefault();  
                    
                    var body_editor = document.getElementById('create-answer-form').getElementById('body');
                    var mce_editor = document.getElementById('create-answer-form').getElementsByClassName('mce-tinymce mce-container mce-panel');

                    if(mce_editor.length > 0) {
                        // var body = ((( (tinymce.get('body').getContent()).replace(/(&nbsp;)*/g, "")).replace(/(<p>)*/g, "")).replace(/<(\/)?p[^>]*>/g, ""));
                        //var body = tinymce.get('body').getContent();
                        var body = tinymce.get('body_create').getContent();
                    } else {
                        // var body = ((( (body_editor.value).replace(/(&nbsp;)*/g, "")).replace(/(<p>)*/g, "")).replace(/<(\/)?p[^>]*>/g, ""));  
                        var body = body_editor.value;
                    }

                    if(!body) return;
                    en4.ggcommunity.answer.create(<?php echo $this->subject->getIdentity() ?>, body, last_answer_id);

                });

                
            </script>

        <?php endif; ?> 
        
    </div> <!-- End of answer box-->
    
</div><!-- End of item sorting box--> 
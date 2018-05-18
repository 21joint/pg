<!-- Comment Box -->
<div class="comment_full_box none large-11 medium-11 large-offset-1 medium-offset-1 comments-holder" id="comments_holder_box_<?php echo $this->subject->getIdentity();?>">

    <div id="comments_box">   

        <!-- Box with form for new comment and all comments listed for that answer -->
        <div class="holder-box white" id="comment_holder_<?php echo $this->subject->getType();?>_<?php echo $this->subject->getIdentity();?>">
            <?php if($this->permissions['comment_question'] != 0) :?>
                <div class="comment_form border-bottom" id="comment_holder_form">
                    <?php echo $this->form_comment->render($this) ?>
                </div>
                <script>
                    var parent_type = '<?php echo $this->subject->getType();?>';
                    var parent_id = <?php echo $this->subject->getIdentity();?>;
                    var main_holder = document.getElementById('comment_holder_'+ parent_type + '_' + parent_id);
                        
                    var comment_form = main_holder.getElementById('comment_holder_form').firstElementChild;

                    comment_form.addEventListener("submit", function(e) {
                        e.preventDefault(); 
                        var body = comment_form.getElementById('comment_body').value;
                        if(!body) return;
                        en4.ggcommunity.comment.create(parent_type, parent_id, body);
                    });

                </script>
            <?php endif; ?><!-- End of comment form -->
            <script type="text/javascript" id="question_comments">
                en4.core.runonce.add(function() {
                    
                    var subject_id = <?php echo sprintf('%d', $this->subject->getIdentity()); ?>;
                    var subject_type = '<?php echo $this->subject->getType(); ?>';
                    var next_id = <?php echo sprintf('%d', $this->nextid) ?>;
                    var subject_guid = '<?php echo $this->subjectGuid ?>';
                    var endOfComment = <?php echo ( $this->endOfComment ? 'true' : 'false' ) ?>;
                    var anchor = document.getElementById('comments_box_'+ subject_id);

                 

                    var comment_viewmore = document.getElementById('comment_viewmore_' + subject_type + '_' + subject_id);
                    var comment_loading = document.getElementById('comment_loading_' + subject_type + '_' + subject_id);
                    var comment_viewmore_link = document.getElementById('comment_viewmore_link_' + subject_type + '_' + subject_id);
                    
                    
                    var ViewMore = window.ViewMore = function(next_id, subject_guid) {


                        var url = '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'comment-index', 'action' => 'index','comment_id' => $this->nextid, 'subject_id' => $this->subject->getIdentity(), 'subject_type'=>$this->subject->getType()), 'default', true) ?>';    

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
                              
                                var all = $$('div#comment_holder_' + subject_type + '_'+ subject_id + ' div.comment_holder_box');
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
            <div class="comments_container" id="comments_box_<?php echo $this->subject->getIdentity();?>">
                <?php if(count($this->paginator) > 0):?>
                
                    <!-- Display all comments one by one for this answer-->
                    <?php foreach($this->paginator as $item):?>
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
                    <div id="ajax_responses" style="display:none;">
                        <script type="text/javascript" id="response"></script>
                    </div>
                
                    <div class="comment_viewmore none" id="comment_viewmore_<?php echo $this->subject->getType() . '_' . $this->subject->getIdentity();?>">
                        <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
                            'id' => 'comment_viewmore_link_' . $this->subject->getType() . '_' .$this->subject->getIdentity() ,
                            'class' => 'buttonlink icon_viewmore'
                        )) ?>
                    </div> 

                    <div class="comment_viewmore none" id="comment_loading_<?php echo $this->subject->getType() . '_' . $this->subject->getIdentity();?>">
                        <i class="fa-spinner fa-spin fa"></i>
                        <?php echo $this->translate("Loading ...") ?>
                    </div>
                <?php else: ?>
                    <p class="tip_msg" id="no_comments_tip"> There are no comments to show for this answer</p>
                <?php endif; ?>
            </div>
        </div> <!-- End of single comment box-->  

    </div><!--End of comments box including comment form-->

</div> <!-- End of comment full box-->  
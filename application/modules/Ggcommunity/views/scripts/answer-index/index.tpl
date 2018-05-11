<script type="text/javascript" >
    en4.core.runonce.add(function() {

        var answer_viewmore = document.getElementById('answer_viewmore');
        var answer_loading = document.getElementById('answer_loading');
        var answer_viewmore_link = document.getElementById('answer_viewmore_link');

        var subject_id = <?php echo sprintf('%d', $this->question_id) ?>;
        var next_id = <?php echo sprintf('%d', $this->nextid) ?>;
        var subject_guid = '<?php echo $this->subjectGuid ?>';
        var endOfAnswer = <?php echo ( $this->endOfAnswer ? 'true' : 'false' ) ?>;
        var anchor = document.getElementById('answers_box');
        
        var ViewMore = window.ViewMore = function(next_id, subject_guid) {

            var url = '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'answer-index', 'action' => 'index','answer_id' => $this->nextid, 'subject_id' => $this->question_id, 'shown_ids' => $this->shown_ids), 'default', true) ?>';    

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
            }))

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
                $params = array(
                    'type'=>'ggcommunity_answer',
                    'id' => $answer->getIdentity()
                );
                $paginator = Engine_Api::_()->getItemTable('ggcommunity_comment')->getCommentsPaginator($params);
                $paginator->setItemCountPerPage(3);
                $paginator->setCurrentPageNumber(1);
            ?>
            <!-- Box with form for new comment and all comments listed for that answer -->
            <div class="holder-box white large-11 medium-11 large-offset-1 medium-offset-1 comments-holder none" id="comment_holder_<?php echo $answer->getType();?>_<?php echo $answer->getIdentity();?>">
                <!-- Render form for new comment(for answer) -->
                
                <?php if($this->permissions['comment_answer'] != 0) :?>
                    <div class="comment_form border-bottom" id="comment_holder_form">
                        <?php echo $this->form_comment->render($this) ?>
                    </div>
                <?php endif; ?>
                <?php if(count($paginator) > 0):?>
                    <div class="comments_container" id="comments_box_<?php echo $answer->getIdentity();?>">
                        <!-- Display all comments one by one for this answer-->
                        <?php foreach($paginator as $item):?>
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
                        <div>
                            <?php echo $this->paginationControl($paginator, null, null, array(
                            'pageAsQuery' => true,
                            'query' => $params,
                            )); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="tip_msg" id="no_comments_tip"> There are no comments to show for this answer</p>
                <?php endif; ?>
            </div> <!-- End of comment box--> 
            
        </div>
    <?php endforeach; ?>
</div>
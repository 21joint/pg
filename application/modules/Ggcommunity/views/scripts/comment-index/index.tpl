<script type="text/javascript">
                       
    en4.core.runonce.add(function() {

        var subject_id = <?php echo sprintf('%d', $this->subject->getIdentity()) ?>;
        var subject_type = '<?php echo $this->subject->getType(); ?>';
        var next_id = <?php echo sprintf('%d', $this->nextid) ?>;
        var subject_guid = '<?php echo $this->subject_guid ?>';
        var endOfComment = <?php echo ( $this->endOfComment ? 'true' : 'false' ) ?>;
        var anchor = document.getElementById('comments_box_'+ subject_id);

        var comment_viewmore = document.getElementById('comment_viewmore_'+ subject_type + '_' + subject_id);
        var comment_loading = document.getElementById('comment_loading_'+ subject_type + '_' + subject_id);
        var comment_viewmore_link = document.getElementById('comment_viewmore_link_'+ subject_type + '_' + subject_id);
        

        var ViewMore = window.ViewMore = function(next_id, subject_guid) {


            var url = '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'comment-index', 'action' => 'index','comment_id' => $this->nextid, 'subject_id' => $this->subject->getIdentity(), 'subject_type' => $this->subject->getType()), 'default', true) ?>';    

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
                
                    var all = $$('div#comment_holder_' + subject_type + '_' + subject_id + ' div.comment_holder_box');
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

<div class="new_comments" >
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
</div>

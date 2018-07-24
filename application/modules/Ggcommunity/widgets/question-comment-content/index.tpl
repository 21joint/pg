<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/author.js"); ?>

<!-- Comment Box -->
<div class="comment_full_box none large-11 medium-11 large-offset-1 medium-offset-1 comments-holder" id="comments_holder_box_<?php echo $this->subject->getIdentity();?>">

    <div id="comments_box">   

        <!-- Box with form for new comment and all comments listed for that answer -->
        <div class="holder-box white" id="comment_holder_<?php echo $this->subject->getType();?>_<?php echo $this->subject->getIdentity();?>">
            <?php if($this->permissions['comment_question'] != 0) :?>
                <div class="comment_form border-bottom" id="comment_holder_form">
                    <?php echo $this->form_comment->render($this) ?>
                </div>
                <script type="text/javascript">
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
                
            </div>
        </div> <!-- End of single comment box-->  

    </div><!--End of comments box including comment form-->

</div> <!-- End of comment full box-->  

<script type="text/javascript">
en4.core.runonce.add(function(){
    loadComments();
});
function loadComments(){
    var requestData = {};
    requestData.limit = 10;
    requestData.page = 1;
    requestData.contentType = "Question";
    requestData.contentID = "<?php echo $this->subject->getIdentity(); ?>";
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = en4.core.baseUrl+"api/v1/comment";
    var container = $("comments_box").getElement(".comments_container");
    
    var request = new Request.JSON({
        url: url,
        method: 'get',
        data: requestData,
        onRequest: function(){ loader.inject(container,"after"); }, //When request is sent.
        onError: function(){ loader.destroy(); }, //When request throws an error.
        onCancel: function(){ loader.destroy(); }, //When request is cancelled.
        onSuccess: function(responseJSON){ //When request is succeeded.
            loader.destroy(); 
            if(responseJSON.status_code == 200){
                var comments = responseJSON.body.Results;
                comments.each(function(comment){
                    var commentElement = getCommentElement(comment);
                    commentElement.inject(container,"bottom");
                });
                Smoothbox.bind(container); 
            }else{
                container.set("html",responseJSON.message);
            }
        }
    });
    request.send();
}
function getCommentElement(comment){
    var author = comment.author;
    var html = "<div class='question-main-description display-flex'>"+
                '<div class="question-main-left large-1 columns medium-1 small-2">'+
                '<div class="question-owner-photo">'+en4.core.pgservicelayer.authorPhoto(author)+
                "</div></div>"+
                '<div class="question-main-right large-11 medium-11 small-9">'+
                '<div class="question-main-top-holder "><div class="question-main-top-info display-flex">'+
                '<div class="question-main-top-box large-10 medium-10 small-10 flex-start"><div class="question-owner-name m-r-10">'+
                "<a href='"+author.href+"' class='owner-name'>"+author.displayName+"</a></div>"+
                '<div class="question-approve-time m-r-10"><p class="approve-time">'+author.memberSinceDateTime+"</p></div>";
    <?php if($this->subject->getType() == 'ggcommunity_answer'): ?>
        <?php if($this->subject->accepted): ?>
            html += '<p class="best_answer"><svg xmlns="http://www.w3.org/2000/svg" style="margin-right:5px;" width="13px" height="13px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 38"><defs><linearGradient id="z" x1="-173.22" y1="1009.42" x2="-172.4" y2="1010.06" gradientTransform="matrix(13.72, 0, 0, -11.03, 2403.25, 11146.77)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#2b3336"/><stop offset="1" stop-color="#333d40"/></linearGradient><linearGradient id="x" x1="-304.1" y1="1050.64" x2="-303.1" y2="1050.64" gradientTransform="matrix(16.54, 0, 0, -10.11, 5029.61, 10635.32)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#333d40"/><stop offset="1" stop-color="#2b3336"/></linearGradient></defs><title>dark_star</title><path d="M38.29,8.11l-7.17,9.44L25,9.36,36.9,6.64C38.65,6.23,39.15,6.93,38.29,8.11Z" fill="url(#z)"/><path d="M16.54,11.4.87,15.33c-1,.23-1.22,1.29-.3,1.7L15.86,21.5Z" fill="url(#x)"/><path d="M14.56,36.53l2-35.61c0-1,1-1.25,1.68-.43L39.58,27.06c.79,1,.57,2.58-1.63,1.72L25.08,23.86,17,37.19a1.3,1.3,0,0,1-2.49-.66Z" fill="#333d40"/></svg></p>'+
            "<?php echo $this->translate('Chosen Theory ')?> ";
        <?php else: ?>
            html += '';
        <?php endif; ?>
    <?php endif; ?>
    
    html += "</div><div class='question-main-top large-2 medium-2 small-2 flex-end' id='hide'>"+
        '<a href="javascript:void(0)" id="dot-options" class="dot-options relative" onclick="en4.ggcommunity.open_options(\'core_comment\', '+comment.commentID+')">'+
        '<svg aria-hidden="true" width="16px" data-prefix="fal" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path  d="M192 256c0 17.7-14.3 32-32 32s-32-14.3-32-32 14.3-32 32-32 32 14.3 32 32zm88-32c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-240 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z" class=""></path></svg>'+
        '</a><div class="holder-options-box hidden absolute" id="hidden_options_<?php echo $this->subject->getType() .'_'.$this->subject->getIdentity();?>"><ul class="options-list">';
    
    if(comment.canDelete){
        html += '<li class="list-inline edit-list-item"><a href="javascript:void(0);" class="edit-item option-item display-flex" onclick="en4.ggcommunity.comment.edit(\<?php echo $this->subject->getType(); ?>\',<?php echo $this->subject->getIdentity(); ?>);">'+
                '<svg aria-hidden="true" data-prefix="fal" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>'+
                '</a></li>';
    }
    
    html += "</ul></div></div></div>"+"<div class='question-main-middle'>"+
        "<div class='question-body <?php echo $this->subject->getType(); ?>' id='<?php echo $this->subject->getType() . '_' . $this->subject->getIdentity();?>'>"+
        "<div class='item_body' id='item_body_<?php echo $this->subject->getIdentity()?>'>"+comment.body+"</div>";
    <?php if($this->subject->getType() == 'ggcommunity_answer'): ?>
    
    <?php endif; ?>
    
    html += "</div></div>";
    
    var commentElement = new Element("div",{
        'class': 'item-main-description border-bottom p-10 comment_holder_box',
        'id': "comment_"+comment.commentID,
        'html': html
    });
    return commentElement;
}
</script>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/core.js"); ?>

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
    
    var loader = en4.pgservicelayer.loader.clone();
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
                hoverBoxImage();
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
                '<div class="question-owner-photo">'+en4.pgservicelayer.authorPhoto(author)+
                "</div></div>"+
                '<div class="question-main-right large-11 medium-11 small-9">'+
                '<div class="question-main-top-holder "><div class="question-main-top-info display-flex">'+
                '<div class="question-main-top-box large-10 medium-10 small-10 flex-start"><div class="question-owner-name m-r-10">'+
                "<a href='"+author.href+"' class='owner-name'>"+author.displayName+"</a></div>"+
                '<div class="question-approve-time m-r-10"><p class="approve-time">'+comment.createdDateTime+"</p></div>";
    
    html += "</div><div class='question-main-top large-2 medium-2 small-2 flex-end' id='hide'>"+
        '<a href="javascript:void(0)" id="dot-options" class="dot-options relative" onclick="en4.ggcommunity.open_options(\'core_comment\', '+comment.commentID+')">'+
        '<svg aria-hidden="true" width="16px" data-prefix="fal" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path  d="M192 256c0 17.7-14.3 32-32 32s-32-14.3-32-32 14.3-32 32-32 32 14.3 32 32zm88-32c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-240 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z" class=""></path></svg>'+
        '</a><div class="holder-options-box hidden absolute" id="hidden_options_core_comment_'+comment.commentID+'"><ul class="options-list">';
    
    if(comment.canDelete){
        html += '<li class="list-inline edit-list-item"><a href="javascript:void(0);" class="edit-item option-item display-flex" onclick="en4.ggcommunity.comment.edit(\'core_comment\','+comment.commentID+');">'+
                '<svg aria-hidden="true" data-prefix="fal" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>'+
                '<?php echo $this->translate("Edit"); ?></a></li>';
        var deleteUrl = en4.core.baseUrl+"ggcommunity/comment-profile/delete/comment_id/"+comment.commentID;
        html += '<li class="list-inline edit-list-item"><a href="'+deleteUrl+'" class="delete-item smoothbox option-item display-flex">'+
                '<svg aria-hidden="true" data-prefix="fal" data-icon="times-circle" role="img" width="19px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 464c-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216zm94.8-285.3L281.5 256l69.3 69.3c4.7 4.7 4.7 12.3 0 17l-8.5 8.5c-4.7 4.7-12.3 4.7-17 0L256 281.5l-69.3 69.3c-4.7 4.7-12.3 4.7-17 0l-8.5-8.5c-4.7-4.7-4.7-12.3 0-17l69.3-69.3-69.3-69.3c-4.7-4.7-4.7-12.3 0-17l8.5-8.5c4.7-4.7 12.3-4.7 17 0l69.3 69.3 69.3-69.3c4.7-4.7 12.3-4.7 17 0l8.5 8.5c4.6 4.7 4.6 12.3 0 17z"></path></svg>'+
                '<?php echo $this->translate("Delete"); ?></a></li>';
    }
    
    <?php if($this->viewer()->getIdentity()): ?>
    var reportUrl = en4.core.baseUrl+"report/create/subject/user_"+author.memberID+"/format/smoothbox";
    html += '<li class="list-inline edit-list-item"><a href="'+reportUrl+'" class="report-item option-item display-flex smoothbox">'+
                '<svg width="18" aria-hidden="true" data-prefix="fal" data-icon="flag" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M344.348 74.667C287.742 74.667 242.446 40 172.522 40c-28.487 0-53.675 5.322-76.965 14.449C99.553 24.713 75.808-1.127 46.071.038 21.532.999 1.433 20.75.076 45.271-1.146 67.34 12.553 86.382 32 93.258V500c0 6.627 5.373 12 12 12h8c6.627 0 12-5.373 12-12V378.398c31.423-14.539 72.066-29.064 135.652-29.064 56.606 0 101.902 34.667 171.826 34.667 51.31 0 91.933-17.238 130.008-42.953 6.589-4.45 10.514-11.909 10.514-19.86V59.521c0-17.549-18.206-29.152-34.122-21.76-36.78 17.084-86.263 36.906-133.53 36.906zM48 28c11.028 0 20 8.972 20 20s-8.972 20-20 20-20-8.972-20-20 8.972-20 20-20zm432 289.333C456.883 334.03 415.452 352 371.478 352c-63.615 0-108.247-34.667-171.826-34.667-46.016 0-102.279 10.186-135.652 26V106.667C87.117 89.971 128.548 72 172.522 72c63.615 0 108.247 34.667 171.826 34.667 45.92 0 102.217-18.813 135.652-34.667v245.333z"></path></svg>'+
                '<?php echo $this->translate("Report"); ?></a></li>';
    if(!comment.canDelete){
        var blockUrl = en4.core.baseUrl+"user/block/add/user_id/"+author.memberID+"/format/smoothbox";
        html += '<li class="list-inline edit-list-item"><a href="'+blockUrl+'" class="block-item option-item display-flex smoothbox">'+
                '<svg aria-hidden="true" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#5CC7CE" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" class=""></path></svg>'+
                '<?php echo $this->translate("Block"); ?></a></li>';
    }
    <?php endif; ?>
    
    var editFormHtml = getEditForm('core_comment',comment.commentID,comment.body);
    html += "</ul></div></div></div>"+"<div class='question-main-middle'>"+
        "<div class='question-body core_comment' id='core_comment_'"+comment.commentID+">"+
        "<div class='item_body' id='item_body_'"+comment.commentID+">"+comment.body+"</div>"+editFormHtml;    
    html += "</div></div>";
    
    var commentElement = new Element("div",{
        'class': 'item-main-description border-bottom p-10 comment_holder_box',
        'id': "comment_"+comment.commentID,
        'html': html
    });
    return commentElement;
}
</script>
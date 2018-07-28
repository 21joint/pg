<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/core.js"); ?>
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
                    <!-- Box with form for new comment and all comments listed for that answer -->
                    <div class="holder-box holder-width-two white large-11 medium-11 large-offset-1 medium-offset-1  comments-holder none" id="comment_holder_<?php echo $answer->getType();?>_<?php echo $answer->getIdentity();?>">
                        <!-- Render form for new comment(for answer) -->
                        
                        <?php if($this->permissions['comment_answer'] != 0) :?>
                            <div class="comment_form border-bottom" id="comment_holder_form">
                                <?php echo $this->form_comment->render($this) ?>
                            </div>
                        <?php endif; ?>
                        <div class="comments_container" id="comments_box_<?php echo $answer->getIdentity();?>">
                            
                        </div>
                    
                    </div> <!-- End of comment box--> 
                    
                </div>
            <?php endif; ?>
                        
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

                    var body_editor = document.querySelector("#body_create");
                    // var body_editor = document.getElementById('create-answer-form').getElementById('body');
                    var mce_editor = document.getElementById('create-answer-form').getElementsByClassName('mce-tinymce mce-container mce-panel');
                    if(mce_editor.length > 0) {
                        // var body = ((( (tinymce.get('body').getContent()).replace(/(&nbsp;)*/g, "")).replace(/(<p>)*/g, "")).replace(/<(\/)?p[^>]*>/g, ""));
                        var body = tinymce.get('body_create').getContent();
                    } else {
                        // var body = ((( (body_editor.value).replace(/(&nbsp;)*/g, "")).replace(/(<p>)*/g, "")).replace(/<(\/)?p[^>]*>/g, ""));  
                        var body = body_editor.value;
                        // empty input field
                        body_editor.value = '';
                    } 

                    if(!body) return;
                    en4.ggcommunity.answer.create(<?php echo $this->subject->getIdentity() ?>, body, last_answer_id);
                });

                
            </script>

        <?php endif; ?> 
        
    </div> <!-- End of answer box-->
    
</div><!-- End of item sorting box--> 


<script type="text/javascript">
en4.core.runonce.add(function(){
    loadAnswers();
});
function loadAnswers(){
    var requestData = {};
    requestData.limit = 10;
    requestData.page = 1;
    requestData.questionID = "<?php echo $this->subject->getIdentity(); ?>";
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = en4.core.baseUrl+"api/v1/answer";
    var container = $("answers_box");
    
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
                var items = responseJSON.body.Results;
                items.each(function(answer){
                    var answerElement = getAnswerElement(answer);
                    answerElement.inject(container,"bottom");
                });
                Smoothbox.bind(container); 
            }else{
                container.set("html",responseJSON.message);
            }
        }
    });
    request.send();
}
function getAnswerElement(answer){
    var author = answer.author;
    var acceptedClass = 'white';
    if(answer.answerChosen){
        acceptedClass = 'green';
    }
    
    var html = "<div class='holder-box "+acceptedClass+"' id='item_main_box_"+answer.answerID+"'>"+'<div class="item-main-description">';    
    html += "<div class='question-main-description display-flex'>"+
                '<div class="question-main-left large-1 columns medium-1 small-2">'+
                    '<div class="question-owner-photo">'+en4.core.pgservicelayer.authorPhoto(author)+'</div>'+
                    '<div class="question_votes_holder" id="vote_ggcommunity_answer_'+answer.answerID+'">'+
                        '<div class="vote-options">'+
                            '<a href="javascript:void(0)" class="vote-up" onclick="en4.ggcommunity.vote(\'ggcommunity_answer\','+answer.answerID+' ,1)"><svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z"></path></svg></a>'+
                            '<p class="question-vote"></p>'+'<a href="javascript:void(0)" class="vote-down" onclick="en4.ggcommunity.vote(\'ggcommunity_answer\','+answer.answerID+' ,0)"><svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-143.6-28.9L288 302.6V120c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v182.6l-72.4-75.5c-9.3-9.7-24.8-9.9-34.3-.4l-10.9 11c-9.4 9.4-9.4 24.6 0 33.9L239 404.3c9.4 9.4 24.6 9.4 33.9 0l132.7-132.7c9.4-9.4 9.4-24.6 0-33.9l-10.9-11c-9.5-9.5-25-9.3-34.3.4z"></path></svg></a>'
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="question-main-right large-11 medium-11 small-9">'+
                    '<div class="question-main-top-holder ">'+
                        '<div class="question-main-top-info display-flex">'+
                            '<div class="question-main-top-box large-10 medium-10 small-10 flex-start">'+
                                '<div class="question-owner-name m-r-10">'+
                                    '<a href="'+author.href+'" class="owner-name">'+author.displayName+'</a>'+
                                '</div>'+
                                '<div class="question-approve-time m-r-10"><p class="approve-time">'+answer.createdDateTime+'</p></div>';
    
        
    html += '</div></div>'+
    "<div class='question-main-top large-2 medium-2 small-2 flex-end' id='hide'>"+
        '<a href="javascript:void(0)" id="dot-options" class="dot-options relative" onclick="en4.ggcommunity.open_options(\'core_comment\', '+answer.answerID+')">'+
        '<svg aria-hidden="true" width="16px" data-prefix="fal" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path  d="M192 256c0 17.7-14.3 32-32 32s-32-14.3-32-32 14.3-32 32-32 32 14.3 32 32zm88-32c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-240 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z" class=""></path></svg>'+
        '</a>'+
        '<div class="holder-options-box hidden absolute" id="hidden_options_<?php echo $this->subject->getType() .'_'.$this->subject->getIdentity();?>">'+
            '<ul class="options-list">';
    
            if(answer.canDelete){
                html += '<li class="list-inline edit-list-item"><a href="javascript:void(0);" class="edit-item option-item display-flex" onclick="en4.ggcommunity.comment.edit(\<?php echo $this->subject->getType(); ?>\',<?php echo $this->subject->getIdentity(); ?>);">'+
                        '<svg aria-hidden="true" data-prefix="fal" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>'+
                        '</a></li>';
            }
    
                html += '</ul>'+
            '</div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        "<div class='question-main-middle'>"+
        "<div class='question-body ggcommunity_answer' id='ggcommunity_answer_"+answer.answerID+"'>"+
        "<div class='item_body' id='item_body_+answer.answerID+'>"+answer.body+"</div></div>"+
        '<div class="question-full-options"><div class="right-options">'+
        '<a href="javascript:void(0)" class="btn answer small black" onclick="en4.ggcommunity.answer.edit(\'ggcommunity_answer\','+answer.answerID+')"><?php echo $this->translate("Edit"); ?></a>'+
        '<a href="javascript:void(0)" class="btn answer small black" id="comment_counter_'+answer.answerID+'" onclick="en4.ggcommunity.answer.comment(\'ggcommunity_answer\','+answer.answerID+')"><?php echo $this->translate("Comment"); ?> | '+answer.commentsCount+'</a>'+
        '</div></div>';
    
    html += "</div></div>";
    
    var answerElement = new Element("div",{
        'class': 'answer_holder_box',
        'id': "answer_holder_box_"+answer.answerID,
        'html': html
    });
    return answerElement;
}
</script>
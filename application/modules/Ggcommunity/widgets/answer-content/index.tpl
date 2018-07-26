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
                '<div class="question-owner-photo">'+en4.core.pgservicelayer.authorPhoto(author)+
                "</div></div>"+
                '<div class="question-main-right large-11 medium-11 small-9">'+
                '<div class="question-main-top-holder "><div class="question-main-top-info display-flex">'+
                '<div class="question-main-top-box large-10 medium-10 small-10 flex-start"><div class="question-owner-name m-r-10">'+
                "<a href='"+author.href+"' class='owner-name'>"+author.displayName+"</a></div>"+
                '<div class="question-approve-time m-r-10"><p class="approve-time">'+answer.createdDateTime+"</p></div>";
    if(answer.answerChosen){
        html += '<p class="best_answer"><svg xmlns="http://www.w3.org/2000/svg" style="margin-right:5px;" width="13px" height="13px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 38"><defs><linearGradient id="z" x1="-173.22" y1="1009.42" x2="-172.4" y2="1010.06" gradientTransform="matrix(13.72, 0, 0, -11.03, 2403.25, 11146.77)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#2b3336"/><stop offset="1" stop-color="#333d40"/></linearGradient><linearGradient id="x" x1="-304.1" y1="1050.64" x2="-303.1" y2="1050.64" gradientTransform="matrix(16.54, 0, 0, -10.11, 5029.61, 10635.32)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#333d40"/><stop offset="1" stop-color="#2b3336"/></linearGradient></defs><title>dark_star</title><path d="M38.29,8.11l-7.17,9.44L25,9.36,36.9,6.64C38.65,6.23,39.15,6.93,38.29,8.11Z" fill="url(#z)"/><path d="M16.54,11.4.87,15.33c-1,.23-1.22,1.29-.3,1.7L15.86,21.5Z" fill="url(#x)"/><path d="M14.56,36.53l2-35.61c0-1,1-1.25,1.68-.43L39.58,27.06c.79,1,.57,2.58-1.63,1.72L25.08,23.86,17,37.19a1.3,1.3,0,0,1-2.49-.66Z" fill="#333d40"/></svg>'+
                "<?php echo $this->translate('Chose Theory'); ?></p>";
    }else{
        html += '<p class="best_answer"><a href="javascript:void(0);"><svg style="margin-right:5px" width="13px" height="13px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"/><stop offset="1" stop-color="#5bc6cd"/></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"/><stop offset="1" stop-color="#51b2b6"/></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"/><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"/><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"/></svg>'+
                "<?php echo $this->translate('Chose Theory'); ?></a></p>";
    }
        
    html += "</div><div class='question-main-top large-2 medium-2 small-2 flex-end' id='hide'>"+
        '<a href="javascript:void(0)" id="dot-options" class="dot-options relative" onclick="en4.ggcommunity.open_options(\'core_comment\', '+answer.answerID+')">'+
        '<svg aria-hidden="true" width="16px" data-prefix="fal" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path  d="M192 256c0 17.7-14.3 32-32 32s-32-14.3-32-32 14.3-32 32-32 32 14.3 32 32zm88-32c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-240 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z" class=""></path></svg>'+
        '</a><div class="holder-options-box hidden absolute" id="hidden_options_<?php echo $this->subject->getType() .'_'.$this->subject->getIdentity();?>"><ul class="options-list">';
    
    if(answer.canDelete){
        html += '<li class="list-inline edit-list-item"><a href="javascript:void(0);" class="edit-item option-item display-flex" onclick="en4.ggcommunity.comment.edit(\<?php echo $this->subject->getType(); ?>\',<?php echo $this->subject->getIdentity(); ?>);">'+
                '<svg aria-hidden="true" data-prefix="fal" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>'+
                '</a></li>';
    }
    
    html += "</ul></div></div></div>"+"<div class='question-main-middle'>"+
        "<div class='question-body ggcommunity_answer' id='ggcommunity_answer_"+answer.answerID+"'>"+
        "<div class='item_body' id='item_body_+answer.answerID+'>"+answer.body+"</div></div>"+
        '<div class="question-full-options"><div class="right-options"></div></div>';
    
    html += "</div></div>";
    
    var answerElement = new Element("div",{
        'class': 'answer_holder_box',
        'id': "answer_holder_box_"+answer.answerID,
        'html': html
    });
    return answerElement;
}
</script>
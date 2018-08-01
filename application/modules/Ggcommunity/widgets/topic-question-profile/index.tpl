<<<<<<< HEAD
<style type="text/css">
.question-main-photo img {
    max-width: 300px;
}    
</style>
<div class="question-box" id='sd-question-box'>
=======

<div class="question-box">
   <div class="holder-box white question-top-box">
        <div class="holder-all">
            <div class="question-title">
                <h1 class="title"><?php echo $this->subject->getTitle(); ?></h1>
            </div>
            <?php $topics = json_decode($this->subject->topic, true);?>
            <div class="question-topics-box flex-start">
                <?php foreach($topics as $topic):?>
                    <?php $topic_item = Engine_Api::_()->getItem('sdparentalguide_topic', $topic['topic_id']); ?>
                        <a id="go_to_topic" href="javascript:void(0)" class="btn tags small"><?php echo $topic_item; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
   </div>
   <div class="holder-box white" id="question-main-box">
       <div class="item-main-description">
          <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                  'item' => $this->subject,
                  'viewer' => $this->viewer,
          )); ?>
            <?php if($this->subject->draft == 0):?>
                <div class="question-full-options large-11 medium-12 columns large-offset-1 medium-offset-1">
                    <div class="left-options  large-6 medium-6 small-11 large-offset-0 medium-offset-0 small-offset-1">
                        
                        <a href="javascript:void(0)" id="count_answers" class="active btn small primary " onclick="switchTab('answer',<?php echo $this->subject->getIdentity();?>)">
                        
                            <?php echo $this->translate(array("Theory | %s", "Theories | %s"  , $this->subject->answer_count),
                            $this->locale()->toNumber($this->subject->answer_count)) ?>
                        
                        </a>
>>>>>>> int
   
                        <?php echo $this->userPermission('comment_question', $this->subject); ?>
                        <?php echo $this->userPermission('edit_question', $this->subject);?>
                        
                    </div>
                    <div class="right-options medium-offset-3 large-offset-3 large-3 medium-3 small-7">
                        <!-- Load social chare icons here -->
                        <div class="dropdown-menu-more" id="dropper">
                            <a href="javascript:void(0);">
                                <svg width="16" aria-hidden="true" data-prefix="fal" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16 fa-9x"><path fill="#fff" d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 96h416c8.8 0 16 7.2 16 16v41.4c-21.9 18.5-53.2 44-150.6 121.3-16.9 13.4-50.2 45.7-73.4 45.3-23.2.4-56.6-31.9-73.4-45.3C85.2 197.4 53.9 171.9 32 153.4V112c0-8.8 7.2-16 16-16zm416 320H48c-8.8 0-16-7.2-16-16V195c22.8 18.7 58.8 47.6 130.7 104.7 20.5 16.4 56.7 52.5 93.3 52.3 36.4.3 72.3-35.5 93.3-52.3 71.9-57.1 107.9-86 130.7-104.7v205c0 8.8-7.2 16-16 16z" class=""></path></svg>
                            </a>
                            <a href="javascript:void(0);">
                                <svg width="16" aria-hidden="true" data-prefix="fab" data-icon="reddit-alien" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-reddit-alien fa-w-16 fa-9x"><path fill="#fff" d="M440.3 203.5c-15 0-28.2 6.2-37.9 15.9-35.7-24.7-83.8-40.6-137.1-42.3L293 52.3l88.2 19.8c0 21.6 17.6 39.2 39.2 39.2 22 0 39.7-18.1 39.7-39.7s-17.6-39.7-39.7-39.7c-15.4 0-28.7 9.3-35.3 22l-97.4-21.6c-4.9-1.3-9.7 2.2-11 7.1L246.3 177c-52.9 2.2-100.5 18.1-136.3 42.8-9.7-10.1-23.4-16.3-38.4-16.3-55.6 0-73.8 74.6-22.9 100.1-1.8 7.9-2.6 16.3-2.6 24.7 0 83.8 94.4 151.7 210.3 151.7 116.4 0 210.8-67.9 210.8-151.7 0-8.4-.9-17.2-3.1-25.1 49.9-25.6 31.5-99.7-23.8-99.7zM129.4 308.9c0-22 17.6-39.7 39.7-39.7 21.6 0 39.2 17.6 39.2 39.7 0 21.6-17.6 39.2-39.2 39.2-22 .1-39.7-17.6-39.7-39.2zm214.3 93.5c-36.4 36.4-139.1 36.4-175.5 0-4-3.5-4-9.7 0-13.7 3.5-3.5 9.7-3.5 13.2 0 27.8 28.5 120 29 149 0 3.5-3.5 9.7-3.5 13.2 0 4.1 4 4.1 10.2.1 13.7zm-.8-54.2c-21.6 0-39.2-17.6-39.2-39.2 0-22 17.6-39.7 39.2-39.7 22 0 39.7 17.6 39.7 39.7-.1 21.5-17.7 39.2-39.7 39.2z" class=""></path></svg>
                            </a>
                            <a href="javascript:void(0);">
                                <svg width="16" aria-hidden="true" data-prefix="fab" data-icon="google-plus-g" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-google-plus-g fa-w-20 fa-9x"><path fill="#fff" d="M386.061 228.496c1.834 9.692 3.143 19.384 3.143 31.956C389.204 370.205 315.599 448 204.8 448c-106.084 0-192-85.915-192-192s85.916-192 192-192c51.864 0 95.083 18.859 128.611 50.292l-52.126 50.03c-14.145-13.621-39.028-29.599-76.485-29.599-65.484 0-118.92 54.221-118.92 121.277 0 67.056 53.436 121.277 118.92 121.277 75.961 0 104.513-54.745 108.965-82.773H204.8v-66.009h181.261zm185.406 6.437V179.2h-56.001v55.733h-55.733v56.001h55.733v55.733h56.001v-55.733H627.2v-56.001h-55.733z" class=""></path></svg>
                            </a>

                            </div>
                        </div>
                        <script>

                            function toggle_visibility(id) {
                                var e = document.getElementById(id);
                                
                                if(e.style.display == 'flex')
                                    e.style.display = 'none';
                                else
                                    e.style.display = 'flex';
                            }

                            function switchTab(tab, id) {
                                var comment_box = document.getElementById('comments_holder_box_'+id);
                                var answer_box = document.getElementById('item_container_'+id);
                                var comment_link = document.getElementById('count_question_comments');
                                var answer_link = document.getElementById('count_answers');
                            
                                if(tab == 'comment') {
                                    if(comment_box.classList.contains('none') && !answer_box.classList.contains('none')) {
                                    answer_box.classList.add('none');
                                    answer_link.classList.remove('primary');
                                    answer_link.classList.add('answer');
                                    comment_box.classList.remove('none');
                                    comment_link.classList.add('btn', 'primary');
                                    } 
                                
                                } else {
                                    if(answer_box.classList.contains('none') && !comment_box.classList.contains('none')) {
                                    comment_box.classList.add('none');
                                    comment_link.classList.remove('primary');
                                    answer_box.classList.remove('none');
                                    answer_link.classList.add('primary');
                                    answer_link.classList.remove('answer');
                                    } 
                                }
                            }

<<<<<<< HEAD
function switchTab(tab, id) {
    var comment_box = document.getElementById('comments_holder_box_'+id);
    var question_box = document.getElementById('item_container_'+id);
    var comment_link = document.getElementById('count_question_comments');
    var question_link = document.getElementById('count_answers');

    if(tab == 'comment') {
        if(comment_box.classList.contains('none') && !question_box.classList.contains('none')) {
        question_box.classList.add('none');
        question_link.classList.remove('primary');
        question_link.classList.add('answer');
        comment_box.classList.remove('none');
        comment_link.classList.add('btn', 'primary');
        } 

    } else {
        if(question_box.classList.contains('none') && !comment_box.classList.contains('none')) {
        comment_box.classList.add('none');
        comment_link.classList.remove('primary');
        question_box.classList.remove('none');
        question_link.classList.add('primary');
        question_link.classList.remove('answer');
        } 
    }
}
en4.core.runonce.add(function(){
    try{
        loadQuestionProfile();
    }catch(e){ console.log(e); }
});
function loadQuestionProfile(){
    var requestData = {};
    requestData.limit = 10;
    requestData.page = 1;
    requestData.questionID = "<?php echo $this->subject->getIdentity(); ?>";
    
    var loader = en4.pgservicelayer.loader.clone();
    loader.addClass("sd_loader");
    var url = en4.core.baseUrl+"api/v1/question";
    var container = $("sd-question-box");
    
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
                var results = responseJSON.body.Results;
                results.each(function(question){
                    var questionElement = getQuestionElement(question);
                    questionElement.inject(container,"after");
                });
                container.destroy();
                container = $("sd-question-box");
                Smoothbox.bind(container); 
                hoverBoxImage();
            }else{
                container.set("html",responseJSON.message);
            }
        }
    });
    request.send();
}
function getQuestionElement(question){
    var html = '';
    var author = question.author;
    
    //Question profile options
    var questionOptions = '<ul class="options-list">';
    var editQuestion = '';
    if(question.canDelete){
        var deleteUrl = en4.core.baseUrl+"ggcommunity/delete/"+question.questionID;
        questionOptions += '<li class="list-inline edit-list-item"><a href="'+deleteUrl+'" class="edit-item option-item display-flex smoothbox">'+
                '<svg aria-hidden="true" data-prefix="fal" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>'+
                '<?php echo $this->translate("Delete"); ?></a></li>';
        var editUrl = en4.core.baseUrl+"ggcommunity/edit/"+question.questionID;
        editQuestion += '<a href="'+editUrl+'" class="btn answer small blue">'+
                '<?php echo $this->translate("Edit"); ?></a>';
    }
    
    <?php if($this->viewer()->getIdentity()): ?>
    var reportUrl = en4.core.baseUrl+"report/create/subject/user_"+author.memberID+"/format/smoothbox";
    questionOptions += '<li class="list-inline edit-list-item"><a href="'+reportUrl+'" class="report-item option-item display-flex smoothbox">'+
                '<svg width="18" aria-hidden="true" data-prefix="fal" data-icon="flag" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M344.348 74.667C287.742 74.667 242.446 40 172.522 40c-28.487 0-53.675 5.322-76.965 14.449C99.553 24.713 75.808-1.127 46.071.038 21.532.999 1.433 20.75.076 45.271-1.146 67.34 12.553 86.382 32 93.258V500c0 6.627 5.373 12 12 12h8c6.627 0 12-5.373 12-12V378.398c31.423-14.539 72.066-29.064 135.652-29.064 56.606 0 101.902 34.667 171.826 34.667 51.31 0 91.933-17.238 130.008-42.953 6.589-4.45 10.514-11.909 10.514-19.86V59.521c0-17.549-18.206-29.152-34.122-21.76-36.78 17.084-86.263 36.906-133.53 36.906zM48 28c11.028 0 20 8.972 20 20s-8.972 20-20 20-20-8.972-20-20 8.972-20 20-20zm432 289.333C456.883 334.03 415.452 352 371.478 352c-63.615 0-108.247-34.667-171.826-34.667-46.016 0-102.279 10.186-135.652 26V106.667C87.117 89.971 128.548 72 172.522 72c63.615 0 108.247 34.667 171.826 34.667 45.92 0 102.217-18.813 135.652-34.667v245.333z"></path></svg>'+
                '<?php echo $this->translate("Report"); ?></a></li>';
        <?php if($this->subject()->getOwner()->isSelf($this->viewer())): ?>
        var blockUrl = en4.core.baseUrl+"user/block/add/user_id/"+author.memberID+"/format/smoothbox";
        questionOptions += '<li class="list-inline edit-list-item"><a href="'+blockUrl+'" class="block-item option-item display-flex smoothbox">'+
                '<svg aria-hidden="true" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#5CC7CE" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" class=""></path></svg>'+
                '<?php echo $this->translate("Block"); ?></a></li>';
        <?php endif; ?>
    <?php endif; ?>
    questionOptions += '</ul>';
    
    //Vote Html
    var voteHtml = '';
    if(question.userVote.status && question.userVote.voteType == 'upvote'){
        voteHtml += '<a href="javascript:void(0)" class="vote-up primary" disabled="disabled" onclick="en4.pgservicelayer.vote(event,\'ggcommunity_question\','+question.questionID+' ,1)"><svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z"></path></svg></a>';
    }else{
        voteHtml += '<a href="javascript:void(0)" class="vote-up" onclick="en4.pgservicelayer.vote(event,\'ggcommunity_question\','+question.questionID+' ,1)"><svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z"></path></svg></a>';
    }
    
    voteHtml += '<p class="question-vote">'+question.totalVoteCount+'</p>';
    if(question.userVote.status && question.userVote.voteType == 'downvote'){
        voteHtml += '<a href="javascript:void(0)" class="vote-down primary" disabled="disabled" onclick="en4.pgservicelayer.vote(event,\'ggcommunity_question\','+question.questionID+' ,0)"><svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-143.6-28.9L288 302.6V120c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v182.6l-72.4-75.5c-9.3-9.7-24.8-9.9-34.3-.4l-10.9 11c-9.4 9.4-9.4 24.6 0 33.9L239 404.3c9.4 9.4 24.6 9.4 33.9 0l132.7-132.7c9.4-9.4 9.4-24.6 0-33.9l-10.9-11c-9.5-9.5-25-9.3-34.3.4z"></path></svg></a>';
    }else{
        voteHtml += '<a href="javascript:void(0)" class="vote-down" onclick="en4.pgservicelayer.vote(event,\'ggcommunity_question\','+question.questionID+' ,0)"><svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-143.6-28.9L288 302.6V120c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v182.6l-72.4-75.5c-9.3-9.7-24.8-9.9-34.3-.4l-10.9 11c-9.4 9.4-9.4 24.6 0 33.9L239 404.3c9.4 9.4 24.6 9.4 33.9 0l132.7-132.7c9.4-9.4 9.4-24.6 0-33.9l-10.9-11c-9.5-9.5-25-9.3-34.3.4z"></path></svg></a>';
    }
    
    var topicHtml = '';
    if(question.questionTopic.topicID.length > 0){
        var topicUrl = en4.core.baseUrl+'topics?topicID='+question.questionTopic.topicID;
        topicHtml += '<div class="question-topics-box flex-start">'+
                '<a id="go_to_topic" href="'+topicUrl+'" class="btn tags small">'+question.questionTopic.topicName+'</div>';
    }
    
    var mainPhoto = '';
    if(question.coverPhoto.photoID != "0"){
        mainPhoto += '<div class="question-main-photo">'+
                    '<img src="'+question.coverPhoto.photoURL+'" alt=""/>'+
                '</div>'
    }
    var communityBox = "<div class='question-main-description display-flex'>"+
                        '<div class="question-main-left large-1 columns medium-1 small-2">'+
                            '<div class="question-owner-photo">'+en4.pgservicelayer.authorPhoto(author)+'</div>'+
                            '<div class="question_votes_holder" id="vote_ggcommunity_question_'+question.questionID+'">'+
                                '<div class="vote-options">'+
                                voteHtml+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '<div class="question-main-right large-11 medium-11 small-9">'+
                        '<div class="question-main-top-holder ">'+
                            '<div class="question-main-top-info display-flex">'+
                                '<div class="question-main-top-box large-10 medium-10 small-10 flex-start">'+
                                    '<div class="question-owner-name m-r-10">'+'<a href="'+author.href+'" class="owner-name">'+author.displayName+'</a>'+'</div>'+
                                    '<div class="question-approve-time m-r-10"><p class="approve-time">'+question.createdDateTime+'</p></div>'+
                                '</div>'+
                                "<div class='question-main-top large-2 medium-2 small-2 flex-end' id='hide'>"+
                                    '<a href="javascript:void(0)" id="dot-options" class="dot-options relative" onclick="en4.pgservicelayer.open_options(event,\'ggcommunity_question\', '+question.questionID+')">'+
                                    '<svg aria-hidden="true" width="16px" data-prefix="fal" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path  d="M192 256c0 17.7-14.3 32-32 32s-32-14.3-32-32 14.3-32 32-32 32 14.3 32 32zm88-32c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-240 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z" class=""></path></svg>'+
                                    '</a>'+
                                    '<div class="holder-options-box hidden absolute" id="hidden_options_ggcommunity_question_'+question.questionID+'">'+questionOptions+'</div>'+
                                '</div>'+
                        '</div>'+
                        "<div class='question-main-middle'>"+
                            "<div class='question-body ggcommunity_question' id='ggcommunity_question_"+question.questionID+"'>"+
                                "<div class='item_body' id='item_body_+question.questionID+'>"+question.body+"</div>"+
                            '</div>'+
                        '</div>'+
                        mainPhoto+
                    '</div>'+  
                '</div>'+
            '</div>';
        
    var commentCountHtml = '<?php echo $this->translate("Comment"); ?>';
    if(question.commentsCount == 1){
        commentCountHtml += " | "+question.commentsCount;
    }
    if(question.commentsCount > 1){
        commentCountHtml = '<?php echo $this->translate("Comments"); ?> | '+question.commentsCount;
    }
    var answerCountHtml = '<?php echo $this->translate("Theory"); ?>';
    if(question.answerCount == 1){
        answerCountHtml += " | "+question.answerCount;
    }
    if(question.answerCount > 1){
        answerCountHtml = '<?php echo $this->translate("Theories"); ?> | '+question.answerCount;
    }
    var questionOptions = '<div class="question-full-options large-11 medium-12 columns large-offset-1 medium-offset-1">'+
            '<div class="left-options  large-6 medium-6 small-11 large-offset-0 medium-offset-0 small-offset-1">'+
                '<a href="javascript:void(0)" id="count_answers" class="active btn small primary " onclick="switchTab(\'answer\',<?php echo $this->subject->getIdentity();?>)">'+
                answerCountHtml+
                '</a>'+
                '<a href="javascript:void(0)" id="count_question_comments" class="btn answer small blue" onclick="switchTab(\'comment\',<?php echo $this->subject->getIdentity();?>)">'+
                commentCountHtml+
                '</a>'+
                editQuestion+
            '</div>'+
        '</div>'
        ;
    
    html += '<div class="holder-box white question-top-box">'+
                '<div class="holder-all">'+
                    '<div class="question-title">'+
                        '<h1 class="title">'+question.title+'</h1>'+
                    '</div>'+
                    topicHtml+
                    '<div class="question-topics-box flex-start">'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="holder-box white" id="question-main-box">'+
                '<div class="item-main-description">'+
                communityBox + questionOptions +
                '</div>'+
            '</div>'+
        '</div>';
            
    
    var questionElement = new Element("div",{
        'class': 'question-box',
        'id': "sd-question-box",
        'html': html
    });
    return questionElement;
}
</script>
=======
                            // By Clicking Topic Button go to the page for that topic
                            // Parameters for Redirecting
                            var topicID = "<?php echo $topic['topic_id']; ?>";
                            var topicName = "<?php echo $topic_item; ?>";
                            // Redirect based on topicID if not said otherwise
                            document.getElementById("go_to_topic").href = en4.core.baseUrl + "topics?topicID=" + topicID;
                            // +PHP to check for topicID;
                            // If previous request fails redirect to -> /topics?topicName={{topicName}}
                            
                    </script>
                </div>
            <?php endif;?>
       </div>
   </div>
</div> <!-- End of Question Box-->
>>>>>>> int



en4.ggcommunity = {
    open_options : function open_options(type, id) {

        event.stopPropagation();    
        event.preventDefault();         
        var holder_options = document.getElementById('hidden_options_'+type+ '_' + id); 

        

        if(holder_options.classList.contains('hidden')) {
            holder_options.classList.remove('hidden');
            holder_options.classList.add('increase-index');
        } else {
            holder_options.classList.remove('increase-index');
            holder_options.classList.add('hidden');
            
        }
        
    },
    vote : function(parent_type, parent_id, vote_type) {
        var main_holder = document.getElementById('vote_'+ parent_type + '_' + parent_id);
        var that = $(event.target);
        if(that.tagName != 'A' || that.tagName != 'a'){
            that  = $(that).getParent("a");
        }
        if(that.hasClass("primary")){
            return;
        }        
        var first_child = main_holder.parentNode.firstElementChild;
        var reactionType = 'upvote';
        if(!vote_type){
            reactionType = 'downvote';
        }
        var voteCountElement = main_holder.getElement(".question-vote");
        var currentVoteCount = voteCountElement.get("html").toInt();
        main_holder.getElements("a").removeClass("primary").set("disabled",null);
        if(vote_type){
            currentVoteCount++;
            main_holder.getElement(".vote-up").addClass("primary").set("disabled","disabled");
        }else{
            currentVoteCount--;
            main_holder.getElement(".vote-down").addClass("primary").set("disabled","disabled");
        }
        voteCountElement.set("html",currentVoteCount);
        en4.core.request.send(new Request.JSON({
            url : en4.core.baseUrl+'api/v1/reaction',
            data : {
                contentType : parent_type,
                contentID : parent_id,
                reactionType : reactionType,
            },
            onComplete: function(responseJSON) {
                if(responseJSON.status_code == 204){                                 
                }else{
                    main_holder.getElements("a").removeClass("primary").set("disabled","disabled");
                    if(vote_type){
                        currentVoteCount--;
                        main_holder.getElement(".vote-up").removeClass("primary").set("disabled",null);
                    }else{
                        currentVoteCount++;
                        main_holder.getElement(".vote-down").removeClass("primary").set("disabled",null);
                    }
                    voteCountElement.set("html",currentVoteCount);
                    alert(responseJSON.message);
                }
            }
        }));
     
    }
}

//build ajax for question comment etc
en4.ggcommunity.question = {
    comment : function(parent_type, parent_id) {

        var main_holder = document.getElementById('item_container_'+parent_id);

        en4.core.request.send(new Request.HTML({
            url : 'ggcommunity/question-profile/comment',
            data : {
                format : 'html',
                question_id : parent_id,
            },
            onComplete: function(responseHTML) {
                                
            }
            
        }), {
            // return holder with new answer in it
            'element' : main_holder
        });
        
    }
}

//build ajax for answers create/edit etc
en4.ggcommunity.answer = {
    create : function(question_id, body, last_answer_id) {
        
        // get answer holder
        var answer_box = document.getElementById('answers_box');
        var holder = document.getElementById('answer_full_box');
        var counter_answer = document.getElementById('count_answers');
        var form = document.getElementById('create_answer_form');
        if(!form){
            form = document.getElementById('create-answer-form');
        }
        var loader = en4.pgservicelayer.loader.clone();

        en4.core.request.send(new Request.JSON({
            url : en4.core.baseUrl+'api/v1/answer',
            data : {
                questionID : question_id,
                body : body,
            },
            onRequest: function(){
                try{
                    loader.inject(form,"after");
                }catch(e){ }
            },
            onComplete: function(responseJSON) {
                // empty body from tinymce
                var body_editor = document.querySelector('#body_create');
                // var body_editor = document.getElementById('create-answer-form').getElementById('body');
                var mce_editor = document.getElementById('create-answer-form').getElementsByClassName('mce-tinymce mce-container mce-panel');
                loader.destroy();
                if(mce_editor.length > 0) {
                    var body = tinymce.get('body_create').setContent('');
                } else {
                    var body = body_editor.value;
                }
                
                var all = $('div.answer_holder_box');
                var last = all[all.length -1];
               
                // increase countner for answers
                var counter = counter_answer.innerHTML.trim();
                var answers = parseInt(counter.substr(counter.indexOf("| ")+2));
                var increment = answers+1;
                if(counter.indexOf("|") < 0){
                    increment = 1;
                }
                
                if(increment > 1) {
                    counter_answer.innerHTML = 'Theories | ' + increment ;
//                    last.parentNode.insertBefore(responseHTML[0], last.nextSibling);
                } else {
                    counter_answer.innerHTML = 'Theory | ' + increment ;
//                    responseHTML[0].inject( answer_box );
                }
                
                if(responseJSON.status_code == 200){
                    var items = responseJSON.body.Results;
                    items.each(function(answer){
                        var answerElement = getAnswerElement(answer);
                        answerElement.inject(answer_box,"bottom");
                    });
                    initTinyMce();
                    Smoothbox.bind(answer_box); 
                }               
            }
        
        }));
    },

    cancel : function() {
        var form = document.getElementById('create-answer-form');
        form.reset();
    },
    
    // build ajax for edit answer
    edit(type, id) {
        var form = document.getElementById('form_edit_'+type+'_'+id);
        var form_holder = form.parentNode;
        var body_holder = form_holder.parentNode.parentNode;
        var answer_body = body_holder.firstElementChild;

        answer_body.className += " none";
        form_holder.removeAttribute("style"); 

        var ed_id = 'edit_ggcommunity_answer_body_' + id;
        tinyMCE.execCommand("mceRemoveEditor", true, ed_id);
        setTimeout(function() {
            tinyMCE.init({
                selector: 'textarea#edit_ggcommunity_answer_body_' + id,
                menubar: false,
                statusbar: false,
                toolbar: 'bold italic, underline | quicklink | alignleft aligncenter alignright alignjustify | blockquote',
                height : '225'
            });
        }, 300);

        form.addEventListener("submit", function(e){

            e.preventDefault();
            try{
                var editor = tinymce.get("tinymce_ggcommunity_answer"+id);
                var body = editor.getContent();
            }catch(e){  }
            var answer_holder_box = document.getElementById('item_main_box_'+id);
            if(!body) return;
            $("ggcommunity_answer_"+id).getElement(".item_body").set("html",body);
            $("ggcommunity_answer_"+id).getElement(".item_body").removeClass('none');
            form_holder.setAttribute("style","display:none");
            
            en4.core.request.send(new Request.JSON({
                url : en4.core.baseUrl+'api/v1/answer',
                data : {
                    answerID : id,
                    questionID:en4.core.subject.id,
                    body : body
                },
                onComplete: function(responseJSON) {
                    
                }
                
            }));
                
        });

        form.getElementById('cancel').addEventListener("click", function(e){
            e.preventDefault();  
            answer_body.classList.remove("none");  
            form_holder.setAttribute("style","display:none");  
            return false;
        }); 
    },

    // build ajax for commenting on answers
    comment : function(parent_type, parent_id) {

        event.stopPropagation();

        // get comment holder
        var comment_holder = document.getElementById('comment_holder_'+ parent_type + '_' + parent_id);
        
        $(comment_holder).toggleClass("none");

        // get comment form and if has class none delete this class
        var comment_form = comment_holder.getElementById('comment_holder_form');
        if(comment_form!=null && comment_form.classList.contains('none')) {
            comment_form.classList.remove('none');
        }
    
        var form = comment_holder.getElement("#create_comment_form");
        var form_holder = form.parentNode;
       
        var comments_only = comment_holder.getElementById('comments_box_'+parent_id);
        var container = $("comments_box_"+parent_id);
        if(!$(comment_holder).hasClass('none')){            
            loadComments('Answer',parent_id,container);
        }
        
        form.addEventListener("submit", function(e){
            
            e.preventDefault();  
            if(form != null) {
                var body = form.getElementById('comment_body').value;
                if(!body) return;
            }

            en4.core.request.send(new Request.JSON({
                url : en4.core.baseUrl+'api/v1/comment',
                data : {
                    contentType : 'Answer',
                    contentID : parent_id,
                    body : body,    
                },
                onComplete: function(responseJSON) {                    
                   if(responseJSON.status_code != 200){
                       return;
                   }
                   form.reset();
                    
                    // increase countner for comments
                    var comment_counter = document.getElementById('comment_counter_'+parent_id);
                    var counter = comment_counter.innerHTML.trim();
                    
                    var comments = responseJSON.body.Results;
                    comments.each(function(comment){
                        var commentElement = getCommentElement(comment);
                        commentElement.inject(container,"top");
                    });
                    Smoothbox.bind(container); 
                
                    if(counter == 'Comment') {
                        var tip_msg = comment_holder.getElementById('no_comments_tip');
                        tip_msg.innerHTML = " ";
                        comment_counter.innerHTML = 'Comment | 1' ;
                        
                    } else {
                        var comments = parseInt(counter.substr(counter.indexOf("| ")+2));
                        var increment = comments+1;
                        comment_counter.innerHTML = 'Comments | ' + increment ;
                        
                    }
                    
                    Smoothbox.bind(comment_holder);
                    
                }
                
            }));

        });
    }

}

en4.ggcommunity.comment = {
    create : function(type, id, body) {

        var main_holder = document.getElementById('comments_holder_box_'+id);
        var comment_holder = main_holder.getElementById('comment_holder_' + type + '_' +id);
        var comment_form_holder = comment_holder.firstElementChild;
        var form = comment_form_holder.firstElementChild;

        var comments_only = comment_holder.getElementById('comments_box_'+ id);


        en4.core.request.send(new Request.JSON({
            url : en4.core.baseUrl+'api/v1/comment/',
            data : {
                contentType : parent_type,
                contentID : id,
                body : body,    
            },
            onComplete: function(responseJSON) {
                // hide form
                form.reset();
                var container = $("comments_box").getElement(".comments_container");
                if(responseJSON.status_code == 200){
                    var comments = responseJSON.body.Results;
                    comments.each(function(comment){
                        var commentElement = getCommentElement(comment);
                        commentElement.inject(container,"top");
                    });
                    Smoothbox.bind(container); 
                }

                // increase countner for question_comments
                var comment_counter = document.getElementById('count_question_comments');
                var counter = comment_counter.innerHTML.trim();
            
                if(counter == 'Comment') {
                    var tip_msg = comment_holder.getElementById('no_comments_tip');
                    tip_msg.innerHTML = " ";
                    comment_counter.innerHTML = 'Comment | 1' ;
                    
                } else {
                    var comments = parseInt(counter.substr(counter.indexOf("| ")+2));
                    var increment = comments+1;
                    comment_counter.innerHTML = 'Comments | ' + increment ;
                }                           
            }
            
        }));

    },


    // build ajax for edit comment
    edit : function(type,id) {
        var comment_holder = document.getElementById('comment_'+id).parentNode.parentNode;
        var comment_box = comment_holder.getElementById('comment_holder_form');
       
        var form = document.getElementById('form_edit_'+type+'_'+id);
        var form_holder = form.parentNode;
        var body_holder = form_holder.parentNode.parentNode;
        var comment_body = body_holder.firstElementChild;
     
        if(!comment_body.classList.contains('none')) {
            comment_body.className += " none";   
        }
        form_holder.removeAttribute("style"); 
        if(form_holder.classList.contains('none')) {
            form_holder.classList.remove('none');
        }

        form.addEventListener("submit", function(e){

            e.preventDefault();
            var body = form.getElementById('edit_'+type+'_body_'+id).value;
            var comment_holder_box = document.getElementById('comment_'+id);
            if(!body) return;
            
            
            en4.core.request.send(new Request.HTML({
                url : 'ggcommunity/comment-profile/edit',
                data : {
                    format : 'html',
                    comment_id : id,
                    body : body,    
                },
                onComplete: function(responseHTML) {
                    // hide form
                    form_holder.className += ' none';                 
                }
                
            }), {
                // return holder with new answer in it
                'element' : comment_holder_box
            });
                
        });

        form.getElementById('cancel').addEventListener("click", function(e){
            e.preventDefault();  
            comment_body.classList.remove("none");                   
            form_holder.classList.add('none');
            return false;
        }); 

    },
   
}


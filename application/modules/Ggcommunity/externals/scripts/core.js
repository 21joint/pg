

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
        var first_child = main_holder.parentNode.firstElementChild;

        en4.core.request.send(new Request.HTML({
            url : 'ggcommunity/vote/vote',
            data : {
                format : 'html',
                parent_type : parent_type,
                parent_id : parent_id,
                vote_type : vote_type,
            },
            onComplete: function(responseHTML) {
                main_holder.remove();
                first_child.parentNode.insertBefore(responseHTML[3], first_child.nextSibling);
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

        en4.core.request.send(new Request.HTML({
            url : 'ggcommunity/answer-index/create',
            data : {
                format : 'html',
                question_id : question_id,
                body_create : body,
            },
            onComplete: function(responseHTML) {
                // empty body from tinymce
                var body_editor = document.querySelector('#body_create');
                // var body_editor = document.getElementById('create-answer-form').getElementById('body');
                var mce_editor = document.getElementById('create-answer-form').getElementsByClassName('mce-tinymce mce-container mce-panel');

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
                
                if(last_answer_id > 0) {
                    counter_answer.innerHTML = 'Theories | ' + increment ;
                    last.parentNode.insertBefore(responseHTML[0], last.nextSibling);
                } else {
                    counter_answer.innerHTML = 'Theory | ' + increment ;
                    responseHTML[0].inject( answer_box );
                }
        
                Smoothbox.bind(answer_box);
               
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
            var body = form.getElementById('edit_'+type+'_body_'+id).value;
            var answer_holder_box = document.getElementById('item_main_box_'+id);
            if(!body) return;
            
            en4.core.request.send(new Request.HTML({
                url : 'ggcommunity/answer-profile/edit',
                data : {
                    format : 'html',
                    answer_id : id,
                    body : tinymce.get('edit_'+type+'_body_'+id).getContent(),
                },
                onComplete: function(responseHTML) {
                    
                    // hide form
                    form_holder.setAttribute("style","display:none");
                }
                
            }), {
                // return holder with new answer in it
                'element' : answer_holder_box
            });
                
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

        if(comment_holder.classList.contains('none')) {
            comment_holder.classList.remove('none');
        } else {
            comment_holder.classList.add('none');
        }

        // get comment form and if has class none delete this class
        var comment_form = comment_holder.getElementById('comment_holder_form');
        if(comment_form!=null && comment_form.classList.contains('none')) {
            comment_form.classList.remove('none');
        }
    
        var form = comment_holder.firstElementChild.firstElementChild;
        var form_holder = form.parentNode;
       
        var comments_only = comment_holder.getElementById('comments_box_'+parent_id);
        
        form.addEventListener("submit", function(e){
            
            e.preventDefault();  
            if(comment_form!=null) {
                var body = form.getElementById('comment_body').value;
                if(!body) return;
            }

            en4.core.request.send(new Request.HTML({
                url : 'ggcommunity/comment-index/create',
                data : {
                    format : 'html',
                    parent_type : parent_type,
                    parent_id : parent_id,
                    body : body,    
                },
                onComplete: function(responseHTML) {
   
                   form.reset();
                    
                    // increase countner for comments
                    var comment_counter = document.getElementById('comment_counter_'+parent_id);
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
                    
                    comments_only.insertBefore(responseHTML[0], comments_only.children[0]);
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


        en4.core.request.send(new Request.HTML({
            url : 'ggcommunity/comment-index/create',
            data : {
                format : 'html',
                parent_type : parent_type,
                parent_id : id,
                body : body,    
            },
            onComplete: function(responseHTML) {
                // hide form
                form.reset();

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
                 
                comments_only.insertBefore(responseHTML[0], comments_only.children[0]);
                Smoothbox.bind(comment_holder);             
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

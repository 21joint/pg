/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function () {
  var $ = 'id' in document ? document.id : window.$;
  en4.pgservicelayer = {
    loader: en4.core.loader,
    authorPhoto: function (author) {
      var hoverHtml = this.authorToolTip(author);
      var authorBadge = '';
      var badgeClass = '';
      var gear = '';
      var badgeCount = '';
      if (author.badgeInfo.hasOwnProperty("count")) {
        badgeClass = author.badgeInfo.class;
        badgeCount = author.badgeInfo.count;
        if (author.badgeInfo.gear)
          gear = author.badgeInfo.gear;
        authorBadge = "<div class='statistic " + author.badgeInfo.gear + " circle-badge position-absolute " + badgeClass + " d-flex justify-content-center align-items-center text-white'> " + author.badgeInfo.count + "</div>";
      }
      var authorHtml = hoverHtml +
        '<a class="d-inline-block" href="' + author.href + '">' +
        "<div class='item-photo-guidance position-relative'>" + authorBadge + "<img count='" + badgeCount + "' gear='" + gear + "' src='" + author.avatarPhoto.photoURLIcon + "' class='thumb_icon item_photo_user " + badgeClass + "'/></div>" +
        '</a>';
      return authorHtml;
    },
    authorToolTip: function (author) {
      var hoverHtml = '<div class="extfox-widgets" id="extfox-widgets">' +
        '<div class="row">' +
        '<div id="box-hover-member" class="box-hover-member bg-white position-absolute">' +
        '<div class="box-holder">' +
        '<div class="header d-flex mx-3 mt-3 p-relative pl-2 pt-2">' +
        '<div class="close position-absolute">' +
        '<a href="javascript:void(0)">' +
        '<svg width="14px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>' +
        '</a>' +
        '</div>' +
        '<div class="photo-image">' +
        '<a href="' + author.href + '">' +
        '<img src="' + author.avatarPhoto.photoURLIcon + '" alt="' + author.displayName + '"/>' +
        '</a>' +
        '</div>' +
        '<div class="right-side pl-3">' +
        '<div class="text-dark font-weight-bold pb-1">' +
        '<a href="' + author.href + '">' + author.displayName +
        '</a>' +
        '</div>' +
        '<div class="header-star d-flex align-items-center">' +
        '<div class="holder-rate d-flex align-items-center">' +
        '<div class="d-flex align-items-center">' +
        '<svg height="20px" style="margin-top: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68.137 80"><defs><style>.b_box_hover{fill:#52b1b8;}.c_box_hover{fill:#5cc7cd;}</style></defs><g transform="translate(13961.751 6200.271)"><g transform="translate(-13961.75 -6200.271)"><path class="b_box_hover" d="M108.85,27,96.662,43.071,86.2,29.121l20.283-4.571C109.459,23.8,110.323,24.992,108.85,27Z" transform="translate(-43.623 -13.136)"/><path class="b_box_hover" d="M30.556,40.68,3.864,47.338c-1.7.386-2.077,2.189-.508,2.9l26.032,7.618Z" transform="translate(-2.361 -21.186)"/><path class="c_box_hover" d="M51.227,64.559l3.438-60.7c0-1.717,1.772-2.118,2.834-.731l36.336,45.3c1.346,1.59.975,4.4-2.773,2.93L69.148,42.97,55.462,65.625C54.076,68.017,51.11,67.042,51.227,64.559Z" transform="translate(-26.41 -2.293)"/></g></g></svg>' +
        '</div>' +
        '<span class="text-primary pl-2">' + author.contribution + '</span>' +
        '</div>' +
        '<i class="fa fa-circle pl-2"></i>' +
        '<div class="holder-followers pl-2">' + //Code for follower
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="badges-earned bg-white mb-2 widget">' +
        '<div class="holder p-2">' +
        '<div class="bottom row d-flex justify-content-center m-0">' +
        '<div class="col-sm bronze">' +
        '<div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">' +
        author.bronzeCount +
        '</div>' +
        '<span class="text-muted small">Bronze</span>' +
        '</div>' +
        '<div class="col-sm silver">' +
        '<div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">' +
        author.silverCount +
        '</div>' +
        '<span class="text-muted small">Silver</span>' +
        '</div>' +
        '<div class="col-sm gold">' +
        '<div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">' +
        author.goldCount +
        '</div>' +
        '<span class="text-muted small">Gold</span>' +
        '</div>' +
        '<div class="col-sm platinium">' +
        '<div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">' +
        author.platinumCount +
        '</div>' +
        '<span class="text-muted small">Platinium</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="footer border-top border-gray d-flex justify-content-between align-items-center">' +
        '<div class="col-sm text-center border-right py-3">' +
        ((author.reviewCount == 1) ? 'Review ' : 'Reviews ') + author.reviewCount +
        '</div>' +
        '<div class="col-sm text-center py-3">' +
        ((author.answerCount == 1) ? 'Answer ' : 'Answers ') + author.answerCount +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
      return hoverHtml;
    },
    handleResponseError: function (responseJSON, container, appendType) {
      if ($("sd_response_error")) {
        $("sd_response_error").destroy();
      }
      var errorElement = new Element('div', {
        'class': 'sd_response_error',
        'id': 'sd_response_error'
      });
      if ($type(responseJSON.message) == 'array' || $type(responseJSON.message) == 'object') {
        var errorsElement = new Element("ul", {class: 'form-errors'});
        var li = new Element("li", {
          'html': 'Error <ul class="errors"><li>Please fill required fields.</li></ul>'
        });
        li.inject(errorsElement, "bottom");
        errorsElement.inject(errorElement, "bottom");
//            responseJSON.message.each(function(error){
//                
//            });
      } else {
        errorElement.set("html", responseJSON.message);
      }
      errorElement.inject(container, appendType);
    },
    open_options: function open_options(event, type, id) {
      event.stopPropagation();
      event.preventDefault();
      var holder_options = document.getElementById('hidden_options_' + type + '_' + id);



      if (holder_options.classList.contains('hidden')) {
        holder_options.classList.remove('hidden');
        holder_options.classList.add('increase-index');
      } else {
        holder_options.classList.remove('increase-index');
        holder_options.classList.add('hidden');

      }

    },
    vote: function (event, parent_type, parent_id, vote_type) {
      var main_holder = document.getElementById('vote_' + parent_type + '_' + parent_id);
      var that = $(event.target);
      if (that.tagName != 'A' || that.tagName != 'a') {
        that = $(that).getParent("a");
      }
      if (that.hasClass("primary")) {
        return;
      }
      var first_child = main_holder.parentNode.firstElementChild;
      var reactionType = 'upvote';
      if (!vote_type) {
        reactionType = 'downvote';
      }
      var voteCountElement = main_holder.getElement(".question-vote");
      main_holder.getElements("a").removeClass("primary").set("disabled", null);
      if (vote_type) {
        main_holder.getElement(".vote-up").addClass("primary").set("disabled", "disabled");
      } else {
        main_holder.getElement(".vote-down").addClass("primary").set("disabled", "disabled");
      }
      en4.core.request.send(new Request.JSON({
        url: en4.core.baseUrl + 'api/v1/reaction',
        data: {
          contentType: parent_type,
          contentID: parent_id,
          reactionType: reactionType,
        },
        onComplete: function (responseJSON) {
          if (responseJSON.status_code == 204 || responseJSON.status_code == 200) {
            if (responseJSON.status_code == 200) {
              voteCountElement.set("html", responseJSON.body.totalCount);
            }
          } else {
            alert(responseJSON.message);
          }
        }
      }));

    }
  };
  var loader = new Element('div', {
    'class': 'container col-1 m-auto sd_loader',
    'html': '<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-ball2"><g ng-attr-transform="translate(0,{{config.dy}})" transform="translate(0,-7.5)"><circle cx="50" ng-attr-cy="{{config.cy}}" r="6.25293" ng-attr-fill="{{config.c1}}" cy="41" fill="#5CC7CE" transform="rotate(282 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform><animate attributeName="r" calcMode="spline" values="0;15;0" keyTimes="0;0.5;1" dur="1" keySplines="0.2 0 0.8 1;0.2 0 0.8 1" begin="0s" repeatCount="indefinite"></animate></circle><circle cx="50" ng-attr-cy="{{config.cy}}" r="8.74707" ng-attr-fill="{{config.c2}}" cy="41" fill="#8AE693" transform="rotate(462 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="180 50 50;540 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform><animate attributeName="r" calcMode="spline" values="15;0;15" keyTimes="0;0.5;1" dur="1" keySplines="0.2 0 0.8 1;0.2 0 0.8 1" begin="0s" repeatCount="indefinite"></animate></circle></g></svg>'
  });
  en4.pgservicelayer.loader = loader;


  en4.pgservicelayer.answer = {
    create: function (question_id, body, last_answer_id) {

      // get answer holder
      var answer_box = document.getElementById('answers_box');
      var holder = document.getElementById('answer_full_box');
      var counter_answer = document.getElementById('count_answers');
      var form = document.getElementById('create_answer_form');
      if (!form) {
        form = document.getElementById('create-answer-form');
      }
      var loader = en4.pgservicelayer.loader.clone();

      en4.core.request.send(new Request.JSON({
        url: en4.core.baseUrl + 'api/v1/answer',
        data: {
          questionID: question_id,
          body: body,
        },
        onRequest: function () {
          try {
            loader.inject(form, "after");
                }catch(e){ }
        },
        onComplete: function (responseJSON) {
          // empty body from tinymce
          var body_editor = document.querySelector('#body_create');
          // var body_editor = document.getElementById('create-answer-form').getElementById('body');
          var mce_editor = document.getElementById('create-answer-form').getElementsByClassName('mce-tinymce mce-container mce-panel');
          loader.destroy();
          if (mce_editor.length > 0) {
            var body = tinymce.get('body_create').setContent('');
          } else {
            var body = body_editor.value;
          }

          var all = $('div.answer_holder_box');
          var last = all[all.length - 1];

          // increase countner for answers
          var counter = counter_answer.innerHTML.trim();
          var answers = parseInt(counter.substr(counter.indexOf("| ") + 2));
          var increment = answers + 1;
          if (counter.indexOf("|") < 0) {
            increment = 1;
          }

          if (increment > 1) {
            counter_answer.innerHTML = 'Theories | ' + increment;
//                    last.parentNode.insertBefore(responseHTML[0], last.nextSibling);
          } else {
            counter_answer.innerHTML = 'Theory | ' + increment;
//                    responseHTML[0].inject( answer_box );
          }

          if (responseJSON.status_code == 200) {
            var items = responseJSON.body.Results;
            items.each(function (answer) {
              var answerElement = getAnswerElement(answer);
              answerElement.inject(answer_box, "bottom");
              initTinyMce("tinymce_ggcommunity_answer" + answer.answerID);
            });
            initTinyMce();
            try {
              hoverBoxImage();
                    }catch(e){}
            Smoothbox.bind(answer_box);
          }
        }

      }));
    },

    cancel: function () {
      var form = document.getElementById('create-answer-form');
      form.reset();
    },

    // build ajax for edit answer
    edit(type, id) {
      var form = document.getElementById('form_edit_' + type + '_' + id);
      var form_holder = form.parentNode;
      var body_holder = form_holder.parentNode.parentNode;
      var answer_body = body_holder.firstElementChild;

      answer_body.className += " none";
      form_holder.removeAttribute("style");

      var ed_id = 'edit_ggcommunity_answer_body_' + id;
      tinyMCE.execCommand("mceRemoveEditor", true, ed_id);
      setTimeout(function () {
        tinyMCE.init({
          selector: 'textarea#edit_ggcommunity_answer_body_' + id,
          menubar: false,
          statusbar: false,
          toolbar: 'bold italic, underline | quicklink | alignleft aligncenter alignright alignjustify | blockquote',
          height: '225'
        });
      }, 300);

      form.addEventListener("submit", function (e) {

        e.preventDefault();
        try {
          var editor = tinymce.get("tinymce_ggcommunity_answer" + id);
          var body = editor.getContent();
            }catch(e){  }
        var answer_holder_box = document.getElementById('item_main_box_' + id);
        if (!body) return;
        $("ggcommunity_answer_" + id).getElement(".item_body").set("html", body);
        $("ggcommunity_answer_" + id).getElement(".item_body").removeClass('none');
        form_holder.setAttribute("style", "display:none");

        en4.core.request.send(new Request.JSON({
          url: en4.core.baseUrl + 'api/v1/answer',
          method: 'put',
          emulation: false,
          data: {
            answerID: id,
            questionID: en4.core.subject.id,
            body: body
          },
          onComplete: function (responseJSON) {

          }

        }));

      });

      form.getElementById('cancel').addEventListener("click", function (e) {
        e.preventDefault();
        answer_body.classList.remove("none");
        form_holder.setAttribute("style", "display:none");
        return false;
      });
    },

    // build ajax for commenting on answers
    comment: function (parent_type, parent_id) {

      event.stopPropagation();

      // get comment holder
      var comment_holder = document.getElementById('comment_holder_' + parent_type + '_' + parent_id);

      $(comment_holder).toggleClass("none");

      // get comment form and if has class none delete this class
      var comment_form = comment_holder.getElementById('comment_holder_form');
      if (comment_form != null && comment_form.classList.contains('none')) {
        comment_form.classList.remove('none');
      }

      var form = comment_holder.getElement("#create_comment_form");
      var form_holder = form.parentNode;

      var comments_only = comment_holder.getElementById('comments_box_' + parent_id);
      var container = $("comments_box_" + parent_id);
      if (!$(comment_holder).hasClass('none') && !comments_only.getElement(".item-main-description")) {
        loadComments('Answer', parent_id, container);
      }

      form.addEventListener("submit", function (e) {

        e.preventDefault();
        if (form != null) {
          var body = form.getElementById('comment_body').value;
          if (!body) return;
        }

        en4.core.request.send(new Request.JSON({
          url: en4.core.baseUrl + 'api/v1/comment',
          data: {
            contentType: 'Answer',
            contentID: parent_id,
            body: body,
          },
          onComplete: function (responseJSON) {
            if (responseJSON.status_code != 200) {
              return;
            }
            form.reset();

            // increase countner for comments
            var comment_counter = document.getElementById('comment_counter_' + parent_id);
            var counter = comment_counter.innerHTML.trim();

            var comments = responseJSON.body.Results;
            comments.each(function (comment) {
              var commentElement = getCommentElement(comment);
              commentElement.inject(container, "top");
            });
            try {
              hoverBoxImage();
                    }catch(e){}
            Smoothbox.bind(container);

            if (counter == 'Comment') {
              var tip_msg = comment_holder.getElementById('no_comments_tip');
              tip_msg.innerHTML = " ";
              comment_counter.innerHTML = 'Comment | 1';

            } else {
              var comments = parseInt(counter.substr(counter.indexOf("| ") + 2));
              var increment = comments + 1;
              comment_counter.innerHTML = 'Comments | ' + increment;

            }

            Smoothbox.bind(comment_holder);

          }

        }));

      });
    }
  }

  en4.pgservicelayer.comment = {
    create: function (type, id, body) {

      var main_holder = document.getElementById('comments_holder_box_' + id);
      var comment_holder = main_holder.getElementById('comment_holder_' + type + '_' + id);
      var comment_form_holder = comment_holder.firstElementChild;
      var form = comment_form_holder.firstElementChild;

      var comments_only = comment_holder.getElementById('comments_box_' + id);


      en4.core.request.send(new Request.JSON({
        url: en4.core.baseUrl + 'api/v1/comment/',
        data: {
          contentType: type,
          contentID: id,
          body: body,
        },
        onComplete: function (responseJSON) {
          // hide form
          form.reset();
          var container = $("comments_box").getElement(".comments_container");
          if (responseJSON.status_code == 200) {
            var comments = responseJSON.body.Results;
            comments.each(function (comment) {
              var commentElement = getCommentElement(comment);
              commentElement.inject(container, "top");
            });
            Smoothbox.bind(container);
            try {
              hoverBoxImage();
                    }catch(e){}
          }

          // increase countner for question_comments
          var comment_counter = document.getElementById('count_question_comments');
          var counter = comment_counter.innerHTML.trim();

          if (counter == 'Comment') {
            var tip_msg = comment_holder.getElementById('no_comments_tip');
            tip_msg.innerHTML = " ";
            comment_counter.innerHTML = 'Comment | 1';

          } else {
            var comments = parseInt(counter.substr(counter.indexOf("| ") + 2));
            var increment = comments + 1;
            comment_counter.innerHTML = 'Comments | ' + increment;
          }
        }

      }));

    },
    edit: function (type, contentID, id) {
      var comment_holder = document.getElementById('comment_' + id).parentNode.parentNode;
      var comment_box = comment_holder.getElementById('comment_holder_form');

      var form = document.getElementById('form_edit_core_comment_' + id);
      var form_holder = form.parentNode;
      var body_holder = form_holder.parentNode.parentNode;
      var comment_body = body_holder.firstElementChild;

      if (!comment_body.classList.contains('none')) {
        comment_body.className += " none";
      }
      form_holder.removeAttribute("style");
      if (form_holder.classList.contains('none')) {
        form_holder.classList.remove('none');
      }

      try {
        var mainParent = $(form).getParent(".comment_holder_box").getElement(".holder-options-box").addClass("hidden").removeClass("increase-index");
        }catch(e){  }

      form.addEventListener("submit", function (e) {

        e.preventDefault();
        var body = form.getElementById('edit_core_comment_body_' + id).value;
        var comment_holder_box = document.getElementById('comment_' + id);
        if (!body) return;


        en4.core.request.send(new Request.JSON({
          url: en4.core.baseUrl + 'api/v1/comment/',
          method: 'put',
          emulation: false,
          data: {
            contentType: type,
            contentID: contentID,
            commentID: id,
            body: body,
          },
          onComplete: function (responseJSON) {
            // hide form

          }

        }));

        form_holder.className += ' none';
        $("core_comment_" + id).getElement(".item_body").set("html", body);
        $("core_comment_" + id).getElement(".item_body").removeClass("none");

      });

      form.getElementById('cancel').addEventListener("click", function (e) {
        e.preventDefault();
        comment_body.classList.remove("none");
        form_holder.classList.add('none');
        return false;
      });

    },
  }
})();

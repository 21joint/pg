 var tagAction = function(tag, tag_id) { 
        $('#tag').val(tag);
        $('#tag_id').val(tag_id);
        $('#filter_form').submit();
    }
    
  var helpfulAction =  function(faq_id, viewer_id, helpful, option_id, count,statisticsHelpful,url) { 
        if (helpful == 1 && count != 0) {
            $.mobile.activePage.find('#show_option_' + faq_id).css('display', 'block');
            $.mobile.activePage.find('#showbox_' + faq_id).css('display', 'none');
        }

        if (option_id != '') {
            var helpful = 1;
        }

        if (helpful == 2 || option_id != '' || count == 0) {
            ($.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                data: {
                    'format': 'html',
                    'helpful': helpful,
                    'faq_id': faq_id,
                    'option_id': option_id,
                    'viewer_id': viewer_id,
                    'statisticsHelpful': statisticsHelpful
                },
                success: function(responseHTML) {
                    $.mobile.activePage.find('#helpful_content_' + faq_id).html(responseHTML);
                    $.mobile.activePage.find('#show_option_' + faq_id).popup('close');
                    $.mobile.activePage.find('#showbox_' + faq_id).css('display', 'none');
                    $.mobile.activePage.find('#showbox_' + faq_id).html('<div class="sucess_message" style="margin:0;"><i class="ui-icon ui-icon-ok"></i>Thanks for your feedback!</div>');
                    $.mobile.activePage.find('#showbox_' + faq_id).css('display', 'block');
                }
            }));
        }
    }

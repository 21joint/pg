import {API_PROXY, OAUTH} from '../../../../../package';
import {renderProfileBox} from "../../components/profile-box/profile-box";
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/popover';
import 'bootstrap/js/dist/dropdown';
import '../../scss/styles.scss';


(function ($, renderProfileBox) {


  let notificationUpdater;

  if ($('#notifications_markread_link')) {
    $('body').on('click', '#notifications_markread_link', function (e) {
      $('#notifications_markread').hide();
      en4.activity.hideNotifications('0 Updates');
    });
  }
  // <?php if ($this->updateSettings && $this->viewer->getIdentity()): ?>
  notificationUpdater = new NotificationUpdateHandler({
    'delay': 60000
  });
  notificationUpdater.start();
  window._notificationUpdater = notificationUpdater;
  // <?php endif;?>
  var $updateElement = $('#core_menu_mini_menu_extfox').find('.core_mini_update');
  if ($updateElement) {
    $updateElement.attr('id', 'updates_toggle');
    // $('#core_mini_updates_pulldown').html($($updateElement.parent().attr('id', 'core_menu_mini_menu_update')));

    $('body')
      .on('click', '.core_mini_update', function () {
        var $el = $(this);
        if (!$el.hasClass('updates_pulldown_active')) {
          $el.addClass('updates_pulldown_active');
          showNotifications();
        } else {
          $el.removeClass('updates_pulldown_active').addClass('updates_pulldown');
        }
      });
  }
  var showNotifications = function () {
    en4.activity.updateNotifications();
    new Request.HTML({
      'url': en4.core.baseUrl + 'activity/notifications/pulldown',
      'data': {
        'format': 'html',
        'page': 1
      },
      'onComplete': function (responseTree, responseElements, responseHTML, responseJavaScript) {
        if (responseHTML) {
          // hide loading icon
          if ($('#notifications_loading').length) $('#notifications_loading').fadeOut();
          $('.notifications_menu').html(responseHTML);
          $('body').on('click', '.notifications_menu', function (event) {
            event.preventDefault(); //Prevents the browser from following the link.
            var $current_link = $(event.target);
            var $notification_li = $current_link.closest('li');
            // if this is true, then the user clicked on the li element itself
            if ($notification_li.attr('id') == 'core_menu_mini_menu_update') {
              $notification_li = $current_link;
            }
            var forward_link;
            if ($current_link.attr('href')) {
              forward_link = $current_link.attr('href');
            } else {
              forward_link = $current_link.find('a:last-child').attr('href');
            }
            if ($notification_li.hasClass('notifications_unread')) {
              $notification_li.removeClass('notifications_unread');
              en4.core.request.send(new Request.JSON({
                url: en4.core.baseUrl + 'activity/notifications/markread',
                data: {
                  format: 'json',
                  'actionid': $notification_li.attr('value')
                },
                onSuccess: function () {
                  window.location = forward_link;
                }
              }));
            } else {
              window.location = forward_link;
            }
          });
        } else {
          $('#notifications_loading').html("You have no new updates.");
        }
      }
    }).send();
  };
  // search box header
  // let $search_holder = $('#search-bar');
  // let $search_icon = $('#search-icon');
  // let $close_icon = $('#close_icon');
  console.log($('#profilePopup'));
  $('[data-render]').each(function () {
    let fn = new Function('return ' + $(this).data('render'));
    // import(/* webpackPreload: true */ "../../components/profile-box/profile-box");
    $(this).html(fn());
  });


  function camelCaseToDash(str) {
    return str.replace(/([a-zA-Z])(?=[A-Z])/g, '$1-').toLowerCase()
  }

  $(document).ready(function () {

  });

})(jQuery, renderProfileBox);

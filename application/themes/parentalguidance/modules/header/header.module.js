import 'popper.js';
import '../styles.module';

(function ($) {
  $(document).ready(function () {
    var notificationUpdater;

    if ($('notifications_markread_link')) {
      $('notifications_markread_link').on('click', function (e) {
        $('notifications_markread').hide();
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
    var $updateElement = $('core_menu_mini_menu_extfox').find('.core_mini_update');
    if ($updateElement) {
      $updateElement.attr('id', 'updates_toggle');
      $('core_mini_updates_pulldown').css('display', 'inline-block').html($updateElement.parent().attr('id', 'core_menu_mini_menu_update'));
      $updateElement.html($('core_mini_updates_pulldown'));
      $('core_mini_updates_pulldown').on('click', function () {
        var $el = $(this);
        if (!$el.hasClass('updates_pulldown_active')) {
          $el.addClass('updates_pulldown_active');
          showNotifications();
          activateProfileItem('notifications');
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
            if ($('notifications_loading').length) $('notifications_loading').fadeOut()
            $('notifications_menu').innerHTML = responseHTML;
            $('notifications_menu').on('click', function (event) {
              event.stop(); //Prevents the browser from following the link.
              var $current_link = $(event.target);
              var $notification_li = $current_link.closest('li');
              // if this is true, then the user clicked on the li element itself
              if ($notification_li.id == 'core_menu_mini_menu_update') {
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
            $('notifications_loading').innerHTML = "You have no new updates.";
          }
        }
      }).send();
    };
    // search box header
    let search_holder = document.getElementById('search-bar');
    let search_icon = document.getElementById('search-icon');
    let close_icon = document.getElementById('close_icon');
    search_icon.addEventListener('click', function () {
      showDropdownItem(search_holder);
      search_holder.children[0].children[0].focus();
    });
    close_icon.addEventListener('click', function () {
      showDropdownItem(search_holder);
    });

    //take element and check if you want to display active or inactive
    function showDropdownItem(element) {
      let isActive = element.classList.value.search('active');
      ((isActive < 1) ? element.className += ' active' : element.classList.remove('active'));
    }

    function displayMenu() {
      let mobileHolder = document.getElementById('parental-mobile-menu-holder');
      if (mobileHolder.classList.contains('active') == false) {
        mobileHolder.classList.add('active');
        document.getElementsByTagName("body")[0].style = 'overflow: hidden';
      } else {
        mobileHolder.classList.remove('active');
        document.getElementsByTagName("body")[0].style = 'overflow-x: hidden';
      }
    }

    function activateProfileItem(type) {
      let $notificationBar = $('core_mini_updates_pulldown');
      let $dropdownHolder = $('#profile-dropdown-menu');
      // hide notifications and hover
      if (type == 'profile-dropdown-menu') {
        $notificationBar.removeClass('updates_pulldown_active');
        $notificationBar.addClass('updates_pulldown');
        hideProfileHover();
      }
      // hide dropdown and notifications
      if (type == 'notifications') {
        $dropdownHolder.removeClass('active');
        hideProfileHover();
      }
      if (type == 'profile-hover') {
        $dropdownHolder.removeClass('active');
        $notificationBar.removeClass('updates_pulldown_active');
        $notificationBar.addClass('updates_pulldown');
      }
      if (type == 'all') {
        hideProfileHover();
        $dropdownHolder.removeClass('active');
        $notificationBar.removeClass('updates_pulldown_active');
        $notificationBar.addClass('updates_pulldown');
      }
    }

    function hideProfileHover() {
      let profileHovers = $('.item_photo_user').closest('.extfox-widgets');
      profileHovers.each(function (i, element) {
        $(element).removeClass('active');
      });
    }

    // hide search items
    // document.onclick = function (e) {
    //   e.stopPropagation();
    //   // Notification hide
    //   if (e.target.id !== 'close_icon') {
    //     activateProfileItem('profile-dropdown-menu');
    //   }
    //   if (e.target.id !== 'btn-drop') {
    //     activateProfileItem('notifications');
    //   }
    // }
  });
})(jQuery);

<header class="prg-header bg-white">
  <div class="prg-header--top position-relative border-bottom py-3">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-between">
        <div class="col-auto d-md-none">
          <button type="button"
                  class="border-0 p-0 m-0 bg-transparent prg-hamburger--btn"
                  aria-hidden="true">
            <span class="bar"></span>
          </button>
        </div>
        <div class="col-auto col-md-4 d-flex align-items-center mr-auto logo">
          <a href="<?= $this->baseUrl(); ?>" class="d-md-block w-100 text-left">
            <svg class="align-middle prg-logo--image"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 480.81 171.45">
              <use href="#prgLogo"></use>
            </svg>
          </a>
        </div> <!-- end of logo -->
        <div class="col-auto col-md-4 col-sm search search-bar" id="search-bar">

          <form class="d-block w-100"
                action="<?= $this->url(array('controller' => 'search'), 'default', true) ?>"
                method="get">
            <input autocomplete="off"
                   type="text"
                   class="form-control form-control-rounded px-3 px-md-4 w-100"
                   name="query"
                   id="global_search_field" alt="<?= $this->translate('Type to search...') ?>"
                   placeholder="<?= $this->translate('Type to search...') ?>"/>
            <button class="bg-transparent rounded-circle d-sm-none d-block btn-close--search text-asphalt"
                    type="button"
                    id="close_icon">
              <i class="fal fa-times"></i>
            </button>
          </form>

        </div> <!-- search -->
        <div class="col-auto col-md-4 mini-menu" id="core_menu_mini_menu_extfox">

          <ul class="list-unstyled list-inline profile-items w-100 d-flex justify-content-sm-end justify-content-center align-items-center my-0">

            <li class="list-inline-item align-middle search d-sm-none mr-sm-2">
              <a class="d-flex align-items-center p-1 p-sm-2"
                 role="button" id="search-icon">
                <svg style="width:22px;height:22px;" class="d-block"
                     xmlns="http://www.w3.org/2000/svg">
                  <use href="#prgSearch" fill="#333D40"></use>
                </svg>
              </a>
            </li> <!-- end of search -->
              <?php if ($this->viewer->getIdentity() > 0): ?>
                <li class="list-inline-item align-middle notifications dropdown">
                  <a role="button"
                     data-toggle="dropdown"
                     aria-haspopup="true"
                     data-boundary="window"
                     class="d-flex align-items-center position-relative core_mini_update updates_toogle p-1 p-sm-2">
                    <i class="far fa-bell"
                       id="close_icon"></i><span class="notifications-count bg-primary text-white font-weight-bold position-absolute d-flex align-items-center justify-content-center"><?= $this->notificationCount; ?></span>
                  </a>
                  <div class="updates_pulldown" id="core_mini_updates_pulldown">
                    <div class="dropdown-menu dropdown-menu-right pulldown_contents_wrapper py-0">
                      <div class="text-right px-3 py-2 d-flex align-items-center justify-content-end mark-all">
                          <?= $this->htmlLink('javascript:void(0);', $this->translate('Mark All Read'), array('id' => 'notifications_markread_link', 'class' => 'font-weight-light small')) ?>
                      </div>
                      <div class="pulldown_contents">
                        <ul class="notifications_menu" id="notifications_menu">
                          <li class="notifications_loading d-flex justify-content-center align-items-center font-weight-light small py-4"
                              id="notifications_loading">
                            <i class="fa fa-spin fa-spinner" style="margin-right: 5px;"></i>
                              <?= $this->translate("Loading ...") ?>
                          </li>
                        </ul>
                      </div>
                      <div class="pulldown_options bg-primary text-center py-2 my-0">
                          <?= $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'notifications'),
                              $this->translate('View All Notifications'),
                              array('id' => 'notifications_viewall_link', 'class' => 'text-white font-weight-bold')) ?>
                      </div>
                    </div>
                  </div>
                </li> <!-- notifications -->
                <li class="list-inline-item align-middle profile-img mr-0">
                    <?php

                    $color = null;
                    if ($this->viewer->gg_expert_platinum_count > 0) {
                        $color = '#ACBED5';
                    } elseif ($this->viewer->gg_expert_gold_count > 0) {
                        $color = '#D9BB66';
                    } elseif ($this->viewer->gg_expert_silver_count > 0) {
                        $color = '#B8B8B8';
                    } elseif ($this->viewer->gg_expert_bronze_count > 0) {
                        $color = '#D7947C';
                    } else {
                        $color = '#fff';
                    }
                    ?>
                    <?= $this->htmlLink($this->viewer->getHref(), $this->itemPhoto($this->viewer, 'thumb.icon')); ?>
                </li> <!-- profile -->
                <li class="list-inline-item align-middle name d-none d-sm-inline-block dropdown ml-3">
                    <?= $this->htmlLink($this->viewer->getHref(), substr($this->viewer->getTitle(), 0, strrpos($this->viewer->getTitle(), ' ')), array('class' => 'font-weight-bold align-middle')); ?>
                  <a role="button"
                     data-toggle="dropdown"
                     aria-haspopup="true"
                     class="align-middle p-2 fa fa-caret-down"
                     aria-expanded="false">
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow p-0 border-0 dropdown-menu--profile"
                       style="overflow: hidden">
                    <ul class="list-unstyled">
                      <li>
                        <div class="dropdown-item bg-light p-3">
                          <h6 class="text-dark m-0 p-0"><b><?= $this->viewer->getTitle(); ?></b></h6>
                          <p class="desc text-muted small m-0">
                              <?= $this->viewer->email; ?>
                          </p>
                        </div>
                      </li>
                      <li>
                        <a class="dropdown-item"
                           href="<?= $this->url(array('controller' => 'settings', 'action' => 'general'), 'user_extended'); ?>">
                          <div class="d-flex align-items-center justify-content-start">
                            <i class="fa fa-cog mr-3" aria-hidden="true"></i>
                            <b>Profile Settings</b>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="#">
                          <div class="d-flex align-items-center justify-content-start">
                            <i class="fa fa-filter mr-3" aria-hidden="true"></i>
                            <b>User Preferences</b>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="<?= $this->baseUrl(); ?>/admin">
                          <div class="d-flex align-items-center justify-content-start">
                            <i class="fa fa-database mr-3" aria-hidden="true"></i>
                            <b>Admin</b>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item border-top py-3" href="<?= $this->baseUrl(); ?>/logout">
                          <div class="d-flex align-items-center justify-content-start">
                            <i class="fas fa-sign-out-alt mr-3" aria-hidden="true"></i>
                            <b>Sign Out</b>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li> <!-- name -->
              <?php else: ?>
                <li class="list-inline-item align-middle d-none d-sm-inline-block">
                    <?= $this->htmlLink($this->url(array('action' => 'login'), 'user_login'), $this->translate('Sign In'), array('class' => 'btn btn-link font-weight-bold text-uppercase px-2')); ?>
                </li>
                <li class="list-inline-item align-middle d-none d-sm-inline-block">
                    <?= $this->htmlLink($this->url(array('action' => 'index'), 'user_signup'), $this->translate('Sign Up'), array('class' => 'btn btn-success text-white text-uppercase')); ?>
                </li>
              <?php endif; ?>
          </ul>
        </div> <!-- mini-menu -->
      </div>
    </div>
  </div> <!-- top-menu -->
  <div class="prg-header--bottom border-bottom prg-mobile--nav" id="prg-mobile-navigation">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
          <ul class="list-unstyled row no-gutters justify-content-around mb-0">
            <li class="col-12 col-md-auto">
              <a class="nav-link" href="<?= $this->baseUrl(); ?>">
                <b><?= $this->translate('Home'); ?></b>
              </a>
            </li>
            <li class="col-12 col-md-auto position-static" data-hover="dropdown" aria-haspopup="true"
                aria-expanded="false">
              <a class="nav-link"
                 href="<?= $this->baseUrl(); ?>/reviews/home"
                 id="navbarDropdownMenuReviews">
                <b><?= $this->translate('Reviews'); ?></b>
              </a>
              <div class="dropdown-menu dropdown-menu--reviews px-4 pt-3" aria-labelledby="navbarDropdownMenuReviews">
                <div class="row">
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6 class="d-flex align-items-center justify-content-between text-primary font-weight-bold">Toys
                      or
                      Books
                      <i class="fas fa-angle-right"></i></h6>
                    <ul class="list-unstyled my-2">
                      <li><a href="#">Arts & Craft</a></li>
                      <li><a href="#">Bath Toys</a></li>
                      <li><a href="#">Books</a></li>
                      <li><a href="#">Building Blocks</a></li>
                      <li><a href="#">Infant Toys</a></li>
                      <li><a href="#">Outdoor Play</a></li>
                    </ul>
                    <div class="mt-2">
                      <a class="text-success font-weight-bold" href="#">view all</a>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="col-12 col-md-auto">
              <a class="nav-link" href="<?= $this->url(array(), 'listing_struggles', true); ?>">
                <b><?= $this->translate('Struggles & Theories'); ?></b>
              </a>
            </li>
            <li class="col-12 col-md-auto">
              <a class="nav-link" href="<?= $this->baseUrl(); ?>/community/home">
                <b><?= $this->translate('Our Community'); ?></b>
              </a>
            </li>
              <?php if ($this->viewer->getIdentity() > 0): ?>
                  <?php if ($this->viewer->isAdmin()): ?>
                  <li class="col-12 col-md-auto d-md-none">
                    <a href="<?= $this->baseUrl(); ?>/admin">
                        <?= $this->translate('Admin'); ?>
                    </a>
                  </li>
                  <?php endif; ?>
                <li class="col-12 col-md-auto d-md-none">
                  <a href="<?= $this->baseUrl(); ?>/logout">
                      <?= $this->translate('Log Out'); ?>
                  </a>
                </li>
              <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div> <!-- header-bottom -->
</header>

<script type="text/javascript">
  var notificationUpdater;

  en4.core.runonce.add(function (e) {

    if ($('notifications_markread_link')) {
      $('notifications_markread_link').addEvent('click', function () {

        //$('notifications_markread').setStyle('display', 'none');
        en4.activity.hideNotifications('<?= $this->string()->escapeJavascript($this->translate("0 Updates"));?>');
      });
    }

      <?php if ($this->updateSettings && $this->viewer->getIdentity()): ?>
    notificationUpdater = new NotificationUpdateHandler({
      'delay': <?= $this->updateSettings;?>
    });
    notificationUpdater.start();
    window._notificationUpdater = notificationUpdater;
      <?php endif;?>


  });


  //var updateElement = $('core_menu_mini_menu').getElement('.core_mini_update');
  var updateElement = $('core_menu_mini_menu_extfox').getElement('.core_mini_update');
  if (updateElement) {
    updateElement.set('id', 'updates_toggle');
    $('core_mini_updates_pulldown').setStyle('display', 'inline-block').inject(updateElement.getParent().set('id', 'core_menu_mini_menu_update'));
    updateElement.inject($('core_mini_updates_pulldown'));
    $('core_mini_updates_pulldown').addEvent('click', function () {
      var element = $(this);
      if (element.className == 'updates_pulldown') {
        element.className = 'updates_pulldown_active';
        showNotifications();
        activateProfileItem('notifications');
      } else {
        element.className = 'updates_pulldown';
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
          if ($('notifications_loading')) $('notifications_loading').setStyle('display', 'none');

          $('notifications_menu').innerHTML = responseHTML;
          $('notifications_menu').addEvent('click', function (event) {
            event.stop(); //Prevents the browser from following the link.

            var current_link = event.target;
            var notification_li = $(current_link).getParent('li');

            // if this is true, then the user clicked on the li element itself
            if (notification_li.id == 'core_menu_mini_menu_update') {
              notification_li = current_link;
            }

            var forward_link;
            if (current_link.get('href')) {
              forward_link = current_link.get('href');
            } else {
              forward_link = $(current_link).getElements('a:last-child').get('href');
            }

            if (notification_li.get('class') == 'notifications_unread') {
              notification_li.removeClass('notifications_unread');
              en4.core.request.send(new Request.JSON({
                url: en4.core.baseUrl + 'activity/notifications/markread',
                data: {
                  format: 'json',
                  'actionid': notification_li.get('value')
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
          $('notifications_loading').innerHTML = '<?= $this->string()->escapeJavascript($this->translate("You have no new updates."));?>';
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
    let notificationBar = $('core_mini_updates_pulldown');
    let dropdownHolder = document.getElementById('profile-dropdown-menu');

    // hide notifications and hover
    if (type == 'profile-dropdown-menu') {
      notificationBar.classList.remove('updates_pulldown_active');
      notificationBar.classList.add('updates_pulldown');
      hideProfileHover();
    }

    // hide dropdown and notifications
    if (type == 'notifications') {
      dropdownHolder.classList.remove('active');
      hideProfileHover();
    }

    if (type == 'profile-hover') {
      dropdownHolder.classList.remove('active');
      notificationBar.classList.remove('updates_pulldown_active');
      notificationBar.classList.add('updates_pulldown');
    }

    if (type == 'all') {
      hideProfileHover();
      dropdownHolder.classList.remove('active');
      notificationBar.classList.remove('updates_pulldown_active');
      notificationBar.classList.add('updates_pulldown');
    }

  }

  function hideProfileHover() {
    let profileHovers = $$('.item_photo_user').getParent().getParent().getParent().getElement('.extfox-widgets');
    profileHovers.each(function (element) {
      element.classList.remove('active');
    });
  }

  // hide search items
  document.onclick = function (e) {
    e.stopPropagation();

    // Notification hide
    if (e.target.id !== 'close_icon') {
      activateProfileItem('profile-dropdown-menu');
    }
    if (e.target.id !== 'btn-drop') {
      activateProfileItem('notifications');
    }

  }

</script>
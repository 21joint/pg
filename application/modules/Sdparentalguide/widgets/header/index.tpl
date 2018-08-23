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
            <svg class="align-middle prg-logo--image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 480.81 171.45">
              <use href="#prgLogo"></use>
            </svg>
          </a>
        </div> <!-- end of logo -->
        <div class="col-auto col-sm col-md-4 search search-bar"
             id="search-bar">

          <form class="d-block w-100"
                action="<?= $this->url(
                  array('controller' => 'search'), 'default', true
                ) ?>"
                method="get">
            <input autocomplete="off"
                   type="text"
                   class="form-control form-control-rounded px-3 px-md-4 w-100"
                   name="query"
                   id="global_search_field"
                   alt="<?= $this->translate('Type to search...') ?>"
                   placeholder="<?= $this->translate('Type to search...') ?>"/>
            <button
              class="bg-transparent rounded-circle d-sm-none d-block btn-close--search text-asphalt"
              type="button"
              id="close_icon">
              <i class="fal fa-times"></i>
            </button>
          </form>

        </div> <!-- search -->
        <div class="col-auto col-md-4 mini-menu" id="core_menu_mini_menu_extfox">

          <ul
            class="list-unstyled profile-items w-100 d-flex justify-content-sm-end justify-content-center align-items-center my-0">

            <li class="list-inline-item align-middle search d-sm-none mr-sm-2">
              <a class="d-flex align-items-center p-1 p-sm-2"
                 role="button" id="search-icon">
                <svg style="width:22px;height:22px;" class="d-block"
                     xmlns="http://www.w3.org/2000/svg">
                  <use href="#prgSearch" fill="#333D40"></use>
                </svg>
              </a>
            </li>
            <?php if ($this->viewer->getIdentity() > 0): ?>
              <li class="list-inline-item align-middle notifications dropdown">
                <a href="#"
                   role="button"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   data-boundary="window"
                   class="d-flex align-items-center position-relative core_mini_update updates_toogle p-1 p-sm-2">
                  <i class="far fa-bell"
                     id="close_icon"></i><span
                    class="notifications-count bg-primary text-white font-weight-bold position-absolute d-flex align-items-center justify-content-center"><?= $this->notificationCount; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right updates_pulldown" id="core_mini_updates_pulldown">
                  <div class="pulldown_contents_wrapper py-0">
                    <div class="text-right px-3 py-2 d-flex align-items-center justify-content-end mark-all">
                      <?= $this->htmlLink(
                        'javascript:void(0);',
                        $this->translate('Mark All Read'),
                        array('id' => 'notifications_markread_link',
                          'class' => 'font-weight-light small')
                      ) ?>
                    </div>
                    <div class="pulldown_contents">
                      <ul class="prg-header--notifications notifications_menu p-0 m-0" id="notifications_menu">
                        <li
                          class="notifications_loading d-flex justify-content-center align-items-center font-weight-light small py-4"
                          id="notifications_loading">
                          <svg>
                            <use href="#prgLoader"></use>
                          </svg>
                          <?= $this->translate("Loading ...") ?>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="pulldown_options bg-primary text-center py-2 my-0">
                      <?= $this->htmlLink(
                        array('route' => 'default', 'module' => 'activity',
                          'controller' => 'notifications'),
                        $this->translate('View All Notifications'),
                        array('id' => 'notifications_viewall_link',
                          'class' => 'text-white font-weight-bold')
                      ) ?>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-inline-item align-middle mr-0">
                <div data-component="renderProfileBox"></div>
              </li>
              <li class="list-inline-item align-middle name d-none d-sm-inline-block dropdown ml-3">
                <?= $this->htmlLink(
                  $this->viewer->getHref(), substr(
                  $this->viewer->getTitle(), 0,
                  strrpos($this->viewer->getTitle(), ' ')
                ), array('class' => 'font-weight-bold align-middle')
                ); ?>
                <a href="#"
                   role="button"
                   id="authProfileDropdown"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   class="text-primary align-middle p-1 fa fa-caret-down"
                   aria-expanded="false">
                </a>
              </li> <!-- name -->
            <?php else : ?>
              <li
                class="list-inline-item align-middle d-none d-sm-inline-block">
                <?= $this->htmlLink(
                  $this->url(array('action' => 'login'), 'user_login'),
                  $this->translate('Sign In'),
                  array('class' => 'btn btn-link font-weight-bold text-uppercase px-2')
                ); ?>
              </li>
              <li
                class="list-inline-item align-middle d-none d-sm-inline-block">
                <?= $this->htmlLink(
                  $this->url(array('action' => 'index'), 'user_signup'),
                  $this->translate('Sign Up'),
                  array('class' => 'btn btn-success text-white text-uppercase')
                ); ?>
              </li>
            <?php endif; ?>
          </ul>
        </div> <!-- mini-menu -->
      </div>
    </div>
  </div> <!-- top-menu -->
  <div class="prg-header--bottom border-bottom prg-mobile--nav"
       id="prg-mobile-navigation">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
          <ul class="list-unstyled row no-gutters justify-content-around mb-0">
            <li class="col-12 col-md-auto">
              <a class="nav-link" href="<?= $this->baseUrl(); ?>">
                <b><?= $this->translate('Home'); ?></b>
              </a>
            </li>
            <li class="col-12 col-md-auto position-static" data-hover="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
              <a class="nav-link"
                 href="<?= $this->baseUrl(); ?>/reviews/home"
                 id="navbarDropdownMenuReviews">
                <b><?= $this->translate('Reviews'); ?></b>
              </a>
              <div class="dropdown-menu dropdown-menu--reviews px-4 pt-3"
                   aria-labelledby="navbarDropdownMenuReviews">
                <div class="row">
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <h6
                      class="d-flex align-items-center justify-content-between text-primary font-weight-bold">
                      Toys
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
                      <a class="text-success font-weight-bold" href="#">view
                        all</a>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="col-12 col-md-auto">
              <a class="nav-link"
                 href="<?= $this->baseUrl(); ?>/guides/home">
                <b><?= $this->translate('Guides'); ?></b>
              </a>
            </li>
            <li class="col-12 col-md-auto">
              <a class="nav-link"
                 href="<?= $this->url(array(), 'listing_struggles', true); ?>">
                <b><?= $this->translate('Struggles & Theories'); ?></b>
              </a>
            </li>
            <li class="col-12 col-md-auto">
              <a class="nav-link"
                 href="<?= $this->baseUrl(); ?>/community/home">
                <b><?= $this->translate('Our Community'); ?></b>
              </a>
            </li>
            <?php if ($this->viewer->getIdentity() > 0): ?>
              <?php if ($this->viewer->isAdmin()): ?>
                <li class="col-12 col-md-auto d-md-none">
                  <a class="nav-link" href="<?= $this->baseUrl(); ?>/admin">
                    <b><?= $this->translate('Admin'); ?></b>
                  </a>
                </li>
              <?php endif; ?>
              <li class="col-12 col-md-auto d-md-none">
                <a class="nav-link" href="<?= $this->baseUrl(); ?>/logout">
                  <b><?= $this->translate('Log Out'); ?></b>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div> <!-- header-bottom -->
</header>

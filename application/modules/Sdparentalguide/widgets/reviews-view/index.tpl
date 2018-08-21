<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<!-- SINGLE REVIEW LAYOUT -->
<div class="prg-review--single">

  <!-- SINGLE REVIEW HERO -->
  <div class="page-hero prg-review--single---hero position-relative pt-0">
    <!-- SINGLE REVIEW HERO IMAGE -->
    <div class="prg-review--single---heroimage lazy" data-loader="galleryLoader"
         data-load="{{ review.coverPhoto.photoURL }}"
         style="background-color: #eeeeee;">
    </div>
    <!-- SINGLE REVIEW HERO IMAGE /ends-->

    <!-- SINGLE REVIEW HERO INNER -->
    <div class="prg-review--single---heroinner">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- CARD STARS -->
            <div data-render="renderRate"></div>
            <!-- CARD STARS /// -->
          </div>
        </div>
      </div>
    </div>
    <!-- SINGLE REVIEW HERO INNER /// -->.

    <!--  SINGLE REVIEW HERO /ends-->

    <!--  SINGLE REVIEW DESCRIPTION -->
    <div class="prg-review--single---content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-8 col-xl-9">
            <div class="row">
              <div class="col-12">
                <div class="content-box">

                  <!-- CARD ACTION  -->
                  <div class="dropdown prg-review--single---action">
                    <button class="btn btn-block bg-white rounded-circle p-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                      <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                  <!-- CARD ACTION -->

                  <!--  BREADCRUMB-->
                  <div class="prg-review--single---breadcrumb">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb bg-transparent border-0 p-0 mb-2">
                        <li class="breadcrumb-item align-middle"><a
                            href="#">Home</a></li>
                        <li class="breadcrumb-item align-middle"><a
                            href="#">Library</a>
                        </li>
                        <li class="breadcrumb-item align-middle active"
                            aria-current="page">Data
                        </li>
                      </ol>
                    </nav>
                  </div>
                  <!--  BREADCRUMB /ends-->

                  <!-- TITLE -->
                  <header class="prg-review--single---header">
                    <h2 class="prg-review--single---title text-primary font-weight-bold mb-3 mb-md-4 mb-lg-5"
                        data-render="review.title">
                      Twisted
                      Mustard
                      Seed
                      Heartbreaker
                      Bag</h2>
                  </header>
                  <div class="prg-review--single---descr" data-render="review.longDescription">
                    <p>We love our gorgeous Twisted Mustard Seed Diaper Bag! It is
                      absolutely beautiful. The pop of color is so <br>
                      fun and adds a lot to your everyday wardrobe. :) What I love
                      most is how it is functional but
                      doesn't
                      even
                      look like a diaper bag because it is so pretty. This bag
                      truly is designed for moms!</p>
                  </div>
                  <!-- REVIEW GALLERY-->
                  <div class="prg-review--single---gallery my-3" data-loader="loadGallery">

                  </div>
                  <!-- REVIEW GALLERY /ends-->
                </div>
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-6 py-3 border-bottom">
                    <h6 class="font-weight-bold small py-3">Rate This Review</h6>
                    <!--                    <ul class="prg-stars list-inline my-0 bg-transparent position-static px-0">-->
                    <!--                      <li class="list-inline-item align-middle">-->
                    <!--                        <a href="#">-->
                    <!--                          <svg xmlns="http://www.w3.org/2000/svg">-->
                    <!--                            <use href="#prgRateStar"></use>-->
                    <!--                          </svg>-->
                    <!--                        </a>-->
                    <!--                      </li>-->
                    <!--                      <li class="list-inline-item align-middle">-->
                    <!--                        <a href="#">-->
                    <!--                          <svg xmlns="http://www.w3.org/2000/svg">-->
                    <!--                            <use href="#prgRateStar"></use>-->
                    <!--                          </svg>-->
                    <!--                        </a>-->
                    <!--                      </li>-->
                    <!--                      <li class="list-inline-item align-middle">-->
                    <!--                        <a href="#">-->
                    <!--                          <svg xmlns="http://www.w3.org/2000/svg">-->
                    <!--                            <use href="#prgRateStar"></use>-->
                    <!--                          </svg>-->
                    <!--                        </a>-->
                    <!--                      </li>-->
                    <!--                      <li class="list-inline-item align-middle">-->
                    <!--                        <a href="#">-->
                    <!--                          <svg xmlns="http://www.w3.org/2000/svg">-->
                    <!--                            <use href="#prgRateStar"></use>-->
                    <!--                          </svg>-->
                    <!--                        </a>-->
                    <!--                      </li>-->
                    <!--                      <li class="list-inline-item align-middle">-->
                    <!--                        <a href="#">-->
                    <!--                          <svg xmlns="http://www.w3.org/2000/svg">-->
                    <!--                            <use href="#prgRateStar"></use>-->
                    <!--                          </svg>-->
                    <!--                        </a>-->
                    <!--                      </li>-->
                    <!--                    </ul>-->
                  </div>
                  <div class="col-6 py-3 border-bottom">
                    <h6 class="font-weight-bold small py-3">Rate This Product</h6>
                    <div data-render="renderRateInput">

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 py-3 border-bottom">
                <div class="row justify-content-between">
                  <div class="col-auto">
                    <span data-comments-count="commentsCount">0</span>
                    Comments
                  </div>
                  <div class="col-auto">
                    <div class="dropdown">Sorted By:
                      <a role="button"
                         id="commentsSortedBy"
                         class="text-asphalt font-weight-bold bg-transparent border-0 align-middle"
                         type="button"
                         aria-haspopup="true"
                         aria-expanded="false"
                         data-toggle="dropdown">Newest <i
                          class="fa fa-caret-down text-primary ml-2"></i>
                      </a>
                      <div style="overflow: hidden"
                           class="dropdown-menu dropdown-menu-right"
                           aria-labelledby="commentsSortedBy">
                        <ul class="list-unstyled p-0 m-0">
                          <li class="dropdown-item"><a role="button"
                                                       class="dropdown-link"
                                                       href="#">Newest</a></li>
                          <li class="dropdown-item"><a role="button"
                                                       class="dropdown-link"
                                                       href="#">Oldest</a></li>
                          <li class="dropdown-item"><a role="button"
                                                       class="dropdown-link"
                                                       href="#">Most Liked</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 py-3">
                <div class="row">
                  <div class="col-auto">
                    <?= $this->htmlLink(
                      $this->viewer->getHref(),
                      $this->itemPhoto($this->viewer, 'thumb.icon')
                    ); ?>
                  </div>
                  <div class="col">
              <textarea title="<?= $this->translate('Join Discussion') ?>"
                        class="form-control border bg-white py-2 px-3"
                        id="prgJoinDiscussion" rows="3"
                        placeholder="Join the discussion..."></textarea>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="prg-comments">
                  <ul>
                    <li class="row prg-comment--single">
                      <div class="col-auto">

                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-xl-3">
            <div class="prg-aside">
              <div style="min-height: auto;" class="content-box">
                <h6 class="pb-2 m-0 border-bottom">Related Reviews</h6>
                <ul class="list-unstyled pl-0 pt-3 m-0">
                  <li>
                    <a href="#">
                      <div class="card card-aside row no-gutters flex-row">
                        <div class="col-auto">
                          <div class="card-aside--img">
                            <img class="img-fluid"
                                 src="https://via.placeholder.com/100x70" alt=" ">
                          </div>
                        </div>
                        <div class="col px-2 py-1">
                          <h6
                            class="card-aside--title font-weight-bold mb-0 small">
                            Gummee Molar Mallet</h6>
                          <p
                            class="card-aside--category small m-0 text-primary font-weight-bold">
                            Category</p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="card card-aside row no-gutters flex-row">
                        <div class="col-auto">
                          <div class="card-aside--img">
                            <img class="img-fluid"
                                 src="https://via.placeholder.com/100x70" alt=" ">
                          </div>
                        </div>
                        <div class="col px-2 py-1">
                          <h6
                            class="card-aside--title font-weight-bold mb-0 small">
                            Gummee Molar Mallet</h6>
                          <p
                            class="card-aside--category small m-0 text-primary font-weight-bold">
                            Category</p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="card card-aside row no-gutters flex-row">
                        <div class="col-auto">
                          <div class="card-aside--img">
                            <img class="img-fluid"
                                 src="https://via.placeholder.com/100x70" alt=" ">
                          </div>
                        </div>
                        <div class="col px-2 py-1">
                          <h6
                            class="card-aside--title font-weight-bold mb-0 small">
                            Gummee Molar Mallet</h6>
                          <p
                            class="card-aside--category small m-0 text-primary font-weight-bold">
                            Category</p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="card card-aside row no-gutters flex-row">
                        <div class="col-auto">
                          <div class="card-aside--img">
                            <img class="img-fluid"
                                 src="https://via.placeholder.com/100x70" alt=" ">
                          </div>
                        </div>
                        <div class="col px-2 py-1">
                          <h6
                            class="card-aside--title font-weight-bold mb-0 small">
                            Gummee Molar Mallet</h6>
                          <p
                            class="card-aside--category small m-0 text-primary font-weight-bold">
                            Category</p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="card card-aside row no-gutters flex-row">
                        <div class="col-auto">
                          <div class="card-aside--img">
                            <img class="img-fluid"
                                 src="https://via.placeholder.com/100x70" alt=" ">
                          </div>
                        </div>
                        <div class="col px-2 py-1">
                          <h6
                            class="card-aside--title font-weight-bold mb-0 small">
                            Gummee Molar Mallet</h6>
                          <p
                            class="card-aside--category small m-0 text-primary font-weight-bold">
                            Category</p>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--  SINGLE REVIEW DESCRIPTION /ends-->

  </div>
  <!-- SINGLE REVIEW LAYOUT /// -->

</div>

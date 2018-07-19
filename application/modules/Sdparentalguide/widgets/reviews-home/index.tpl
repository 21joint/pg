<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>


<!-- PAGE HERO -->
<div class="page-hero bg-white">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-10 text-center">
        <h1 class="text-primary text-uppercase page-hero--title">Reviews</h1>
        <p class="text-primary page-hero--caption">Share Your Struggle, Provide Your Theories, and Gain Advice</p>
      </div>
      <div class="w-100 mb-sm-5"></div>
      <div class="col-md-10">
        <form action="">
          <div class="form-row">
            <div class="form-group col col-md-8 col-lg-9 mb-0">
              <input type="text"
                     class="form-control form-control-lg form-control-rounded px-4 w-100"
                     placeholder="Search for a specific review"/>
            </div>
            <div class="form-group col-auto col-sm-4 col-lg-3 mb-0">
              <button class="btn btn-lg btn-block btn-success text-white px-0">
                <i class="fa fa-search d-sm-none"></i><span class="d-none d-sm-inline">Search now</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- PAGE HERO ///-->

<!--PAGE SECTION-->
<div class="page-section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="content-box bg-sm-white" data-load=".card">
          <div class="row">
            <div class="col-12">
              <h2 class="content-box--title mb-0">Featured Reviews</h2>
              <hr>
            </div>
            <div class="col-12 px-0 px-sm-3">
              <div class="row no-gutters cards-grid reviews"
                   id="featuredReviewsGrid"
                   data-filter="featured">
              </div>
            </div>
            <!--            <div class="col-12 px-0">-->
            <!--              <hr class="row">-->
            <!--              <div class="row">-->
            <!--                <div class="col-auto ml-sm-auto">-->
            <!--                  <nav aria-label="Page navigation example">-->
            <!--                    <ul class="pagination mb-0">-->
            <!--                      <li class="page-item pagination-nav">-->
            <!--                        <a class="page-link" href="#" aria-label="Previous">-->
            <!--                          <i aria-hidden="true" class="fa fa-chevron-left"></i>-->
            <!--                          <span class="sr-only">Previous</span>-->
            <!--                        </a>-->
            <!--                      </li>-->
            <!--                      <li class="page-item"><a class="page-link" href="#">1</a></li>-->
            <!--                      <li class="page-item"><a class="page-link" href="#">2</a></li>-->
            <!--                      <li class="page-item active"><a class="page-link" href="#">3</a></li>-->
            <!--                      <li class="page-item"><a class="page-link" href="#">...</a></li>-->
            <!--                      <li class="page-item"><a class="page-link" href="#">386</a></li>-->
            <!--                      <li class="page-item pagination-nav">-->
            <!--                        <a class="page-link" href="#" aria-label="Next">-->
            <!--                          <i aria-hidden="true" class="fa fa-chevron-right"></i>-->
            <!--                          <span class="sr-only">Next</span>-->
            <!--                        </a>-->
            <!--                      </li>-->
            <!--                    </ul>-->
            <!--                  </nav>-->
            <!--                </div>-->
            <!--              </div>-->
            <!--            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--PAGE SECTION  ///-->


<div id="reviewSingleModal" class="modal fade" tabindex="-1" role="document">
  <div class="modal-dialog modal-fluid">
    <div class="modal-content">
      <!-- SINGLE REVIEW LAYOUT -->
      <div class="prg-review--single">
        <!--  hero-->
        <div class="prg-review--single---hero position-relative">
          <!-- SINGLE REVIEW HERO IMAGE -->
          <div class="prg-review--single---heroimage"
               style="background-color: #eeeeee;">
            <img class="img-fluid w-100 invisible"
                 src="https://dzhywv9htu615.cloudfront.net/public/sitereview_listing/bf/8d/02/43691a2684f60db713b745e344f8fe54.JPG"
                 alt="">
          </div>
          <!-- SINGLE REVIEW HERO IMAGE ///-->

          <!-- SINGLE REVIEW HERO INNER -->
          <div class="prg-review--single---heroinner">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 position-static">
                  <!-- CARD STARS -->
                  <div class="prg-stars prg-review--single---stars text-center">
                    <ul class="list-inline my-0">
                      <li class="list-inline-item align-middle">
                        <a href="#">
                          <svg xmlns="http://www.w3.org/2000/svg"
                               xmlns:xlink="http://www.w3.org/1999/xlink"
                               viewBox="0 0 21.059 20">
                            <defs>
                              <style> .cls-1 {
                                  fill: url(#linear-gradient);
                                }

                                .cls-2 {
                                  fill: url(#linear-gradient-2);
                                }

                                .cls-3 {
                                  fill: url(#linear-gradient-3);
                                }</style>
                              <linearGradient id="linear-gradient"
                                              x1="-0.222"
                                              y1="0.952"
                                              x2="0.971"
                                              y2="-0.34"
                                              gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#ce8f2a"></stop>
                                <stop offset="0.17" stop-color="#cf9330"></stop>
                                <stop offset="0.38" stop-color="#d39d3f"></stop>
                                <stop offset="0.62" stop-color="#d8ae59"></stop>
                                <stop offset="0.67" stop-color="#dab360"></stop>
                                <stop offset="0.92" stop-color="#edc64f"></stop>
                                <stop offset="1" stop-color="#edc64f"></stop>
                              </linearGradient>
                              <linearGradient id="linear-gradient-2"
                                              x1="1.543"
                                              y1="1.119"
                                              x2="0.162"
                                              y2="-0.102"
                                              gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#ce8f2a"></stop>
                                <stop offset="0.17" stop-color="#a28f70"></stop>
                                <stop offset="0.38" stop-color="#d39d3f"></stop>
                                <stop offset="0.62" stop-color="#d8ae59"></stop>
                                <stop offset="0.67" stop-color="#dab360"></stop>
                                <stop offset="0.92" stop-color="#edc64f"></stop>
                                <stop offset="1" stop-color="#edc64f"></stop>
                              </linearGradient>
                              <linearGradient id="linear-gradient-3"
                                              x1="1.616"
                                              y1="1.474"
                                              x2="0.258"
                                              y2="-0.026"
                                              xlink:href="#linear-gradient"></linearGradient>
                            </defs>
                            <g id="Symbol_583_1" data-name="Symbol 583 – 1" transform="translate(-435 -1135)">
                              <path id="Path_1811"
                                    data-name="Path 1811"
                                    class="cls-1"
                                    d="M173.963,249.27l-3.757,4.959L167,249.922l6.261-1.4C174.163,248.269,174.463,248.619,173.963,249.27Z"
                                    transform="translate(281.182 890.03)"></path>
                              <path id="Path_1812"
                                    data-name="Path 1812"
                                    class="cls-2"
                                    d="M149.407,253.5l-8.265,2.054c-.5.1-.651.7-.15.9l8.065,2.354Z"
                                    transform="translate(294.316 887.504)"></path>
                              <path id="Path_1813"
                                    data-name="Path 1813"
                                    class="cls-3"
                                    d="M156,260.741l1.052-18.734c0-.551.551-.651.9-.25l11.22,13.975c.4.5.3,1.352-.852.9l-6.762-2.6-4.208,7.013C156.9,261.843,155.948,261.543,156,260.741Z"
                                    transform="translate(286.674 893.486)"></path>
                            </g>
                          </svg>
                        </a>
                      </li>
                      <li class="list-inline-item align-middle">
                        <a href="#">
                          <svg xmlns="http://www.w3.org/2000/svg"
                               xmlns:xlink="http://www.w3.org/1999/xlink"
                               viewBox="0 0 21.059 20">
                            <g id="Symbol_583_1" data-name="Symbol 583 – 1" transform="translate(-435 -1135)">
                              <path id="Path_1811"
                                    data-name="Path 1811"
                                    class="cls-1"
                                    d="M173.963,249.27l-3.757,4.959L167,249.922l6.261-1.4C174.163,248.269,174.463,248.619,173.963,249.27Z"
                                    transform="translate(281.182 890.03)"></path>
                              <path id="Path_1812"
                                    data-name="Path 1812"
                                    class="cls-2"
                                    d="M149.407,253.5l-8.265,2.054c-.5.1-.651.7-.15.9l8.065,2.354Z"
                                    transform="translate(294.316 887.504)"></path>
                              <path id="Path_1813"
                                    data-name="Path 1813"
                                    class="cls-3"
                                    d="M156,260.741l1.052-18.734c0-.551.551-.651.9-.25l11.22,13.975c.4.5.3,1.352-.852.9l-6.762-2.6-4.208,7.013C156.9,261.843,155.948,261.543,156,260.741Z"
                                    transform="translate(286.674 893.486)"></path>
                            </g>
                          </svg>
                        </a>
                      </li>
                      <li class="list-inline-item align-middle">
                        <a href="#">
                          <svg xmlns="http://www.w3.org/2000/svg"
                               xmlns:xlink="http://www.w3.org/1999/xlink"
                               viewBox="0 0 21.059 20">
                            <g id="Symbol_583_1" data-name="Symbol 583 – 1" transform="translate(-435 -1135)">
                              <path id="Path_1811"
                                    data-name="Path 1811"
                                    class="cls-1"
                                    d="M173.963,249.27l-3.757,4.959L167,249.922l6.261-1.4C174.163,248.269,174.463,248.619,173.963,249.27Z"
                                    transform="translate(281.182 890.03)"></path>
                              <path id="Path_1812"
                                    data-name="Path 1812"
                                    class="cls-2"
                                    d="M149.407,253.5l-8.265,2.054c-.5.1-.651.7-.15.9l8.065,2.354Z"
                                    transform="translate(294.316 887.504)"></path>
                              <path id="Path_1813"
                                    data-name="Path 1813"
                                    class="cls-3"
                                    d="M156,260.741l1.052-18.734c0-.551.551-.651.9-.25l11.22,13.975c.4.5.3,1.352-.852.9l-6.762-2.6-4.208,7.013C156.9,261.843,155.948,261.543,156,260.741Z"
                                    transform="translate(286.674 893.486)"></path>
                            </g>
                          </svg>
                        </a>
                      </li>
                      <li class="list-inline-item align-middle">
                        <a href="#">
                          <svg xmlns="http://www.w3.org/2000/svg"
                               xmlns:xlink="http://www.w3.org/1999/xlink"
                               viewBox="0 0 21.059 20">
                            <g id="Symbol_583_1" data-name="Symbol 583 – 1" transform="translate(-435 -1135)">
                              <path id="Path_1811"
                                    data-name="Path 1811"
                                    class="cls-1"
                                    d="M173.963,249.27l-3.757,4.959L167,249.922l6.261-1.4C174.163,248.269,174.463,248.619,173.963,249.27Z"
                                    transform="translate(281.182 890.03)"></path>
                              <path id="Path_1812"
                                    data-name="Path 1812"
                                    class="cls-2"
                                    d="M149.407,253.5l-8.265,2.054c-.5.1-.651.7-.15.9l8.065,2.354Z"
                                    transform="translate(294.316 887.504)"></path>
                              <path id="Path_1813"
                                    data-name="Path 1813"
                                    class="cls-3"
                                    d="M156,260.741l1.052-18.734c0-.551.551-.651.9-.25l11.22,13.975c.4.5.3,1.352-.852.9l-6.762-2.6-4.208,7.013C156.9,261.843,155.948,261.543,156,260.741Z"
                                    transform="translate(286.674 893.486)"></path>
                            </g>
                          </svg>
                        </a>
                      </li>
                      <li class="list-inline-item align-middle">
                        <a href="#">
                          <svg xmlns="http://www.w3.org/2000/svg"
                               xmlns:xlink="http://www.w3.org/1999/xlink"
                               viewBox="0 0 21.059 20">
                            <g id="Symbol_583_1" data-name="Symbol 583 – 1" transform="translate(-435 -1135)">
                              <path id="Path_1811"
                                    data-name="Path 1811"
                                    class="cls-1"
                                    d="M173.963,249.27l-3.757,4.959L167,249.922l6.261-1.4C174.163,248.269,174.463,248.619,173.963,249.27Z"
                                    transform="translate(281.182 890.03)"></path>
                              <path id="Path_1812"
                                    data-name="Path 1812"
                                    class="cls-2"
                                    d="M149.407,253.5l-8.265,2.054c-.5.1-.651.7-.15.9l8.065,2.354Z"
                                    transform="translate(294.316 887.504)"></path>
                              <path id="Path_1813"
                                    data-name="Path 1813"
                                    class="cls-3"
                                    d="M156,260.741l1.052-18.734c0-.551.551-.651.9-.25l11.22,13.975c.4.5.3,1.352-.852.9l-6.762-2.6-4.208,7.013C156.9,261.843,155.948,261.543,156,260.741Z"
                                    transform="translate(286.674 893.486)"></path>
                            </g>
                          </svg>
                        </a>
                      </li>
                    </ul>
                  </div>
                  <!-- CARD STARS /// -->
                  <!-- CARD ACTION  -->
                  <div class="prg-review--single---action">
                    <button type="button" class="btn btn-block bg-white rounded-circle p-0">
                      <i class="fa fa-ellipsis-h"></i>
                    </button>
                  </div>
                  <!-- CARD ACTION /// -->
                </div>
              </div>
            </div>
          </div>
          <!-- SINGLE REVIEW HERO INNER /// -->
        </div>
        <!--  hero ///-->
        <!--  BREADCRUMB-->
        <div class="prg-review--single---breadcrumb">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb bg-transparent border-0">
                    <li class="breadcrumb-item align-middle"><a href="#">Home</a></li>
                    <li class="breadcrumb-item align-middle"><a href="#">Library</a></li>
                    <li class="breadcrumb-item align-middle active" aria-current="page">Data</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!--  BREADCRUMB ///-->
        <!--  SINGLE REVIEW DESCRIPTION -->
        <div class="prg-review--single---content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-9">
                <h2 class="prg-review--single---title text-primary font-weight-bold mb-5">Twisted Mustard Seed
                  Heartbreaker
                  Bag</h2>
                <div class="prg-review--single---descr">
                  <p>We love our gorgeous Twisted Mustard Seed Diaper Bag! It is absolutely beautiful. The pop of color
                    is
                    so
                    fun
                    and adds a lot to your everyday wardrobe. :) What I love most is how it is functional but doesn't
                    even
                    look like
                    a diaper bag because it is so pretty. This bag truly is designed for moms!
                  </p>
                  <p>
                    I love that it is simple in design. There's not too many pockets and not too much going on on the
                    outside
                    either. It does what it needs to and is gorgeous. It is definitely a timeless diaper bag!
                  </p>
                  <p>The exterior is made from genuine cowhide leather in a super gorgeous turquoise blue color. The
                    inside
                    is
                    black
                    with white polka dots and is such a fun contrast! I always love getting out my changing pad. This
                    diaper
                    bag is
                    just soo classy and pretty with some gold accents on the lettering and feet. <3
                  </p>
                  <p>Probably the greatest part about it is the way it looks because it means that even after you are
                    finished
                    with
                    needing a diaper bag, you can continue to use this as a purse. Yay for a versatile bag!
                  </p>
                  <p>
                    The Heartbreaker diaper bag can be carried as a typical tote diaper bag or you can use the messenger
                    strap. I
                    personally love using messenger straps so that is always what I do! It also came with a changing pad
                    and
                    stroller straps.

                  </p>
                </div>
                <!-- gallery-->
                <div class="prg-review--single---gallery">

                </div>
                <!--  gallery ///-->
              </div>
              <div class="col-sm-3">

              </div>
            </div>
          </div>
        </div>
        <!--  SINGLE REVIEW DESCRIPTION ///-->
      </div>
      <!-- SINGLE REVIEW LAYOUT /// -->
    </div>
  </div>
</div>
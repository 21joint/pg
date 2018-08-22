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
        <p class="text-primary page-hero--caption">Share Your Struggle, Provide
          Your Theories, and Gain Advice</p>
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
                <i class="fa fa-search d-sm-none"></i><span
                  class="d-none d-sm-inline">Search now</span></button>
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
        <div class="content-box bg-sm-white">
          <div class="row">
            <div class="col-12">
              <h2 class="content-box--title mb-0">Featured Reviews</h2>
              <hr>
            </div>
            <div class="col-12 px-0 px-sm-3">
              <div class="row no-gutters cards-grid reviews" id="featuredReviewsGrid">
                <!-- Reviews gonna be here-->
              </div>
            </div>
            <div class="col-12 px-0">
              <hr class="row">
              <div class="row">
                <div class="col-auto ml-sm-auto">
                  <nav aria-label="Page navigation example">
                    <ul class="pagination mb-0">
                      <li class="page-item pagination-nav">
                        <a class="page-link" href="#" aria-label="Previous">
                          <i aria-hidden="true" class="fa fa-chevron-left"></i>
                          <span class="sr-only">Previous</span>
                        </a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">1</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">2</a>
                      </li>
                      <li class="page-item active"><a class="page-link"
                                                      href="#">3</a></li>
                      <li class="page-item"><a class="page-link"
                                               href="#">...</a></li>
                      <li class="page-item"><a class="page-link"
                                               href="#">386</a></li>
                      <li class="page-item pagination-nav">
                        <a class="page-link" href="#" aria-label="Next">
                          <i aria-hidden="true" class="fa fa-chevron-right"></i>
                          <span class="sr-only">Next</span>
                        </a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--PAGE SECTION  ///-->

<script src="/scripts/reviews_home.bundle.js"></script>

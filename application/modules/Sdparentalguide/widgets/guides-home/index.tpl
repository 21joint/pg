<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>
<div class="page-hero prg-guides--home bg-white">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-10 text-center">
        <h1 class="text-primary text-uppercase page-hero--title">Guides</h1>
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
                     placeholder="Search for a specific guide">
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
<div class="page-section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="content-box bg-sm-white">
          <div class="row">
            <div class="col-12 px-0 px-sm-3">
              <div class="row no-gutters cards-grid guides"
                   id="guidesGrid"
                   data-load=".card"
                   data-url="guide">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/scripts/guides_home.bundle.js"></script>

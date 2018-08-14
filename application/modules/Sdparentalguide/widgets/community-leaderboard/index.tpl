<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>


<div class="leaderboard">
  <div class="leaderboard_title d-flex justify-content-between align-items-center">
    <h3><?php echo $this->translate('Leaderboard'); ?></h3>
    <ul class="d-flex align">
      <li id="leaderboard_nav_ovl"
          class="leaderboard_nav leaderboard_title_active">Overall
      </li>
      <li id="leaderboard_nav_mth" class="leaderboard_nav">Month</li>
      <li id="leaderboard_nav_wek" class="leaderboard_nav">Week</li>
      <li id="leaderboard_nav_day" class="leaderboard_nav">Today</li>
    </ul><!-- Add back in when the service is provided -->
  </div>
  <div class="leaderboard_main d-flex justify-content-between">
    <div class="d-flex justify-content-center"><?php echo $this->translate(
        'Rank'
      ); ?></div>
    <div class="d-flex"><?php echo $this->translate('Leader'); ?></div>
    <!-- Categories Start -->
    <div id="points"
         class="order_by d-flex justify-content-center align-items-center"
         data-order="contributionPoints"><?php echo $this->translate(
        'Contribution'
      ); ?></div>
    <div id="reviews"
         class="order_by d-none d-md-flex justify-content-center align-items-center"
         data-order="reviewCount"><?php echo $this->translate(
        'Reviews'
      ); ?></div>
    <!-- <div class="d-none d-md-flex justify-content-center align-items-center">Answers -->
    <!-- Sort By Answer not supported yet by the service layer -->
    <!-- </div> -->
    <div id="questions"
         class="order_by d-none d-md-flex justify-content-center align-items-center"
         data-order="questionCount"><?php echo $this->translate(
        'Struggles'
      ); ?></div>
    <!-- <div id="followers" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="followers"><?php echo $this->translate(
      'Followers'
    ); ?></div> -->
    <!-- Categories End -->
    <div class="d-flex d-md-none justify-content-center align-items-center">
      <button id="order_btn" class="btn-primary rounded-circle">></button>
    </div>
    <!-- Toggle Button End -->
  </div>
  <div id="sd-response" class="container d-flex justify-content-center">
    <!-- Loader goes here -->
  </div>
  <div class="leaderboard_content">

  </div>
  <div
    class="leaderboard_pagination d-flex justify-content-end align-items-center mt-5 mr-5">
    <!-- Content Pagination -->
    <span id="leaderboard_previous" class="pagination_button"><</span>
    <span id="leaderboard_pageNum" class="mx-3">
            <!-- Displays the current page of Leaderboard Results -->
        </span>
    <span id="leaderboard_next" class="pagination_button">></span>
  </div>
</div>

<script src="/scripts/community_leaderboard.bundle.js"></script>

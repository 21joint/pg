<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepagereview/externals/styles/style_sitepagereview.css')
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepagereview/externals/styles/show_star_rating.css');
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<script type="text/javascript">
  function showReviewTab(){
    if($('main_tabs')) {
      tabContainerSwitch($('main_tabs').getElement('.tab_layout_sitepagereview_profile_sitepagereviews'));
      ShowContent('<?php echo $this->content_id; ?>', execute_Request_Review, '<?php echo $this->content_id ?>', 'review', 'sitepagereview', 'profile-sitepagereviews', page_showtitle,'null');
    }
    if($('profile_status')) {
      location.hash = 'profile_status';
    }
  }
</script>

<ul class="sitepage_sidebar_list sitepagereview_sidebar">

  <?php $iteration = 1; ?>
  <?php foreach ($this->ratingData as $reviewcat): ?>

    <?php if (!empty($reviewcat['reviewcat_name'])): ?>
      <?php
      $showRatingImage = Engine_Api::_()->sitepagereview()->showRatingImage($reviewcat['avg_rating'], 'box');
      $rating_value = $showRatingImage['rating_value'];
      ?>
    <?php else: ?>
      <?php
      $showRatingImage = Engine_Api::_()->sitepagereview()->showRatingImage($reviewcat['avg_rating'], 'star');
      $rating_value = $showRatingImage['rating_value'];
      $rating_valueTitle = $showRatingImage['rating_valueTitle'];
      ?>
    <?php endif; ?>

    <li class="sitepagereview_overall_rating">
      <?php if (!empty($reviewcat['reviewcat_name'])): ?>
        <div class="review_cat_rating" style="width:70px;">
          <ul class='rating-box-small <?php echo $rating_value; ?>'>
            <li id="1" class="rate one">1</li>
            <li id="2" class="rate two">2</li>
            <li id="3" class="rate three">3</li>
            <li id="4" class="rate four">4</li>
            <li id="5" class="rate five">5</li>
          </ul>
        </div>
      <?php else: ?>
        <div class="review_cat_rating">
          <ul title="<?php echo $rating_valueTitle . $this->translate(" rating"); ?>" class='rating <?php echo $rating_value; ?>' style="background-image: url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitepagereview/externals/images/show-star-matrix.png);">
            <li id="1" class="rate one">1</li>
            <li id="2" class="rate two">2</li>
            <li id="3" class="rate three">3</li>
            <li id="4" class="rate four">4</li>
            <li id="5" class="rate five">5</li>
          </ul>
        </div>
      <?php endif; ?>
      <div class="review_cat_title">
        <?php if (!empty($reviewcat['reviewcat_name'])): ?>
          <?php echo $this->translate($reviewcat['reviewcat_name']); ?>
        <?php else: ?>
          <b><?php echo $this->translate("Overall Rating"); ?></b>
        <?php endif; ?>
      </div>

    </li>

    <?php if ($iteration == 1): ?>
      <li>
        <?php if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1)): ?>
          <?php echo $this->translate("Recommended by ") . '<b>' . $this->recommend_percentage . '%</b>' . $this->translate(" members"); ?>
        <?php endif; ?>
      </li>

    <?php endif; ?>

    <?php $iteration++; ?>
  <?php endforeach; ?>

  <li>

    <?php if ($this->totalReviews == 1): ?>
      <?php $more_link = $this->totalReviews . $this->translate(' Review'); ?>
    <?php else: ?>
      <?php $more_link = $this->translate('All ') . $this->totalReviews . $this->translate(' Reviews'); ?>
    <?php endif; ?>

    <?php $pagelayout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layout.setting', 1); ?>

    <?php $url = $this->url(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($this->sitepage->page_id), 'tab' => $this->content_id), 'sitepage_entry_view', true);?>

    <?php if ($this->can_create == 1 && empty($this->is_manageadmin) && !empty($this->content_id)): ?>

      <?php echo $this->htmlLink(array('route' => 'sitepagereview_create', 'page_id' => $this->sitepage->page_id, 'tab' => $this->content_id), $this->translate('Rate & Review'), array('class' => 'sitepagereview_more_link')) ?> 
      <span class="sitepagereview_more_sep">|</span>

      <?php if ($pagelayout) : ?>
        <a href="<?php echo $url; ?>" onclick='showReviewTab();return false;' class="sitepagereview_more_link"><?php echo $more_link; ?></a>
      <?php else: ?>
        <a class="tab_<?php echo $this->content_id; ?> sitepagereview_more_link" href="<?php echo $url; ?>" onclick='showReviewTab();return false;'><?php echo $more_link; ?></a>
      <?php endif; ?>

    <?php elseif ($this->content_id): ?>		 

      <?php if ($pagelayout) : ?>
        <a href="<?php echo $url; ?>" onclick='showReviewTab();return false;' class="sitepagereview_more_link"><?php echo $more_link . ' &raquo;'; ?></a>
      <?php else: ?>
        <a class="tab_<?php echo $this->content_id; ?>" href="<?php $url; ?>" onclick='showReviewTab();return false;' class="sitepagereview_more_link"><?php echo $more_link . ' &raquo;'; ?></a>
      <?php endif; ?>
    <?php endif; ?>
  </li>
</ul>
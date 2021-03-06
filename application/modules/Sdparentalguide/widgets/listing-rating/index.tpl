<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<?php
$this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . "application/modules/Sdparentalguide/externals/styles/style_ratingcustom.css");
?>

<?php $reviewRatingClass = ""; ?>
<?php $listingRatingClass = ""; ?>
<?php $reviewRatingHtml = ""; ?>
<?php $listingRatingHtml = ""; ?>
<?php if (!empty($this->userRating)): ?>
    <?php $reviewRatingClass = $this->userRating->getRatingClass($this->userRating->review_rating); ?>
    <?php $listingRatingClass = $this->userRating->getRatingClass($this->userRating->product_rating); ?>
    <?php $reviewRatingHtml = $this->translate("Authors Rating Review: %s", $this->review_rating); ?>
    <?php $listingRatingHtml = $this->translate("Product Rating: %s", $this->review_rating); ?>
<?php endif; ?>

<div class="prg-stars">
  <h6 class="font-weight-bold small"><?= $this->translate("Rate this review:") ?></h6>
  <ul class="list-inline m-0 sr_ug_rating <?= $reviewRatingClass; ?>"
      title="<?= $reviewRatingHtml; ?>"
      data-type="review_rating">
    <li id="1" class="rate one"><a href="javascript:void(0);" onclick="doDefaultRating(this," 1", "onestar");"
      title="<?= $this->translate("1 Star"); ?>" id="star_1_0">1</a></li>
    <li id="2" class="rate two"><a href="javascript:void(0);" onclick="doDefaultRating(this," 2", "twostar");"
      title="<?= $this->translate("2 Stars"); ?>" id="star_2_0">2</a></li>
    <li id="3" class="rate three"><a href="javascript:void(0);" onclick="doDefaultRating(this," 3", "threestar");"
      title="<?= $this->translate("3 Stars"); ?>" id="star_3_0">3</a></li>
    <li id="4" class="rate four"><a href="javascript:void(0);" onclick="doDefaultRating(this," 4", "fourstar");"
      title="<?= $this->translate("4 Stars"); ?>" id="star_4_0">4</a></li>
    <li id="5" class="rate five"><a href="javascript:void(0);" onclick="doDefaultRating(this," 5", "fivestar");"
      title="<?= $this->translate("5 Stars"); ?>" id="star_5_0">5</a></li>
  </ul>
</div>

<div class="prg-stars">
  <h6 class="font-weight-bold small" class="sd_rating_label"><?= $this->translate("Rate this product:") ?></h6>
  <ul class="sr_ug_rating <?= $listingRatingClass; ?>" title="<?= $listingRatingHtml; ?>" data-type="product_rating">
    <li id="1" class="rate one"><a href="javascript:void(0);" onclick="doDefaultRating(this," 1", "onestar");"
      title="<?= $this->translate("1 Star"); ?>" id="star_1_0">1</a></li>
    <li id="2" class="rate two"><a href="javascript:void(0);" onclick="doDefaultRating(this," 2", "twostar");"
      title="<?= $this->translate("2 Stars"); ?>" id="star_2_0">2</a></li>
    <li id="3" class="rate three"><a href="javascript:void(0);" onclick="doDefaultRating(this," 3", "threestar");"
      title="<?= $this->translate("3 Stars"); ?>" id="star_3_0">3</a></li>
    <li id="4" class="rate four"><a href="javascript:void(0);" onclick="doDefaultRating(this," 4", "fourstar");"
      title="<?= $this->translate("4 Stars"); ?>" id="star_4_0">4</a></li>
    <li id="5" class="rate five"><a href="javascript:void(0);" onclick="doDefaultRating(this," 5", "fivestar");"
      title="<?= $this->translate("5 Stars"); ?>" id="star_5_0">5</a></li>
  </ul>
</div>
<script type="text/javascript">

  function doRating(element_id, ratingparam_id, classstar) {
    $(element_id + "_" + ratingparam_id).getParent().getParent().className = "sr-us-box rating-box " + classstar;
    $("review_rate_" + ratingparam_id).value = $(element_id + "_" + ratingparam_id).getParent().id;
  }

  function doDefaultRating(element, rating, classstar) {
    var ul = $(element).getParent("ul");
    ul.className = "sr_ug_rating " + classstar;
    rateListing(ul.get("data-type"), rating);
  }

  function rateListing(type, rating) {
    var req = new Request.JSON({
      url: en4.core.baseUrl + "widget/index/content_id/" + <?= sprintf("'%d'", $this->identity) ?>,
      data: {
        format: "json",
        type: type,
        rating: rating,
        subject: en4.core.subject.guid
      },
      onSuccess: function (responseJSON) {

      },
    });
    req.send();
  }
</script>
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
        ->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sdparentalguide/externals/styles/style_ratingcustom.css');
?>

<?php $reviewRatingClass = ''; ?>
<?php $listingRatingClass = ''; ?>
<?php $reviewRatingHtml = ''; ?>
<?php $listingRatingHtml = ''; ?>
<?php if(!empty($this->userRating)): ?>
    <?php $reviewRatingClass = $this->userRating->getRatingClass($this->userRating->review_rating); ?>
    <?php $listingRatingClass = $this->userRating->getRatingClass($this->userRating->product_rating); ?>
    <?php $reviewRatingHtml = $this->translate("Authors Rating Review: %s", $this->review_rating); ?>
    <?php $listingRatingHtml = $this->translate("Product Rating: %s", $this->review_rating); ?>
<?php endif; ?>

<div class='card-stars' title="" style="margin: 0px auto;">
    <span class='sd_rating_label'><?php echo $this->translate('Rate this review:') ?></span>
    <ul class='list-inline my-0 sr_ug_rating <?php echo $reviewRatingClass; ?>' title="<?php echo $reviewRatingHtml; ?>" data-type='review_rating'>
        <li id="1" class="rate one"><a href="javascript:void(0);" onclick="doDefaultRating(this,'1', 'onestar');" title="<?php echo $this->translate("1 Star"); ?>"   id="star_1_0">1</a></li>
        <li id="2" class="rate two"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'2', 'twostar');" title="<?php echo $this->translate("2 Stars"); ?>"   id="star_2_0">2</a></li>
        <li id="3" class="rate three"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'3', 'threestar');" title="<?php echo $this->translate("3 Stars"); ?>" id="star_3_0">3</a></li>
        <li id="4" class="rate four"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'4', 'fourstar');" title="<?php echo $this->translate("4 Stars"); ?>"   id="star_4_0">4</a></li>
        <li id="5" class="rate five"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'5', 'fivestar');" title="<?php echo $this->translate("5 Stars"); ?>"  id="star_5_0">5</a></li>
    </ul>
</div>

<div class='sd_rate_listing' style="margin: 0px auto;margin-top: 5px;">
    <span class='sd_rating_label'><?php echo $this->translate('Rate this product:') ?></span>
    <ul class='sr_ug_rating <?php echo $listingRatingClass; ?>' title="<?php echo $listingRatingHtml; ?>" data-type='product_rating'>
        <li id="1" class="rate one"><a href="javascript:void(0);" onclick="doDefaultRating(this,'1', 'onestar');" title="<?php echo $this->translate("1 Star"); ?>"   id="star_1_0">1</a></li>
        <li id="2" class="rate two"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'2', 'twostar');" title="<?php echo $this->translate("2 Stars"); ?>"   id="star_2_0">2</a></li>
        <li id="3" class="rate three"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'3', 'threestar');" title="<?php echo $this->translate("3 Stars"); ?>" id="star_3_0">3</a></li>
        <li id="4" class="rate four"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'4', 'fourstar');" title="<?php echo $this->translate("4 Stars"); ?>"   id="star_4_0">4</a></li>
        <li id="5" class="rate five"><a href="javascript:void(0);"  onclick="doDefaultRating(this,'5', 'fivestar');" title="<?php echo $this->translate("5 Stars"); ?>"  id="star_5_0">5</a></li>
    </ul>
</div>
 <script type="text/javascript">

function doRating(element_id, ratingparam_id, classstar) {
    $(element_id + '_' + ratingparam_id).getParent().getParent().className= 'sr-us-box rating-box ' + classstar;
    $('review_rate_' + ratingparam_id).value = $(element_id + '_' + ratingparam_id).getParent().id;
}

function doDefaultRating(element,rating,classstar) {
    var ul = $(element).getParent("ul");
    ul.className = 'sr_ug_rating ' + classstar;
    rateListing(ul.get("data-type"),rating);
}

function rateListing(type,rating){
    var req = new Request.JSON({
        url: en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
        data: {
            format: 'json',
            type: type,
            rating: rating,
            subject: en4.core.subject.guid
        },
        onSuccess: function(responseJSON){
            
        },
    });
    req.send();
}
</script>
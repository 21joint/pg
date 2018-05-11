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
<?php $ratingValue = $this->element->getValue(); ?>
<?php
  switch ($ratingValue) {
    case 0:
      $rating_value = '';
      break;
    case 1:
      $rating_value = 'onestar';
      break;
    case 2:
      $rating_value = 'twostar';
      break;
    case 3:
      $rating_value = 'threestar';
      break;
    case 4:
      $rating_value = 'fourstar';
      break;
    case 5:
      $rating_value = 'fivestar';
      break;
  }
  ?>
<div class="form-wrapper" id="owner_rating-wrapper">
    <div class="form-label" id="owner_rating-label">
        <label class="optional" for="owner_rating"><?php echo $this->translate('Owner Rating');?></label>
    </div>
    <div class="form-element" id="owner_rating-element">
        <ul id= 'rate_0' class='sr_ug_rating <?php echo $rating_value; ?>'>
            <li id="1" class="rate one"><a href="javascript:void(0);" onclick="doDefaultRating('star_1', '0', 'onestar');" title="<?php echo $this->translate("1 Star"); ?>"   id="star_1_0">1</a></li>
            <li id="2" class="rate two"><a href="javascript:void(0);"  onclick="doDefaultRating('star_2', '0', 'twostar');" title="<?php echo $this->translate("2 Stars"); ?>"   id="star_2_0">2</a></li>
            <li id="3" class="rate three"><a href="javascript:void(0);"  onclick="doDefaultRating('star_3', '0', 'threestar');" title="<?php echo $this->translate("3 Stars"); ?>" id="star_3_0">3</a></li>
            <li id="4" class="rate four"><a href="javascript:void(0);"  onclick="doDefaultRating('star_4', '0', 'fourstar');" title="<?php echo $this->translate("4 Stars"); ?>"   id="star_4_0">4</a></li>
            <li id="5" class="rate five"><a href="javascript:void(0);"  onclick="doDefaultRating('star_5', '0', 'fivestar');" title="<?php echo $this->translate("5 Stars"); ?>"  id="star_5_0">5</a></li>
        </ul>
        <input type='hidden' name='owner_rating' id='review_rate_0' value='<?php echo $ratingValue; ?>'/>
    </div>
    
</div>
 <script type="text/javascript">

  function doRating(element_id, ratingparam_id, classstar) {
    $(element_id + '_' + ratingparam_id).getParent().getParent().className= 'sr-us-box rating-box ' + classstar;
    $('review_rate_' + ratingparam_id).value = $(element_id + '_' + ratingparam_id).getParent().id;
  }

  function doDefaultRating(element_id, ratingparam_id, classstar) {
    $(element_id + '_' + ratingparam_id).getParent().getParent().className= 'sr_ug_rating ' + classstar;
    $('review_rate_' + ratingparam_id).value = $(element_id + '_' + ratingparam_id).getParent().id;
  }

</script>
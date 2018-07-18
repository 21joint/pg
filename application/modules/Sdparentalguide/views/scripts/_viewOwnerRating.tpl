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
<?php $ratingValue = $this->rating; ?>
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
<?php $html_title = $this->translate("Authors Rating: %s", $this->rating);; ?>
<ul class='sr_ug_rating <?= $rating_value; ?> sd_view_listing_rating'
    title="<?= $html_title; ?>"
    style="margin: 0px auto;">
  <li class="rate one"><?= $this->translate('1') ?></li>
  <li class="rate two"><?= $this->translate('2') ?></li>
  <li class="rate three"><?= $this->translate('3') ?></li>
  <li class="rate four"><?= $this->translate('4') ?></li>
  <li class="rate five"><?= $this->translate('5') ?></li>
</ul>
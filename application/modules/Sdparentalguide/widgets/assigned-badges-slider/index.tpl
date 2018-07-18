<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitealbum/externals/styles/style_sitealbum.css'); ?>

<style type="text/css">
.layout_sdparentalguide_assigned_badges_slider .flex-control-nav {
    display: none !important;
}
.layout_sdparentalguide_assigned_badges_slider .flexslider {
    margin-bottom: 0px !important;
}
</style>

<div class="flexslider">
<ul class="seaocore_photo_strips slides">
    <?php foreach($this->assignedBadges as $badge): ?>
        <li class="thumb_photo">
            <?= $this->itemPhoto($badge,'thumb.profile',$badge->getTitle(),array('title' => $badge->getTitle(),'class' => 'thumb_img')); ?>
        </li>
    <?php endforeach; ?>
</ul>
</div>
<?= $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl.'application/modules/Sdparentalguide/externals/scripts/flexslider/flexslider.css'); ?>
<script type="text/javascript" src="<?= $this->layout()->staticBaseUrl; ?>application/modules/Sdparentalguide/externals/scripts/flexslider/jquery.min.js"></script>
<script type="text/javascript">var sdjq = $.noConflict();</script>
<script type="text/javascript" src="<?= $this->layout()->staticBaseUrl; ?>application/modules/Sdparentalguide/externals/scripts/flexslider/jquery.flexslider-min.js"></script>
<script type="text/javascript">
window.addEvent("domready",function(){
    sdjq('.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: <?= (int)$this->photoWidth; ?>,
        itemMargin: 5,
        pausePlay: false,
        allowOneSlide: true,
    });
});   
</script>
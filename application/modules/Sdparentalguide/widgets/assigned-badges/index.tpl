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
  div.thumb_photo{
    height:  <?php echo $this->photoHeight; ?>px !important;
    width: <?php echo $this->photoWidth; ?>px !important; 
    background: transparent !important;
    margin: 5px !important;
  }
  div.thumb_photo a.thumb_img {
    width: <?php echo $this->photoWidth; ?>px !important; 
    height:  <?php echo $this->photoHeight; ?>px !important; 
    background-size: cover ;
  }
</style>

<div class="seaocore_photo_strips">
    <?php
    if ($this->photoWidth > $this->normalLargePhotoWidth):
      $photo_type = 'thumb.main';
    elseif ($this->photoWidth > $this->normalPhotoWidth):
      $photo_type = 'thumb.medium';
    else:
      $photo_type = 'thumb.normal';
    endif;
    ?>
    <?php foreach($this->assignedBadges as $badge): ?>
        <div class="thumb_photo">
            <a href="javascript:void(0);"  title="<?php echo $badge->getTitle(); ?>" style="background-image: url('<?php echo $badge->getPhotoUrl($photo_type); ?>');" class="thumb_img">
            </a>
        </div>
    <?php endforeach; ?>
</div>

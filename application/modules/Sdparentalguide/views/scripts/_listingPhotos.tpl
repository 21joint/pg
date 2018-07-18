<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<?php $sitereview = $this->sitereview; ?>
<?php $photosPaginator = Engine_Api::_()->sdparentalguide()->getListingPhotos($sitereview); ?>
<ul class='sd_listing_photos slides'>
<?php if($photosPaginator->getTotalItemCount() <= 0): ?>
    <li>
        <?= $this->htmlLink("javascript:void(0);", $this->itemPhoto($sitereview, 'thumb.normal'),array('class' => 'zoom', 'onclick' => 'showLargeImage(this)','data-thumb' => $sitereview->getPhotoUrl('thumb.main'))) ?>
    </li>
<?php else: ?>
    <?php foreach($photosPaginator as $photo): ?>
        <li>
            <?= $this->htmlLink("javascript:void(0);", $this->itemPhoto($photo, 'thumb.normal'),array('class' => 'zoom', 'onclick' => 'showLargeImage(this)','data-thumb' => $photo->getPhotoUrl('thumb.main'))) ?>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>

<div class="holder-owner-photo">
    <a href="<?php echo $viewer->getHref(); ?>">
        <?php echo $ownerPhoto = $this->itemPhoto($viewer, 'thumb.icon');?>
    </a>
</div>
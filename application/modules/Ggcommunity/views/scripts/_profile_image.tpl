<?php $viewer = Engine_Api::_()->user()->getViewer();?>

<div class="holder-owner-photo">
    <a href="<?php echo $viewer->getHref(); ?>">
        <?php echo $ownerPhoto = $this->itemPhoto($viewer, 'thumb.icon');?>
        <div class="owner_level">
            <?php echo $viewer->getOwner()->level_id;?>
        </div>
    </a>
</div>
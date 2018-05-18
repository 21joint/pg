<div class="latest_widget">
    <div class="widget_holder">
        <div class="widget_title">
            <p class="widget_name"><?php echo $this->title;?></p>
            <a href="<?php echo $this->url(array(), 'browse_struggles', true) . '?param=latest';?>" class="view_more"><?php echo $this->translate('view more'); ?></a>
        </div>
        <div class="topic_holder">
            <?php foreach($this->paginator as $item): ?>
                <div class="struggle_holder">
                    <div class="struggle_left_side">
                        <a href="<?php echo $item->getOwner()->getHref();?>" class="struggle_owner_image">
                            <?php echo $this->itemPhoto($item->getOwner(), 'thumb.icon', array('class'=> 'owner_thumb')) ?>
                            <div class="owner_level">
                                <?php echo $item->getOwner()->level_id;?>
                            </div>
                        </a>
                    </div>
                    <div class="struggle_right-side">
                        <a href="<?php echo $item->getHref();?>" class="struggle_title"><?php echo $item->getTitle();?></a>
                        <ul class="struggle_info">
                            <li class="struggle_time_created">
                                <?php echo 'asked '. Engine_Api::_()->ggcommunity()->time_elapsed_string($item->creation_date);?>
                            </li>
                            <li>᛫</li>
                            <li class="struggle_owner_name">
                                <?php echo $item->getowner(); ?>
                            </li>
                        </ul>

                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
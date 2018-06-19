<div class="top_theorist_widget">
    <div class="widget_holder">
        <div class="widget_title">
            <p class="widget_name"><?php echo $this->translate('Top Theorists');?></p>
            <?php if($this->more == 1):?>
                <a href="<?php echo $this->url(array(), 'ggcommunity_leaderboard', true);?>" class="view_more"><?php echo $this->translate('view more'); ?></a>
            <?php endif;?>
        </div>
        <div class="members_holder">
            <ul class="members_list ">
                <?php $x = 1;?>
                <?php foreach($this->paginator as $item): ?>
                    <li class="member_item">
                        <div class="member_left_side large-9 medium-9 small-9">
                            <div class="counter-number large-1 medium-1 small-1">
                                <?php echo $x++;?>
                            </div>
                            <?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon')); ?>
                            <a href="<?php echo $item->getOwner()->getHref();?>" class="struggle_owner_name">
                                <?php echo $item->getOwner()->getTitle();?> 
                            </a>
                        </div>
                        <div class="member_right_side">
                            <svg style="margin-right:5px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"/><stop offset="1" stop-color="#5bc6cd"/></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"/><stop offset="1" stop-color="#51b2b6"/></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"/><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"/><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"/></svg>
                            <span class="member_credits">35412</span>
                        </div>
                       
                    </li>
                   
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</div>
<?php if($this->more == 0):?>
    <a href="<?php echo $this->url(array(), 'ggcommunity_leaderboard', true);?>" class="all_top_members"><?php echo $this->translate('View the full list');?></a>
<?php endif;?>

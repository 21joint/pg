<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var badgesAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Badges'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_badges')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', badgesAccountParams);
</script>
<?php endif; ?>

<?php if($this->showContent): ?>

    <div class="container mb-4">
        <div class="badges_main_holder">
            <div class="main_box">
                <div class="holder-my-badges">
                    
                    <?php if($this->specialBadges->getTotalItemCount() > 0): ?>
                    <div class="holder-special-badges pt-lg-3 pb-lg-4 px-lg-5 bg-white mb-4">

                        <!-- title holder -->
                        <div class="title-holder mb-4">
                            <h4 class="pb-4"><?php echo $this->translate('Special Badges');?></h4>
                        </div>

                        <!-- win badges -->
                        <ul class="grib-grab-two">
                        <?php foreach($this->specialBadges as $special):?>
                            <li class="d-flex align-items-center border-grey-light">
                                
                                <div class="left-side col-xl-2 col-lg-2 col-3 thumbnail-photo">
                                    <?php echo $this->itemPhoto($special, 'thumb.normal'); ?>
                                </div>
                                <div class="right-side">
                                    <div class="title-holder">
                                        <h5>
                                            <?php echo $special->name; ?>
                                        </h5>
                                    </div>
                                    <div class="description-holder">
                                        <p><?php echo $special->description; ?></p>
                                    </div>
                                </div>
                                
                            </li>
                        <?php endforeach; ?>
                        </ul>

                    </div> <!-- holder-special -->
                    <?php endif; ?>

                    <?php if($this->contributorBadges->getTotalItemCount() > 0): ?>
                    <div class="contributor-badges-holder pt-3 pb-4 px-5 bg-white mb-4">
                        <!-- title holder -->
                        <div class="title-holder mb-4">
                            <h4 class="pb-4">
                                <?php echo $this->translate('Contributor Badges');?>
                            </h4>
                        </div>

                        <ul class="grib-grab-two">
                            <?php foreach($this->contributorBadges as $topic):?>

                            <?php
                                $table = Engine_Api::_()->getDbTable('badges', 'sdparentalguide');
                                // select special badges
                                $select = $table->select()
                                    ->where('topic_id = ?', $topic->topic_id)
                                    ->order( 'name ASC' )
                                    ->limit(5)
                                ;
                                $badges = $table->fetchAll($select);
                            ?>

                            <li class="border-grey-light py-3">
                                <!-- title and descriptiotn -->
                                <div class="top-holder border-bottom-grey-light px-4 ">
                                    <div class="title-holder pb-3">
                                        <h6>
                                            <?php echo $topic->getTitle(); ?>
                                        </h6>
                                    </div>
                                    <div class="description-holder pb-3">
                                        <p><?php echo $topic->getDescription(); ?></p>
                                    </div>
                                </div>
                                <!-- badge image -->
                                <div class="bottom-holder d-flex align-items-center px-3 pt-3 ml-auto mr-auto holder-badge-image">
                                    <?php foreach($badges as $badge ):?>
                                        
                                        <?php if($badge->profile_display > 0): ?>
                                            <?php 
                                                $userTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
                                                $select = $userTable->select()
                                                    ->where('user_id = ?', $this->subject->getIdentity(0))
                                                    ->where('badge_id = ?', $badge->getIdentity())
                                                ;
                                                $uBadge = $userTable->fetchRow($select);
                                            ?>
                                            <?php if(count($uBadge) > 0): ?>
                                                <div class="badge-image col-xl-2 col-lg-2 col-6 pl-lg-0 <?php echo ($uBadge->active == 1) ? 'active' : 'inactive'; ?> ">
                                                    <?php echo $this->itemPhoto($badge, 'thumb.normal'); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="badge-image col-xl-2 col-lg-2 col-6 pl-lg-0 inactive">
                                                    <?php echo $this->itemPhoto($badge, 'thumb.normal'); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                        <?php endif; ?>
                                       
                                    <?php endforeach; ?>
                                </div>
                            </li>
                            <?php endforeach; ?>

                        </ul>

                    </div> <!-- end of contributor badges -->
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>



<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var badgesAccountParams = {
            requestParams :{"title":"<?= $this->translate('Badges'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_badges')
        }
        en4.gg.ajaxTab.attachEvent('<?= $this->identity ?>', badgesAccountParams);
</script>
<?php endif; ?>

<?php if($this->showContent): ?>

    <div class="container mb-4">
        <div class="badges_main_holder">
            <div class="main_box">
                <div class="holder-my-badges">

                    <div class="holder-special-badges pt-lg-3 pb-lg-4 px-lg-5 bg-white mb-4">
                        <?php if($this->specialBadges->getTotalItemCount() > 0): ?>
                        

                            <!-- title holder -->
                            <div class="title-holder mb-4">
                                <h4 class="pb-4"><?= $this->translate('Special Badges');?></h4>
                            </div>

                            <!-- win badges -->
                            <ul class="grib-grab-two">
                            <?php foreach($this->specialBadges as $special):?>
                                <li class="d-flex align-items-center border-grey-light">
                                    
                                    <div class="left-side col-xl-2 col-lg-2 col-3 thumbnail-photo">
                                        <?= $this->itemPhoto($special, 'thumb.normal'); ?>
                                    </div>
                                    <div class="right-side">
                                        <div class="title-holder">
                                            <h5>
                                                <?= $special->name; ?>
                                            </h5>
                                        </div>
                                        <div class="description-holder">
                                            <p><?= $special->description; ?></p>
                                        </div>
                                    </div>
                                    
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                        <div class="tip-message py-2 w-100">
                            <span class="mb-0">
                                <?= $this->translate('No special badges to your profile yet..'); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div> <!-- holder-special -->

                    <!-- contributor badges -->
                    <div class="contributor-badges-holder pt-3 pb-4 px-5 bg-white mb-4">
                        <?php if($this->contributorBadges->getTotalItemCount() > 0): ?>
                   
                        <!-- title holder -->
                        <div class="title-holder mb-4">
                            <h4 class="pb-4">
                                <?= $this->translate('Contributor Badges');?>
                            </h4>
                        </div>

                        <ul class="grib-grab-two">
                            <?php foreach($this->contributorBadges as $topic):?>

                            <?php
                                $table = Engine_Api::_()->getDbTable('badges', 'sdparentalguide');
                                // select special badges
                                $select = $table->select()
                                    ->where('topic_id = ?', $topic->topic_id)
                                    ->order('level DESC')
                                    ->limit(5)
                                ;
                                $badges = $table->fetchAll($select);
                                
                            ?>

                            <li class="border-grey-light py-3">
                                <!-- title and descriptiotn -->
                                <div class="top-holder border-bottom-grey-light px-4 ">
                                    <div class="title-holder pb-3">
                                        <h6>
                                            <?= $topic->getTitle(); ?>
                                        </h6>
                                    </div>
                                    <div class="description-holder pb-3">
                                        <p><?= $topic->getDescription(); ?></p>
                                    </div>
                                </div>
                                <!-- badge image -->
                                <div class="bottom-holder  d-flex align-items-center px-3 pt-3 ml-auto mr-auto holder-badge-image">
                                      
                                    <?php foreach($badges as $badge ):?>

                                        <?php if($badge->profile_display > 0): ?>
                                            <?php 
                                                $userTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
                                                $selectAssignedBadges = $userTable->select()
                                                    ->where('user_id = ?', $this->subject->getIdentity(0))
                                                    ->where('badge_id = ?', $badge->getIdentity())
                                                    ->limit(1)
                                                ;
                                                
                                                $uBadge = $userTable->fetchRow($selectAssignedBadges);
                                            ?>
                                            <?php if(count($uBadge) > 0): ?>
                                                <div class="badge-image col-xl-2 col-lg-2 col-6 pl-lg-0 <?= ($uBadge->active == 1) ? 'active' : 'inactive'; ?> ">
                                                    <?= $this->itemPhoto($badge, 'thumb.normal'); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="badge-image col-xl-2 col-lg-2 col-6 pl-lg-0 inactive">
                                                    <?= $this->itemPhoto($badge, 'thumb.normal'); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                        <?php endif; ?>
                                       
                                    <?php endforeach; ?>
                                </div>
                            </li>
                            <?php endforeach; ?>

                        </ul>

                    
                        <?php else: ?>
                        <div class="tip-message py-2 w-100">
                            <span class="mb-0">
                                <?= $this->translate('No Contributor badges to your profile yet..'); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div> <!-- end of contributor badges -->
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>



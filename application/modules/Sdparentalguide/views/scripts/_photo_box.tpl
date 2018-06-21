<?php $subject = $this->subject; ?>

<?php 
    $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
    $select = $table->select()
        ->where('user_id = ?',$subject->getIdentity() );
    $answer = $table->fetchAll($select);
    
?>
<div class="extfox-widgets">
    
    <div class="row">
    
        <div class="box-hover-member bg-white position-absolute">
            <div class="box-holder">

                <div class="header d-flex mx-3 mt-3 p-relative pl-2 pt-2">

                    <div class="close position-absolute">
                        <a href="javascript:void(0)">
                            <svg width="14px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>
                        </a>
                    </div>

                    <div class="photo-image">
                        <a href="<?php echo $subject->getHref(); ?>">
                            <img src="<?php echo $subject->getPhotoUrl(); ?>" />
                        </a>
                    </div>
                    <div class="right-side pl-3">
                        <div class="text-dark font-weight-bold pb-1">
                            <?php echo $this->htmlLink($subject->getHref(), $subject->getTitle()); ?>
                        </div>
                        <div class="header-star d-flex align-items-center">
                            <div class="holder-rate d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <svg height="20px" style="margin-top: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68.137 80"><defs><style>.b_box_hover{fill:#52b1b8;}.c_box_hover{fill:#5cc7cd;}</style></defs><g transform="translate(13961.751 6200.271)"><g transform="translate(-13961.75 -6200.271)"><path class="b_box_hover" d="M108.85,27,96.662,43.071,86.2,29.121l20.283-4.571C109.459,23.8,110.323,24.992,108.85,27Z" transform="translate(-43.623 -13.136)"/><path class="b_box_hover" d="M30.556,40.68,3.864,47.338c-1.7.386-2.077,2.189-.508,2.9l26.032,7.618Z" transform="translate(-2.361 -21.186)"/><path class="c_box_hover" d="M51.227,64.559l3.438-60.7c0-1.717,1.772-2.118,2.834-.731l36.336,45.3c1.346,1.59.975,4.4-2.773,2.93L69.148,42.97,55.462,65.625C54.076,68.017,51.11,67.042,51.227,64.559Z" transform="translate(-26.41 -2.293)"/></g></g></svg>
                                </div>
                                <span class="text-primary pl-2"><?php echo $subject->gg_contribution; ?> </span>
                            </div>
                            <i class="fa fa-circle pl-2"></i>
                            <div class="holder-followers pl-2">
                                <?php echo $subject->gg_followers_count;?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="badges-earned bg-white mb-2 widget">
                    <div class="holder p-2">
                        <div class="bottom row d-flex justify-content-center m-0">
                            <div class="col-sm bronze">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $subject->gg_bronze_count; ?>
                                </div>
                                <span class="text-muted small">Platinium</span>
                            </div>
                            <div class="col-sm silver">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $subject->gg_silver_count; ?>     
                                </div>
                                <span class="text-muted small">Gold</span>
                            </div>
                            <div class="col-sm gold">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    
                                    <?php echo $subject->gg_gold_count; ?>
                                </div>
                                <span class="text-muted small">Silver</span>
                            </div>
                            <div class="col-sm platinium">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $subject->gg_platinum_count; ?>
                                </div>
                                <span class="text-muted small">Bronze</span>
                            </div>
                        </div>
                    </div>
                </div> <!-- end of badges-earned -->

                <div class="footer border-top border-gray d-flex justify-content-between align-items-center">
                    
                    <div class="col-sm text-center border-right py-3">
                        Reviews 
                        <span class="text-primary font-weight-bold">
                            <?php echo $subject->gg_review_count;?>
                        </span>
                    </div>

                    <div class="col-sm text-center py-3">
                        Answers 
                        <span class="text-primary font-weight-bold">
                           <?php echo count($answer); ?>
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

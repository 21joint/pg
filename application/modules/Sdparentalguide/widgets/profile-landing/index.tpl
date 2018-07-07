<?php
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Ggcommunity/externals/scripts/swiper.min.js');
    $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
        . 'application/modules/Ggcommunity/externals/styles/swiper.min.css');

    $struggles= Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsPaginator($this->subject->user_id);
?>



<div class="container extfox-widgets">
    <div class="row my-4">

        <div class="col-sm-4 col-12 left-side">
            
            <div class="contribution bg-white mb-4 widget">
                <div class="holder p-4">
                    <div class="top border-bottom border-gray pb-3">
                        <h5 class="text-primary font-weight-bold">
                            <?php echo $this->subject->gg_contribution; ?> 
                        </h5>
                        <p class="desc text-dark">
                            <?php echo $this->translate('Contribution points'); ?>
                        </p>
                    </div>

                    <div class="bottom row pt-4">
                        <div class="col-sm col-3 text-center border-right">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_review_count;?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Reviews'); ?>
                            </p>
                        </div>
                        <div class="col-sm col-3 text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                5
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Guides'); ?>
                            </p>
                        </div>
                        <div class="col-sm col-3 text-center border-left">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo count($struggles); ?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Struggles'); ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div> <!-- end of contribution -->

            <div class="badges-earned bg-white mb-4 widget">
                <div class="holder p-4">
                    <p class="desc title text-dark border-bottom pb-3">
                        <?php echo $this->translate('Badges Earned'); ?>
                    </p>
                    <div class="bottom row pt-4">
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 bronze text-center">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_bronze_count; ?>
                                </div>
                            
                            </div>
                            <span class="text-muted small text-center w-100">Bronze</span>
                        </div>
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 silver text-center">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_silver_count; ?>
                                </div>
                            </div>
                            <span class="text-muted small  w-100">Silver</span>
                        </div>
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 gold ">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_gold_count; ?>
                                </div>
                            </div>
                            <span class="text-muted small text-center w-100">Gold</span>
                        </div>
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 platinium">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_platinum_count; ?>
                                </div>
                            </div>
                            <span class="text-muted small text-center w-100">Platinium</span>
                        </div>
                    </div>
                </div>
            </div> <!-- end of badges-earned -->
            <?php if($this->specialBadges->getTotalItemCount() >= 1):?>
            <div class="badges-slide mb-4 bg-white widget">
                <div class="holder p-4">
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Badges Earned'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)"  class="badges-ern text-primary">
                                <?php echo $this->translate('view all'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="contributor slider pt-4">

                        <!-- Swiper -->
                        <!-- Swiper -->
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <?php foreach($this->specialBadges as $special):?>
                                <div class="swiper-slide mb-0">
                                    <a href="javascript:void(0)">
                                        <?php echo $this->itemPhoto($special, 'thumb.normal'); ?>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination"></div>
                        </div>

                    </div>
                </div>
            </div> <!-- end of badges slide -->
            <?php endif;?>
            

            <?php if($this->showFriends):?>
            <div class="following bg-white widget">
                <div class="holder p-4">
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                Following
                                <span class="total text-primary">
                                <?php echo $this->subject->gg_following_count;?>
                                </span>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> view all </a>
                        </div>
                    </div>

                    <div class="row m-0 pt-4 bottom followers">

                        <?php for($i = 0; $i < 4; $i++): ?>
                        <div class="col-sm-6 mb-1 p-0">
                            <div class="item-holder text-center py-3 m-2">
                                <?php echo $this->htmlLink($this->viewer->getHref(), $this->itemPhoto($this->viewer, 'thumb.normal'), array('class' => 'profile-img')); ?>
                                <p class="title font-weight-bold pt-1">
                                    <?php echo $this->htmlLink($this->viewer->getHref(), $this->viewer); ?>
                                </p>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div> <!-- end of  following -->
            <?php endif; ?>
        </div> <!-- left side -->

        <div class="col-sm-7 col-12 right-side">
                
            <div class="latest-review bg-white mb-4 widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                Latest Reviews
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> view all </a>
                        </div>
                    </div>

                    <div class="bottom small text-muted pt-4">
                        more to come..
                    </div>

                </div>
            </div> <!-- end of latest-review -->

            <div class="latest-guides bg-white mb-4 widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                Latest Guides
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> view all </a>
                        </div>
                    </div>

                    <div class="bottom small text-muted pt-4">
                        more to come..
                    </div>

                </div>
            </div> <!-- end of latest-guides -->

            <div class="latest-strugles bg-white widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                Latest Strugles
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> view all </a>
                        </div>
                    </div>

                    <div class="bottom small text-muted pt-4">
                        more to come..
                    </div>

                </div>
            </div> <!-- end of latest-strugles -->
            
        </div> <!-- right side -->

    </div>
</div>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
      },
      loop: true,
    });

    en4.core.runonce.add(function () {
        let buttonFriend = document.getElementById('user-friendship-cover');
        if(!buttonFriend) return;
        let buttonFriendLink = buttonFriend.getElement('a');
        buttonFriendLink.removeClass('buttonlink');
        buttonFriendLink.addClass('btn btn-success px-5 py-2 text-white');

        <?php if($this->profileSettings):?>
            var tabs = document.getElementsByClassName('tabs_alt'); 
            let liElements = tabs[1].getChildren('ul').getChildren()[0];
            for(var i = 0; i < liElements.length; i++) {
                let getParam = '<?php echo $this->profileSettings; ?>';
                let textContent = liElements[i].getChildren()[0].textContent;
            
                if( ((textContent == 'Personal Info') && ( getParam === 'general' )) || ((textContent == 'User Preferences') && ( getParam === 'preference' )) ){      
                    showEditContent();
                    liElements[i].getChildren()[0].click();
                }
            }
        <?php endif; ?>
            
    });

    $$('.badges-ern').addEvent('click',function(event){
       $$('.tabs_alt ').each(function(elements){
            elements.getElements('ul > li').each(function(el){
                if(el.classList[1] === 'tab_layout_sdparentalguide_ajax_badges'){
                    el.getChildren()[0].click(); 
                }
            });
       });
    });

</script>
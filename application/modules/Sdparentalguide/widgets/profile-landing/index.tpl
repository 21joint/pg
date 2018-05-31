<?php
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Ggcommunity/externals/scripts/swiper.min.js');
    $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
        . 'application/modules/Ggcommunity/externals/styles/swiper.min.css');
?>

<div class="container extfox-widgets">
    <div class="row my-4">

        <div class="col-sm-4 col-12 left-side">
            
            <div class="contribution bg-white mb-4 widget">
                <div class="holder p-4">
                    <div class="top border-bottom border-gray pb-3">
                        <h5 class="text-primary font-weight-bold">
                            35900
                        </h5>
                        <p class="desc text-dark">
                            Contribution points
                        </p>
                    </div>

                    <div class="bottom row pt-4">
                        <div class="col-sm text-center border-right">
                            <h5 class="text-primary font-weight-bold"> 
                                15
                            </h5>
                            <p class="desc text-dark">
                                Reviews
                            </p>
                        </div>
                        <div class="col-sm text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                5
                            </h5>
                            <p class="desc text-dark">
                                Guides
                            </p>
                        </div>
                        <div class="col-sm text-center border-left">
                            <h5 class="text-primary font-weight-bold"> 
                                3
                            </h5>
                            <p class="desc text-dark">
                                Struggles
                            </p>
                        </div>
                    </div>

                </div>
            </div> <!-- end of contribution -->

            <div class="badges-earned bg-white mb-4 widget">
                <div class="holder p-4">
                    <p class="desc title text-dark border-bottom pb-3">
                        Badges Earned
                    </p>
                    <div class="bottom row pt-4">
                        <div class="col-sm col-2 bronze">
                            <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                0
                            </div>
                        </div>
                        <div class="col-sm col-2 silver">
                            <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                0
                            </div>
                        </div>
                        <div class="col-sm col-2 gold">
                            <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                0
                            </div>
                        </div>
                        <div class="col-sm col-2 platinium">
                            <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                0
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end of badges-earned --> 

            <div class="badges-slide mb-4 bg-white widget">
                <div class="holder p-4">
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                Badges Earned
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> view all </a>
                        </div>
                    </div>

                    <div class="contributor slider pt-4">

                        <!-- Swiper -->
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <img src="./application/themes/guidanceguide/assets/images/contributors/9199c86bf4fc5644401b314adf5dc845.png" alt="Image Contributor" />
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <img src="./application/themes/guidanceguide/assets/images/contributors/98b7d9e5a550b81396ab634f7b7b97da.png" alt="Image Contributor" />
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <img src="./application/themes/guidanceguide/assets/images/contributors/CarSeatContributor_Bronze.png" alt="Image Contributor" />
                                    </a>
                                </div>
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

            <div class="following bg-white widget">
                <div class="holder p-4">
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                Following
                                <span class="total text-primary">
                                    15
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

        </div> <!-- left side -->

        <div class="col right-side">
                
            <div class="latest-review col-12 bg-white mb-4 widget">
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

            <div class="latest-guides col-12 bg-white mb-4 widget">
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

            <div class="latest-strugles col-12 bg-white widget">
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
    });
</script>
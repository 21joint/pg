

<div class="holder-my-badges">

    <div class="holder-special-badges pt-lg-3 pb-lg-4 px-lg-5 bg-white mb-4">

        <!-- title holder -->
        <div class="title-holder mb-4">
            <h4 class="pb-4"><?php echo $this->translate('Special Badges');?></h4>
        </div>

        <!-- win badges -->
        <ul class="grib-grab-two">
            <?php for($x = 0; $x < 2; $x++):?>
                <li class="d-flex align-items-center border-grey-light ">
                    
                    <div class="left-side col-xl-2 col-lg-2 col-3">
                        <img class="w-100 d-block" src="<?php echo $this->baseUrl(). '/application/modules/Sdparentalguide/externals/images/mvp_badge.png' ?>"/>
                    </div>
                    <div class="right-side">
                        <div class="title-holder">
                            <h5>MVP</h5>
                        </div>
                        <div class="description-holder">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                        </div>
                    </div>
                    
                </li>
            <?php endfor; ?>

        <!-- not win badges -->
            <?php for($x = 0; $x < 2; $x++):?>
                <li class="d-flex align-items-center border-grey-light ">
                    <div class="left-side col-xl-2 col-lg-2 col-3">
                        <img class="w-100 d-block"  src="<?php echo $this->baseUrl(). '/application/modules/Sdparentalguide/externals/images/badge_not.png' ?>"/>
                    </div>
                    <div class="right-side">
                        <div class="title-holder">
                            <h5>BA Founding</h5>
                        </div>
                        <div class="description-holder">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                        </div>
                    </div>
                    
                </li>
            <?php endfor; ?>
        </ul>

    </div>

    <div class="contributor-badges-holder pt-3 pb-4 px-5 bg-white mb-4">
        <!-- title holder -->
        <div class="title-holder mb-4">
            <h4 class="pb-4"><?php echo $this->translate('Contributor Badges');?></h4>
        </div>

        <ul class="grib-grab-two">

            <?php for($x = 0; $x < 4; $x++):?>
                <li class="border-grey-light py-3">
                    <!-- title and descriptiotn -->
                    <div class="top-holder border-bottom-grey-light px-4">
                        <div class="title-holder pb-3">
                            <h6>Sleep Contributor</h6>
                        </div>
                        <div class="description-holder pb-3">
                            <p>Earn a Silver Car Seat Badge by creating X Car Seat Reviews that gain a four star rating by our community! Click here to create a New Review</p>
                        </div>
                    </div>
                    <!-- badge image -->
                    <div class="bottom-holder d-flex align-items-center px-3 pt-3 ml-auto mr-auto holder-badge-image">
                        <?php for($y = 0; $y < 5; $y++):?>
                            <div class="col-xl-2 col-lg-2 pl-0 ">
                                <img class="w-100 d-block" src="<?php echo $this->baseUrl(). '/application/modules/Sdparentalguide/externals/images/badge_carseat.png' ?>"/>
                            </div>
                        <?php endfor; ?>
                    </div>

                </li>
            <?php endfor; ?>

            <?php for($x = 0; $x < 4; $x++):?>
                <li class="border-grey-light py-3">
                    <!-- title and descriptiotn -->
                    <div class="top-holder border-bottom-grey-light px-4">
                        <div class="title-holder pb-3">
                            <h6>Sleep Contributor</h6>
                        </div>
                        <div class="description-holder pb-3">
                            <p>Earn a Silver Car Seat Badge by creating X Car Seat Reviews that gain a four star rating by our community! Click here to create a New Review</p>
                        </div>
                    </div>
                    <!-- badge image -->
                    <div class="bottom-holder d-flex align-items-center px-3 pt-3 ml-auto mr-auto holder-badge-image">
                        <?php for($y = 0; $y < 5; $y++):?>
                            <div class="col-xl-2 col-lg-2 pl-0 ">
                                <img class="w-100 d-block" src="<?php echo $this->baseUrl(). '/application/modules/Sdparentalguide/externals/images/badge_baby.png' ?>"/>
                            </div>
                        <?php endfor; ?>
                    </div>

                </li>
            <?php endfor; ?>

        </ul>

    </div>
</div>
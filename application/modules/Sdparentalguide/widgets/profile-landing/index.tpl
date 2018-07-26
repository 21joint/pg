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
                            <?php echo $this->subject->gg_contribution; ?> 
                        </h5>
                        <p class="desc text-dark">
                            <?php echo $this->translate('Contribution points'); ?>
                        </p>
                    </div>

                    <div class="bottom row pt-4">
                        <div class="col-sm col-5 col-xl-3 col-lg-3  text-center border-right">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_review_count;?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Reviews'); ?>
                            </p>
                        </div>
                        <!-- <div class="col-sm col-3 text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                5
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Guides'); ?>
                            </p>
                        </div> -->
                        <div class="col-sm col-5 col-xl-3 col-lg-3 text-center border-left">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_question_count; ?>
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
                    </div>

                    <div class="contributor slider pt-4">

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
          
        </div> <!-- left side -->

        <div class="col-sm-7 col-12 right-side">
                
            <div class="latest-reviews bg-white mb-4 widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Latest Reviews'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> <?php echo $this->translate('view all'); ?> </a>
                        </div>
                    </div>

                    <div class="bottom small text-muted pt-4">
                        <?php echo $this->translate('“Coming Soon”'); ?>
                    </div>

                </div>
            </div> <!-- end of latest-review -->

            <div class="latest-guides bg-white mb-4 widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Latest Guides'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> <?php echo $this->translate('view all'); ?> </a>
                        </div>
                    </div>

                    <div class="bottom small text-muted pt-4">
                        <?php echo $this->translate('“Coming Soon”'); ?>
                    </div>

                </div>
            </div> <!-- end of latest-guides -->

            <div class="latest-struggles bg-white widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Latest Struggles'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a class="text-primary" href="javascript:void(0)"><?php echo $this->translate('view all'); ?></a>
                        </div>
                    </div>

                    <div class="struggles_content">

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
        buttonFriendLink.addClass('btn btn-success px-sm-5 px-3 py-2 text-white');

        <?php if($this->profileSettings):?>
            var tabs = document.getElementsByClassName('tabs_alt'); 
            let liElements = tabs[1].getChildren('ul').getChildren()[0];
            for(var i = 0; i < liElements.length; i++) {
                let getParam = '<?php echo $this->profileSettings; ?>';
                let textContent = liElements[i].getChildren()[0].textContent;
            
                if( ((textContent == 'Personal Info') && ( getParam === 'general' )) || ((textContent == 'User Preferences') && ( getParam === 'preference' )) ){      
                    var tabToOpen = liElements[i].getChildren()[0];
                }
            }
            setTimeout(function () {
                tabToOpen.click();
                showEditContent();
            }, 300);
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

    // Latest Struggles Component Ajax Call
    en4.core.runonce.add(function () {
       loadQuestionResults();
    });

    function loadQuestionResults(){
        // Request data can be linked to form inputs
        var requestData = {};
        requestData.limit = 20;// Display limit for users
        requestData.page = 1;// Place for pagination

        // For user profile
        <?php if(Engine_Api::_()->core()->hasSubject("user")): ?>
           requestData.authorID = "<?php echo $this->subject()->getIdentity(); ?>";
        <?php endif; ?>

        var loader = en4.core.loader.clone();
        loader.addClass("sd_loader my-5");
        var url = en4.core.baseUrl+"api/v1/question";

        var request = new Request.JSON({
            url: url,
            method: 'get',
            data: requestData,
            onRequest: function(){ /*loader.inject($("sd-response"));*/ }, // When request is sent.
            onError: function(){ loader.destroy(); }, // When request throws an error.
            onCancel: function(){ loader.destroy(); }, // When request is cancelled.
            onSuccess: function(responseJSON){ // When request is succeeded.
                loader.destroy();

                var strugglesContent = document.querySelector('.struggles_content');

                if(responseJSON.status_code == 200){
                    var html = "";
                    var results = responseJSON.body.Results;
                    for(var i = 0; i < 5; i++) {
                        html += '<div class="struggle_holder my-3 d-flex flex-wrap">'+
                                    '<div class="struggle_box_left d-flex align-items-center large-12 medium-12 small-12">'+
                                        '<div class="struggle_left_side d-inline-block">'+
                                            '<div class="extfox-widgets" id="extfox-widgets">'+

                                            '</div>'+
                                            '<div class="item-photo-guidance position-relative">'+
                                                '<div class="statistic circle-badge position-absolute thumb_icon item_photo_user primary d-flex justify-content-center align-items-center text-white">'+
                                                    results[i].author.contributionLevel+
                                                '</div>'+
                                                '<img src="'+ results[i].author.avatarPhoto.photoURLIcon +'" alt="owner_thumb" class="thumb_icon item_photo_user primary" count="" gear=""/>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="struggle_right-side d-inline-block">'+
                                            '<a href="#" class="struggle_title">'+
                                                results[i].title+
                                            '</a>'+
                                            '<ul class="struggle_info d-flex">'+
                                                '<li class="struggle_time_created">'+
                                                results[i].createdDateTime+
                                                '</li>'+
                                                '<li>᛫</li>'+
                                                '<li class="struggle_owner_name">'+
                                                    '<a href="#">'+
                                                        results[i].author.displayName+
                                                    '</a>'+
                                                '</li>'+
                                                '<li class="list-inline edit-list-item">'+
                                                    '<a href="#" class="edit-item option-item display-flex">'+
                                                        '<svg aria-hidden="true" data-prefix="fas" data-icon="edit" role="img" width="12px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-edit fa-w-18 fa-9x"><path fill="#5CC7CE" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z" class=""></path></svg>'+
                                                        'Edit'+
                                                    '</a>'+
                                                '</li>'+
                                                '<li class="list-inline delete-list-item">'+
                                                    '<a href="#" class="delete-item smoothbox option-item display-flex">'+
                                                        '<svg aria-hidden="true" data-prefix="fal" data-icon="times-circle" role="img" width="12px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 464c-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216zm94.8-285.3L281.5 256l69.3 69.3c4.7 4.7 4.7 12.3 0 17l-8.5 8.5c-4.7 4.7-12.3 4.7-17 0L256 281.5l-69.3 69.3c-4.7 4.7-12.3 4.7-17 0l-8.5-8.5c-4.7-4.7-4.7-12.3 0-17l69.3-69.3-69.3-69.3c-4.7-4.7-4.7-12.3 0-17l8.5-8.5c4.7-4.7 12.3-4.7 17 0l69.3 69.3 69.3-69.3c4.7-4.7 12.3-4.7 17 0l8.5 8.5c4.6 4.7 4.6 12.3 0 17z"></path></svg>'+
                                                        'Delete'+
                                                    '</a>'+
                                                '</li>'+
                                            '</ul>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="struggle_box_right large-6 medium-6 small-6">'+
                                        '<ul class="struggle_count_info d-flex justify-content-around primary">'+
                                            '<li class="count_info d-flex align-items-center px-1" id="vote_count">'+
                                                '<svg class="d-lg-none" aria-hidden="true" data-prefix="fas" width="12px" style="margin-right:3px;" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#17becb" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z"></path></svg>'+
                                                '<p class="count_title title_active pr-1 d-none d-lg-inline">votes</p>'+
                                                results[i].upVoteCount+
                                            '</li>'+
                                            '<li class="count_info d-flex align-items-center px-1 answered_true" id="answer_count"'+
                                            'data-answered="'+ results[i].answerChosen +'">'+
                                                '<span class="answer_star"></span>'+
                                                '<p class="count_title title_active pr-1 d-none d-lg-inline">answers</p>'+
                                                results[i].answerCount+
                                            '</li>'+
                                            '<li class="count_info d-flex align-items-center px-1" id="comment_count">'+
                                                '<svg class="d-lg-none" aria-hidden="true" width="12px" style="margin-right:3px;" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#17becb" d="M224 358.857c-37.599 0-73.027-6.763-104.143-18.7-31.375 24.549-69.869 39.508-110.764 43.796a8.632 8.632 0 0 1-.89.047c-3.736 0-7.111-2.498-8.017-6.061-.98-3.961 2.088-6.399 5.126-9.305 15.017-14.439 33.222-25.79 40.342-74.297C17.015 266.886 0 232.622 0 195.429 0 105.16 100.297 32 224 32s224 73.159 224 163.429c-.001 90.332-100.297 163.428-224 163.428zm347.067 107.174c-13.944-13.127-30.849-23.446-37.46-67.543 68.808-64.568 52.171-156.935-37.674-207.065.031 1.334.066 2.667.066 4.006 0 122.493-129.583 216.394-284.252 211.222 38.121 30.961 93.989 50.492 156.252 50.492 34.914 0 67.811-6.148 96.704-17 29.134 22.317 64.878 35.916 102.853 39.814 3.786.395 7.363-1.973 8.27-5.467.911-3.601-1.938-5.817-4.759-8.459z" class=""></path></svg>'+
                                                '<p class="count_title title_active pr-1 d-none d-lg-inline">comments</p>'+
                                                results[i].commentsCount+
                                            '</li>'+
                                        '</ul>'+
                                    '</div>'+
                                '</div>';
                    }
                    strugglesContent.innerHTML = html;
                    console.log(responseJSON);
                    // Adding Styles to Answered Struggles
                    document.querySelectorAll('.answered_true').forEach(function(answered_true){
                        if(answered_true.dataset.answered == "true"){
                            answered_true.addClass('answered_question');
                            answered_true.querySelector('.answer_star').innerHTML = '<svg class="d-lg-none" xmlns="http://www.w3.org/2000/svg" style="margin-right:3px;" width="13px" height="13px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 38"><defs><linearGradient id="z" x1="-173.22" y1="1009.42" x2="-172.4" y2="1010.06" gradientTransform="matrix(13.72, 0, 0, -11.03, 2403.25, 11146.77)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5BC7CE"></stop><stop offset="1" stop-color="#5BC7CE"></stop></linearGradient><linearGradient id="x" x1="-304.1" y1="1050.64" x2="-303.1" y2="1050.64" gradientTransform="matrix(16.54, 0, 0, -10.11, 5029.61, 10635.32)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5BC7CE"></stop><stop offset="1" stop-color="#5BC7CE"></stop></linearGradient></defs><title>star_pg</title><path d="M38.29,8.11l-7.17,9.44L25,9.36,36.9,6.64C38.65,6.23,39.15,6.93,38.29,8.11Z" fill="url(#z)"></path><path d="M16.54,11.4.87,15.33c-1,.23-1.22,1.29-.3,1.7L15.86,21.5Z" fill="url(#x)"></path><path d="M14.56,36.53l2-35.61c0-1,1-1.25,1.68-.43L39.58,27.06c.79,1,.57,2.58-1.63,1.72L25.08,23.86,17,37.19a1.3,1.3,0,0,1-2.49-.66Z" fill="#5BC7CE"></path></svg>';
                        }else{
                           answered_true.querySelector('.answer_star').innerHTML = '<svg class="d-lg-none" viewBox="0 0 42 40" style="margin-right:3px;" width="13px" height="13px" preserveAspectRatio="xMidYMid meet" x="0" y="0" xmlns="http://www.w3.org/2000/svg"><path d="M32.52 18.024l8.06 10.036c.79 1 .57 2.58-1.63 1.72l-12.87-4.92L18 38.19a1.3 1.3 0 0 1-2.49-.66h.05l.852-15.161L1.57 18.03c-.92-.41-.7-1.47.3-1.7l15.093-3.785.597-10.625c0-1 1-1.25 1.68-.43l7.068 8.8L37.9 7.64c1.75-.41 2.25.29 1.39 1.47l-6.77 8.914z" fill-rule="nonzero" stroke="#5BC7CE" stroke-width="4" fill="none"></path></svg>'; 
                        }
                    });
                }else{
                    strugglesContent.innerHTML = responseJSON.message;
                }
            }
        });
        request.send();
    }

</script>
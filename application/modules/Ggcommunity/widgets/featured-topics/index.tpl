<?php
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Ggcommunity/externals/scripts/swiper.min.js');
    $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
        . 'application/modules/Ggcommunity/externals/styles/swiper.min.css');
?>

<!-- This Widget is Not Displayed on the Page until said otherwise -->
<div class="featured_widget row ">
    <div class="widget_holder">
        <div class="holder-title">
            <div class="widget_title">
                <p class="widget_name"><?php echo $this->title;?></p>
            </div>
        </div>
        <div class="swiper-container topic_holder">
            <div class="swiper-wrapper">
                <?php foreach($this->paginator as $item): ?>
                <div class="image_holder swiper-slide">
                    <div class="background_holder">
                        <h4 class="topic_name"><?php echo $item->getTitle(); ?></h4>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Navigation -->
            <div class="swiper-button-next swiper-narrow"></div>
            <div class="swiper-button-prev swiper-narrow"></div>

        </div>
        
    </div>
</div>



<script>
     var swiper = new Swiper('.swiper-container', {
      slidesPerView: 5,
      spaceBetween: 30,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        1024: {
          slidesPerView: 4,
          spaceBetween: 40,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
        640: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        320: {
          slidesPerView: 1,
          spaceBetween: 10,
        }
      }
    });
    
  </script>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$baseURL = $this->baseUrl();
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/jquery.min.js');
?>
<script type="text/javascript">
    if (typeof (window.jQuery) != 'undefined') {
        jQuery.noConflict();
    }
</script>

<?php
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/image_rotate.css');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/image_rotate.js');
?>

<script type="text/javascript">
    window.addEvent('domready', function () {
        durationOfRotateImage = <?php echo!empty($this->defaultDuration) ? $this->defaultDuration : 500; ?>;
        image_rotate();

    });
    <?php if (Engine_Api::_()->seaocore()->getCurrentActivateTheme()): ?>   
        if (($$('.layout_siteusercoverphoto_user_cover_photo').length > 0) || ($$('.layout_sitecontentcoverphoto_content_cover_photo').length > 0) || ($$('.layout_captivate_banner_images').length > 0)) {
            $('global_content').setStyles({
                'width': '100%',
                'margin-top': '-16px'
            });
        }
   
    setTimeout(function () {
        if ((($$('.layout_captivate_navigation div').getChildren().length < 3) && (($$('.layout_siteusercoverphoto_user_cover_photo').length > 0) || ($$('.layout_sitecontentcoverphoto_content_cover_photo').length > 0) || ($$('.layout_captivate_banner_images').length > 0)))) {
            if (document.getElementsByTagName("BODY")[0]) {
                if($$('.layout_sitemenu_menu_main').length < 1)
                document.getElementsByTagName("BODY")[0].addClass('captivate_transparent_header');
            }

            window.addEvent('scroll', function () {
                if ($$(".layout_page_header").length > 0)
                {
                    var scrollTop = document.body.scrollTop ? document.body.scrollTop : document.documentElement.scrollTop; 


                    if (scrollTop > 50) {
                        $$(".layout_page_header").addClass("captivate_fix_header");
                    } else {
                        $$(".layout_page_header").removeClass("captivate_fix_header");
                    }
                }
            });
        }
    }, 100);

     <?php endif;?>
</script>

<style type="text/css">
    #slide-images{
        width: <?php echo!empty($this->slideWidth) ? $this->slideWidth . 'px;' : '100%'; ?>;
        height: <?php echo $this->slideHeight . 'px !important'; ?>;
    }
    .slideblok_image img{
        height: <?php echo $this->slideHeight . 'px !important'; ?>;
    }

    .layout_captivate_banner_images .bannerimage-text {
        height: <?php echo $this->slideHeight . 'px !important'; ?>;
    }
</style>

<div id="slide-images" class="slideblock">
    <?php
    foreach ($this->list as $imagePath):
        if (!is_array($imagePath)):
            $iconSrc = "application/modules/Captivate/externals/images/" . $imagePath;
        else:
            $iconSrc = Engine_Api::_()->captivate()->displayPhoto($imagePath['file_id'], 'thumb.icon');
        endif;
        if (!empty($iconSrc)):
            ?>
            <div class="slideblok_image">
                <img src="<?php echo $iconSrc; ?>" />
            </div>
            <?php
        endif;
    endforeach;
    ?>
    <section class="bannerimage-text">
        <div>
            <?php if ($this->captivateHtmlTitle): ?>
                <h1><?php echo $this->translate($this->captivateHtmlTitle); ?></h1>
            <?php endif; ?>
            <?php if ($this->captivateHtmlDescription): ?>
                <article><?php echo $this->translate($this->captivateHtmlDescription); ?></article>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php if (Engine_Api::_()->seaocore()->getCurrentActivateTheme()): ?>    
    <style type="text/css">
        .layout_main {
            width:1200px;
            margin: 0 auto;
        }
    </style>
<?php endif; ?>
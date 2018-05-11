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
$showFooterBackgroundImage = "'" . $this->showFooterBackgroundImage . "'";
?>

<?php
$tempFlag = $flag = 0;
$footerContent = $footertempCatContent = '';
$i = 1;?>
<?php if ($this->showCaptivateFooterTemplate != 3): ?>
<?php if ($this->showFooterLogo) {
    if ($this->selectFooterLogo) {
        echo '<div class="captivate_footer_logo"><a href="' . $this->url(array('action' => 'home'), "user_general", true) . '"><img src="' . $this->selectFooterLogo . '"></img></a></div>';
    } else {
        $title = Engine_Api::_()->getApi('settings', 'core')->getSetting('core_general_site_title', $this->translate('_SITE_TITLE'));
        $route = $this->viewer()->getIdentity() ? array('route' => 'user_general', 'action' => 'home') : array('route' => 'default');
        echo '<div class="captivate_footer_logo">' . $this->htmlLink($route, $title) . '</div>';
    }
}
?>
<?php endif;?>
<?php if ($this->showCaptivateFooterTemplate == 2): ?>
    <?php
    if ($this->captivatefooterLendingBlockValue):
        echo '<div class="captivate_footer_desc">' . $this->captivatefooterLendingBlockValue . '</div>';
    else:
        ?>
        <?php
        echo '<div class="captivate_footer_desc"><p>Explore &amp; Watch videos that you have always dreamed of, and post &amp; share your videos to connect with own community.</p></div>';
    endif;
    ?>
    <?php
endif;
?>

<?php if ($this->showCaptivateFooterTemplate != 3): ?>
<?php
foreach ($this->navigation as $navigation) :
    if ($navigation->uri == 'javascript:void(0)') :
        if (!empty($tempFlag)) :
            $footerContent .= '</ul></div>';
        endif;
        $tempFlag = 1;

        if (!empty($navigation->icon)) :
            $footertempCatContent = '<div class="captivate_footer_block" id="captivate_footer_block_' . $i . '" style="background-image:url(\'' . $navigation->icon . '\'); background-repeat:no-repeat;"><ul>';
        else:
            $footertempCatContent = '<div class="captivate_footer_block" id="captivate_footer_block_' . $i . '"><ul><li class="captivate_footer_block_head">' . $this->translate($navigation->getLabel()) . '</li>';
        endif;
        $i++;
    else:
        if (!empty($footertempCatContent)) :
            $footerContent .= $footertempCatContent;
            $footertempCatContent = '';
        endif;
        if (!empty($navigation->icon)) :
            $tempContent = '<img src="' . $navigation->icon . '" title="' . $this->translate($navigation->getLabel()) . '" />' . ' ' . $this->translate($navigation->getLabel());
        else:
            $tempContent = $this->translate($navigation->getLabel());
        endif;

        if (isset($navigation->target)) {
            $footerContent .= '<li><a href="' . $navigation->getHref() . '" target="' . $navigation->target . '">' . $tempContent . '</a></li>';
        } else {
            $footerContent .= '<li><a href="' . $navigation->getHref() . '">' . $tempContent . '</a></li>';
        }
    endif;
//  endif;
endforeach;

if (!empty($tempFlag)) :
  $footerContent .= '</ul></div>';
endif;

if (!empty($footerContent)) :
    echo '<div class="footerlinks">' . $footerContent . '</div>';

endif;
endif;
?>
    
<ul class="socialshare_links">
	<?php if ($this->showCaptivateFooterTemplate == 2): ?>
		<li class="captivate_footer_block_head"><?php echo $this->translate("Follow Us") ?></li>
  <?php endif; ?>
    <li>
        <?php if (!empty($this->facebook_url)) : ?>
            <a target="_blank" href="<?php echo $this->facebook_url ?>">
                <?php if (!empty($this->facebook_default_icon)) : ?>
                    <img onmouseover="this.src = '<?php
                    if (!empty($this->facebook_hover_icon)) : echo $this->facebook_hover_icon;
                    endif;
                    ?>';" onmouseout="this.src = '<?php echo $this->facebook_default_icon; ?>';" src="<?php echo $this->facebook_default_icon ?>" title="<?php echo $this->facebook_title ?>" height="32px" width="32px" />
                <?php else: ?>
                    <span class="captivate_sociallinks"><?php echo $this->facebook_title ?></span>
            <?php endif; ?>
            </a>
        <?php endif; ?>

            <?php if (!empty($this->twitter_url)) : ?>
            <a target="_blank" href="<?php echo $this->twitter_url ?>">
                <?php if (!empty($this->twitter_default_icon)) : ?>
                    <img onmouseover="this.src = '<?php
                         if (!empty($this->twitter_hover_icon)) : echo $this->twitter_hover_icon;
                         endif;
                         ?>';" onmouseout="this.src = '<?php echo $this->twitter_default_icon; ?>';" src="<?php echo $this->twitter_default_icon ?>" title="<?php echo $this->twitter_title ?>" height="32px" width="32px" />
                <?php else: ?>
                    <span class="captivate_sociallinks"><?php echo $this->twitter_title ?></span>
            <?php endif; ?>
            </a>
        <?php endif; ?>

            <?php if (!empty($this->linkedin_url)) : ?>
            <a target="_blank" href="<?php echo $this->linkedin_url ?>">
                <?php if (!empty($this->linkedin_default_icon)) : ?>
                    <img onmouseover="this.src = '<?php
                         if (!empty($this->linkedin_hover_icon)) : echo $this->linkedin_hover_icon;
                         endif;
                         ?>';" onmouseout="this.src = '<?php echo $this->linkedin_default_icon; ?>';" src="<?php echo $this->linkedin_default_icon ?>" title="<?php echo $this->linkedin_title ?>" height="32px" width="32px" />
            <?php else: ?>
                    <span class="captivate_sociallinks"><?php echo $this->linkedin_title ?></span>
            <?php endif; ?>
            </a>
            <?php endif; ?>

            <?php if (!empty($this->youtube_url)) : ?>
            <a target="_blank" href="<?php echo $this->youtube_url ?>">
                     <?php if (!empty($this->youtube_default_icon)) : ?>
                    <img onmouseover="this.src = '<?php
         if (!empty($this->youtube_hover_icon)) : echo $this->youtube_hover_icon;
         endif;
         ?>';" onmouseout="this.src = '<?php echo $this->youtube_default_icon; ?>';" src="<?php echo $this->youtube_default_icon ?>" title="<?php echo $this->youtube_title ?>" height="32px" width="32px" />
            <?php else: ?>
                    <span class="captivate_sociallinks"><?php echo $this->youtube_title ?></span>
            <?php endif; ?>
            </a>
            <?php endif; ?>

            <?php if (!empty($this->pinterest_url)) : ?>
            <a target="_blank" href="<?php echo $this->pinterest_url ?>">
                     <?php if (!empty($this->pinterest_default_icon)) : ?>
                    <img onmouseover="this.src = '<?php
                    if (!empty($this->pinterest_hover_icon)) : echo $this->pinterest_hover_icon;
                    endif;
                    ?>';" onmouseout="this.src = '<?php echo $this->pinterest_default_icon; ?>';" src="<?php echo $this->pinterest_default_icon ?>" title="<?php echo $this->pinterest_title ?>" height="32px" width="32px" />
    <?php else: ?>
                    <span class="captivate_sociallinks"><?php echo $this->pinterest_title ?></span>
    <?php endif; ?>
            </a>
<?php endif; ?>

    </li>
</ul>

<script type="text/javascript">
    $$(".layout_page_footer").removeClass("captivate_foot_template_1");
    $$(".layout_page_footer").removeClass("captivate_foot_template_2");
<?php if ($this->showCaptivateFooterTemplate == 2): ?>
        $$(".layout_page_footer").addClass("captivate_foot_template_1");
<?php elseif ($this->showCaptivateFooterTemplate == 3): ?>
        $$(".layout_page_footer").addClass("captivate_foot_template_2");
<?php endif; ?>

<?php if ($this->selectFooterBackground == 2): ?>
        $$(".layout_page_footer").setStyle('background-image', 'url("' +<?php echo $showFooterBackgroundImage; ?> + '")');
<?php endif; ?>


<?php if ($this->selectFooterLogo): ?>
        $$(".layout_page_footer").addClass("captivate_footer_with_logo");
<?php endif; ?>


</script>
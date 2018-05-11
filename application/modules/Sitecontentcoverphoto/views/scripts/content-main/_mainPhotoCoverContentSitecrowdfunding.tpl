<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecontentcoverphoto
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: get-main-photo.tpl 6590 2013-06-03 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
$dashboardurl = $view->url(array('action' => 'edit', 'project_id' => $this->subject()->project_id), 'sitecrowdfunding_specific', true);
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitecrowdfunding/externals/scripts/core.js'); ?>
<div class="seaocore_profile_cover_head_section_inner sitecrowdfunding_contentcover_wrapper" id="seaocore_profile_cover_head_section_inner">
    <div class="seaocore_profile_coverinfo_status">
        <?php if (!empty($this->showContent) && in_array('title', $this->showContent)): ?>
            <?php if (empty($this->cover_photo_preview)): ?>
                <h2 title="<?php echo $this->subject()->getTitle(); ?>"><?php echo Engine_Api::_()->seaocore()->seaocoreTruncateText($this->subject()->getTitle(), 100); ?></h2>
            <?php else: ?>
                <?php $getShortType = ucfirst($this->subject()->getShortType()); ?>
                <h2><?php echo $this->translate("%s Title", $getShortType); ?></h2>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($this->showContent) && in_array('description', $this->showContent)): ?>
            <div class="mtop10 sitecrowdfunding_des" title="<?php echo $this->subject->description; ?>">
                <?php
                echo Engine_Api::_()->seaocore()->seaocoreTruncateText($this->subject->description, 50);
                ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($this->showContent) && in_array('owner', $this->showContent)): ?>
            <div class="sitecrowdfunding_listings_stats">
                <div class="o_hidden f_small">
                    <?php if (!empty($this->cover_photo_preview)): ?>
                        <div class="mtop10 sitecrowdfunding_created_by"><?php echo $this->translate("Owner Name"); ?></div>
                    <?php else: ?>
                        <div class="mtop10 sitecrowdfunding_created_by"><?php echo $this->translate('Created By %1$s', $this->subject->getOwner()->__toString()); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="sitecrowdfunding_profile_cover_otherinfo">
            <?php if (is_array($this->showContent) && in_array('category', $this->showContent) && $this->subject()->category_id): ?> 
                <div class="sitecrowdfunding_listings_stats">
                    <div class="o_hidden f_small sitecrowdfunding_category"> 
                        <?php $category = Engine_Api::_()->getItem('sitecrowdfunding_category', $this->subject->category_id); ?>
                        <?php if ($category->file_id): ?>
                            <?php $url = Engine_Api::_()->storage()->get($category->file_id)->getPhotoUrl(); ?>
                        <?php else: ?>
                            <?php $url = $this->layout()->staticBaseUrl . "application/modules/Sitecrowdfunding/externals/images/category_images/icons/noicon_category.png" ?>
                        <?php endif; ?>
                        <img src="<?php echo $url ?>" style="width: 16px; height: 16px;" alt="<?php echo $category->getTitle(); ?>">
                        <?php echo $this->htmlLink($category->getHref(), $category->getTitle()) ?>
                    </div>
                </div> 
            <?php endif; ?>
            <?php if (is_array($this->showContent) && in_array('location', $this->showContent) && $this->subject()->location): ?> 
                <div class="sitecrowdfunding_listings_stats">

                    <div class="o_hidden f_small sitecrowdfunding_location" title="<?php
                    if (!empty($this->sitecontentcoverphotoChangeTabPosition)) : echo $this->translate("Location");
                    endif;
                    ?>"> 
                        <i class="seao_icon_location" title="<?php echo $this->translate("Location") ?>"></i><span><?php echo $this->subject()->location;?></span>
                    </div>
                </div> 
            <?php endif; ?>
        </div>
        <div class="seaocore_profile_coverinfo_graph">
            <?php
            $fundedAmount = $this->subject()->getFundedAmount();
            $goalAmount = $this->subject()->goal_amount;
            $fundedRatio = $this->subject()->getFundedRatio();
            $fundedAmount = Engine_Api::_()->sitecrowdfunding()->getPriceWithCurrency($fundedAmount);
            $goalAmount = Engine_Api::_()->sitecrowdfunding()->getPriceWithCurrency($goalAmount);
            ?>
            <?php if (!empty($this->showContent) && in_array('fundingRatio', $this->showContent)): ?>
                <div class="sitecrowdfunding_listings_stats">
                    <div class="o_hidden f_small">
                        <div class="chart" id="fund-ratio-graph" data-percent="<?php echo $fundedRatio ?>"></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->showContent) && in_array('fundedAmount', $this->showContent)): ?>
                <div class="sitecrowdfunding_listings_stats">
                    <div class="o_hidden f_small">
                        <div class="mtop5  sitecrowdfunding_Pledged">
                            <?php echo $this->translate("$fundedAmount / $goalAmount <br> </br> Backed"); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->showContent) && in_array('daysLeft', $this->showContent)): ?>
                <div class="sitecrowdfunding_listings_stats">
                    <div class="o_hidden f_small">
                        <div class="mtop5 sitecrowdfunding_days_left">
                            <?php $days = Engine_Api::_()->sitecrowdfunding()->findDays($this->subject()->expiration_date); ?>
                            <?php $daysToStart = Engine_Api::_()->sitecrowdfunding()->findDays($this->subject()->start_date); ?>
                            <?php
                            $currentDate = date('Y-m-d');
                            $projectStartDate = date('Y-m-d', strtotime($this->subject()->start_date));
                            ?> 
                            <?php if ($this->subject()->state == 'successfull') : ?>
                                <?php echo $this->translate("Funding <br>Successfull"); ?>
                            <?php elseif ($this->subject()->state == 'failed') : ?>
                                <?php echo $this->translate("Funding <br>Failed"); ?>
                            <?php elseif ($this->subject()->state == 'draft') : ?>
                                <?php echo $this->translate("In <br>Draft mode"); ?>
                            <?php elseif (strtotime($currentDate) < strtotime($projectStartDate)): ?>
                                <?php echo $daysToStart; ?>
                                <br /> <br /> 
                                <?php echo $this->translate(array('%s Day to Live', '%s Days to Live', $daysToStart), ''); ?>
                            <?php elseif ($this->subject()->lifetime): ?>
                                <?php echo $this->translate('Life Time'); ?>
                            <?php elseif ($days >= 1): ?>
                                <?php echo $days; ?>
                                <br /> <br /> 
                                <?php echo $this->translate(array('%s Day Left', '%s Days Left', $days), ''); ?>
                            <?php else: ?> 
                                <?php echo $this->translate($this->subject()->getProjectStatus()); ?>
                            <?php endif; ?> 
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->showContent) && in_array('backerCount', $this->showContent)): ?>
                <div class="sitecrowdfunding_listings_stats">
                    <div class="o_hidden f_small">
                        <div class="mtop5 sitecrowdfunding_backers">
                            <?php $backerCount = $this->subject()->backer_count; ?>
                            <?php echo $this->translate(array('%s <br> </br> Backer', '%s <br> </br> Backers', $backerCount), $backerCount); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (($this->profile_like_button == 1) || (in_array('optionsButton', $this->showContent))): ?>
        <?php if (!$this->contentFullWidth): ?>
            <div class="seaocore_profile_coverinfo_buttons">

                <?php if ($this->profile_like_button == 1) : ?>
                    <div>
                        <?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitelike')): ?>
                            <?php echo $this->content()->renderWidget("sitelike.commoncover-like-button"); ?>
                        <?php else: ?>
                            <?php echo $this->content()->renderWidget("seaocore.like-button"); ?>
                        <?php endif; ?>
                    </div>  
                <?php endif; ?>
                <?php if (is_array($this->showContent) && in_array('backButton', $this->showContent)): ?>  
                    <?php if($this->subject()->showBackProjectLink()):?>
                        <div class="seaocore_button">
                            <a href="<?php echo $this->url(array('action'=>'reward-selection','project_id'=>$this->subject()->project_id), 'sitecrowdfunding_backer', true); ?>">
                                    <span><?php echo 'Back Now'; ?></span>
                            </a>  
                        </div> 
                    <?php endif; ?>     
                <?php endif; ?>
                <?php
                if (is_array($this->showContent) && in_array('shareOptions', $this->showContent)) {
                    $this->subject = $this->subject();
                    include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_shareCoverPhotoButtons.tpl';
                }
                ?> 

                <?php if (is_array($this->showContent) && in_array('dashboardButton', $this->showContent) && $this->can_edit): ?>
                    <div class="seaocore_button">
                        <a href="<?php echo $dashboardurl;?>">
                            <span><?php echo 'Dashboard'; ?></span>
                        </a> 
                    </div>
                <?php endif; ?>

                <?php if (!empty($this->showContent) && in_array('optionsButton', $this->showContent)): ?>
                    <?php $this->navigationProfile = $coreMenus->getNavigation("sitecrowdfunding_project_profile");
                    ?>
                    <?php if (count($this->navigationProfile) > 0): ?>
                        <div class="seaocore_button seaocore_profile_option_btn prelative">
                            <a href="javascript:void(0);" onclick="showPulDownOptions();"><i class="icon_cog"></i><i class="icon_down"></i></a>
                            <ul class="seaocore_profile_options_pulldown" id="sitecontent_cover_settings_options_pulldown" style="display:none;right:0;">
                                <li>
                                    <?php echo $this->navigation()->menu()->setContainer($this->navigationProfile)->setPartial(array('_navIcons.tpl', 'sitecrowdfunding'))->render(); ?>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="seaocore_profile_coverinfo_buttons">
                <?php if ($this->profile_like_button == 1) : ?>
                    <div>
                        <?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitelike')): ?>
                            <?php echo $this->content()->renderWidget("sitelike.commoncover-like-button"); ?>
                        <?php else: ?>
                            <?php echo $this->content()->renderWidget("seaocore.like-button"); ?>
                        <?php endif; ?>
                    </div>  
                <?php endif; ?>

                <?php if (is_array($this->showContent) && in_array('backButton', $this->showContent)): ?>
                    <?php if($this->subject()->showBackProjectLink()):?>
                        <div class="seaocore_button">
                            <a href="<?php echo $this->url(array('action'=>'reward-selection','project_id'=>$this->subject()->project_id), 'sitecrowdfunding_backer', true); ?>">
                                    <span><?php echo 'Back Now'; ?></span>
                            </a>  
                        </div> 
                    <?php endif; ?>       
                <?php endif; ?>

                <?php
                if (is_array($this->showContent) && in_array('shareOptions', $this->showContent)) {
                    $this->subject = $this->subject();
                    include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_shareCoverPhotoButtons.tpl';
                }
                ?>  
                <?php if (is_array($this->showContent) && in_array('dashboardButton', $this->showContent) && $this->can_edit): ?>
                    <div class="seaocore_button">
                        <a href="<?php echo $dashboardurl;?>">
                            <span><?php echo 'Dashboard'; ?></span>
                         </a> 
                    </div>
                <?php endif; ?>

                <?php if (!empty($this->showContent) && in_array('optionsButton', $this->showContent)): ?>
                    <?php $this->navigationProfile = $coreMenus->getNavigation("sitecrowdfunding_project_profile");
                    ?>
                    <?php if (count($this->navigationProfile) > 0): ?>
                        <div class="seaocore_button seaocore_profile_option_btn prelative">
                            <a href="javascript:void(0);" onclick="showPulDownOptions();"><i class="icon_cog"></i><i class="icon_down"></i></a>
                            <ul class="seaocore_profile_options_pulldown" id="sitecontent_cover_settings_options_pulldown" style="display:none;right:0;">
                                <li>
                                    <?php echo $this->navigation()->menu()->setContainer($this->navigationProfile)->setPartial(array('_navIcons.tpl', 'sitecrowdfunding'))->render(); ?>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php $fbmodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('facebookse'); ?>
    <?php if ($fbmodule && !empty($fbmodule->enabled) && ($this->profile_like_button == 2)) : ?>
        <div class="seaocore_profile_cover_fb_like_button"> 
            <?php echo $this->content()->renderWidget("Facebookse.facebookse-commonlike", array('subject' => $this->subject()->getGuid())); ?>
        </div>	
    <?php endif; ?>
</div>

<style>
    .seaocore_profile_coverinfo_status,.seaocore_profile_coverinfo_status h2, .seaocore_profile_coverinfo_status a, .seaocore_profile_coverinfo_status div, .seaocore_profile_coverinfo_statistics, .seaocore_profile_coverinfo_statistics div{
        color:<?php echo $this->fontcolor; ?> !important;
    }
</style>

<script>
    var circleFillColor = '<?php echo Engine_Api::_()->getApi("settings", "core")->getSetting("sitecrowdfunding_fundedcirclecolor","#ffffff"); ?>';
    window.addEvent('domready', function () {

        setTimeout(function () {
            circularFundingBar();
        }, 500);
    })

    function circularFundingBar() {

        var el = $('fund-ratio-graph'); // get canvas
        if(el) {
            var options = {
                percent: el.getAttribute('data-percent') || 25,
                size: el.getAttribute('data-size') || 100,
                lineWidth: el.getAttribute('data-line') || 18,
                rotate: el.getAttribute('data-rotate') || 0
            }

            var canvas = document.createElement('canvas');
            var span = document.createElement('span');
            span.textContent = options.percent + '%';
            if (options.percent == 0)
                options.percent = 0.1;
            if (typeof (G_vmlCanvasManager) !== 'undefined') {
                G_vmlCanvasManager.initElement(canvas);
            }

            var ctx = canvas.getContext('2d');
            canvas.width = canvas.height = options.size;

            el.appendChild(span);
            el.appendChild(canvas);

            ctx.translate(options.size / 2, options.size / 2); // change center
            ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI); // rotate -90 deg

            //imd = ctx.getImageData(0, 0, 240, 240);

            var drawCircle = function (color, lineWidth, percent, size) {

                var radius = (size - lineWidth) / 2;
                percent = Math.min(Math.max(0, percent || 1), 1);
                ctx.beginPath();
                ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
                ctx.strokeStyle = color;
                ctx.lineCap = ''; // butt, round or square
                ctx.lineWidth = lineWidth
                ctx.stroke();
            };
            var fontcolor = '<?php echo $this->fontcolor; ?>';
            drawCircle(circleFillColor, 1, 100 / 100, options.size - 2 * options.lineWidth + 1);
            drawCircle('rgba(255,0,0,0)', options.lineWidth, 100 / 100, options.size);
            drawCircle(circleFillColor, options.lineWidth, options.percent / 100, options.size);
            drawCircle(circleFillColor, 1, 100 / 100, options.size);
        } 
    }
</script>
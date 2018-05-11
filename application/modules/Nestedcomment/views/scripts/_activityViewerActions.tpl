<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedactivity
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _activityViewerActions.tpl 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
  include APPLICATION_PATH . '/application/modules/Nestedcomment/views/scripts/_activitySettings.tpl';
  $commentReverseOrder = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.commentreverseorder', false);
?>
<script type="text/javascript">
    var CommentLikesTooltips;
    var ComposerNestedActivityComment;
    var hideItemFeeds;
    var unhideItemFeed;
    var moreEditOptionsSwitch;
    var unhideReqActive = false;
    var el_siteevent;
    var photoEnabled = '<?php echo $photoEnabled ?>';
    var smiliesEnabled = '<?php echo $smiliesEnabled ?>';
    var activityTaggingContent = '<?php echo $activityTaggingContent;?>';
    var allowQuickComment = '<?php echo ($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? 0 : 1; ?>';
    var allowQuickReply = '<?php echo ($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? 0 : 1; ?>';
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitealbum') && Engine_Api::_()->nestedcomment()->checkVersion(Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitealbum')->version, '4.8.4')): ?>
    var requestOptionsURLNestedComment = en4.core.baseUrl + 'sitealbum/album/compose-upload/type/comment';
    var fancyUploadOptionsURLNestedComment = en4.core.baseUrl + 'sitealbum/album/compose-upload/format/json/type/comment';
    <?php else: ?>
    var requestOptionsURLNestedComment = en4.core.baseUrl + 'nestedcomment/album/compose-upload/type/comment';
    var fancyUploadOptionsURLNestedComment = en4.core.baseUrl + 'nestedcomment/album/compose-upload/format/json/type/comment';
    <?php endif; ?>
    en4.core.runonce.add(function () {
        $('activity-feed').getElements('.seao_icons_toolbar_attach').each(function (el) {
            if (($('activity-feed').getCoordinates().right * 0.65) < el.getCoordinates().left) {
                el.addClass('seao_icons_toolbar_right');
            } else {
                el.removeClass('seao_icons_toolbar_right');
            }
        });
        en4.core.language.addData({
            "Stories from %s are hidden now and will not appear in your Activity Feed anymore.": "<?php echo $this->string()->escapeJavascript($this->translate("Stories from %s are hidden now and will not appear in your Activity Feed anymore.")); ?>"
        });
        // Add hover event to get likes
        $$('.comments_comment_likes').addEvent('mouseover', function (event) {
            var el = $(event.target);
            if (!el.retrieve('tip-loaded', false)) {
                el.store('tip-loaded', true);
                el.store('tip:title', '<?php echo $this->string()->escapeJavascript($this->translate('Loading...')) ?>');
                el.store('tip:text', '');
                var id = el.get('id').match(/\d+/)[0];
                // Load the likes
                var url = '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'get-likes'), 'default', true) ?>';
                var req = new Request.JSON({
                    url: url,
                    data: {
                        format: 'json',
                        //type : 'core_comment',
                        action_id: el.getParent('li').getParent('li').getParent('li').get('id').match(/\d+/)[0],
                        comment_id: id
                    },
                    onComplete: function (responseJSON) {
                        el.store('tip:title', responseJSON.body);
                        el.store('tip:text', '');
                        CommentLikesTooltips.elementEnter(event, el); // Force it to update the text

                    }
                });
                req.send();
            }
        });
        // Add tooltips
        CommentLikesTooltips = new Tips($$('.comments_comment_likes'), {
            fixed: true,
            className: 'comments_comment_likes_tips',
            offset: {
                'x': 20,
                'y': 10
            }
        });
        // Enable links in comments
        $$('.comments_body').enableLinks();
        $$('.feed_item_body_content').enableLinks();
        if (feedToolTipAAFEnable) {
            // Add hover event to get tool-tip
            var show_tool_tip = false;
            var counter_req_pendding = 0;
            $$('.sea_add_tooltip_link').addEvent('mouseover', function (event) {
                var el = $(event.target);
                el_siteevent = el;
                ItemTooltips.options.offset.y = el.offsetHeight;
                ItemTooltips.options.showDelay = 0;
                if (!el.get('rel')) {
                    el = el.parentNode;
                }
                if (el && !el.retrieve('tip-loaded', false)) {
                    el.store('tip:title', '');
                    el.store('tip:text', '');
                }
                if (el.getParent('.layout_advancedactivitypost_feed_short'))
                    return;
                show_tool_tip = true;
                if (!el.retrieve('tip-loaded', false)) {
                    counter_req_pendding++;
                    var resource = '';
                    if (el.get('rel'))
                        resource = el.get('rel');
                    if (resource == '')
                        return;

                    el.store('tip-loaded', true);
                    el.store('tip:title', '<div class="" style="">' +
                            ' <div class="uiOverlay info_tip" style="width: 300px; top: 0px; ">' +
                            '<div class="info_tip_content_wrapper" ><div class="info_tip_content"><div class="info_tip_content_loader">' +
                            '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif" alt="Loading" /><?php echo $this->translate("Loading ...") ?></div>' +
                            '</div></div></div></div>'
                            );
                    el.store('tip:text', '');
                    // Load the likes
                    var url = '<?php echo $this->url(array('module' => 'seaocore', 'controller' => 'feed', 'action' => 'show-tooltip-info'), 'default', true) ?>';
                    el.addEvent('mouseleave', function () {
                        show_tool_tip = false;
                    });

                    var req = new Request.HTML({
                        url: url,
                        data: {
                            format: 'html',
                            'resource': resource
                        },
                        evalScripts: true,
                        onSuccess: function (responseTree, responseElements, responseHTML, responseJavaScript) {
                            el.store('tip:title', '');
                            el.store('tip:text', responseHTML);
                            ItemTooltips.options.showDelay = 0;
                            ItemTooltips.elementEnter(event, el); // Force it to update the text 
                            counter_req_pendding--;
                            if (!show_tool_tip || counter_req_pendding > 0) {
                                //ItemTooltips.hide(el);
                                ItemTooltips.elementLeave(event, el);
                            }
                            var tipEl = ItemTooltips.toElement();
                            tipEl.addEvents({
                                'mouseenter': function () {
                                    ItemTooltips.options.canHide = false;
                                    ItemTooltips.show(el);
                                },
                                'mouseleave': function () {
                                    ItemTooltips.options.canHide = true;
                                    ItemTooltips.hide(el);
                                }
                            });
                            Smoothbox.bind($$(".sea_add_tooltip_link_tips"));

                        }
                    });
                    req.send();
                }
            });
            // Add tooltips
            var window_size = window.getSize()
            var ItemTooltips = new SEATips($$('.sea_add_tooltip_link'), {
                fixed: true,
                title: '',
                className: 'sea_add_tooltip_link_tips',
                hideDelay: 200,
                offset: {'x': 0, 'y': 0},
                windowPadding: {'x': 370, 'y': (window_size.y / 2)}
            }
            );
        }
    <?php if ($this->viewer()->getIdentity()): ?>
        hideItemFeeds = function (type, id, parent_type, parent_id, parent_html, report_url) {
            if (en4.core.request.isRequestActive())
                return;
            var url = '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'feed', 'action' => 'hide-item'), 'default', true); ?>';
            var req = new Request.JSON({
                url: url,
                data: {
                    format: 'json',
                    type: type,
                    id: id
                },
                onComplete: function (responseJSON) {

                    if (type == 'activity_action' && $('activity-item-' + id)) {

                        if ($('activity-item-undo-' + id))
                            $('activity-item-undo-' + id).destroy();
                        $('activity-item-' + id).style.display = 'none';
                        var innerHTML = "<li id='activity-item-undo-" + id + "'><div class='feed_item_hide'>"
                                + "<b><?php echo $this->string()->escapeJavascript($this->translate("This story is now hidden from your Activity Feed.")) ?></b>" + " <a href='javascript:void(0);' onclick='unhideItemFeed(\"" + type + "\" , \"" + id + "\")'>" + "<?php echo $this->string()->escapeJavascript($this->translate("Undo")) ?> </a> <br /> ";
                        if (report_url == '') {
                            innerHTML = innerHTML + "<span> <a href='javascript:void(0);' onclick='hideItemFeeds(\"" + parent_type + "\" , \"" + parent_id + "\",\"\",\"" + id + "\", \"" + parent_html + "\",\"\")'>"
                                    + '<?php
  echo $this->string()->escapeJavascript($this->translate('Hide all by '));
  ?>' + parent_html + "</a></span>";
                        } else {
                            innerHTML = innerHTML + "<span> <?php echo $this->string()->escapeJavascript($this->translate("To mark it offensive, please ")) ?> <a href='javascript:void(0);' onclick='Smoothbox.open(\"" + report_url + "\")'>" + "<?php echo $this->string()->escapeJavascript($this->translate("file a report")) ?>" + "</a>" + "<?php echo '.' ?>" + "</span>";
                        }

                        innerHTML = innerHTML + "</div></li>";
                        Elements.from(innerHTML).inject($('activity-item-' + id), 'after');

                    } else {
                        if ($('activity-item-undo-' + parent_id))
                            $('activity-item-undo-' + parent_id).destroy();
                        var innerHTML = "<li id='activity-item-undo-" + id + "'><b>" + en4.core.language.translate("Stories from %s are hidden now and will not appear in your Activity Feed anymore.", parent_html) + "</b> <a href='javascript:void(0);' onclick='unhideItemFeed(\"" + type + "\" , \"" + id + "\")'>" + "<?php echo $this->string()->escapeJavascript($this->translate("Undo")) ?> </a>" + "</li>";
                        Elements.from(innerHTML).inject($('activity-item-' + parent_id), 'after');

                        var className = '.Hide_' + type + '_' + id;
                        var myElements = $$(className);
                        for (var i = 0; i < myElements.length; i++) {
                            myElements[i].style.display = 'none';
                        }
                    }
                }
            });
            req.send();
        }

        unhideItemFeed = function (type, id) {
            if (unhideReqActive)
                return;
            unhideReqActive = true;
            var url = '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'feed', 'action' => 'un-hide-item'), 'default', true); ?>';
            var req = new Request.JSON({
                url: url,
                data: {
                    format: 'json',
                    type: type,
                    id: id
                },
                onComplete: function (responseJSON) {
                    if ($('activity-item-undo-' + id))
                        $('activity-item-undo-' + id).destroy();
                    if (type == 'activity_action' && $('activity-item-' + id)) {

                        $('activity-item-' + id).style.display = '';
                        //document.getElementById('activity-feed').removeChild($('activity-item-undo-'+id));
                    } else {
                        var className = '.Hide_' + type + '_' + id;
                        var myElements = $$(className);
                        for (var i = 0; i < myElements.length; i++) {
                            myElements[i].style.display = '';
                        }
                        //  document.getElementById('activity-feed').removeChild($('activity-item-undo-'+id));
                    }
                    unhideReqActive = false;
                }
            });
            req.send();
        }

    <?php if (!$this->feedOnly && !$this->onlyactivity): ?>
        moreEditOptionsSwitch = function (el) {
            moreADVHideEventEnable = true;
            var hideElements = $$('.aaf_pulldown_btn_wrapper');
            for (var i = 0; i < hideElements.length; i++) {
                if (hideElements[i] != el)
                    hideElements[i].removeClass('aaf_tabs_feed_tab_open').addClass('aaf_tabs_feed_tab_closed');
            }
            el.getParent().toggleClass('aaf_tabs_feed_tab_open');
            el.getParent().toggleClass('aaf_tabs_feed_tab_closed');
        }
    <?php endif; ?>
    <?php endif; ?>

        if (en4.sitevideoview) {
            en4.sitevideoview.attachClickEvent(Array('feed', 'feed_video_title', 'feed_sitepagevideo_title', 'feed_sitebusinessvideo_title', 'feed_ynvideo_title', 'feed_sitegroupvideo_title', 'feed_sitestorevideo_title'));
        }

        if (en4.sitevideolightboxview) {
            en4.sitevideolightboxview.attachClickEvent(Array('feed', 'feed_video_title', 'feed_sitepagevideo_title', 'feed_sitebusinessvideo_title', 'feed_ynvideo_title', 'feed_sitegroupvideo_title', 'feed_sitestorevideo_title', 'sitevideo_thumb_viewer'));
        }

        // Add tooltips
    <?php if ($this->allowReaction): ?>
        try { en4.sitereaction.attachReaction(); } catch( e ) {}
    <?php endif; ?>

        SmoothboxSEAO.bind($('activity-feed'));

    });
</script>
<?php $sharesTable = Engine_Api::_()->getDbtable('shares', 'advancedactivity'); ?>
<?php if( !$this->ignoreScriptInclude ): ?>
  <script type="text/javascript">
    en4.core.runonce.add(function () {
      en4.advancedactivity.bindOnLoadForViewerFeeds({
        allowReaction: <?php echo $this->allowReaction ? 1 : 0 ?>,
        likeLoadUrl: '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'get-likes'), 'default', true) ?>'
      })
    });
  </script>
<?php endif; ?>

<?php
    $advancedactivityCoreApi = Engine_Api::_()->advancedactivity();
    $advancedactivitySaveFeed = Engine_Api::_()->getDbtable('saveFeeds', 'advancedactivity');
?>
<?php
    $action = $this->action;
    $subject = $action->getSubject();
    $object = $action->getObject();
?>
<?php $item = (isset($action->getTypeInfo()->is_object_thumb) && !empty($action->getTypeInfo()->is_object_thumb)) ? $action->getObject() : $action->getSubject(); ?>

<?php
    if( $this->onViewPage ): 
        $actionBaseId = "view-" . $action->action_id;
    else:
        $actionBaseId = $action->action_id;
    endif;
?>
<?php
    $this->commentForm->setActionIdentity($actionBaseId);
    $this->commentForm->action_id->setValue($action->action_id);
?>
<?php // Icon, time since, action links ?>
    <?php
    $icon_type = 'activity_icon_' . $action->type;
    list($attachment) = $action->getAttachments();
    if (is_object($attachment) && $action->attachment_count > 0 && $attachment->item):
        $icon_type .= ' item_icon_' . $attachment->item->getType() . ' ';
    endif;
    $canComment = ( isset($action) && $action->getTypeInfo()->commentable && $action->commentable &&
            $this->viewer()->getIdentity() &&
            Engine_Api::_()->authorization()->isAllowed($action->getCommentObject(), null, 'comment') &&
            !empty($this->commentForm) );
    ?>

    <div class='feed_item_date feed_item_icon'>
        <ul>  
        <?php if ($canComment): ?>
          <?php if($showAsLike):?>
            <?php if ($this->allowReaction): ?>
                <li class="feed_item_option_reaction seao_icons_toolbar_attach">
            <?php
              echo $this->reactions($action, array(
                'target' => $action->action_id,
                'id' => 'like_'.$action->action_id,
                'class' => 'aaf_like_toolbar'
                ));
            ?>
                    
                </li>
          <?php else: ?>

            <?php if ($action->likes()->isLike($this->viewer())): ?>
                <li class="feed_item_option_unlike">              
                <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Unlike'), array('onclick' => 'javascript:en4.advancedactivity.unlike(this,' . $action->action_id . ');', 'action-title' => $this->translate('Like'))) ?>
                    
                </li>
            <?php else: ?>
                <li class="feed_item_option_like">              	
                <?php
                echo $this->htmlLink('javascript:void(0);', $this->translate('Like'), array('onclick' => 'javascript:en4.advancedactivity.like(this,' . $action->action_id . ');', 'action-title' => $this->translate('Unlike')))
                ?>
                    
                </li>
            <?php endif; ?>
           <?php endif; ?>
        <?php else :?>
            <?php if (!$action->likes()->isLike($this->viewer())): ?>
                <?php if ($this->allowReaction && $showLikeWithoutIcon !=3): ?>
                <li class="feed_item_option_reaction seao_icons_toolbar_attach nstcomment_wrap">
                    <?php
                      echo $this->reactions($action, array(
                        'target' => $action->action_id,
                        'id' => 'like_'.$action->action_id,
                        'class' => 'aaf_like_toolbar',
                        'unlikeDisable' => true,
                        'likeClass' => !$showLikeWithoutIcon?  'nstcomment_like' : ''
                        ));
                    ?>
                    
                </li>
                  <?php else: ?>
                <li class="feed_item_option_like nstcomment_wrap">

                    <?php if(!$showLikeWithoutIcon):?>
                        <?php
                        echo $this->htmlLink('javascript:void(0);', $this->translate('Like'), array('onclick' => 'javascript:en4.advancedactivity.like(this,' . $action->action_id . ');', 'action-title' => '<img src="application/modules/Seaocore/externals/images/core/loading.gif" />', 'class' => 'nstcomment_like'))
                        ?>
                    
                    <?php else:?>

                        <?php if($showLikeWithoutIcon == 3):?>
                        <?php
                        //echo $this->htmlLink('javascript:void(0);', $this->translate('Vote up'), array('onclick' => 'javascript:en4.advancedactivity.like(this,' . $action->action_id . ');', 'action-title' => '<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'))
                        ?>


                        <?php if ($action->likes()->getLikeCount() > 0): ?>
                                   <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'activity-like', 'action_id' => $action->action_id, 'call_status' => 'public', 'showLikeWithoutIcon' => 3), 'default', true);?>
                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $action->likes()->getLikeCount();?></a>
                               <?php endif ?>

                    <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this, '<?php echo $action->action_id ?>');" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                        <img title="<?php echo $this->translate('Vote up');?>" src="application/modules/Nestedcomment/externals/images/arrow-up.png" />
                    </a>
                    <span>|</span>
                        <?php else:?>
                         <?php
                        echo $this->htmlLink('javascript:void(0);', $this->translate('Like'), array('onclick' => 'javascript:en4.advancedactivity.like(this,' . $action->action_id . ');', 'action-title' => '<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'))
                        ?>
                    
                        <?php endif;?>
                    <?php endif;?>

                </li>
                <?php endif; ?>
            <?php else :?>
                <?php if ($this->allowReaction && $showLikeWithoutIcon !=3): ?>
                <li class="feed_item_option_reaction seao_icons_toolbar_attach nstcomment_wrap">
                    <?php
                      echo $this->reactions($action, array(
                        'target' => $action->action_id,
                        'id' => 'like_'.$action->action_id,
                        'class' => 'aaf_like_toolbar',
                        'unlikeDisable' => true,
                        'likeClass' => !$showLikeWithoutIcon ?  'nstcomment_like' : ''
                        ));
                    ?>
                    
                </li>
                  <?php else: ?>
                <li class="nstcomment_wrap feed_item_option_like"> 


                    <?php if($showLikeWithoutIcon != 3):?>
                        <?php //SHOW ICON WITH LIKE?>
                            <?php if(!$showLikeWithoutIcon):?>
                    <img  src="application/modules/Nestedcomment/externals/images/like_light.png" />
                            <?php endif;?>
                        <?php
                        //DISABLE LINK
                        echo $this->translate('Like');
                        ?>
                    
                    <?php else:?>
                      <?php if ($action->likes()->getLikeCount() > 0): ?>
                                    <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'activity-like', 'action_id' => $action->action_id, 'call_status' => 'public', 'showLikeWithoutIcon' => 3), 'default', true);?>
                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $action->likes()->getLikeCount();?></a>
                               <?php endif ?>
                    <img  src="application/modules/Nestedcomment/externals/images/arrow-up_light.png" />
                    <span>|</span>
                    <?php endif;?>

                </li>
                <?php endif;?>
            <?php endif;?>
               <?php if (!$action->dislikes()->isDislike($this->viewer())):?>
                <li class="nstcomment_wrap feed_item_option_unlike">  

                 <?php if(!$showLikeWithoutIcon):?>       
                 <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Dislike'), array('onclick' => 'javascript:en4.advancedactivity.unlike(this,' . $action->action_id . ');', 'action-title' => '<img src="application/modules/Seaocore/externals/images/core/loading.gif" />', 'class' => 'nstcomment_unlike')); ?>

                 <?php else:?>

                  <?php if($showLikeWithoutIcon != 3):?>
                  <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Dislike'), array('onclick' => 'javascript:en4.advancedactivity.unlike(this,' . $action->action_id . ');', 'action-title' => '<img src="application/modules/Seaocore/externals/images/core/loading.gif" />')); ?>
                  <?php else:?>
                  <?php //echo $this->htmlLink('javascript:void(0);', $this->translate('Dislike'), array('onclick' => 'javascript:en4.advancedactivity.unlike(this,' . $action->action_id . ');', 'action-title' => '<img src="application/modules/Seaocore/externals/images/core/loading.gif" />')); ?>
                  <?php if ($action->dislikes()->getDisLikeCount() > 0): ?>
                                    <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'activity-dislike',  'action_id' => $action->action_id, 'call_status' => 'public', 'showLikeWithoutIcon' => 3), 'default', true);?>
                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $action->dislikes()->getDisLikeCount();?></a>
                               <?php endif ?>
                    <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this, '<?php echo $action->action_id ?>');" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                        <img title="<?php echo $this->translate('Vote down');?>" src="application/modules/Nestedcomment/externals/images/arrow-down.png" />
                    </a>

                  <?php endif;?>

                 <?php endif;?>
                    
                </li>
            <?php else:?>
                <li class="feed_item_option_unlike nstcomment_wrap"> 

                    <?php if($showLikeWithoutIcon != 3):?>     
                        <?php //SHOW ICON WITH LIKE?>
                            <?php if(!$showLikeWithoutIcon):?>
                    <img  src="application/modules/Nestedcomment/externals/images/dislike_light.png" />
                            <?php endif;?>
                        <?php
                        //DISABLE LINK
                        echo $this->translate('Dislike');
                        ?>
                    <?php else:?>
                      <?php if ($action->dislikes()->getDisLikeCount() > 0): ?>
                                    <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'activity-dislike', 'action_id' => $action->action_id, 'call_status' => 'public', 'showLikeWithoutIcon' => 3), 'default', true);?>
                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $action->dislikes()->getDisLikeCount();?></a>
                               <?php endif ?>
                    <img  src="application/modules/Nestedcomment/externals/images/arrow-down_light.png" title="<?php echo $this->translate('Vote down');?>"/>
                    <?php endif;?>
                    
                </li>
            <?php endif;?>

        <?php endif;?>

        <?php if (Engine_Api::_()->getApi('settings', 'core')->core_spam_comment): // Comments - likes  ?>
                <li class="feed_item_option_comment">               
              <?php
              echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(), 'format' => 'smoothbox'), $this->translate('Comment'), array(
                  'class' => 'smoothbox', 'title' => $this->translate('Leave a comment')
              ))
              ?>
                    
                </li>
          <?php else: ?>
                <li class="feed_item_option_comment">

              <?php
              echo $this->htmlLink('javascript:void(0);', $this->translate('Comment'), array('onclick' =>'showCommentBox("' . $this->commentForm->getAttrib('id') . '", "' . $this->commentForm->body->getAttrib('id') . '"); 
                    document.getElementById("' . $this->commentForm->submit->getAttrib('id') . '").style.display = "' . (($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? "block" : "none") . '";
                    if(document.getElementById("feed-comment-form-open-li_' . $actionBaseId . '")){
                    document.getElementById("feed-comment-form-open-li_' . $actionBaseId . '").style.display = "none";}  
                    document.getElementById("' . $this->commentForm->body->getAttrib('id') . '").focus();document.getElementById("' . "comment-likes-activityboox-item-$actionBaseId" . '").style.display = "block";', 'title' => $this->translate('Leave a comment'))) ?>
                    
                </li>              
          <?php endif; ?>
        <?php endif; ?>
        <?php if (in_array($action->getTypeInfo()->type, array('signup', 'friends', 'friends_follow'))): ?>    
          <?php $userFriendLINK = $this->aafUserFriendshipAjax($action); ?>
          <?php if ($userFriendLINK): ?>
                <li class="feed_item_option_add_tag"><?php echo $userFriendLINK; ?>
                    </li>  
          <?php endif; ?>
        <?php endif; ?>    
        <?php
        if ($this->viewer()->getIdentity() && (
                'user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) && $advancedactivityCoreApi->hasFeedTag($action)
        ):
          ?>
                <li class="feed_item_option_add_tag">             
            <?php
            echo $this->htmlLink(array(
                'route' => 'default',
                'module' => 'advancedactivity',
                'controller' => 'feed',
                'action' => 'tag-friend',
                'id' => $action->action_id
                    ), $this->translate('Tag Friends'), array('class' => 'smoothbox', 'title' =>
                $this->translate('Tag more friends')))
            ?>

                </li>
        <?php elseif ($this->viewer()->getIdentity() && $advancedactivityCoreApi->hasMemberTagged($action, $this->viewer())): ?>  
                <li class="feed_item_option_remove_tag">
            <?php
            echo $this->htmlLink(array(
                'route' => 'default',
                'module' => 'advancedactivity',
                'controller' => 'feed',
                'action' => 'remove-tag',
                'id' => $action->action_id
                    ), $this->translate('Remove Tag'), array('class' => 'smoothbox'))
            ?>
                </li>
        <?php endif; ?>


        <?php // Share ?>
        <?php $shareableItem = $action->getShareableItem(); ?>
        <?php if( $this->viewer()->getIdentity() && $shareableItem ): ?>
          <li class="feed_item_option_share seao_icons_toolbar_attach">
            <?php
            echo $this->settings('aaf.social.share.enable', 1) ? $this->shareIcons($action) : '';
            echo $this->htmlLink(array('route' => 'default', 'module' => 'seaocore',
              'controller' => 'activity', 'action' => 'share', 'type' => $shareableItem->getType(), 'id' =>
              $shareableItem->getIdentity(), 'action_id' => $action->getIdentity(), 'format' => 'smoothbox', "not_parent_refresh" => 1), $this->translate('ADVACADV_SHARE'), array('class' => 'smoothbox share_icons_link', 'title' => $this->translate('Share this by re-posting it with your own message.')))
            ?>
          </li>
        <?php endif; ?>
        <?php if ($canComment && $this->aaf_comment_like_box): ?> 
          <?php $likeCount = $action->likes()->getLikeCount(); ?>
          <?php $commentCount = $action->comments()->getCommentCount() ?> 
          <?php $dislikeCount = $action->dislikes()->getDislikePaginator()->getTotalItemCount(); ?>
          <?php if ($likeCount || $commentCount || $dislikeCount): ?>
                <li class="like_comment_counts" onclick="$('comment-likes-activityboox-item-<?php echo $actionBaseId ?>').toggle()">
              <?php if ($likeCount): ?>
                    <span class="nstcomment_like"><?php echo $this->locale()->toNumber($likeCount); ?></span>
              <?php endif; ?>

               <?php if ($dislikeCount && !$showAsLike): ?>
                    <span class="nstcomment_unlike"><?php echo $this->locale()->toNumber($dislikeCount); ?></span>
              <?php endif; ?>

              <?php if ($commentCount): ?>
                    <span class="comment_icon"><?php echo $this->locale()->toNumber($commentCount); ?></span>
              <?php endif; ?>
                    
                </li>
          <?php endif; ?>
        <?php endif; ?>
        <?php $feedBodyText = $this->string()->escapeJavascript(strip_tags($action->body)); ?>
        <?php if( $this->settings('aaf.translation.feed.enable', 1) && !empty($feedBodyText) ) : ?>
          <li class="feed_item_option_translate">
            <a href="javascript:void(0)" onclick="en4.advancedactivity.translateFeed('<?php echo $feedBodyText; ?>')"><?php echo $this->translate('Translate') ?></a>
          </li>
        <?php endif; ?>
                <li>
          <?php //echo $this->timestamp($action->getTimeValue()) ?>
          <?php $category = $action->getCategory(); ?>
          <?php if ($category): ?>
                    <span class = "aaf_feed_category feed_item_option_add_tag">
              <?php echo $this->translate("in %s", $this->htmlLink($category->getHref(), $this->translate($category->getTitle()), array('class' => ''))) ?>
                    </span>
          <?php endif; ?>
                </li>
            </ul>
        </div>
    <?php if (($action->getTypeInfo()->commentable && $action->commentable)) : // Comments - likes -share  ?>
        <div id='comment-likes-activityboox-item-<?php echo $actionBaseId ?>' class='comments' <?php if (!$this->viewAllLikes && !$this->viewAllComments && $this->aaf_comment_like_box && $this->viewer()->getIdentity()): ?>style="display: none;"  <?php endif; ?>>
            <ul class="seao_advcomment">  
                <?php // Share Count ?>
                <?php if ($action->getTypeInfo()->shareable && $action->shareable && 0): ?>
                    <li class="aaf_share_counts">
                        <div></div>
                        <div class="comments_likes">
                            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'advancedactivity', 'controller' => 'index', 'action' => 'share-item', 'action_id' => $action->getIdentity(), 'format' => 'smoothbox'), $this->translate(array('%s share', '%s shares', $share), $this->locale()->toNumber($share)), array('class' => 'smoothbox seaocore_icon_share aaf_commentbox_icon')) ?>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if ($action->getTypeInfo()->commentable && $action->commentable): // Comments - likes -share?> 
                    <?php if($showLikeWithoutIcon != 3) :?>
                            <?php $this->dislikes = $action->dislikes()->getDislikePaginator(); ?>
                            <?php if (($action->likes()->getLikeCount() > 0 && (count($action->likes()->getAllLikesUsers()) > 0)) || ($this->dislikes->getTotalItemCount() > 0 && !$showAsLike)):?>
                            <li>
                                <div></div>
                            <?php if ($action->likes()->getLikeCount() > 0 && (count($action->likes()->getAllLikesUsers()) > 0)):?>
                            <?php if ($this->allowReaction): ?>
                                    <?php echo $this->likeReactionsLink($action);?>
                            <?php endif; ?>
                                <span class="comments_likes">
                            <?php if ($this->allowReaction): ?>
                                <?php $likeS = '%s reacted on this.'; $likeP = '%s reacted on this.'; ?>
                                <?php $url =  $this->url(array('action' => 'likes', 'module' => 'sitereaction',
                                  'controller' => 'index', 'subject_type' => $action->getType(), 'subject_id' => $action->getIdentity()), 'default', true); ?>
                            <?php else: ?>
                                <?php $likeS = '%s likes this.'; $likeP = '%s like this.'; ?>
                                <?php $url = ''; ?>
                            <?php endif; ?>
                                <?php if ($action->likes()->getLike($this->viewer()) && $action->likes()->getLikeCount() == 1) :?>
                                <?php echo $this->translate(array($likeP, $likeS, $action->likes()->getLikeCount()), $this->aafNCFluentList($action->likes()->getAllLikesUsers(), false, $action, $url)) ?>
                            <?php else:?>
                                <?php echo $this->translate(array($likeS, $likeP, $action->likes()->getLikeCount()), $this->aafNCFluentList($action->likes()->getAllLikesUsers(), false, $action, $url)) ?>
                            <?php endif;?>
                                </span>
                    <?php endif; ?> 
                    <?php if ($this->dislikes->getTotalItemCount() > 0 && !$showAsLike):?>
                        <?php if($showDislikeUsers) :?>
                        <?php if ($action->likes()->getLikeCount() > 0 && (count($action->likes()->getAllLikesUsers()) > 0)):?>
                            &nbsp;|&nbsp;
                        <?php endif;?>
                            <span class="comments_likes">
                            <?php if ($action->dislikes()->getDislike($this->viewer()) && $this->dislikes->getTotalItemCount() == 1):?>
                                <?php echo $this->translate(array('%s dislike this.', '%s dislikes this.', $this->dislikes->getTotalItemCount()), $this->aafFluentDisLikeList($action->dislikes()->getAllDislikesUsers(), false, $action)) ?>
                            <?php else:?>
                                <?php echo $this->translate(array('%s dislikes this.', '%s dislike this.', $this->dislikes->getTotalItemCount()), $this->aafFluentDisLikeList($action->dislikes()->getAllDislikesUsers(), false, $action)) ?>
                           <?php endif;?>
                            </span>
                        <?php else:?>
                                <?php echo $this->translate(array('%s person dislikes this.', '%s people dislike this.', $this->dislikes->getTotalItemCount()), $this->locale()->toNumber($this->dislikes->getTotalItemCount()));?>
                        <?php endif;?>
                    <?php endif; ?>
                        </li>
                    <?php endif; ?>
                    <?php endif; ?> 
                    <?php if (($action->comments()->getCommentCount() == 0) && $commentReverseOrder && $canComment) : ?>
                        <?php  echo $this->commentForm->render(); ?>
                    <?php endif; ?>
                    <?php if ($action->comments()->getCommentCount() > 0): ?>
                        <?php if (!$commentReverseOrder && $action->comments()->getCommentCount() > 5 && !$this->viewAllComments): ?>
                        <li>
                            <div></div>
                            <div class="comments_viewall" id="comments_viewall">
                            <?php if (0): ?>
                              <?php
                              echo $this->htmlLink($action->getHref(array('show_comments' => true)), $this->translate(array('View all %s comment', 'View all %s comments', $action->comments()->getCommentCount()), $this->locale()->toNumber($action->comments()->getCommentCount())))
                              ?>
                            <?php else: ?>
                              <?php
                              echo $this->htmlLink('javascript:void(0);', $this->translate(array('View all %s comment', 'View all %s comments', $action->comments()->getCommentCount()), $this->locale()->toNumber($action->comments()->getCommentCount())), array('onclick' => 'en4.advancedactivity.viewComments(' . $action->action_id . ');'))
                              ?>
                            <?php endif; ?>
                            </div>
                            <div style="display:none;" id="show_view_all_loading">
                                <img src="application/modules/Seaocore/externals/images/core/loading.gif" alt="Loading" />
                            </div>
                        </li>
                      <?php endif; ?>
                      <?php if ($commentReverseOrder && $canComment): ?>
                          <?php $commentFormId = $this->commentForm->getAttrib('id');?>
                          <?php $commentFormBodyId = $this->commentForm->body->getAttrib('id');?>
                          <li id='feed-comment-form-open-li_<?php echo $actionBaseId ?>' onclick='<?php echo '
                        document.getElementById("' . $this->commentForm->submit->getAttrib('id') . '").style.display = "' . (($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? "block" : "none") . '";
                        document.getElementById("feed-comment-form-open-li_' . $actionBaseId . '").style.display = "none";
                        document.getElementById("' . $this->commentForm->body->getAttrib('id') . '").focus();' ?> showCommentBox("<?php echo $commentFormId?>", "<?php echo $commentFormId?>");' <?php if (!$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment): ?> style="display:none;"<?php endif; ?> >                  <div></div>
                           <div class="nested_user_photo">
                           <div class="comment_form_user_photo">
                            <?php echo $this->itemPhoto($this->viewer(), 'thumb.icon') ?>
                           </div>
                            <div class="seaocore_comment_box seaocore_txt_light"><?php echo $this->translate('Write a comment...') ?></div></div>
                            </li>
                      <?php  echo $this->commentForm->render(); ?>
                        <?php endif; ?>
                      <?php foreach ($action->getComments($this->viewAllComments) as $comment): ?>
                      <?php
                        $this->replyForm->setActionIdentity($comment->comment_id);
                        $this->replyForm->comment_id->setValue($comment->comment_id);
                        $this->replyForm->action_id->setValue($action->action_id);
                      ?>

    <script type="text/javascript">
        (function () {
            en4.core.runonce.add(function () {
                <?php if ($this->onViewPage): ?>
                        (function () {
                <?php endif; ?>
                    if (!$('<?php echo $this->replyForm->body->getAttrib('id') ?>'))
                        return;
                    $('<?php echo $this->replyForm->body->getAttrib('id') ?>').autogrow();

                    if (allowQuickReply == '1' && <?php echo $this->submitReply ? '1' : '0' ?>) {
                        document.getElementById("<?php echo $this->replyForm->getAttrib('id') ?>").style.display = "";
                        document.getElementById("<?php echo $this->replyForm->submit->getAttrib('id') ?>").style.display = "none";
                        if (document.getElementById("feed-reply-form-open-li_<?php echo $comment->comment_id ?>")) {
                            document.getElementById("feed-reply-form-open-li_<?php echo$comment->comment_id ?>").style.display = "none";
                        }
                        document.getElementById("<?php echo $this->replyForm->body->getAttrib('id') ?>").focus();
                    }
            <?php if ($this->onViewPage): ?>
                        }).delay(1000);
            <?php endif; ?>
                    });
                })();
    </script>

    <li id="comment-<?php echo $comment->comment_id ?>" class="seao_nestcomment">
        <?php
              if ($this->viewer()->getIdentity() &&
                    (  $this->activity_moderate ||
                    ("user" == $comment->poster_type && $this->viewer()->getIdentity() == $comment->poster_id) ||
                    ("user" !== $comment->poster_type && Engine_Api::_()->getItemByGuid($comment->poster_type . "_" . $comment->poster_id)->isOwner($this->viewer())))):
        ?>
            <span class="seaocore_replies_info_op">

                <span class="seaocore_replies_pulldown">
                    <div class="seaocore_dropdown_menu_wrapper">
                        <div class="seaocore_dropdown_menu">
                            <ul>  
                                <?php
                                      if ($this->viewer()->getIdentity() &&
                                            ( $this->activity_moderate ||
                                            ("user" == $comment->poster_type && $this->viewer()->getIdentity() == $comment->poster_id) ||
                                            ("user" !== $comment->poster_type && Engine_Api::_()->getItemByGuid($comment->poster_type . "_" . $comment->poster_id)->isOwner($this->viewer()))  )):
                                ?>
                                    <li>
                                      <?php 
                                        $attachMentArray  = array();
                                        if (!empty($comment->attachment_type) && null !== ($attachment = $this->item($comment->attachment_type, $comment->attachment_id))): ?>
                                        <?php $attachMentArray = array(
                                            "type" => $comment->attachment_type,
                                            "guid"=> $attachment->getGuid(),
                                            "id" => $comment->attachment_id,
                                            "src" => $attachment->getPhotoUrl()
                                        );?>
                                        <?php if($comment->attachment_type == 'album_photo'):?>
                                        <?php $status = true; ?>
                                        <?php $photo_id = $attachment->photo_id; ?>
                                        <?php $album_id = $attachment->album_id; ?>
                                        <?php $src = $attachment->getPhotoUrl(); ?>
                                        <?php $attachMentArray = array_merge(array("status" => $status, "photo_id"=> $photo_id , "album_id" => $attachment->album_id, "src" => $src), $attachMentArray);?>
                                        <?php endif;?>
                                        <?php endif;?>


                                        <script type="text/javascript">
                                            en4.core.runonce.add(function () {
                                                commentAttachment.editComment['<?php echo $comment->comment_id ?>'] = {'body': '<?php echo $this->string()->escapeJavascript($comment->body);?>', 'attachment_body':<?php echo Zend_Json_Encoder::encode($attachMentArray);?>}
                                            });
                                        </script>
                                        <a href='javascript:void(0);' title="<?php echo $this->translate('Edit') ?>" onclick="en4.nestedcomment.nestedcomments.showCommentEditForm('<?php echo $comment->comment_id?>', '<?php echo ($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? 0 : 1; ?>');"><?php echo $this->translate('Edit'); ?></a>
                                    </li>    
                                        <?php endif ?>
                                <?php
                                  if ($this->viewer()->getIdentity() &&
                                        ( $this->activity_moderate ||
                                        ("user" == $comment->poster_type && $this->viewer()->getIdentity() == $comment->poster_id) ||
                                        ("user" !== $comment->poster_type && Engine_Api::_()->getItemByGuid($comment->poster_type . "_" . $comment->poster_id)->isOwner($this->viewer())) )):
                                ?>
                                    <li>
                                      <?php /* echo $this->htmlLink(array(
                                        'route'=>'default',
                                        'module'    => 'advancedactivity',
                                        'controller'=> 'index',
                                        'action'    => 'delete',
                                        'action_id' => $action->action_id,
                                        'comment_id'=> $comment->comment_id,
                                        ),'', array('class' => 'smoothbox
                                        aaf_icon_remove','title'=>$this->translate('Delete Comment'))) */ ?>
                                                    <a href="javascript:void(0);" title="<?php
                                      echo
                                      $this->translate('Delete Comment')
                                      ?>" onclick="deletefeed('<?php
                                         echo
                                         $action->action_id
                                         ?>', '<?php echo $comment->comment_id ?>', '<?php
                                         echo
                                         $this->escape($this->url(array('route' => 'default',
                                                     'module' => 'advancedactivity', 'controller' => 'index', 'action' => 'delete')))
                                         ?>')"><?php echo $this->translate('Delete') ?></a>
                                    </li>
                                       <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <span class="seaocore_comment_dropbox"></span>
                </span>
            </span>
            <?php endif;?>
        <div class="comments_author_photo">
            <?php 
                    echo $this->htmlLink($this->item($comment->poster_type, $comment->poster_id)->getHref(), $this->itemPhoto($this->item($comment->poster_type, $comment->poster_id), 'thumb.icon', $action->getSubject()->getTitle()), array('class' => 'sea_add_tooltip_link', 'rel' => $this->item($comment->poster_type, $comment->poster_id)->getType() . ' ' . $this->item($comment->poster_type, $comment->poster_id)->getIdentity())
                          )
            ?>
        </div>
        <div class="comments_info">
            <span class='comments_author'>
                <?php
                    echo $this->htmlLink($this->item($comment->poster_type, $comment->poster_id)->getHref(), $this->item($comment->poster_type, $comment->poster_id)->getTitle(), array('class' => 'sea_add_tooltip_link', 'rel' => $this->item($comment->poster_type, $comment->poster_id)->getType() . ' ' . $this->item($comment->poster_type, $comment->poster_id)->getIdentity())
                    );
                ?>
            </span>
            <span class="comments_body" id="comments_body_<?php echo $comment->comment_id ?>">
                <?php 
                    include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_commentBody.tpl';
                ?>
            </span>

            <div id="comment_edit_<?php echo $comment->comment_id ?>" class="mtop5 comment_edit" style="display: none;"><?php include APPLICATION_PATH . '/application/modules/Nestedcomment/views/scripts/_editComment.tpl' ?>
            </div>

            <?php if (!empty($comment->attachment_type) && null !== ($attachment = $this->item($comment->attachment_type, $comment->attachment_id))): ?>
                <div class="seaocore_comments_attachment seaocore_comments_attachment_<?php echo $comment->attachment_type?>" id="seaocore_comments_attachment_<?php echo $comment->comment_id ?>">
                    <div class="seaocore_comments_attachment_photo">
                        <?php if (null !== $attachment->getPhotoUrl()): ?>
                            <?php if (SEA_ACTIVITYFEED_LIGHTBOX && strpos($comment->attachment_type, '_photo')): ?>
                                <?php echo $this->htmlLink($attachment->getHref(), $this->itemPhoto($attachment, 'thumb.normal', $attachment->getTitle(), array('class' => 'thumbs_photo')), array('onclick' => 'openSeaocoreLightBox("' . $attachment->getHref() . '");return false;')) ?>
                            <?php else:?>
                                <?php echo $this->htmlLink($attachment->getHref(), $this->itemPhoto($attachment, 'thumb.normal', $attachment->getTitle(), array('class' => 'thumbs_photo'))) ?>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>
                    <div class="seaocore_comments_attachment_info">
                        <div class="seaocore_comments_attachment_title">
                            <?php echo $this->htmlLink($attachment->getHref(array('message' => $comment->comment_id)), $attachment->getTitle()) ?>
                        </div>
                        <div class="seaocore_comments_attachment_des">
                            <?php echo $attachment->getDescription() ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>	
            <ul class="comments_date">
                <?php if($canComment) :?>
                    <?php if($showAsNested):?>
                        <li class="feed_item_option_comment"> 
                            <?php
                                  echo $this->htmlLink('javascript:void(0);', $this->translate('Reply'), array('onclick' => ' showReplyBox("' . $this->replyForm->getAttrib('id') . '", "' . $this->replyForm->body->getAttrib('id') . '"); 
                                            document.getElementById("' . $this->replyForm->submit->getAttrib('id') . '").style.display = "' . (($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? "block" : "none") . '";
                                            if(document.getElementById("feed-reply-form-open-li_' . $comment->comment_id . '")){
                                            document.getElementById("feed-reply-form-open-li_' . $comment->comment_id . '").style.display = "none";}  
                                            document.getElementById("' . $this->replyForm->body->getAttrib('id') . '").focus();document.getElementById("' . "comment-likes-activityboox-item-$actionBaseId" . '").style.display = "block"; ', 'title' =>
                                                              $this->translate('Leave a reply')))
                            ?>

                        </li>
                    <?php endif;?>
                    <?php if($showAsLike):?>
                            <?php  $isLiked = $comment->likes()->isLike($this->viewer());?>
                                <li class="comments_like">
                                    <?php if($showAsNested):?>
                                            &#183;
                                    <?php endif;?>
                                    <?php if (!$isLiked): ?>
                                        <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title="<?php echo $this->translate('unlike') ?>">
                                            <?php echo $this->translate('like') ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title="<?php echo $this->translate('like') ?>">
                                            <?php echo $this->translate('unlike') ?>
                                        </a>
                                    <?php endif ?>
                                </li>
                        <?php else:?>
                            <?php  $isLiked = $comment->likes()->isLike($this->viewer());?>
                                <?php if (!$isLiked): ?>
                                    <li class="comments_like"> 
                                        <?php if($showAsNested):?>
                                            &#183;
                                        <?php endif;?>
                                        <?php if($showLikeWithoutIconInReplies == 0):?>     
                                            <a class='nstcomment_like' href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                               <?php echo $this->translate('like') ?>
                                            </a>
                                        <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                            <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                               <?php echo $this->translate('like') ?>
                                            </a>
                                        <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                            <a class='nstcomment_like' href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>    
                                            </a>
                                        <?php elseif($showLikeWithoutIconInReplies == 3):?>
                                        <?php if ($comment->likes()->getLikeCount() > 0): ?>
                                            <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'likelist', 'resource_type' => $comment->getType(), 'resource_id' => $comment->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                            <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $comment->likes()->getLikeCount();?></a>
                                       <?php endif ?>
                                            <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                                <img title="<?php echo $this->translate('Vote up');?>" src="application/modules/Nestedcomment/externals/images/arrow-up.png" />
                                            </a>
                                        <?php endif;?>
                                    </li>
                                <?php else: ?>
                                    <li class="comments_like nstcomment_wrap"> 
                                        <?php if($showAsNested):?>
                                            &#183;
                                        <?php endif;?>
                                        <?php if($showLikeWithoutIconInReplies == 0):?> 
                                            <img src="application/modules/Nestedcomment/externals/images/like_light.png" />
                                        <?php echo $this->translate('like') ?>
                                        <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                           <?php echo $this->translate('like') ?>
                                        <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                            <img src="application/modules/Nestedcomment/externals/images/like_light.png" />
                                        <?php elseif($showLikeWithoutIconInReplies == 3):?>

                                        <?php if ($comment->likes()->getLikeCount() > 0): ?>
                                            <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'likelist', 'resource_type' => $comment->getType(), 'resource_id' => $comment->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $comment->likes()->getLikeCount();?></a>
                                           <?php endif ?>
                                                <img title="<?php echo $this->translate('Vote up');?>" src="application/modules/Nestedcomment/externals/images/arrow-up_light.png" />
                                        <?php endif;?>
                                    </li>
                                    <?php endif;?>
                            <?php $isDisLiked = Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->isDislike($comment, $this->viewer())?>
                                <?php if (!$isDisLiked): ?>
                                    <li class="comments_unlike"> 
                                        &#183; 
                                         <?php if($showLikeWithoutIconInReplies == 0):?>     
                                        <a class='nstcomment_unlike' href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                           <?php echo $this->translate('dislike') ?>
                                        </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                        <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                           <?php echo $this->translate('dislike') ?>
                                        </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                        <a class='nstcomment_unlike' href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>    
                                        </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 3):?>

                                         <?php if(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment ) && !$showAsLike):?>
                                          <?php if($showDislikeUsers) :?>
                                            <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'dislikelist', 'resource_type' => $comment->getType(), 'resource_id' => $comment->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                        <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )?></a>

                                            <?php else:?>
                                                <?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )?>
                                            <?php endif;?>   
                                        <?php endif;?>
                                        <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                            <img title="<?php echo $this->translate('Vote down');?>" src="application/modules/Nestedcomment/externals/images/arrow-down.png" />
                                        </a>
                                         <?php endif;?>
                                    </li>
                                    <?php else: ?>
                                    <li class="comments_unlike nstcomment_wrap"> 
                                            &#183;  
                                        <?php if($showLikeWithoutIconInReplies == 0):?> 
                                            <img src="application/modules/Nestedcomment/externals/images/dislike_light.png" />
                                            <?php echo $this->translate('dislike') ?>
                                        <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                            <?php echo $this->translate('dislike') ?>
                                        <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                            <img src="application/modules/Nestedcomment/externals/images/dislike_light.png" />
                                        <?php elseif($showLikeWithoutIconInReplies == 3):?>
                                        <?php if(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment ) && !$showAsLike):?>
                                            <?php if($showDislikeUsers) :?>
                                            <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'dislikelist', 'resource_type' => $comment->getType(), 'resource_id' => $comment->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                            <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )?></a>
                                                <?php else:?>
                                                    <?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )?>
                                                <?php endif;?>
                                            <?php endif;?>
                                            <img title="<?php echo $this->translate('Vote down');?>" src="application/modules/Nestedcomment/externals/images/arrow-down_light.png" />
                                        <?php endif;?>
                                    </li>
                                    <?php endif;?>
                            <?php endif;?>
                        <?php endif ?>
                        <?php if($showLikeWithoutIconInReplies != 3):?>
                            <?php if ($comment->likes()->getLikeCount() > 0): ?>
                                    <li class="comments_likes_total"> 
                                    <?php if($canComment || $this->viewer()->getIdentity()) :?>
                                          &#183;
                                    <?php endif;?> 
                                    <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'likelist', 'resource_type' => $comment->getType(), 'resource_id' => $comment->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                        <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $this->translate(array('%s likes this.', '%s like this.', $comment->likes()->getLikeCount()), $this->locale()->toNumber($comment->likes()->getLikeCount()));?></a>
                                    </li>
                            <?php endif ?>

                            <?php if (Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment ) > 0 && !$showAsLike): ?>
                                    <li class="comments_likes_total"> 
                                  <?php if($canComment || $this->viewer()->getIdentity()) :?>
                                        &#183;
                                   <?php endif;?> 

                              <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'dislikelist', 'resource_type' => $comment->getType(), 'resource_id' => $comment->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                              <?php if($showDislikeUsers) :?>
                                        <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $this->translate(array('%s dislikes this.', '%s dislike this.', Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )), $this->locale()->toNumber(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )))?></a>
                              <?php else:?>
                                <?php echo $this->translate(array('%s dislikes this.', '%s dislike this.', Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )), $this->locale()->toNumber(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment )))?>
                              <?php endif;?>
                                    </li>
                              <?php endif ?>
                            <?php endif ?>
                                    <li class="comments_timestamp">
                                <?php if ((Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $comment ) > 0 && !$showAsLike) || ($comment->likes()->getLikeCount() > 0) || ($this->viewer()->getIdentity() && $canComment)): ?>
                                        &#183;
                                <?php endif ?>
                              <?php echo $this->timestamp($comment->creation_date); ?>
                                    </li>

                                </ul>

                          <?php if($showAsNested && count($action->getReplies($comment->comment_id))):?>
                                <a <?php if($defaultViewReplyLink):?>style="display:block;"<?php else:?>style="display:none;"<?php endif;?> id="replies_show_<?php echo $comment->comment_id ?>" class="fleft f_small buttonlink activity_icon_reply_seaocore_reply comments_viewall mtop5" href="javascript:void(0);" onclick="en4.nestedcomment.nestedcomments.loadCommentReplies('<?php echo $comment->comment_id;?>');"><?php echo $this->translate(array("View %s Reply", "View %s Replies", count($action->getReplies($comment->comment_id))), count($action->getReplies($comment->comment_id)));?></a>

                                <a <?php if($defaultViewReplyLink):?>style="display:none;"<?php else:?>style="display:block;"<?php endif;?> id="replies_hide_<?php echo $comment->comment_id ?>" class="fleft f_small buttonlink activity_icon_reply_seaocore_reply comments_viewall mtop5 mbot5" href="javascript:void(0);" onclick="en4.nestedcomment.nestedcomments.hideCommentReplies('<?php echo $comment->comment_id;?>');"><?php echo $this->translate(array("Hide %s Reply", "Hide %s Replies", count($action->getReplies($comment->comment_id))), count($action->getReplies($comment->comment_id)));?></a>
                    		<?php endif;?>
                            </div>

                            <div class="comments">
                                <ul class="seao_reply">
                            <?php if($showAsNested):?>  
                            <?php foreach ($action->getReplies($comment->comment_id) as $reply): ?>
                                    <li id="reply-<?php echo $reply->comment_id ?>" class="reply<?php echo $comment->comment_id;?>" <?php if($defaultViewReplyLink):?>style="display:none;"<?php else:?>style="display:inline-block;"<?php endif;?> >
                                   <?php
                                      if ($this->viewer()->getIdentity() &&
                                            ( $this->activity_moderate ||
                                            ("user" == $reply->poster_type && $this->viewer()->getIdentity() == $reply->poster_id) ||
                                            ("user" !== $reply->poster_type && Engine_Api::_()->getItemByGuid($reply->poster_type . "_" . $reply->poster_id)->isOwner($this->viewer()))  )):
                                        ?>
                                        <span class="seaocore_replies_info_op">
                                            <span class="seaocore_replies_pulldown">
                                                <div class="seaocore_dropdown_menu_wrapper">
                                                    <div class="seaocore_dropdown_menu">
                                                        <ul>  
                                                            <li>
                                    <?php
                                      if ($this->viewer()->getIdentity() &&
                                            ( $this->activity_moderate ||
                                            ("user" == $reply->poster_type && $this->viewer()->getIdentity() == $reply->poster_id) ||
                                            ("user" !== $reply->poster_type && Engine_Api::_()->getItemByGuid($reply->poster_type . "_" . $reply->poster_id)->isOwner($this->viewer()))  )):
                                        ?>

                                       <?php 
                                       $attachMentArray  = array();
                                       if (!empty($reply->attachment_type) && null !== ($attachment = $this->item($reply->attachment_type, $reply->attachment_id))): ?>
                                        <?php $attachMentArray = array(
                                            "type" => $reply->attachment_type,
                                            "guid"=> $attachment->getGuid(),
                                            "id" => $reply->attachment_id,
                                            "src" => $attachment->getPhotoUrl()
                                        );?>
                                          <?php if($reply->attachment_type == 'album_photo'):?>
                                          <?php $status = true; ?>
                                          <?php $photo_id = $attachment->photo_id; ?>
                                          <?php $album_id = $attachment->album_id; ?>
                                          <?php $src = $attachment->getPhotoUrl(); ?>
                                          <?php $attachMentArray = array_merge(array("status" => $status, "photo_id"=> $photo_id , "album_id" => $attachment->album_id, "src" => $src), $attachMentArray);?>
                                          <?php endif;?>
                                        <?php endif;?>

                                                                <script type="text/javascript">
                                                                    en4.core.runonce.add(function () {
                                                                        replyAttachment.editReply['<?php echo $reply->comment_id ?>'] = {'body': '<?php echo $this->string()->escapeJavascript($reply->body);?>', 'attachment_body':<?php echo Zend_Json_Encoder::encode($attachMentArray);?>}
                                                                    });
                                                                </script>
                                                                <a href='javascript:void(0);' title="<?php echo $this->translate('Edit') ?>" onclick="en4.nestedcomment.nestedcomments.showReplyEditForm('<?php echo $reply->comment_id?>', '<?php echo ($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? 0 : 1; ?>');"><?php echo $this->translate('Edit'); ?>  
                                                                </a>
                     								<?php endif ?>
                                                            </li>                                            

                                    <?php
                                      if ($this->viewer()->getIdentity() &&
                                            ( $this->activity_moderate ||
                                            ("user" == $reply->poster_type && $this->viewer()->getIdentity() == $reply->poster_id) ||
                                            ("user" !== $reply->poster_type && Engine_Api::_()->getItemByGuid($reply->poster_type . "_" . $reply->poster_id)->isOwner($this->viewer()))  )):
                                        ?>

                                                            <li>
                                      <?php /* echo $this->htmlLink(array(
                                        'route'=>'default',
                                        'module'    => 'advancedactivity',
                                        'controller'=> 'index',
                                        'action'    => 'delete',
                                        'action_id' => $action->action_id,
                                        'comment_id'=> $reply->comment_id,
                                        ),'', array('class' => 'smoothbox
                                        aaf_icon_remove','title'=>$this->translate('Delete Reply'))) */ ?>
                                                                <a href="javascript:void(0);" title="<?php echo $this->translate('Delete')?>" onclick="deletereply('<?php echo $action->action_id ?>', '<?php echo $reply->comment_id ?>', '<?php echo $this->escape($this->url(array('route' => 'default', 'module' => 'advancedactivity', 'controller' => 'index', 'action' => 'delete'))) ?>')"><?php echo $this->translate('Delete') ?></a>
                                                            </li>
                                       <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <span class="seaocore_comment_dropbox"></span>
                                            </span>
                                        </span>
                          <?php endif;?>
                                        <div class="comments_author_photo">
                                  <?php
                                  echo $this->htmlLink($this->item($reply->poster_type, $reply->poster_id)->getHref(), $this->itemPhoto($this->item($reply->poster_type, $reply->poster_id), 'thumb.icon', $action->getSubject()->getTitle()), array('class' => 'sea_add_tooltip_link', 'rel' => $this->item($reply->poster_type, $reply->poster_id)->getType() . ' ' . $this->item($reply->poster_type, $reply->poster_id)->getIdentity())
                                  )
                                  ?>
                                        </div>
                                        <div class="comments_info">
                                            <span class='comments_author'>
                                    <?php
                                    echo $this->htmlLink($this->item($reply->poster_type, $reply->poster_id)->getHref(), $this->item($reply->poster_type, $reply->poster_id)->getTitle(), array('class' => 'sea_add_tooltip_link', 'rel' => $this->item($reply->poster_type, $reply->poster_id)->getType() . ' ' . $this->item($reply->poster_type, $reply->poster_id)->getIdentity())
                                    );
                                    ?> 
                                            </span>
                                            <span class="comments_body" id="reply_body_<?php echo $reply->comment_id ?>">
                                <?php 
                                    include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_replyBody.tpl';
                                ?>    
                                            </span>
                                            <div id="reply_edit_<?php echo $reply->comment_id ?>" style="display: none;" class="reply_edit"><?php include APPLICATION_PATH . '/application/modules/Nestedcomment/views/scripts/_editReply.tpl' ?>
                                            </div>

                                    <?php if (!empty($reply->attachment_type) && null !== ($attachment = $this->item($reply->attachment_type, $reply->attachment_id))): ?>
                                            <div class="seaocore_comments_attachment seaocore_comments_attachment_<?php echo $reply->attachment_type?>" id="seaocore_comments_attachment_<?php echo $reply->comment_id ?>">
                                                <div class="seaocore_comments_attachment_photo">
                                      <?php if (null !== $attachment->getPhotoUrl()): ?>
                                       <?php if (SEA_ACTIVITYFEED_LIGHTBOX && strpos($reply->attachment_type, '_photo')): ?>
                                            <?php echo $this->htmlLink($attachment->getHref(), $this->itemPhoto($attachment, 'thumb.normal', $attachment->getTitle(), array('class' => 'thumbs_photo')), array('onclick' => 'openSeaocoreLightBox("' . $attachment->getHref() . '");return false;')) ?>
                                             <?php else:?>
                                             <?php echo $this->htmlLink($attachment->getHref(), $this->itemPhoto($attachment, 'thumb.normal', $attachment->getTitle(), array('class' => 'thumbs_photo'))) ?>
                                             <?php endif;?>
                  <?php endif; ?>
                                                </div>
                                                <div class="seaocore_comments_attachment_info">
                                                    <div class="seaocore_comments_attachment_title">
                  <?php echo $this->htmlLink($attachment->getHref(array('message' => $reply->comment_id)), $attachment->getTitle()) ?>
                                                    </div>
                                                    <div class="seaocore_comments_attachment_des">
                  <?php echo $attachment->getDescription() ?>
                                                    </div>
                                                </div>
                                            </div>
                              <?php endif; ?>
                                            <ul class="comments_date">
                                    <?php if ($canComment):?>

                                      <?php if($showAsLike):?>
                                            <?php $isLiked = $reply->likes()->isLike($this->viewer());?>
                                                <li class="comments_like"> 

                                              <?php if (!$isLiked): ?>
                                                    <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title="<?php echo $this->translate('unlike') ?>">
                                                  <?php echo $this->translate('like') ?>
                                                    </a>
                                              <?php else: ?>
                                                    <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title="<?php echo $this->translate('like') ?>">
                                                  <?php echo $this->translate('unlike') ?>
                                                    </a>
                                              <?php endif ?>
                                                </li>
                                      <?php else:?>

                                      <?php $isLiked = $reply->likes()->isLike($this->viewer());?> 
                                         <?php if(!$isLiked) :?>
                                                <li class="comments_like"> 

                                        <?php if($showLikeWithoutIconInReplies == 0):?>     
                                                    <a class='nstcomment_like' href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                           <?php echo $this->translate('like') ?>
                                                    </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                                    <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                           <?php echo $this->translate('like') ?>
                                                    </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                                    <a class='nstcomment_like' href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>    
                                                    </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 3):?>
                                         <?php if ($reply->likes()->getLikeCount() > 0): ?>
                                            <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'likelist', 'resource_type' => $reply->getType(), 'resource_id' => $reply->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $reply->likes()->getLikeCount();?></a>
                                       <?php endif ?>
                                                    <a href="javascript:void(0)" onclick="en4.advancedactivity.like(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                                        <img title="<?php echo $this->translate('Vote up');?>" src="application/modules/Nestedcomment/externals/images/arrow-up.png" />
                                                    </a>
                                         <?php endif;?>
                                                </li>
                                       <?php else:?>
                                                <li class="comments_like nstcomment_wrap"> 

                                    <?php if($showLikeWithoutIconInReplies == 0):?> 
                                                    <img src="application/modules/Nestedcomment/externals/images/like_light.png" />
                                      <?php echo $this->translate('like') ?>
                                    <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                       <?php echo $this->translate('like') ?>
                                    <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                                    <img src="application/modules/Nestedcomment/externals/images/like_light.png" />
                                    <?php elseif($showLikeWithoutIconInReplies == 3):?>
                                    <?php if ($reply->likes()->getLikeCount() > 0): ?>
                                            <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'likelist', 'resource_type' => $reply->getType(), 'resource_id' => $reply->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $reply->likes()->getLikeCount();?></a>
                                       <?php endif ?>
                                                    <img title="<?php echo $this->translate('Vote up');?>" src="application/modules/Nestedcomment/externals/images/arrow-up_light.png" />
                                    <?php endif;?>
                                                </li>
                                       <?php endif;?>
                                       <?php $isDisLiked = Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->isDislike($reply, $this->viewer())?>
                                       <?php if(!$isDisLiked) :?>
                                                <li class="comments_unlike"> 
                                                    &#183; 
                                        <?php if($showLikeWithoutIconInReplies == 0):?>     
                                                    <a class='nstcomment_unlike' href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                           <?php echo $this->translate('dislike') ?>
                                                    </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                                    <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                           <?php echo $this->translate('dislike') ?>
                                                    </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                                    <a class='nstcomment_unlike' href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>    
                                                    </a>
                                         <?php elseif($showLikeWithoutIconInReplies == 3):?>
                                         <?php if(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply ) && !$showAsLike):?>
                                       <?php if($showDislikeUsers) :?>
                                          <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'dislikelist', 'resource_type' => $reply->getType(), 'resource_id' => $reply->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )?></a>
                                                <?php else:?>
                                                    <?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )?>
                                                 <?php endif;?>
                                        <?php endif;?>
                                                    <a href="javascript:void(0)" onclick="en4.advancedactivity.unlike(this,<?php echo sprintf("'%d', %d", $action->getIdentity(), $reply->getIdentity()) ?>);" action-title='<img src="application/modules/Seaocore/externals/images/core/loading.gif" />'>
                                                        <img title="<?php echo $this->translate('Vote down');?>" src="application/modules/Nestedcomment/externals/images/arrow-down.png" />
                                                    </a>
                                         <?php endif;?>
                                                </li>
                                      <?php else:?>
                                                <li class="comments_unlike nstcomment_wrap">

                                                    &#183;  
                                    <?php if($showLikeWithoutIconInReplies == 0):?> 
                                                    <img src="application/modules/Nestedcomment/externals/images/dislike_light.png" />
                                      <?php echo $this->translate('dislike') ?>
                                    <?php elseif($showLikeWithoutIconInReplies == 1):?>
                                       <?php echo $this->translate('dislike') ?>
                                    <?php elseif($showLikeWithoutIconInReplies == 2):?>
                                                    <img src="application/modules/Nestedcomment/externals/images/dislike_light.png" />
                                    <?php elseif($showLikeWithoutIconInReplies == 3):?>
                                    <?php if(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply ) && !$showAsLike):?>
                                       <?php if($showDislikeUsers) :?>
                                          <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'dislikelist', 'resource_type' => $reply->getType(), 'resource_id' => $reply->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )?></a>
                                                <?php else:?>
                                                <?php echo Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )?>
                                                <?php endif;?>
                                        <?php endif;?>
                                                    <img title="<?php echo $this->translate('Vote down');?>" src="application/modules/Nestedcomment/externals/images/arrow-down_light.png" />
                                    <?php endif;?>
                                                </li>
                                       <?php endif;?>

                                      <?php endif;?>
                                    <?php endif ?>

                                    <?php if($showLikeWithoutIconInReplies != 3):?>
                                        <?php if ($reply->likes()->getLikeCount() > 0): ?>
                                                <li class="comments_likes_total">  
                                              <?php if($canComment || $this->viewer()->getIdentity()) :?>
                                                    &#183;
                                   <?php endif;?> 

                                    <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'likelist', 'resource_type' => $reply->getType(), 'resource_id' => $reply->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $this->translate(array('%s likes this.', '%s like this.', $reply->likes()->getLikeCount()), $this->locale()->toNumber($reply->likes()->getLikeCount()));?></a>

                                                </li>
                                        <?php endif ?>

                                        <?php if (Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply ) > 0 && !$showAsLike): ?>
                                                <li class="comments_likes_total"> 

                                              <?php if($canComment || $this->viewer()->getIdentity()) :?>
                                                    &#183;
                                   <?php endif;?>
                                              <?php if($showDislikeUsers) :?>
                                                <?php $url = $this->url(array('module' => 'nestedcomment', 'controller' => 'like', 'action' => 'dislikelist', 'resource_type' => $reply->getType(), 'resource_id' => $reply->getIdentity(), 'call_status' => 'public'), 'default', true);?>
                                                    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url;?>')"><?php echo $this->translate(array('%s dislikes this.', '%s dislike this.', Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )), $this->locale()->toNumber(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )))?></a>
                                                <?php else:?>
                                                    <?php echo $this->translate(array('%s dislikes this.', '%s dislike this.', Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )), $this->locale()->toNumber(Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply )))?>
                                                <?php endif;?>
                                                </li>
                                        <?php endif ?>
                                    <?php endif ?>
                                                <li class="comments_timestamp"> 
                                         <?php if ((Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount( $reply ) > 0 && !$showAsLike) || ($reply->likes()->getLikeCount() > 0) || ($this->viewer()->getIdentity() && $canComment)): ?>
                                                    &#183;
                                <?php endif ?>
                                      <?php echo $this->timestamp($reply->creation_date); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                            <?php endforeach;?>
                            <?php endif ?>

                                </ul>
                            </div>

                        </li> 

             					<?php if ($canComment && $showAsNested): ?>      
                        <?php $replyFormId = $this->replyForm->getAttrib('id');?>
                        <li id='feed-reply-form-open-li_<?php echo $comment->comment_id ?>' onclick='showReplyBox("<?php echo $replyFormId?>");' <?php echo '
                                document.getElementById("' . $this->replyForm->submit->getAttrib('id') . '").style.display = "' . (($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? "block" : "none") . '";
                                document.getElementById("feed-reply-form-open-li_' . $comment->comment_id . '").style.display = "none";
                                document.getElementById("' . $this->replyForm->body->getAttrib('id') . '").focus();' ?>' <?php if (!$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment): ?> style="display:none;"<?php endif; ?> style="display:none;">                  				<div></div>
                            <div class="nested_user_photo">
                                <div class="comment_form_user_photo">
                                  <?php echo $this->itemPhoto($this->viewer(), 'thumb.icon') ?>
                                 </div>
                            <div class="seaocore_comment_box seaocore_txt_light"><?php echo $this->translate('Write a reply...') ?></div></div>
                        </li>
                      <?php endif;?>

                      <?php if ($canComment && $showAsNested) echo $this->replyForm->render();?>  

                      <?php endforeach; ?>
                      	<?php if (!$commentReverseOrder && $canComment): ?>
                          <?php $commentFormId = $this->commentForm->getAttrib('id');?>
                          <?php $commentFormBodyId = $this->commentForm->body->getAttrib('id');?>
                        <li id='feed-comment-form-open-li_<?php echo $actionBaseId ?>' onclick='<?php echo '
                                document.getElementById("' . $this->commentForm->submit->getAttrib('id') . '").style.display = "' . (($this->isMobile || !$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment) ? "block" : "none") . '";
                                document.getElementById("feed-comment-form-open-li_' . $actionBaseId . '").style.display = "none";
                                document.getElementById("' . $this->commentForm->body->getAttrib('id') . '").focus();' ?> showCommentBox("<?php echo $commentFormId?>", "<?php echo $commentFormId?>");' <?php if (!$this->commentShowBottomPost || Engine_Api::_()->getApi('settings', 'core')->core_spam_comment): ?> style="display:none;"<?php endif; ?> >                  <div></div>
                                <div class="nested_user_photo">
                          <div class="comment_form_user_photo">
                            <?php echo $this->itemPhoto($this->viewer(), 'thumb.icon') ?>
                           </div>
                            <div class="seaocore_comment_box seaocore_txt_light"><?php echo $this->translate('Write a comment...') ?></div></div></li>
                        <?php endif; ?>
                      <?php endif; ?>
                    <?php endif; ?> 
                <?php if ($commentReverseOrder && $action->comments()->getCommentCount() > 5 && !$this->viewAllComments): ?>
                                       <li>
                                           <div></div>
                                           <div class="comments_viewall" id="comments_viewall">
                                           <?php if (0): ?>
                                             <?php
                                             echo $this->htmlLink($action->getHref(array('show_comments' => true)), $this->translate(array('View all %s comment', 'View all %s comments', $action->comments()->getCommentCount()), $this->locale()->toNumber($action->comments()->getCommentCount())))
                                             ?>
                                           <?php else: ?>
                                             <?php
                                             echo $this->htmlLink('javascript:void(0);', $this->translate(array('View all %s comment', 'View all %s comments', $action->comments()->getCommentCount()), $this->locale()->toNumber($action->comments()->getCommentCount())), array('onclick' => 'en4.advancedactivity.viewComments(' . $action->action_id . ');'))
                                             ?>
                                           <?php endif; ?>
                                           </div>
                                           <div style="display:none;" id="show_view_all_loading">
                                               <img src="application/modules/Seaocore/externals/images/core/loading.gif" alt="Loading" />
                                           </div>
                                       </li>
                                     <?php endif; ?> 
                    </ul>
                <?php if (!$commentReverseOrder && $canComment) echo $this->commentForm->render(); ?>

                </div>
            <?php endif; ?>
<script type="text/javascript">

    function deletefeed(action_id, comment_id, action_link) {
        if (comment_id == 0) {

            var msg = "<div class='aaf_show_popup'><h3>" + "<?php echo $this->translate('Delete Activity Item?') ?>" + "</h3><p>" + "<?php echo $this->string()->escapeJavascript($this->translate('Are you sure that you want to delete this activity item? This action cannot be undone.')) ?>" + "</p>" + "<button type='submit' onclick='content_delete_act(" + action_id + ", 0); return false;'>" + "<?php echo $this->string()->escapeJavascript($this->translate('Delete')) ?>" + "</button>" + " <?php echo $this->string()->escapeJavascript($this->translate('or')) ?> " + "<a href='javascript:void(0);'onclick='AAFSmoothboxClose();'>" + "<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>" + "</a></div>"

        } else {
            var msg = "<div class='aaf_show_popup'><h3>" + "<?php echo $this->string()->escapeJavascript($this->translate('Delete Comment?')) ?>" + "</h3><p>" + "<?php echo $this->string()->escapeJavascript($this->translate('Are you sure that you want to delete this comment? This action cannot be undone.')) ?>" + "</p>" + "<button type='submit' onclick='content_delete_act(" + action_id + "," + comment_id + "); return false;'>" + "<?php echo $this->string()->escapeJavascript($this->translate('Delete')) ?>" + "</button>" + " <?php echo $this->string()->escapeJavascript($this->translate('or')) ?> " + "<a href='javascript:void(0);'onclick='AAFSmoothboxClose();'>" + "<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>" + "</a></div>"
        }
        Smoothbox.open(msg);
    }


    function deletereply(action_id, comment_id, action_link) {

        var msg = "<div class='aaf_show_popup'><h3>" + "<?php echo $this->string()->escapeJavascript($this->translate('Delete Reply?')) ?>" + "</h3><p>" + "<?php echo $this->string()->escapeJavascript($this->translate('Are you sure that you want to delete this reply? This action cannot be undone.')) ?>" + "</p>" + "<button type='submit' onclick='content_reply_delete_act(" + action_id + "," + comment_id + "); return false;'>" + "<?php echo $this->string()->escapeJavascript($this->translate('Delete')) ?>" + "</button>" + " <?php echo $this->string()->escapeJavascript($this->translate('or')) ?> " + "<a href='javascript:void(0);'onclick='AAFSmoothboxClose();'>" + "<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>" + "</a></div>"
        Smoothbox.open(msg);
    }


</script>

<script type="text/javascript">
    var content_delete_act = function (action_id, comment_id) {
        if (comment_id == 0) {
            if ($('activity-item-' + action_id))
                $('activity-item-' + action_id).destroy();
        } else {
            if ($('comment-' + comment_id))
                $('comment-' + comment_id).destroy();

            if ($$('.reply' + comment_id))
                $$('.reply' + comment_id).destroy();

            if ($('feed-reply-form-open-li_' + comment_id))
                $('feed-reply-form-open-li_' + comment_id).destroy();

            if ($('activity-reply-form-' + comment_id))
                $('activity-reply-form-' + comment_id).destroy();

        }
        AAFSmoothboxClose();
        url = en4.core.baseUrl + 'advancedactivity/index/delete';
        var request = new Request.JSON({
            'url': url,
            'method': 'post',
            'data': {
                'format': 'json',
                'action_id': action_id,
                'comment_id': comment_id,
                'subject': en4.core.subject.guid
            }
        });
        request.send();
    }

    var content_reply_delete_act = function (action_id, comment_id) {
        if (comment_id == 0) {
            if ($('activity-item-' + action_id))
                $('activity-item-' + action_id).destroy();
        } else {
            if ($('reply-' + comment_id))
                $('reply-' + comment_id).destroy();
        }
        AAFSmoothboxClose();
        url = en4.core.baseUrl + 'advancedactivity/index/delete';
        var request = new Request.JSON({
            'url': url,
            'method': 'post',
            'data': {
                'format': 'json',
                'action_id': action_id,
                'comment_id': comment_id,
                'subject': en4.core.subject.guid
            }
        });
        request.send();
    }

    function showLinkPost(url) {
        url = '<?php echo ((!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] ?>' + url;
        var content = '<div class="aaf_gtp_pup"><h3><?php echo $this->string()->escapeJavascript($this->translate('Link to this Feed')) ?></h3><div class="aaf_gtp_feed_url">\n\
<p><?php echo $this->string()->escapeJavascript($this->translate('Copy this link to send this feed to others:')) ?></p>\n\
<div>\n\
<input type="text" id="show_link_post_input"  value="' + url + '" readonly="readonly"><span class="bold" style="margin-left:10px;"><a href="' + url + '" target="_blank" noreferrer="true"><?php echo $this->string()->escapeJavascript($this->translate('Go!')) ?> </a></span></div>\n\
</div>\n\
<div>\n\
<p><button name="close" onclick="AAFSmoothboxClose()"><?php echo $this->string()->escapeJavascript($this->translate('Close')) ?></button></p>\n\
</div>\n\
</div>';
        Smoothbox.open(content);
        $('show_link_post_input').select();
    }
    function AAFSmoothboxClose() {
        if (typeof parent.Smoothbox == 'undefined') {
            Smoothbox.close();
        } else {
            parent.Smoothbox.close();
        }
    }
</script>

<?php  include APPLICATION_PATH . '/application/modules/Nestedcomment/views/scripts/_smilies.tpl' ?>
<style type="text/css">
    .seao_advcomment + form .compose-body #compose-photo-form-fancy-file,
    .seao_advcomment .compose-body #compose-photo-form-fancy-file{
        right: 35px;
    }
</style>
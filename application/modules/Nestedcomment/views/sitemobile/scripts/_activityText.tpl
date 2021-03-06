<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemobile
 * @copyright  Copyright 2014-2015 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _activityText.tpl 6590 2013-06-03 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
if (empty($this->actions)) {
  echo $this->translate("The action you are looking for does not exist.");
  return;
} else {
  $actions = $this->actions;
}
?>
<?php //GET VERSION OF SITEMOBILE APP.
  $addClassDone = true;
  if(Engine_Api::_()->sitemobile()->isApp()) {
    if(!Engine_Api::_()->nestedcomment()->checkVersion(Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitemobileapp')->version, '4.8.6p1'))
      $addClassDone = false;
  }

?>

<?php
  include_once APPLICATION_PATH . '/application/modules/Nestedcomment/views/sitemobile/scripts/_activitySettings.tpl';
  
 // if(!$showAsNested) {
      //$showLikeWithoutIcon = 1;
  //}
  
  //if($showAsLike)
      //$showLikeWithoutIconInReplies = 1;
?>

<script type="text/javascript">
  sm4.core.runonce.add(function(){
    sm4.activity.setPhotoScroll(0);
    <?php if ($this->allowReaction): ?>
      sm4.sitereaction.attachReaction();
    <?php endif; ?>
     setTimeout(function() {
        $('#activity-feed-sitefeed').find('.feed_item_option_share .aaf_share_toolbar .ui-link').each(function(i, el) {
          $(el).removeClass('ui-link').removeClass('ui-btn');
        });
    }, 500);
    $('#activity-feed-sitefeed').find('.feed_item_option_share .aaf_share_toolbar .web-share').off('vclick').on('vclick', function(e) {
       e.preventDefault();
       ActivityAppCommentPopup($(this).attr('href'), "feedsharepopup");
    });
  });
  var like_commentURL = "<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment'), 'default', 'true'); ?>"
  var enabledModuleForMobile = 1;
  var showAsLike = '<?php echo $showAsLike;?>';
  var showLikeWithoutIconInReplies = '<?php echo $showLikeWithoutIconInReplies;?>';
  var showLikeWithoutIcon = '<?php echo $showLikeWithoutIcon;?>';
  var showDislikeUsers = '<?php echo $showDislikeUsers;?>';
</script>
<?php if ($this->viewer()->getIdentity() && !$this->feedOnly && !$this->onlyactivity): ?>
  <script type="text/javascript">
    var unhideReqActive = false;
    hideItemFeeds = function(type,id,parent_type,parent_id,parent_html, report_url){
      $.mobile.loading().loader("show");
      var url = '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'feed', 'action' => 'hide-item'), 'default', true); ?>';
      sm4.core.request.send({ 
        type: "GET", 
        dataType: "json",
        url : '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'feed', 'action' => 'hide-item'), 'default', true); ?>',
        data : {
          format : 'json',
          type : type,
          id : id
        },
        success : function(responseJSON, textStatus, xhr) {
          $.mobile.loading().loader("hide");
          $('#activity-item-'+ id).css('display', 'none');
          if(type=='activity_action' && $('activity-item-'+id)) {

            if($('#activity-item-undo-'+ id))
              $('#activity-item-undo-'+id).remove();
            var innerHTML = "<div class='feed_item_hide'>"
              +"<b><?php echo $this->string()->escapeJavascript($this->translate("This story is now hidden from your Activity Feed.")) ?></b>" +" <a href='javascript:void(0);' onclick='unhideItemFeed(\""+type+"\" , \""+id+"\" , \""+parent_id+"\")' class='ui-link'>" +"<?php echo $this->string()->escapeJavascript($this->translate("Undo")) ?> </a> <br /> ";
            if (report_url==''){
              innerHTML= innerHTML+"<span> <a href='javascript:void(0);' class='ui-link' onclick='hideItemFeeds(\""+parent_type+"\" , \""+parent_id+"\",\"\",\""+id+"\", \""+parent_html+"\",\"\")'>" 
                +'<?php echo
  $this->string()->escapeJavascript($this->translate('Hide all by ')); ?>'+parent_html+"</a></span>";
            }

            else{
              innerHTML= innerHTML  +"<span> <?php echo $this->string()->escapeJavascript($this->translate("To mark it offensive, please ")) ?> <a href=\""+report_url + "\" class='smoothbox ui-link'>" +"<?php echo $this->string()->escapeJavascript($this->translate("file a report")) ?>"+"</a>" +"<?php echo '.' ?>"+"</span>";
            }

            innerHTML=innerHTML+"</div>";
          } else{
            if($('#activity-item-undo-'+parent_id))
              $('#activity-item-undo-'+parent_id).remove();
            var innerHTML = "<div class='feed_item_hide'><b>"+sm4.core.language.translate("Stories from %s are hidden now and will not appear in your Activity Feed anymore.",parent_html) +"</b> <a href='javascript:void(0);' onclick='unhideItemFeed(\""+type+"\" , \""+id+"\" , \""+parent_id+"\")' class='ui-link'>"  +"<?php echo $this->string()->escapeJavascript($this->translate("Undo")) ?> </a></div>";            

            var className= '.Hide_'+type+'_'+id;
            var myElements = $(className);
            for(var i=0;i< myElements.length;i++){
              $(myElements[i]).css('display', 'none');
            }
          }
          if(type=='activity_action') {
            $('<li />', {
              'id' : 'activity-item-undo-'+ id,                    
              'html' : innerHTML

            }).inject($('#activity-item-'+id), 'after');
            sm4.activity.hideOptions(id);
          }
          else {
            $('<li />', {
              'id' : 'activity-item-undo-'+ parent_id,                    
              'html' : innerHTML

            }).inject($('#activity-item-'+parent_id), 'after');
            sm4.activity.hideOptions(parent_id);
          }
        }
      });
                 
    }
                
    unhideItemFeed= function(type,id, parent_id){
      if( unhideReqActive) return;
      $.mobile.loading().loader("show");
      unhideReqActive=true;
      var url = '<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'feed', 'action' => 'un-hide-item'), 'default', true); ?>';
      sm4.core.request.send({ 
        type: "GET", 
        dataType: "json",
        url : url,
        data : {
          format : 'json',
          type : type,
          id : id
        },
        success : function(responseJSON, textStatus, xhr) { 
          $.mobile.loading().loader("hide");
                     
          $('#activity-item-'+id).css('display', 'block');
          if(type=='activity_action' && $('#activity-item-'+id)){   
            $('#activity-item-'+id).css('display', 'block');
            if($('#activity-item-undo-'+id))
              $('#activity-item-undo-'+id).remove();
                       
          }else{        
            if($('#activity-item-undo-'+parent_id))
              $('#activity-item-undo-'+parent_id).remove();
            var className= '.Hide_'+type+'_'+id;
            var myElements = $(className);                
            for(var i=0;i< myElements.length;i++){
              $(myElements[i]).css('display', ''); 
            }              
          }
          unhideReqActive=false;
        }
      });
                 
    }

  </script>
<?php endif; ?> 

<?php if (!$this->feedOnly && !$this->onlyactivity): ?>
  <ul class='feeds' id="activity-feed-sitefeed">
  <?php endif ?>
  <?php $advancedactivityCoreApi = Engine_Api::_()->advancedactivity();
  $advancedactivitySaveFeed = Engine_Api::_()->getDbtable('saveFeeds', 'advancedactivity'); ?>
  <?php
  foreach ($actions as $action): // (goes to the end of the file)
    try { // prevents a bad feed item from destroying the entire page
      // Moved to controller, but the items are kept in memory, so it shouldn't hurt to double-check
      if (!$action->getTypeInfo()->enabled)
        continue;
      if (!$action->getSubject() || !$action->getSubject()->getIdentity())
        continue;
      if (!$action->getObject() || !$action->getObject()->getIdentity())
        continue;

      ob_start();
      if (!$this->noList && !$this->subject() && $action->getTypeInfo()->type == 'birthday_post'):
        echo $this->birthdayActivityLoopSM($action, array(
            'action_id' => $this->action_id,
            'viewAllComments' => $this->viewAllComments,
            'viewAllLikes' => $this->viewAllLikes,
            'commentShowBottomPost' => $this->commentShowBottomPost
        ));
        ob_end_flush();
        continue;
      endif;
      ?>
      <?php $item = (isset($action->getTypeInfo()->is_object_thumb) && !empty($action->getTypeInfo()->is_object_thumb)) ? $action->getObject() : $action->getSubject(); ?>
      <?php if (!$this->noList): ?>
        <li id="activity-item-<?php echo $action->action_id ?>" class="activty_ul_li <?php echo 'Hide_' . $item->getType() . "_" . $item->getIdentity() ?>" data-activity-feed-item="<?php echo $action->action_id ?>">
        <?php endif; ?>


        <?php // User's profile photo   ?>
        <div id="main-feed-<?php echo $action->action_id ?>">
          <?php
              echo $this->partial(
                'application/modules/Sitemobile/modules/Advancedactivity/views/scripts/_activityContent.tpl', null, array(
                'action' => $action,
                'isAttachment' => false,
                'similarActivities' => $this->similarActivities,
                'activity_moderate' => $this->activity_moderate,
                'is_owner' => $this->is_owner,
                'allow_delete' => $this->allow_delete,
                'allowEdit' => $this->allowEdit
                )
              )
              ?>
          <?php // Icon, time since, action links  ?>
          <?php
            $icon_type = 'activity_icon_' . $action->type;
            list($attachment) = $action->getAttachments();
            if (is_object($attachment) && $action->attachment_count > 0 && $attachment->item):
              $icon_type .= ' item_icon_' . $attachment->item->getType() . ' ';
            endif;
            $canComment = ( $action->getTypeInfo()->commentable && $action->commentable &&
                    $this->viewer()->getIdentity() &&
                    Engine_Api::_()->authorization()->isAllowed($action->getCommentObject(), null, 'comment') &&
                    !empty($this->commentForm) );
            ?>
          <?php $commentCount = $action->getComments($this->viewAllComments, true);?>
          <div class="feed_item_btm" style="text-transform:capitalize;">
            <?php if($this->hashtag && !empty($this->hashtag[$action->action_id])):?>
             <ul class="hashtag_activity_item">
              <?php $url = $this->url(array('controller' => 'index', 'action' => 'index'),"sitehashtag_general")."?search=";
                for ($i = 0; $i < count($this->hashtag[$action->action_id]); $i++) { ?>
                <li>
                  <a href="<?php echo $url.urlencode($this->hashtag[$action->action_id][$i]);?>"><?php  echo $this->hashtag[$action->action_id][$i]; ?></a>
                </li>
                <?php } ?>
              </ul>
            <?php endif; ?>
            <div class="like_comments_stats" data-reaction="<?php echo $this->allowReaction ?>">
                <?php echo $this->partial(
                    'application/modules/Sitemobile/modules/Advancedactivity/views/scripts/_activityStats.tpl',
                    null,
                    array(
                        'action' => $action,
                        'allowReaction' => $this->allowReaction,
                        'showAsLike' => $showAsLike,
                        'showLikeWithoutIcon' => $showLikeWithoutIcon
                    )
                );?>
              </div>
          </div>
          <?php $shareableItem = $action->getShareableItem(); ?>
          <div class="feed_item_option">
            <?php if ($canComment || ($shareableItem && $this->viewer()->getIdentity()) ): ?>          
              <div data-role="navbar" data-inset="false">
                <ul>
                  <?php if ($canComment): ?>

                   <?php if($showAsLike):?>
                        <?php if ($this->allowReaction): ?>
                        <li class="feed_item_option_reaction seao_icons_toolbar_attach">
                            <?php
                              echo $this->reactions($action, array(
                                'target' => $action->action_id,
                                'id' => 'like_'.$action->action_id,
                                'class' => 'aaf_like_toolbar',
                                ), true);
                            ?>
                        </li>
                        <?php else: ?>
                            <?php if ($action->likes()->isLike($this->viewer())): ?>
                              <li>
                                <a href="javascript:void(0);" onclick="javascript:sm4.activity.unlike('<?php echo $action->action_id ?>');">
                                  <i class="ui-icon ui-icon-thumbs-up-alt feed-unlike-icon feed-unliked-icon"></i>
                                  <span><?php echo $this->translate('Like') ?></span>
                                </a>
                              </li>
                            <?php else: ?>
                              <li>
                                <a href="javascript:void(0);" onclick="javascript:sm4.activity.like('<?php echo $action->action_id ?>');">
                                  <i class="ui-icon ui-icon-thumbs-up-alt feed-like-icon"></i>
                                  <span><?php echo $this->translate('Like') ?></span>
                                </a>
                              </li>
                            <?php endif; ?>
                        <?php endif; ?>  
                    <?php else:?>

                          <?php if($showLikeWithoutIcon != 3):?>
                            <?php if ($this->allowReaction): ?>
                                <li class="feed_item_option_reaction seao_icons_toolbar_attach">
                                <?php
                                  echo $this->reactions($action, array(
                                    'target' => $action->action_id,
                                    'id' => 'like_'.$action->action_id,
                                    'class' => 'aaf_like_toolbar',
                                    'unlikeDisable' => true,
                                    ), true);
                                ?>
                                </li>
                              <?php else: ?>
                                <?php if ($action->likes()->isLike($this->viewer())): ?>
                                  <li>
                                    <a>
                                      <i class="ui-icon ui-icon-thumbs-up-alt feed-unlike-icon feed-unliked-icon"></i>
                                      <span><?php echo $this->translate('Like') ?></span>
                                    </a>
                                  </li>
                                <?php else:?>
                                   <li>
                                      <a href="javascript:void(0);" onclick="javascript:sm4.activity.like('<?php echo $action->action_id ?>');">
                                      <i class="ui-icon ui-icon-thumbs-up-alt feed-like-icon"></i>
                                      <span><?php echo $this->translate('Like') ?></span>
                                      </a>
                                  </li>
                                <?php endif;?>
                              <?php endif;?>
                          <?php else :?>
                            <?php if ($action->likes()->isLike($this->viewer())): ?>
                              <li>
                                <a>
                                  <i class="ui-icon ui-icon-chevron-up feed-unlike-icon feed-unliked-icon"></i>
<!--                                  <span><?php //echo $this->translate('Vote Up') ?></span>-->
                                </a>
                              </li>
                            <?php else:?>
                               <li>
                                  <a href="javascript:void(0);" onclick="javascript:sm4.activity.like('<?php echo $action->action_id ?>');">
                                  <i class="ui-icon ui-icon-chevron-up feed-like-icon"></i>
<!--                                  <span><?php //echo $this->translate('Vote Up') ?></span>-->
                                  </a>
                              </li>
                            <?php endif;?>
                          <?php endif;?>

                          <?php if($showLikeWithoutIcon != 3):?>
                            <?php if ($action->dislikes()->isDislike($this->viewer())): ?>
                              <li>
                                <a>
                                  <i class="ui-icon ui-icon-thumbs-down-alt feed-unlike-icon feed-unliked-icon"></i>
                                  <span><?php echo $this->translate('Dislike') ?></span>
                                </a>
                              </li>
                            <?php else:?>
                               <li>
                                  <a href="javascript:void(0);" onclick="javascript:sm4.activity.dislike('<?php echo $action->action_id ?>');">
                                  <i class="ui-icon ui-icon-thumbs-down-alt feed-unlike-icon"></i>
                                  <span><?php echo $this->translate('Dislike') ?></span>
                                  </a>
                              </li>
                            <?php endif;?>
                          <?php else :?>
                           <?php if ($action->dislikes()->isDislike($this->viewer())): ?>
                              <li>
                                <a>
                                  <i class="ui-icon ui-icon-chevron-down feed-unlike-icon feed-unliked-icon"></i>
<!--                                  <span><?php //echo $this->translate('Vote Down') ?></span>-->
                                </a>
                              </li>
                            <?php else:?>
                               <li>
                                  <a href="javascript:void(0);" onclick="javascript:sm4.activity.dislike('<?php echo $action->action_id ?>');">
                                  <i class="ui-icon ui-icon-chevron-down feed-unlike-icon"></i>
<!--                                  <span><?php //echo $this->translate('Vote Down') ?></span>-->
                                  </a>
                              </li>
                            <?php endif;?>
                          <?php endif;?>

                    <?php endif;?>

                    <?php if (Engine_Api::_()->getApi('settings', 'core')->core_spam_comment): // Comments - likes   ?>
                      <li>
                        <a href="<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(), 'format' => 'smoothbox', 'showLikeWithoutIcon' => $showLikeWithoutIcon), 'default', 'true'); ?>">
                          <i class="ui-icon ui-icon-comment"></i>
                          <?php if($showLikeWithoutIcon == 3 && $showAsLike == 0):?>
                            
                          <?php else:?>
                            <span><?php echo $this->translate('Comment'); ?></span>
                          <?php endif;?>
                        </a>
                      </li>
                    <?php else: ?>
                      <li>
                        <a href="javascript:void(0);" onclick='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(),'writecomment'=>'true', 'showLikeWithoutIcon' => $showLikeWithoutIcon), 'default', 'true'); ?>" , "feedsharepopup", <?php echo $action->getIdentity();?>)'>
                          <i class="ui-icon ui-icon-comment"></i>
                          <?php if($showLikeWithoutIcon == 3 && $showAsLike == 0):?>
                            
                          <?php else:?>
                            <span><?php echo $this->translate('Comment'); ?></span>
                          <?php endif;?>
                        </a>
                      </li>
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if( $shareableItem && $this->viewer()->getIdentity() ): ?>
                    <?php if( Engine_Api::_()->getApi('settings', 'core')->getSetting('aaf.social.share.enable', 1) ) : ?>
                      <?php $shareIcons = $this->shareIcons($action, true) ?>
                      <li class="feed_item_option_share seao_icons_toolbar_attach seao_icons_toolbar_right"> 
                        <?php echo $shareIcons; ?> 
                        <a href="javascript::void()" class='share_icons_link'>
                          <i class="ui-icon ui-icon-share-alt"></i>
                          <?php if( $showLikeWithoutIcon == 3 && $showAsLike == 0 ): ?>
                          <?php else: ?>
                            <span><?php echo $this->translate('Share'); ?></span>
                          <?php endif; ?>
                        </a>
                      </li>
                    <?php else: ?>
                      <li>
                        <a href="javascript:void(0);" onclick ='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'share', 'type' => $shareableItem->getType(), 'id' => $shareableItem->getIdentity()), 'default', 'true'); ?>", "feedsharepopup")'>
                          <i class="ui-icon ui-icon-share-alt"></i>
                          <?php if( $showLikeWithoutIcon == 3 && $showAsLike == 0 ): ?>
                          <?php else: ?>
                            <span><?php echo $this->translate('Share'); ?></span>
                          <?php endif; ?>
                        </a>
                      </li>
                    <?php endif; ?>
                  <?php endif; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div> 
        </div>
          
       <?php // include the some preloaded comments for every activity feed ?>
          <?php if(Engine_Api::_()->sitemobile()->isApp()):?>
          <div style="display:none;">
            <div id="preloadedcomments_<?php echo $action->action_id;?>">
            <?php 
               echo $this->advancedActivitySM($action, array('commentForm' => $this->commentForm, 'canComment' => $canComment, 'viewAllComments' => $this->viewAllComments,), 'preloadcomments');
             ?>
            </div>
            <script type="text/javascript">
              sm4.activity.preloadedCommentArray['activity-comments_<?php echo $action->action_id;?>'] = $('#' + 'preloadedcomments_' + <?php echo $action->action_id;?>);
        $('#' + 'preloadedcomments_' + <?php echo $action->action_id;?>).remove();      
            </script>  
          </div>
        <?php endif;?>
        <div id="feed-options-<?php echo $action->action_id ?>" class="feed_item_option_box" style="display:none">
          <div class="feed_overlay" onclick="sm4.activity.hideOptions('<?php echo $action->action_id ?>');"></div>
          <?php
          $privacy_icon_class = null;
          $privacy_titile = null;
          $privacy_titile_array = array();
          ?>
          <?php if (!$this->subject() && $this->viewer()->getIdentity() && $action->getTypeInfo()->type != 'birthday_post' && (!$this->viewer()->isSelf($action->getSubject()))): ?>
            <?php if (!$this->subject()): ?>
              <?php if ($this->allowSaveFeed): ?>
                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateSaveFeed('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($advancedactivitySaveFeed->getSaveFeed($this->viewer(), $action->action_id)) ? 'Unsaved Feed' : 'Save Feed') ?>
                </a>
              <?php endif; ?>            

              <a href="javascript:void(0);" class="ui-btn-default ui-btn-action" onclick='hideItemFeeds("<?php echo $action->getType() ?>","<?php echo $action->getIdentity() ?>","<?php echo $item->getType() ?>","<?php echo $item->getIdentity() ?>","<?php echo $this->string()->escapeJavascript($item->getTitle()); ?>", "");'>
                <?php echo $this->translate('Hide'); ?>
              </a>
            <?php endif; ?>

            <a href="javascript:void(0);" class="ui-btn-default ui-btn-action" onclick='hideItemFeeds("<?php echo $action->getType() ?>","<?php echo $action->getIdentity() ?>","<?php echo $item->getType() ?>","<?php echo $item->getIdentity() ?>","<?php echo $this->string()->escapeJavascript($item->getTitle()); ?>", "<?php echo $this->url(array('module' => 'core', 'controller' => 'report', 'action' => 'create', 'subject' => $action->getGuid(), 'format' => 'smoothbox'), 'default', true); ?>");'>
              <?php echo $this->translate('Report Feed'); ?>
            </a>

            <?php if (!$this->subject()): ?>                        
              <a href="javascript:void(0);" class="ui-btn-default ui-btn-action" onclick='hideItemFeeds("<?php echo $item->getType() ?>","<?php echo $item->getIdentity() ?>","","<?php echo $action->getIdentity() ?>","<?php echo $this->string()->escapeJavascript($item->getTitle()); ?>","");'>
                <?php echo $this->translate('Hide all by %s', $item->getTitle()); ?>
              </a>
            <?php endif; ?>
            <?php
            if ($this->viewer()->getIdentity() && (
                    $this->activity_moderate || $this->is_owner || (
                    $this->allow_delete && (
                    ('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) ||
                    ('user' == $action->object_type && $this->viewer()->getIdentity() == $action->object_id)
                    )
                    )
                    )):
              ?>
              <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-danger" onclick="javascript:sm4.activity.activityremove(this);" data-url="<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'delete', 'action_id' => $action->action_id), 'default', 'true'); ?>" data-message="0-<?php echo $action->action_id ?>">
                <?php echo $this->translate('Delete Feed') ?>
              </a>

              <?php if ($action->getTypeInfo()->commentable): ?>

                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateCommentable('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($action->commentable) ? 'Disable Comments' : 'Enable Comments') ?>
                </a>

              <?php endif; ?>              
              <?php if ($action->getTypeInfo()->shareable > 1 || ($action->getTypeInfo()->shareable == 1 && $action->attachment_count == 1 && ($attachment = $action->getFirstAttachment()))): ?>

                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateShareable('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($action->shareable) ? 'Lock this Feed' : 'Unlock this Feed') ?>
                </a>

              <?php endif; ?>
            <?php endif; ?>

          <?php elseif ($this->allowEdit && !empty($action->privacy) && in_array($action->getTypeInfo()->type, array("post", "post_self", "status", 'sitetagcheckin_add_to_map', 'sitetagcheckin_content', 'sitetagcheckin_status', 'sitetagcheckin_post_self', 'sitetagcheckin_post', 'sitetagcheckin_checkin', 'sitetagcheckin_lct_add_to_map','post_self_photo','post_self_video','post_self_music','post_self_link')) && $this->viewer()->getIdentity() && (('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id))): ?>
            <a href="#privacyoptions-popup-<?php echo $action->getIdentity() ?>" data-rel="popup" class="ui-btn-default ui-btn-action"><?php echo $this->translate('Edit Privacy Setting') ?></a>

            <?php $privacy = $action->privacy ?>
            <div id="privacyoptions-popup-<?php echo $action->getIdentity() ?>" data-role="popup" >
              <?php foreach ($this->privacyDropdownList as $key => $value): ?>
                <?php if ($value == "separator"): ?>

                <?php elseif ($key == 'network_custom'): ?>
                  <a href="advancedactivity/index/add-more-list-network?action_id=<?php echo $action->getIdentity() ?>&format=smoothbox"  title="<?php echo $this->translate("Choose multiple Networks to share with."); ?>" data-role="button" data-mini="true"><?php echo $this->translate($value); ?></a>
                <?php elseif (strpos($key, "custom") !== false): ?>
                  <?php if ($key == 'custom_2'): ?>

                    <a href="advancedactivity/index/add-more-list?action_id=<?php echo $action->getIdentity() ?>&format=smoothbox" 
                       title="<?php echo $this->translate("Choose multiple Friend Lists to share with."); ?>" data-role="button" data-mini="true"><?php echo $this->translate($value); ?></a>
                     <?php else: ?>
                    <a href="javascript:void(0)" onclick="editPostStatusPrivacy('<?php echo $action->getIdentity() ?>','<?php echo $key ?>')"
                       title="<?php echo $this->translate("Choose multiple Friend Lists to share with."); ?>" data-role="button" data-mini="true"><?php echo $this->translate($value); ?></a>
                     <?php endif; ?>
                   <?php elseif (in_array($key, array("everyone", "networks", "friends", "onlyme"))): ?>
                     <?php
                     if ($key == $privacy):
                       $privacy_icon_class = "aaf_icon_feed_" . $key;
                       $privacy_titile = $value;

                     endif;
                     ?>
                  <a href="javascript:void(0)" class="<?php echo ( $key == $privacy ? 'ui-btn-active' : '' ) ?> user_profile_friend_list_<?php echo $key ?> aaf_custom_list" id="privacy_list_<?php echo $key ?>" onclick="editPostStatusPrivacy('<?php echo $action->getIdentity() ?>','<?php echo $key ?>')" title="<?php echo $this->translate("Share with %s", $this->translate($value)); ?>" data-role="button" data-mini="true" ><?php echo $this->translate($value); ?></a>
                <?php else: ?>
                  <?php
                  if ((in_array($key, explode(",", $privacy)))):
                    $privacy_titile_array[] = $value;
                  endif;
                  ?>
                  <a href="javascript:void(0)" class="<?php echo ( (in_array($key, explode(",", $privacy))) ? 'ui-btn-active' : '' ) ?> user_profile_friend_list_<?php echo $key ?>" id="privacy_list_<?php echo $key ?>" onclick="editPostStatusPrivacy('<?php echo $action->getIdentity() ?>','<?php echo $key ?>')" title="<?php echo $this->translate("Share with %s", $value); ?>" data-role="button" data-mini="true">

                    <?php echo $this->translate($value) ?>
                  </a>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <?php
            if (!empty($privacy_titile_array)):
              $privacy_titile = join(", ", $privacy_titile_array);
              if (Engine_Api::_()->advancedactivity()->isNetworkBasePrivacy($privacy)):
                $privacy_icon_class = (count($privacy_titile_array) > 1) ? "aaf_icon_feed_custom" : "aaf_icon_feed_network_list";
              else:
                $privacy_icon_class = (count($privacy_titile_array) > 1) ? "aaf_icon_feed_custom" : "aaf_icon_feed_list";
              endif;
            endif;
            ?>

            <?php if ($this->allowSaveFeed): ?>

              <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateSaveFeed('<?php echo $action->action_id ?>')">
                <?php echo $this->translate(($advancedactivitySaveFeed->getSaveFeed($this->viewer(), $action->action_id)) ? 'Unsaved Feed' : 'Save Feed') ?>
              </a>

            <?php endif; ?>

            <?php if ($this->activity_moderate || $this->allow_delete || $this->is_owner): ?>


              <?php /* echo $this->htmlLink(array(
                'route' => 'default',
                'module' => 'advancedactivity',
                'controller' => 'index',
                'action' => 'delete',
                'action_id' => $action->action_id
                ), $this->translate('Delete Feed'), array('class' => 'smoothbox')) */ ?>
              <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-danger" onclick="javascript:sm4.activity.activityremove(this);" data-url="<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'delete', 'action_id' => $action->action_id), 'default', 'true'); ?>" data-message="0-<?php echo $action->action_id ?>">
                <?php echo $this->translate('Delete Feed') ?>
              </a>

              <?php if ($action->getTypeInfo()->commentable): ?>

                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateCommentable('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($action->commentable) ? 'Disable Comments' : 'Enable Comments') ?>
                </a>

              <?php endif; ?>
              <?php if ($action->getTypeInfo()->shareable > 1 || ($action->getTypeInfo()->shareable == 1 && $action->attachment_count == 1 && ($attachment = $action->getFirstAttachment()))): ?> 
                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateShareable('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($action->shareable) ? 'Lock this Feed' : 'Unlock this Feed') ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>


          <?php else: ?>
            <?php
            if ($this->viewer()->getIdentity() && (
                    $this->activity_moderate || $this->is_owner || (
                    $this->allow_delete && (
                    ('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) ||
                    ('user' == $action->object_type && $this->viewer()->getIdentity() == $action->object_id)
                    )
                    )
                    )):
              ?>


              <?php if ($this->allowSaveFeed): ?>

                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateSaveFeed('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($advancedactivitySaveFeed->getSaveFeed($this->viewer(), $action->action_id)) ? 'Unsaved Feed' : 'Save Feed') ?>
                </a>

              <?php endif; ?> 
              <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-danger" onclick="javascript:sm4.activity.activityremove(this);" data-url="<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'delete', 'action_id' => $action->action_id), 'default', 'true'); ?>" data-message="0-<?php echo $action->action_id ?>">
                <?php echo $this->translate('Delete Feed') ?>
              </a>

              <?php if ($action->getTypeInfo()->commentable): ?>

                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateCommentable('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($action->commentable) ? 'Disable Comments' : 'Enable Comments') ?>
                </a>

              <?php endif; ?>
              <?php if ($action->getTypeInfo()->shareable > 1 || ($action->getTypeInfo()->shareable == 1 && $action->attachment_count == 1 && ($attachment = $action->getFirstAttachment()))): ?>

                <a href="javascript:void(0);" title="" class="ui-btn-default ui-btn-action" onclick="sm4.activity.updateShareable('<?php echo $action->action_id ?>')">
                  <?php echo $this->translate(($action->shareable) ? 'Lock this Feed' : 'Unlock this Feed') ?></a>

              <?php endif; ?>                 

            <?php endif; ?>
          <?php endif; ?>
          <a href="#" class="ui-btn-default" onclick="sm4.activity.hideOptions('<?php echo $action->action_id ?>');">
            <?php echo $this->translate("Cancel"); ?>
          </a>

        </div>



        <!--        ADD THE OPTIONS TO FEED OF ACTIONS..-->


        <?php if (!$this->noList): ?>
          <div style="clear:both;"></div>
        </li>
      <?php endif; ?>

      <?php
      ob_end_flush();
    } catch (Exception $e) {
      ob_end_clean();
      if (APPLICATION_ENV === 'development') {
        echo $e->__toString();
      }
    };
  endforeach;
  ?> 

  <?php if (!$this->feedOnly && !$this->onlyactivity): ?>
  </ul>

  <div data-role="popup" id="popupDialog" data-overlay-theme="a" data-theme="c" data-dismissible="false" style="max-width:400px;" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1><?php echo $this->translate('Delete Activity Item?'); ?></h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <h3 class="ui-title"></h3>
      <p><?php echo $this->translate('Are you sure that you want to delete this activity item? This action cannot be undone.') ?></p>

     <div style="margin-top:10px;"> <a href="#" data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" onclick="javascript:sm4.activity.activityremove()"><?php echo $this->translate("Delete"); ?></a>
      <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c"><?php echo $this->translate("Cancel"); ?></a></div>
    </div>
  </div>
  <div data-role="popup" id="popupDialog-Comment" data-overlay-theme="a" data-theme="c" data-dismissible="false" style="max-width:400px;" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1><?php echo $this->translate('Delete Comment?'); ?></h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <h3 class="ui-title"></h3>
      <p><?php echo $this->translate('Are you sure that you want to delete this comment? This action cannot be undone.'); ?></p>              

      <div style="margin-top:10px;"><a href="javascript:void(0);" data-role="button" data-inline="true" data-transition="flow" data-theme="b" onclick="javascript:sm4.activity.activityremove();<?php if($addClassDone):?> $.mobile.activePage.addClass('dnone');<?php endif;?>$.mobile.activePage.find('#popupDialog-Comment').popup('close')"><?php echo $this->translate("Delete"); ?></a>
      <a href="javascript:void(0);" data-role="button" data-inline="true" data-theme="c" onclick="<?php if($addClassDone):?> $.mobile.activePage.addClass('dnone'); <?php endif;?> $.mobile.activePage.find('#popupDialog-Comment').popup('close');"><?php echo $this->translate("Cancel"); ?></a></div>
    </div>
  </div>
  <div data-role="popup" id="popupDialog-Reply" data-overlay-theme="a" data-theme="c" data-dismissible="false" style="max-width:400px;" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1><?php echo $this->translate('Delete Reply?'); ?></h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <h3 class="ui-title"></h3>
      <p><?php echo $this->translate('Are you sure that you want to delete this reply? This action cannot be undone.'); ?></p>              

      <div style="margin-top:10px;"><a href="javascript:void(0);" data-role="button" data-inline="true" data-transition="flow" data-theme="b" onclick="javascript:sm4.activity.replyremove();<?php if($addClassDone):?> $.mobile.activePage.addClass('dnone');<?php endif;?>$.mobile.activePage.find('#popupDialog-Reply').popup('close')"><?php echo $this->translate("Delete"); ?></a>
      <a href="javascript:void(0);" data-role="button" data-inline="true" data-theme="c" onclick="<?php if($addClassDone):?> $.mobile.activePage.addClass('dnone'); <?php endif;?> $.mobile.activePage.find('#popupDialog-Reply').popup('close');"><?php echo $this->translate("Cancel"); ?></a></div>
    </div>
  </div>
  <?php endif ?>

  <script type="text/javascript">
//    if($('#popupDialog-Comment').length > 0) {
//      $('body').append($('#popupDialog-Comment'));
//    }
    ActivityAppCommentPopup = function(Url, popupid, action_id) {  
      
       if(sm4.core.isApp()) {
         if($.type(sm4.activity.preloadedCommentArray['activity-comments_' + action_id]) != 'undefined') 
          sm4.activity.preloadedCommentString = sm4.activity.preloadedCommentArray['activity-comments_' + action_id].html();
        else
          sm4.activity.preloadedCommentString = '';
         
       }
      sm4.activity.openPopup(Url, popupid, action_id);
    }
  </script>  
  
    <script type="text/javascript">
//    if($('#popupDialog-Comment').length > 0) {
//      $('body').append($('#popupDialog-Comment'));
//    }
    ActivityAppReplyPopup = function(Url, popupid, action_id) {  
        if(sm4.core.isApp()) {
         if($.type(sm4.activity.preloadedReplyArray['activity-replies_' + action_id]) != 'undefined') 
          sm4.activity.preloadedReplyArray = sm4.activity.preloadedReplyArray['activity-replies_' + action_id].html();
        else
          sm4.activity.preloadedReplyArray = '';
         
       }
      sm4.activity.openReplyPopup(Url, popupid, action_id);
      
    }
  </script>

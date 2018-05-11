/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: core.js 2016-07-07 00:00:00 SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
en4.sitereaction = {
  attachReaction: function() {
    $$('.aaf_like_toolbar').addEvent('click', function(event) {
      var el = $(event.target);
      if (!el.hasClass('icon-button-wapper')) {
        el = el.getParent('.icon-button-wapper');
      }
      var actionId = el.get('data-target');
      var reaction = el.get('data-type');
      var $likeEl = $('aaf_reaction_' + actionId);
      if ($likeEl.get('data-current-reaction') === reaction) {
        return;
      }
      var $i = $likeEl.getElement('i');
      el.getParent('.seao_icons_toolbar_wappper').addClass('dnone');
      en4.advancedactivity.like($likeEl, actionId, 0, reaction);
      $likeEl.innerHTML = el.get('data-title');
      if ($i) {
        $i.setStyle('backgroundImage', 'url('+ el.get('data-icon') +')');
        $i.inject($likeEl, 'top');
      }
    }.bind(this));
  },
  attachNestedCommentReaction: function(type, id, comment_id, order, parent_comment_id, option, taggingContent, showAsNested, showAsLike, showDislikeUsers, showLikeWithoutIcon, showLikeWithoutIconInReplies,page) {
    $$('.aaf_like_toolbar').addEvent('click', function(event) {
      var el = $(event.target);
      if (!el.hasClass('icon-button-wapper')) {
        el = el.getParent('.icon-button-wapper');
      }
      var reaction = el.get('data-type');
      var $likeEl = $('like_comments_' + type+ '_'+id);
      if ($likeEl.get('data-current-reaction') === reaction) {
          return;
      }
      el.getParent('.seao_icons_toolbar_wappper').addClass('dnone');
      en4.nestedcomment.nestedcomments.like(type, id, comment_id, order, parent_comment_id, option, taggingContent, showAsNested, showAsLike, showDislikeUsers, showLikeWithoutIcon, showLikeWithoutIconInReplies, page, reaction);
    }.bind(this));
  },
  unlikeReaction: function(el, actionId) {
    el.removeClass('bold');
    en4.advancedactivity.unlike(el, actionId);
  },
  likeReaction: function(el, actionId) {
    el.addClass('bold');
    en4.advancedactivity.like(el, actionId);
  },
};

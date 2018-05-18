<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if (!$this->isAjax): ?>
  <div class="seaocore_members_popup seaocore_members_popup_notbs reaction_tabs_wp">
    <div class="">
      <h2><?php echo $this->translate('People who reacted on this');?></h2>
      <?php echo $this->likeReactionsLink($this->subject, true, $this->reaction); ?>
    </div>
    <div class="seaocore_members_popup_content" id="aff_reaction_likes">
      <div id="aff_reaction_loading" class="seaocore_item_list_popup_loader dnone">
        <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/loading.gif' alt="Loading" />
      </div>
    <?php endif; ?>
    <?php $icons = $this->icons; ?>
    <ul id="aaf_reaction_users_list_<?php echo $this->reaction ? : 'all' ?>" class="aaf_reactions_users_list">
      <?php foreach ($this->paginator as $like): ?>
        <?php $owner = $like->getOwner() ?>
        <li class="item_member_list">
          <div class="item_member_thumb">
            <?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.icon')) ?>
            <?php if (isset($this->reactionIcons[$like->reaction])): ?>
              <i style="background-image: url(<?php echo $this->reactionIcons[$like->reaction]['icon'] ?>)" ></i>
            <?php endif; ?>
          </div>
          <?php if ($this->viewer()->getIdentity()): ?>
            <div class='item_member_option'>
              <?php echo $this->userFriendshipAjax($owner) ?>
            </div>
          <?php endif; ?>
          <div class='item_member_details'>
            <div class="item_member_name">
              <?php echo $this->htmlLink($owner->getHref(), $owner->getTitle()) ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php if (!$this->isAjax): ?>
    </div>
  </div>
  <div class="seaocore_members_popup_bottom">
    <button  onclick="javascript:SmoothboxSEAO.close()"><?php echo $this->translate("Close"); ?></button>
  </div>
  <script>
    en4.core.runonce.add(function() {
      $$('.aff_reaction_tab').addEvent('click', function(event) {
        event.stop();
        var el = $(event.target);
        if (!el.hasClass('aff_reaction_tab')) {
          el = $(event.target).getParent('.aff_reaction_tab');
        }
        el.getParent('.reaction_tabs').getElements('li').removeClass('active');
        el.getParent('li').addClass('active');
        $$('.aaf_reactions_users_list').addClass('dnone');
        if ($('aaf_reaction_users_list_' + el.get('data-target'))) {
          $('aaf_reaction_users_list_' + el.get('data-target')).removeClass('dnone');
          return;
        }
        $('aff_reaction_loading').removeClass('dnone');
        var request = new Request.HTML({
          url: el.href,
          data: {
            format: 'html',
            subject: en4.core.subject.guid,
            is_ajax: true
          },
          evalScripts: true,
          onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
            $('aff_reaction_loading').addClass('dnone');
            Elements.from(responseHTML).inject($('aff_reaction_likes'));
            $('aaf_reaction_users_list_' + el.get('data-target')).removeClass('dnone');
            en4.core.runonce.trigger();
            Smoothbox.bind('aff_reaction_likes');
          }
        });
        request.send();
      });
    });

  </script>
<?php endif; ?>

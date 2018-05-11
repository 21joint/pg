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
<script type="text/javascript">
  function faq_show(id) {
      if ($(id)) {
          if ($(id).style.display == 'block') {
              $(id).style.display = 'none';
          } else {
              $(id).style.display = 'block';
          }
      }
  }

<?php if ($this->faq_id): ?>
    window.addEvent('domready', function () {
        faq_show('<?php echo $this->faq_id; ?>');
    });
<?php endif; ?>
</script>
<div class="admin_seaocore_files_wrapper">
  <ul class="admin_seaocore_files seaocore_faq">
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');">How can I use / enable reactions & stickers for Content?</a>
      <div class='faq' style='display: none;' id='faq_1'>
        You need to place our "Comments and Replies" widget wherever you want users to give reactions and attach stickers. This widget comes from our plugin
        <a target="_blank"  href="https://www.socialengineaddons.com/socialengine-advanced-comments-plugin-nested-comments-replies-voting-attachments">
          Advanced Comments Plugin - Nested Comments, Replies, Voting & Attachments</a>, so you need to install this plugin.
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');">How can I use / enable reactions & stickers for activity feed?</a>
      <div class='faq' style='display: none;' id='faq_2'>
        For enabling reactions and stickers in activity feed you need to install our 
        <a target="_blank"  href="https://www.socialengineaddons.com/socialengine-advanced-comments-plugin-nested-comments-replies-voting-attachments">
          Advanced Comments Plugin - Nested Comments, Replies, Voting & Attachments (for stickers)</a> & 
        <a target="_blank"  href="https://www.socialengineaddons.com/socialengine-advanced-activity-feeds-wall-plugin">Advanced Activity Feeds / Wall Plugin</a> (for reactions)

      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');">Is there any setting by which I can show some stickers for particular duration?</a>
      <div class='faq' style='display: none;' id='faq_3'>
        Yes, you can set your data and time for particular sticker collections while creating / editing them from Manage Sticker Store tab.

      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');">Is there any limit for uploading Sticker collection?</a>
      <div class='faq' style='display: none;' id='faq_4'>
        No, you can upload as many sticker collection you want, there isn't any limitation.
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_5');">Can I upload more than 6 reactions for post / content?
      </a>
      <div class='faq' style='display: none;' id='faq_5'>
        Yes, there isn't any limit on reactions but we recommend you not to upload more than 8 reaction as it may spoil your website's UI.
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');">How users will be able to search the stickers? On which basis sticker search works?</a>
      <div class='faq' style='display: none;' id='faq_6'>
       Stickers will be searched on the basis of their "Search Words", entered while adding/editing sticker. So, you should keep the sticker's search words as, Love, Romance, Valentine, Hearts or Christmas, Santa, Tree, Stars. By this users can easily search the stickers using all the words (separated via comma) specified by admin.
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_7');">Is this plugin compatible with content modules?</a>
      <div class='faq' style='display: none;' id='faq_7'>
        Yes, users can react and attach stickers on content if you have placed our "Comments and Replies" widget on that content page. (Dependent on <a target="_blank"  href="https://www.socialengineaddons.com/socialengine-advanced-comments-plugin-nested-comments-replies-voting-attachments">Advanced Comments Plugin - Nested Comments, Replies, Voting & Attachments</a>)
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_8');">How can I display my sticker collection in Sticker Store?</a>
      <div class='faq' style='display: none;' id='faq_8'>
        You can simply do it while creating / editing sticker collection, If you want to display the current sticker collection in sticker store then disable the setting "Do you want this collection to be available to users in their sticker bucket by default?"
      </div>
    </li>
  </ul>
</div>

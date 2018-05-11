<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
  <?php if($this->target): ?>
    en4.core.runonce.add(function() {
      var faq = '<?php echo $this->target ?>';
      var id = $('faq_' + faq);
      if($(id)) {
        faq_show(id);
      }
    })
  <?php endif; ?>
</script>
<div class="admin_seaocore_files_wrapper">
  <ul class="admin_seaocore_files seaocore_faq sitemetatag_faq">
    <li>
      <?php $globalSetting = '' ?>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');">I am trying to enable / disable open graph and twitter card for various widgetized pages, but it is not working. What might be the reason behind it?</a>
      <div class='faq' style='display: none;' id='faq_1'>
        <?php echo "If you have disabled the open graph and twitter cards from ‘$globalSetting of this plugin then they won’t work for various widgetized pages of your website. To resolve this problem, go to ‘<a href='admin/sitemetatag/settings' target='_blank'>Global Settings</a>’ and enable open graph and twitter cards. " ;?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');">How can I set the default image for my website, various widgetized and non widgetized pages. How it will work?</a>
      <div class='faq' style='display: none;' id='faq_2'>
        Please follow steps mentioned below to set the images for:
          <ul>
            <li>Website: Go to ‘Global Settings’ → ‘Default Website’s Image’.</li>
            <li>Various Widgetized Pages: Go to ‘Manage Meta Tags’ → ‘Widgetized Pages’ → ‘Meta Image’.</li>
            <li>Non Widgetized Pages: Go to ‘Manage Meta Tags’ → ‘Non Widgetized Pages’ → ‘Meta Image’.</li>
          </ul>
        Now, let’s see how it works:
          <ul>
            <li>If user has uploaded an image for the content being shared on a social network then that image will be shared along with the content.</li>
            <li>If user has not uploaded any image for the content being shared then the image added by the site admin in ‘Manage Meta Tags’ → ‘Widgetized Pages’ → ‘Meta Image’ for the particular content type will be shared with the content.</li>
            <li>If user has not uploaded any image for the content being shared and the site admin also has not added any image in ‘Manage Meta Tags’ → ‘Widgetized Pages’ → ‘Meta Image’ then the image added in the ‘Global Settings → Default Website’s Image’ will be shared with the content.</li>
            <li>For non widgetized pages, if no image has been selected from ‘Manage Meta Tags’ → ‘Non Widgetized Pages’ → ‘Meta Image’ then the image added in the ‘Global Settings → Default Website’s Image’ will be shared with the non widgetized page.</li>
          </ul>
          <div>It is just a precaution that content being shared must have an image to make the post look more attractive.</div>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');">How different options will work for ‘Meta Title’ and ‘Meta Description’ as available in Global Settings?</a>
      <div class='faq' style='display: none;' id='faq_3'>
        There are four ways to use ‘Meta Title’ and ‘Meta Description’ for your website’s content being shared:
          <ul>
            <li>None: If you don’t want to show ‘Meta Title’ and ‘Meta Description’ alongwith the content’s title and description.</li>
            <li>Website Title / Description: To append only website’s title / description with the content’s title / description.</li>
            <li>Page Title / Description:  To append only the title / description added by site admin in ‘Manage Meta Tags’ → ‘Widgetized Pages’ → ‘Meta Title’ / ‘Meta Description’ with the content’s title / description.</li>
            <li>
              Both Website and Page Title / Description: 
              <ul>
                <li>When enabled, both website’s and page meta title / description will append to the title / description added by user for the specific content.</li>
                <li>If user has not added any title / description then the title / description added by site admin in ‘Manage Meta Tags’ → ‘Widgetized Pages’ → ‘Meta Title’ / ‘Meta Description’ for that particular page will be shown. And the website’s title / description will append to it.</li>
                <li>In case, user has not added any title / description and also site admin has not added any title / description in ‘Manage Meta Tags’ → ‘Widgetized Pages’ → ‘Meta Title’ / ‘Meta Description’ then only website’s title / description will be shown.</li>
              </ul>
            </li>
          </ul>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');">Why I am not able to get proper open graph and twitter card for certain pages / URLs?</a>
      <div class='faq' style='display: none;' id='faq_4'>
        Below are the reasons for the problem you are facing while getting open graph and twitter card for certain pages:
          <ul>
            <li>The page / URL you are trying to access is private.</li>
            <li>The page / URL you are trying to access requires login first.</li>
          </ul>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_5');">I am sharing a page again on facebook after doing some changes in that page. The changes are not reflecting in the page while sharing it again on facebook. What might be the reason behind it?</a>
      <div class='faq' style='display: none;' id='faq_5'>
        Facebook caches the page (URL) once you share it. So, if you are sharing the same page again then you have to first clear the facebook cache for that page’s URL. To so follow below steps:
          <ul>
            <li>Go to <a href='https://developers.facebook.com/tools/debug/sharing' target='_blank'> Facebook Sharing Debugger Tool</a>.</li>
            <li>Paste the URL of you page and click on ‘Debug’ button.</li>
            <li>To clear the cache for this page click on ‘Scrape Again’ button.</li>
            <li>Now you can share this URL on facebook and the changes done by you will reflect in the facebook post.</li>
          </ul>
      </div>
    </li>
  </ul>
</div>
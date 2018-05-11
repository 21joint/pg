<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Facebookse
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: search-console-faq.tpl 6590 2011-01-06 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>Ultimate SEO / Sitemaps Plugin</h2>
<?php if (count($this->navigation)): ?>
  <div class='seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<script type="text/javascript">
  function show_content(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
  <?php if($this->target): ?>
    en4.core.runonce.add(function() {
      var content = '<?php echo $this->target ?>';
      var id = $('content_' + content);
      if($(id)) {
        show_content(id);
      }
    })
  <?php endif; ?>
</script>
<div class="admin_seaocore_files_wrapper">
	<ul class="admin_seaocore_files seaocore_faq siteseo_faq siteseo_faq">
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_1');">What is Google Search Console and why to use it?</a>
      <div class='faq' style='display: none;' id='content_1'>
        <div><a href="https://www.google.com/webmasters/tools/" target="_blank"> Google Search Console</a> (previously Google Webmaster Tools) is a free service offered by Google that helps you monitor and maintain your site's presence in Google Search results. You don't have to sign up for Search Console for your site to be included in Google's search results, but doing so can help you understand how Google views your site and optimize its performance in search results. <a href="https://support.google.com/webmasters/" target="_blank"> Click here </a> to know more about Google Search Console.</div>
        <div>Google Search console is used for many purposes. Some of them are listed below.</div>
        <ul>
          <li>Submit and check a sitemap.</li>
          <li>Check and set the crawl rate, and view statistics about when Googlebot accesses a particular site.</li>
          <li>Write and check a robots.txt file to help discover pages that are blocked in robots.txt accidentally.</li>
          <li>List internal and external pages that link to the site.</li>
          <li>Get a list of links which Googlebot had difficulty crawling, including the error that Googlebot received when accessing the URLs in question.</li>
          <li>See what keyword searches on Google led to the site being listed in the SERPs, and the click through rates of such listings. (Previously named 'Search Queries'; rebranded May 20, 2015 to 'Search Analytics' with extended filter possibilities for devices, search types and date periods).</li>
          <li>Receive notifications from Google for manual penalties.</li>
          <li>Rich Cards a new section added, for better mobile user experience.</li>
        </ul>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_2');">How to verify your website in Google search console?</a>
      <div class='faq' style='display: none;' id='content_2'>
        <ul>
        <li>Go to <a href="https://www.google.com/webmasters/tools/" target="_blank"> Google Search Console</a>.</li>
          <li>If you do not have any website, you will see a 'Add a Property' button. Enter your website link and click on 'Add a Property' button.</li>
          <li>A new page will be opened where you can verify the ownership of your website through various methods. If you don’t know which one to use, Click on Alternate Methods and select HTML tag method.</li>
          <li>Copy the meta tag as suggested by google and paste it in the 'Head Scripts/Styles' input box in your <a href="admin/core/settings/general" target="_blank">general settings</a> and click on Save.</li>
          <li>Now go to google search console verification page and Click Verify button.</li>
        </ul>
        </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_3');">How to integrate this plugin with Google Search console?</a>
      <div class='faq' style='display: none;' id='content_3'>
        For integrating this plugin with Google Search console, firstly you have to enable API’s for your Project.
        <ul>
          <li>Open the <a href="https://console.developers.google.com/apis/library/" target="_blank"> Library page </a> in the API Console.</li>
          <li>Select the project associated with your application.</li>
          <li>Create a project if you do not have any project created already.</li>
          <li>Use the Library page to search for Google search console API. Once you see Google Search Console API link, Click on it and Enable it.</li>
          <li>Open the Credentials page. Click Create credentials >> Service Account Key. If you have a Service Account, select the service account or click on New service account if you don’t have already.</li>
          <li>Fill the service account name, select role as owner and key type as JSON. Click on Create.</li>
          <li>Once you click on create a file with extension json will be downloaded to your system. This file is the key for your search console.</li>
          <li><a href="admin/siteseo/settings/upload-search-console-key" class="smoothbox" target="_blank"> Click here </a> and upload the json key file.</li>
          <li>Go to Credentials Page and in service account section click on Manage Service Accounts. Now copy your service account id (i.e. dummytext@dummytext-171207.iam.gserviceaccount.com). You need to provide access to this service account id in google search console.</li>
          <li>Go to <a href="https://www.google.com/webmasters/tools/" target="_blank"> Google Search Console</a>. Add and Verify your Website in your Google search console if you have not added or verified.</li>
          <li>Click on your website link in Google search console. Click on the setting icon on the top right corner and select Users and property owners. Add a new user with email you copied from credentials page and choose permissions to full. You have to provide full access to each website where you want to integrate google search console.</li>
        </ul>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_4');">From where I can get answers to some other questions that I have related to this plugin?</a>
      <div class='faq' style='display: none;' id='content_4'>Go to <a href="admin/siteseo/settings/faq" target="_blank">FAQ</a> section available in the admin panel of this plugin to get all the information related to this plugin.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_5');"> I want detailed information about different features and functionality of Ultimate SEO / Sitemaps Plugin, from where I can get the same?</a>
      <div class='faq' style='display: none;' id='content_5'>To get detailed information about Ultimate SEO / Sitemaps Plugin plugin, go to <a href="https://www.socialengineaddons.com/socialengine-ultimate-seo-sitemaps-plugin" target="_blank">https://www.socialengineaddons.com/socialengine-ultimate-seo-sitemaps-plugin</a>.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_6');">My queries has not been resolved by above questions, what should I do?</a>
      <div class='faq' style='display: none;' id='content_6'>If you still have any other queries left, please file a support ticket from the "Support" section of your Client Area on SocialEngineAddOns (<a href="http://www.socialengineaddons.com/user/login" target="_blank">http://www.socialengineaddons.com/user/login</a>) so that our support team could look into this. Purchase of this Software, entitles the Licensee of 60 days technical support from SocialEngineAddOns. If your support duration has expired, then please subscribe to our <a href="http://www.socialengineaddons.com/subscriptions" target="_blank">"Basic"</a> or <a href="http://www.socialengineaddons.com/subscriptions" target="_blank">"Plus"</a> Subscription Plans.</div>
    </li>
  </ul>
</div>
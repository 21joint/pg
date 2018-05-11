<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
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
	<ul class="admin_seaocore_files seaocore_faq siteseo_faq">
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');">How does Ultimate SEO and Sitemap plugin helps in Search Engine Optimization?</a>
      <div class='faq' style='display: none;' id='faq_1'>Installing SEO and Sitemap plugin helps in building visibility of website content, blogs and different webpages in Search Engines. You can manage the meta tags, meta keywords and meta description for different pages as well as for different contents of your website, that helps in attracting right visitors to your website.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');">In how much time my website would start getting ranked in different search engines?</a>
      <div class='faq' style='display: none;' id='faq_2'>This depends upon thousands of factors. You need to constantly update website with fresh content, do link-building, decide which keywords you want your website to be ranked, actively use different social media platforms, submit sitemaps in <a href="admin/siteseo/settings/seo-tips/target/3" target="_blank">Search Console</a>.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');">How do Social Media Platforms help to increase website traffic?</a>
      <div class='faq' style='display: none;' id='faq_3'>Your target audience spends a lot of time over social media networks. This makes social media networks perfect medium for driving traffic from target audience, thus, helping build awareness about your brand. To drive social media traffic to your website, you not only need to register over these platforms, but also, need to stay active over these platforms which helps in increasing customer retention and brand loyalty.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');">Do I need to submit sitemaps to search engines daily?</a>
      <div class='faq' style='display: none;' id='faq_4'>
        No, you need not to submit sitemaps of your website daily. There is an option of submitting the sitemaps automatically. So, you can choose that option and also you can customize the timing as after how much time you want to submit sitemaps of your website.
        <p>Follow below steps to enable auto submission of sitemaps:</p>
        <ul>
          <li>Go to <a href="admin/siteseo/sitemap" target="_blank">‘Sitemaps’</a> section in the admin panel of this plugin.</li>
          <li>Click on ‘Auto Submit Settings’ button.</li>
          <li>Choose the search engines for which you want to auto submit the sitemaps.</li>
          <li>Select the time interval for auto submission of sitemaps.</li>
          <li>Click on ‘Save Changes’.</li>
        </ul>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_5');">How does Schema Markup help in search engine optimization?</a>
      <div class='faq' style='display: none;' id='faq_5'>Schema Markups are a specific vocabulary of tags (or microdata) that you can add to your HTML to improve the way your page is represented in SERPs. It helps to provide information search engines need to understand about their content and provide the best search results possible at a time. Adding Schema markup to your HTML improves the way your page displays in SERPs by enhancing the rich snippets that are displayed beneath the page title.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');">How different options will work for ‘Meta Title’ and ‘Meta Description’ as available in Global Settings?</a>
      <div class='faq' style='display: none;' id='faq_6'>
        There are four ways to use ‘Meta Title’ and ‘Meta Description’ for your website:
          <ul>
            <li>None: If you don’t want to show ‘Meta Title’ and ‘Meta Description’ along with the content’s title and description.</li>
            <li>Website Title / Description: To append only website’s title / description with the content’s title / description.</li>
            <li>Page Title / Description:  To append only the title / description added by site admin in ‘Manage Meta Tags’ → ‘Meta Title’ / ‘Meta Description’ with the content’s title / description.</li>
            <li>
              Both Website and Page Title / Description:
              <ul>
                <li>When enabled, both website’s and page meta title / description will append to the title / description added by user for the specific content.</li>
                <li>If user has not added any title / description then the title / description added by site admin in ‘Manage Meta Tags’ → ‘Meta Title’ / ‘Meta Description’ for that particular page. And the website’s title / description will append to it.</li>
                <li>In case, user has not added any title / description and also site admin has not added any title / description in ‘Manage Meta Tags’ → ‘Meta Title’ / ‘Meta Description’ then only website’s title / description will be shown.</li>
              </ul>
            </li>
          </ul>
      </div>
    </li>

	</ul>
</div>
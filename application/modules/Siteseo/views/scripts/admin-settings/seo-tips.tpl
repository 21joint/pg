<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Facebookse
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 6590 2011-01-06 9:40:21Z SocialEngineAddOns $
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
	<ul class="admin_seaocore_files seaocore_faq siteseo_faq">
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_1');">What is SEO?</a>
      <div class='faq' style='display: none;' id='content_1'>Search Engine Optimization(SEO) is a way of building visibility of web pages in Search Engines. By building <b>Search Engine Visibility</b>, website traffic increases from different search engines such as Google, Bing etc.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_2');">Add your website to different Social Media platforms.</a>
      <div class='faq' style='display: none;' id='content_2'>
        You should register your website on different Social Media Platforms and also, need to stay active on those platforms. This will help in marketing of your website resulting in addition of new site users.
        <div>
          <b>Note:</b>
          You can get our <a href="https://www.socialengineaddons.com/socialengine-advanced-share-plugin" target="_blank">Advanced Share Plugin</a> and <a href="https://www.socialengineaddons.com/socialengine-social-meta-tags-plugin" target="_blank">Social Meta Tags Plugin</a> to attractively display your website’s content on any social media platform.
        </div>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_3');">Register in Google Search Console and Bing webmasters tools.</a>
      <div class='faq' style='display: none;' id='content_3'>Most Search Engines have webmaster tools such as <a href="https://www.google.com/webmasters/tools/" target="_blank">Google Search Console</a> (previously Google webmaster tools) by Google and <a href="https://www.bing.com/toolbox/webmaster/" target="_blank">Bing Webmaster Tools</a> by Bing. You should register in both of them. The crawl statistics, sitemap statistics and much more information about your website can be gathered from these tools.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_4');">Title, Meta Description & Meta tags</a>
      <div class='faq' style='display: none;' id='content_4'>
        Title tags, meta descriptions and meta keywords are important elements of your website’s content. These tags should include keywords relevant to the web page content. This help Search Engines understand what the page is about and index your web pages accordingly for added keywords or keyword phrases.
        <div>
          <b>Note:</b>
          You can set Meta titles, Meta Description and Meta Keywords of pages and contents of your website through <a href="admin/siteseo/meta-tags/manage" target="_blank">Manage Meta Tags</a> section of this plugin. 
        </div>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_5');">Good Keyword Research</a>
      <div class='faq' style='display: none;' id='content_5'>Keyword research forms an important part of SEO. Using <a href="https://support.google.com/adwords/answer/2999770" target="_blank">Google Adwords Keyword Planner</a> tool you can sought out your industry-specific keywords with highest search volume and frame website content, blogs around those keywords. Optimizing content as per researched keywords helps to reach the right customers and increase visibility of website content & blogs in Search Engines.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_6');">Go Responsive and opt for a mobile version of your website</a>
      <div class='faq' style='display: none;' id='content_6'>
        With the drastic growth of mobile devices like smart phones and tablets, and mobile devices usage, via apps, social media, etc; it is important that your website should be mobile-friendly and optimized for all the mobile screens.
        <div>
          <b>Note:</b>
          You can get our <a href="https://www.socialengineaddons.com/socialengine-mobile-tablet-plugin" target="_blank">Mobile / Tablet Plugin</a> for different mobile and tablet versions of your websites.
        </div>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_7');">Migrating Website from HTTP to HTTPs server</a>
      <div class='faq' style='display: none;' id='content_7'>Migrating website from HTTP to HTTPs server makes it more secure in terms of data transfer between client and server. Also, Google prefers to index secured web pages over non-secured web pages.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_8');">Adding Schema Markups</a>
      <div class='faq' style='display: none;' id='content_8'>Schema markup is code (semantic vocabulary) that you put on your website to help the search engines return more informative results for users. Schema tells the search engines what your data means, not just what it says. Using this plugin, you can easily add <a href="admin/siteseo/settings/schema" target="_blank">schema markups</a> for your website.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_9');">Adding Blog Section and Updating Website Content Consistently</a>
      <div class='faq' style='display: none;' id='content_9'>SEO is all about content and hence, Blogging forms one of the important aspect for SEO. Blog directly helps in driving traffic to your website. Also, updating website content regularly with fresh content helps to improve chances of website pages indexing and thus, improve its ranking in Search Engines.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_10');">Checking Website Speed</a>
      <div class='faq' style='display: none;' id='content_10'>If your website is slow, it can be bad for your website. From performance settings HTML Compression must be enabled. This reduces the size of text in your webpage. Google PageSpeed Insight Tool can be used to check the speed for your website.
        <div>
          <b>Note:</b>
          To improve website speed at a faster pace you can also, install <a href="https://www.socialengineaddons.com/socialengine-page-cache-plugin" target="_blank">Page Cache </a> & <a href="https://www.socialengineaddons.com/socialengine-minify-plugin-speedup-website" target="_blank">Minify Plugin</a> .  Or you can purchase our discounted kit <a href="https://www.socialengineaddons.com/socialengine-website-speed-enhancement-kit" target="_blank">‘Website Speed Enhancement Kit’</a>.
        </div>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_11');">Check for Robots.txt</a>
      <div class='faq' style='display: none;' id='content_11'>The <a href="https://support.google.com/webmasters/answer/6062608" target="_blank">/robots.txt</a> file gives instructions about website to web robots; this is called The Robots Exclusion Protocol. Using this plugin; you can create robots.txt file and edit from File Editors Section. The "User-agent: *" means this section applies to all robots. The "Disallow: /" tells the robot that it should not visit any pages on the site.</div>
    </li>
	</ul>
</div> 
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
      <a href="javascript:void(0);" onClick="show_content('content_1');">Google Analytics</a>
      <div class='faq' style='display: none;' id='content_1'>Google Analytics is a freemium web analytics service offered by Google that tracks and reports website traffic. Google Analytics is one the most widely used web analytics service on the Internet. This is a core feature provided by SocialEngine.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_2');">Google Adwords Keyword Planner</a>
      <div class='faq' style='display: none;' id='content_2'>Google AdWords Keyword Planner is a keyword research tool that helps you find the right keywords for the target audience. From the keyword planner, you can search for keywords and ad group ideas, check its search volume, and might even create a new keyword list by combining several keywords together.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_3');">Open Graph Validator Tool</a>
      <div class='faq' style='display: none;' id='content_3'>Open Graph Validator Tool helps to check whether all parameters of open graph have been implemented correctly or not. Open graph provides rich experience to users when anyone shares a webpage to facebook or any social platform. By integrating Open Graph Meta Tags into your page’s content, you can identify which element of your page you want to show when someone shares your page.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_4');">Twitter Card Validator Tool</a>
      <div class='faq' style='display: none;' id='content_4'>Twitter Card Validator Tool helps to check whether all parameters of twitter cards have been implemented correctly or not. With twitter cards, you can attach rich photos, videos and media experience to tweets , helping to drive traffic to your website.</div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="show_content('content_5');">Google Structured Data Testing Tool</a>
      <div class='faq' style='display: none;' id='content_5'><a href="https://developers.google.com/search/docs/guides/intro-structured-data" target="_blank"> Structured data </a> is used to describes things on the web, along with their properties. Structured data is added directly to a page's HTML markup. Search engines use structured data to generate rich snippets, which are small pieces of information that will then appear in search results. <a href="https://search.google.com/structured-data/testing-tool/" target="_blank"> Google Structured Data Testing Tool </a> is used to test structure data on a web page.</div>
    </li>
  </ul>
</div>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
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
</script>

<div class="admin_seaocore_files_wrapper">
	<ul class="admin_seaocore_files seaocore_faq">	

    <li>
			<a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("I want to use this plugin for a different purpose. How can I change the word: 'FAQs' to 'Tutorials' or ‘Opinions’ or any other term in this plugin?");?></a>
			<div class='faq' style='display: none;' id='faq_2'>
				<?php echo $this->translate("This plugin has been developed such that you can use it for any purpose of your choice on your site. You can easily change the word ‘FAQs’ to ‘Tutorials’ or ‘Opinions’ or Help Center’. To do so, go to the ‘Layout’ > 'Language Manager' section in the Admin Panel and edit phrases to change the word: 'FAQs' to a word of your choice and the word: ‘FAQ’ to a word of your choice.");?></a>
			</div>
		</li>

    <li>
			<a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("I want to use this plugin for a different purpose. In the various URLs of this plugin, how can I change the terms: ‘faqs’ and ‘faq’ to: ‘tutorials’ and ‘tutorial’ or something else of my choice?");?></a>
			<div class='faq' style='display: none;' id='faq_3'>
				<?php echo $this->translate("<p>To change the terms &lsquo;faqs&rsquo; and &lsquo;faq&rsquo; in the URLs of this plugin to &lsquo;tutorials&rsquo; and &lsquo;tutorial&rsquo; or any other of your choice, please follow the steps below:<br>1) Open this file: 'application/modules/Sitefaq/settings/manifest.php'.<br>2) Replace the words 'faq' and 'faqs' with &lsquo;tutorial&rsquo; and &lsquo;tutorials&rsquo; respectively, or anything else of your choice, in your directory type in the route part of all the routes defined in this file.<br><br>For example, to change &lsquo;faqs&rsquo; to &lsquo;tutorials&rsquo;, search for route, like the one below:<br><br>&nbsp;&nbsp;&nbsp; <b>'route' =&gt; 'faqs/:action/*'</b><br><br>The above code should be replaced with the below code.<br><br>&nbsp;&nbsp;&nbsp; <b>'route' =&gt; 'tutorials/:action/*'</b><br><br>Please note that in the above example, the change is 'faqs' =&gt; 'tutorials'. But you can also change it as 'faqs' =&gt; 'articles' or anything else according to your specifications. This type of change should be made in all the routes of this file.</p>");?></a>
			</div>
		</li>

    <li>
			<a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("I want to configure the various widgets of this plugin according to my requirements? How can I do it ?");?></a>
			<div class='faq' style='display: none;' id='faq_4'>
				<?php echo $this->translate("To configure the various widgets of this plugin according to your requirements, please place those widgets at the desired locations from the ‘Layout’ > 'Layout Editor' section in the Admin Panel and click on 'edit' option against the desired widgets.");?></a>
			</div>
		</li>

    <li>
			<a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("How can I enable Activity Feeds for FAQs related activities like creation, commenting, etc.?");?></a>
			<div class='faq' style='display: none;' id='faq_5'>
				<?php echo $this->translate('To enable activity feeds for FAQs, go to the "Settings" > "Activity Feed Settings" section in the Admin Panel and click on "Activity Feed Item Type Settings page" link to enable activity feeds for desired "Action Feed Item" from this plugin.');?></a>
			</div>
		</li>

    <li>
			<a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("The CSS of this plugin is not coming on my site. What should I do ?");?></a>
			<div class='faq' style='display: none;' id='faq_1'>
				<?php echo $this->translate("Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'.");?></a>
			</div>
		</li>

	</ul>
</div>
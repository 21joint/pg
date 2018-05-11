<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>Ultimate SEO / Sitemaps Plugin</h2>
<?php if (count($this->navigation)): ?>
	<div class='seaocore_admin_tabs clr'>
		<?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
	</div>
<?php endif; ?>
<div class="">
	<div class="seaocore_admin_tabs clr" id="file_editors_links">
		<ul class="navigation">
			<li class="file_editors_link active" id="robots_txt_link" data-target="robots_txt_editor">
				<a>Robots txt Editor</a>
			</li>
			<li class="file_editors_link" id="htaccess_link" data-target="htaccess_editor">
				<a>htaccess Editor</a>
			</li>
			<li class="file_editors_link" id="osd_link" data-target="osd_editor">
				<a>Open Search Editor</a>
			</li>
		</ul>
	</div>
	<div class="admin_theme_editor_wrapper" id="robots_txt_editor">
		<form action="<?php echo $this->url(array('action' => 'save-robots')) ?>" method="post">
			<div class="admin_theme_edit">
				<div class="admin_theme_header_controls">
					<div style="clear: both; float: none;">
						Robots.txt file is at the root of your site that indicates which part of your site can or cannot be accessed by search engine crawlers. To know more about robots.txt, please <a href="https://support.google.com/webmasters/answer/6062608" target="_blank">Click here </a>
					</div>
				</div>
				<?php if( $this->writeableRobotFile ): ?>
					<div class="admin_theme_editor_edit_wrapper">
						<div class="admin_theme_editor">
							<?php echo $this->formTextarea('body-robots', $this->robotFileContent, array('spellcheck' => 'false')) ?>
						</div>
						<button class="activate_button" type="submit" onclick="saveFileChanges('robots');return false;">Save Changes</button>
					</div>
				<?php else: ?>
					<div class="admin_theme_editor_edit_wrapper">
						<div class="tip">
							<span>
								The Robots.txt file of your website is not writeable. Please set full permissions (CHMOD 0777) on "robots.txt" and try again.
							</span>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</form>
	</div>	
	<div class="admin_theme_editor_wrapper" id="htaccess_editor">
		<form action="<?php echo $this->url(array('action' => 'save-htaccess')) ?>" method="post">
			<div class="admin_theme_edit">
				<div class="admin_theme_header_controls">
					<div style="clear: both; float: none;">
						htaccess file controls the behavior of your site or a specific directory on your site. This file can be used to restrict anyone from accessing whole website or a section of your website.
					</div>
				</div>
				<?php if( $this->writeableHtaccessFile ): ?>
					<div class="admin_theme_editor_edit_wrapper">
						<div class="admin_theme_editor">
							<?php echo $this->formTextarea('body-htaccess', $this->htaccessFileContent, array('spellcheck' => 'false')) ?>
						</div>
						<button class="activate_button" type="submit" onclick="saveFileChanges('htaccess');return false;">Save Changes</button>
					</div>
				<?php else: ?>
					<div class="admin_theme_editor_edit_wrapper">
						<div class="tip">
							<span>The .htaccess file of your website is not writeable. Please set full permissions (CHMOD 0777) on ".htaccess" and try again.</span>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</form>
	</div>
	<div class="admin_theme_editor_wrapper" id="osd_editor">
		<form action="<?php echo $this->url(array('action' => 'save-open-search')) ?>" method="post">
			<div class="admin_theme_edit">
				<div class="admin_theme_header_controls">
					<div style="clear: both; float: none;">
						OpenSearch is a collection of simple formats for the sharing of search results. The OpenSearch description document format can be used to describe a search engine so that it can be used by search client applications.
					</div>
				</div>
				<?php if( $this->writeableOpenSearchFile ): ?>
					<div class="admin_theme_editor_edit_wrapper">
						<div class="admin_theme_editor">
							<?php echo $this->formTextarea('body-open-search', $this->OpenSearchFileContent, array('spellcheck' => 'false')) ?>
						</div>
						<button class="activate_button" type="submit" onclick="saveFileChanges('open-search');return false;">Save Changes</button>
					</div>
				<?php else: ?>
					<div class="admin_theme_editor_edit_wrapper">
						<div class="tip">
							<span>The osdd.xml file of your website is not writeable. Please set full permissions (CHMOD 0777) on "osdd.xml" and try again.</span>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var saveFileChanges = function(filetype) {
		if(filetype == 'htaccess'){
			body = $('body-htaccess').value;
			url = '<?php echo $this->url(array('action' => 'save-htaccess')) ?>';
		}
		else if(filetype == 'robots'){
			url = '<?php echo $this->url(array('action' => 'save-robots')) ?>';
			body = $('body-robots').value;
		} else {
			url = '<?php echo $this->url(array('action' => 'save-open-search')) ?>';
			body = $('body-open-search').value;
		}

		var request = new Request.JSON({
			url : url,
			data : {
				'body' : body,
				'format' : 'json'
			},
			onComplete : function(responseJSON) {
				if( responseJSON.status ) {
					alert('Your changes have been saved!');
				} else {
					alert('An error has occurred. Changes could NOT be saved.');
				}
			}
		});
		request.send();
	}


	$$('#file_editors_links li').addEvent('click', function($this) {

		$$('#file_editors_links li').removeClass('active');
		this.addClass('active');
		var div = $(this.get('data-target'));
		$$('.admin_theme_editor_wrapper').hide();
		div.show();
	})

	en4.core.runonce.add( function () {
		$$('.admin_theme_editor_wrapper').hide();
		$('robots_txt_editor').show();
	})
</script>
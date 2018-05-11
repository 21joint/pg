<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: detail.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="sitepage_admin_popup" style="margin:10px 10px 0 10px;">
	<?php foreach ($this->sitepageofferDetail as $item) ?>
	<div>
		<h3><?php echo $this->translate('Page Offer Details'); ?></h3>
		<br />
		<table>
			<tr>
				<tr valign="top">
					<td width="120"><b><?php echo $this->translate('Title:'); ?></b></td>
					<td>
						<?php echo $this->translate($this->sitepageofferDetail->title); ?>&nbsp;&nbsp;
					</td>
				</tr>
				<tr valign="top">
					<td width="120"><b><?php echo $this->translate('Description:'); ?></b></td>
					<td>
						<?php echo $this->translate($this->sitepageofferDetail->description); ?>&nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<td></td>
					<td><br /><button  onclick='javascript:parent.Smoothbox.close()' ><?php echo $this->translate('Close')  ?></button></td>
				</tr>
			</tr>
		</table>
		<?php if (@$this->closeSmoothbox): ?>
			<script type="text/javascript">
				TB_close();
			</script>
		<?php endif; ?>
		<style type="text/css">
		td{padding:3px; }
		td b{font-weight:bold;}
		</style>
	</div>
</div>
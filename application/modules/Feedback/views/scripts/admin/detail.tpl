<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: detail.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<style type="text/css">
td{
	padding:3px;		
}
td b{
	font-weight:bold;
}
</style>

<form method="post" class="global_form_popup">
 	<div>
	  <h3><?php echo $this->translate('Feedback Details :');?></h3>
	 	<br />
	 	<table>
	 		<tr>
	 			<td width="120"><b><?php echo $this->translate('Title :');?></b></td>
	 			<td>
	 				<?php echo $this->htmlLink($this->feedback->getHref(), $this->feedback->getTitle(), array('target'=>'_parent')) ?>&nbsp;&nbsp;
	 				 <?php if($this->feedback->featured == 1): ?>
			        <?php echo $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_goldmedal1.gif', '') ?>
			     <?php endif;?>
	 			</td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('Owner :');?></b></td>
	 			<td>
	 				<?php if(!empty($this->feedback->owner_id)) {
	 								echo $this->htmlLink($this->feedback->getOwner()->getHref(), $this->feedback->getOwner()->getTitle(), array('target'=>'_parent'));
	 							}		
	 							else {
	 								echo $this->translate('Anonymous User');
	 							}
	 				?>
	 			</td>
	 		</tr>
	 		<?php if(empty($this->feedback->owner_id)): ?>
	 		<tr>
	 			<td><b><?php echo $this->translate('Owner Email :');?></b></td>
	 			<td>
	 				<?php echo $this->feedback->anonymous_email;?>
	 			</td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('Owner Name :');?></b></td>
	 			<td>
	 				<?php echo $this->feedback->anonymous_name;?>
	 			</td>
	 		</tr>
	 		<?php endif;?>
	 		<tr valign="top">
	 			<td><b><?php echo $this->translate('Description :');?></b></td>
	 			<td>
	 				<?php echo $this->viewMore($this->feedback->feedback_description) ?>
	 			</td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('Creation Date :');?></b></td>
	 			<td>
	 				<?php echo $this->translate('about %s',	$this->timestamp($this->feedback->creation_date)) ?>
	 			</td>
	 		</tr>
	 		<tr>
				<td><b><?php echo $this->translate('Category :');?></b></td><td><?php echo $this->category_name; ?></td>
	 		</tr>
	 		<tr>
				<td><b><?php echo $this->translate('Severity :');?></b></td><td><?php echo $this->severity_name; ?></td>
	 		</tr>
	 		<tr>
				<td><b><?php echo $this->translate('Status :');?></b></td><td style ="color:<?php echo $this->stat_color; ?>"><?php echo $this->stat_name; ?></td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('Views :');?></b></td>
	 			<td><?php echo $this->feedback->views?></td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('Votes :');?></b></td>
	 			<td><?php echo $this->feedback->total_votes?></td>
	 		</tr>
	 		<?php if(!empty($this->feedback->owner_id)):?>
		 		<tr>
		 			<td><b><?php echo $this->translate('Comments :');?></b></td>
		 			<td><?php echo $this->feedback->comment_count?></td>
		 		</tr>
		 	<?php endif; ?>	
	 		<tr>
	 			<td><b><?php echo $this->translate('Pictures :');?></b></td>
	 			<td><?php echo $this->feedback->total_images?></td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('IP Address :');?></b></td>
	 			<td><?php echo $this->feedback->ip_address?></td>
	 		</tr>
	 		<tr>
	 			<td><b><?php echo $this->translate('Browser Detail :');?></b></td>
	 			<td><?php echo $this->feedback->browser_name?></td>
	 		</tr>
	 	</table>
    <br />
		<button onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate('Close');?></button>
  </div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
  		TB_close();
	</script>
<?php endif; ?>
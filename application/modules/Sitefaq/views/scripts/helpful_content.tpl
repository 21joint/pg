<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: helpful_content.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $option_count = count($this->options);?>
<div id="show_option_<?php echo $this->faq_id;?>" style="display:none;">
	<b><?php echo $this->translate("Why not?");?></b><br />
	<?php if($option_count):?>
		<?php foreach ($this->options as $item):?>
			<a href='javascript:void(0);' onclick="javascript:helpfulAction('<?php echo $this->faq_id; ?>','<?php echo $this->viewer_id; ?>','','<?php echo $item->option_id; ?>','<?php echo $option_count?>');"><?php echo $this->translate($item->reason);?></a><br />
		<?php endforeach;?>
  <?php endif;?>
</div>

<?php if(!empty($this->statisticsHelpful) || !empty($this->helpful_allow)): ?>
	<div id="showbox_<?php echo $this->faq_id;?>">
		<?php if($this->statisticsHelpful): ?>
			<div class="sfhc_fl">
				<?php if($this->totalHelpCount && $this->totalHelpCount != 200):?>
					<?php echo '<b>'.$this->totalHelpCount.'%</b>'.$this->translate(' users marked this FAQ as helpful.');?>
				<?php elseif($this->totalHelpCount == 200): ?>
					<?php echo '<b>0%</b>'.$this->translate(' users marked this FAQ as helpful.');?>
				<?php endif; ?>	
			</div>
		<?php endif; ?>
		<div id="showmaincontent_<?php echo $this->faq_id;?>">
			<?php if($this->statisticsHelpful && $this->totalHelpCount): ?>
				<?php if(!empty($this->helpful_allow)):?>
						<span class="sfhc-sep sfhc_fl">|</span>
				<?php endif;?>
			<?php endif;?>
			<?php if(empty($this->viewer_id) && !empty($this->helpful_allow)):?>
				<div class="sfhc_fl"><?php echo $this->translate("Was this answer helpful?");?></div>
				<div class="sfhc_buttons">
					<?php if($this->action = 'view'): ?>
						<?php echo $this->htmlLink(Array('route' => 'sitefaq_view', 'faq_id' => $this->faq_id, 'category_id' => $this->first_category_id, 'subcategory_id' => $this->first_subcategory_id, 'subsubcategory_id' => $this->first_subsubcategory_id, 'slug' => $this->faq_slug, 'anonymous' => 1), '<i class="y_btn"></i>'.$this->translate("Yes"), array('target' => '_parent', 'class' => 'yn_btn')); ?>
						<?php echo $this->htmlLink(Array('route' => 'sitefaq_view', 'faq_id' => $this->faq_id, 'category_id' => $this->first_category_id, 'subcategory_id' => $this->first_subcategory_id, 'subsubcategory_id' => $this->first_subsubcategory_id, 'slug' => $this->faq_slug, 'anonymous' => 1), '<i class="n_btn"></i>'.$this->translate("No"), array('target' => '_parent', 'class' => 'yn_btn')); ?>
					<?php elseif($this->action == 'browse'): ?>
						<?php echo $this->htmlLink(Array('route' => 'sitefaq_general', 'action' => 'browse', 'anonymous' => 1), '<i class="y_btn"></i>'.$this->translate("Yes"), array('target' => '_parent', 'class' => 'yn_btn')); ?>
						<?php echo $this->htmlLink(Array('route' => 'sitefaq_general', 'action' => 'browse', 'anonymous' => 1), '<i class="n_btn"></i>'.$this->translate("No"), array('target' => '_parent', 'class' => 'yn_btn')); ?>
					<?php endif;?>
				</div>	

			<?php elseif(!empty($this->helpful_allow)):?>
				
				<div class="sfhc_fl"><?php echo $this->translate("Was this answer helpful?");?></div>
				<div class="sfhc_buttons">
					<?php if($this->previousHelpMark == 1): ?>
						<a class="yn_btn" href='javascript:void(0);' onclick="javascript:helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '2','','<?php echo $option_count?>');"><i class="y_btn"></i><?php echo $this->translate("Yes");?></a>
						<span class="yn_btn yn_disable"><i class="n_d_btn"></i><?php echo $this->translate("No");?></span>
					<?php elseif($this->previousHelpMark == 2): ?>
						<span class="yn_btn yn_disable"><i class="y_d_btn"></i><?php echo $this->translate("Yes");?></span>
						<a class="yn_btn" href='javascript:void(0);' onclick="javascript:helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '1','','<?php echo $option_count?>');"><i class="n_btn"></i><?php echo $this->translate("No");?></a>
					<?php else: ?>
						<a class="yn_btn" href='javascript:void(0);' onclick="javascript:helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '2','','<?php echo $option_count?>');"><i class="y_btn"></i><?php echo $this->translate("Yes");?></a>
						<a class="yn_btn" href='javascript:void(0);' onclick="javascript:helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '1','','<?php echo $option_count?>');"><i class="n_btn"></i><?php echo $this->translate("No");?></a>
					<?php endif;?>
				</div>	
			<?php endif;?>
		</div>
		<span class="seaocore_txt_light sfhc-st sfhc_fl clr">
			<?php if($this->statisticsHelpful && ($this->helpful_allow) && ($this->totalVoteCount)):?>
				<?php echo $this->translate(array('%s vote', '%s votes', $this->totalVoteCount), $this->locale()->toNumber($this->totalVoteCount)) ?>
			<?php endif;?>
			<?php if(!empty($this->helpful_allow)):?>
				<?php if($this->previousHelpMark):?>
					<?php if($this->statisticsHelpful && ($this->totalVoteCount)):?>
						|
					<?php endif;?>
					<?php echo $this->translate("Your previous answer: ");?>
					<?php if($this->previousHelpMark == 2):?>
						<?php echo $this->translate("Yes");?>
					<?php elseif($this->previousHelpMark == 1):?>
						<?php echo $this->translate("No");?>
					<?php endif;?>
				<?php endif;?>
			<?php endif;?>
		</span>	
	</div>
<?php endif;?>
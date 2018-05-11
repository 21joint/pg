<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  var tagAction =function(tag, tag_id){
    $('tag').value = tag;
		$('tag_id').value = tag_id;
    $('filter_form').submit();
  }
</script>

<form id='filter_form' class='global_form_box' method='get' action='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) ?>' style='display: none;'>
	<input type="hidden" id="tag" name="tag" value=""/>
	<input type="hidden" id="tag_id" name="tag_id" value=""/>
</form>
<ul class="sitefaq_faq_info">

	<?php if($this->posted): ?>
		<li>
			<span class="sitefaq_faq_info_label">
				<?php echo $this->translate('Posted by:')?>
			</span>
			<span class="sitefaq_faq_info_element">
				<?php if(!empty($this->owner_photo)):?>
					<?php echo $this->htmlLink($this->sitefaq->getParent(), $this->itemPhoto($this->sitefaq->getParent(), 'thumb.icon', '' , array('align'=>'center'))) ?>
				<?php endif ;?>
				<?php echo $this->htmlLink($this->sitefaq->getParent(), $this->sitefaq->getParent()->getTitle()) ?>
			</span>	
		</li>
	<?php endif;?>
	
	<?php if(!empty($this->modified_date)): ?>
		<li>
			<span class="sitefaq_faq_info_label">
				<?php echo $this->translate('Updated:')?>
			</span>
			<span class="sitefaq_faq_info_element seaocore_txt_light">
				<?php echo $this->translate($this->timestamp($this->sitefaq->modified_date)) ?>	
			</span>
		</li>
	<?php endif;?>

	<?php if($this->creation_date):?>
		<li>
			<span class="sitefaq_faq_info_label">
				<?php echo $this->translate('Created:')?>
			</span>
			<span class="sitefaq_faq_info_element seaocore_txt_light">
				<?php echo $this->translate('Created about %s', $this->timestamp($this->sitefaq->creation_date)) ?>
			</span>	
		</li>
	<?php endif; ?>
	
	<?php if($this->statisticsView):?>
		<li>
			<span class="sitefaq_faq_info_label">
				<?php echo $this->translate('Views:')?>
			</span>
			<span class="sitefaq_faq_info_element seaocore_txt_light">
				<?php echo $this->translate(array('%s view', '%s views', $this->sitefaq->view_count), $this->locale()->toNumber($this->sitefaq->view_count)) ?>
			</span>
		</li>
	<?php endif; ?>
		
	<?php if($this->tags && count($this->sitefaqTags) > 0):?>
		<li>
			<span class="sitefaq_faq_info_label">
				<?php echo $this->translate('Topics Covered:')?>
			</span>
			<span class="sitefaq_faq_info_element">
				<?php foreach ($this->sitefaqTags as $tag): ?>
					<?php if (!empty($tag->getTag()->text)):?>
						<?php $string =  $this->string()->escapeJavascript($tag->getTag()->text); ?>
						<a href='javascript:void(0);' onclick='javascript:tagAction("<?php echo $string; ?>", <?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</span>
		</li>
	<?php endif; ?>
</ul>
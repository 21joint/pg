<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: tagcloud.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  var tagAllAction = function(tag, tag_id){
    $('tag').value = tag;
		$('tag_id').value = tag_id;
    $('filter_form_tagscloud').submit();
  }
</script>

<div class="headline">
	<h2>
	  <?php echo $this->translate('FAQs');  ?>
	</h2>
	<div class='tabs'>
	  <?php echo $this->navigation($this->navigation)->render() ?>
	</div>
</div>

<h3 class="sep"><span><?php echo $this->translate('Popular Tags for FAQs'); ?></span></h3>
<p><?php echo $this->translate('Browse the tags for FAQs.'); ?></p>

<?php if(!empty($this->tag_array)):?>

	<form id='filter_form_tagscloud' class='global_form_box' method='get' action='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) ?>' style='display: none;'>
		<input type="hidden" id="tag" name="tag"  value=""/>
		<input type="hidden" id="tag_id" name="tag_id"  value=""/>
	</form>

	<div style="margin-top:10px;">
		<?php foreach($this->tag_array as $key => $frequency):?>
			<?php $string = $this->string()->escapeJavascript($key); ?>
			<?php $step = $this->tag_data['min_font_size'] + ($frequency - $this->tag_data['min_frequency'])*$this->tag_data['step'] ?>
			<a href='javascript:void(0);' onclick='javascript:tagAllAction("<?php echo $string; ?>", <?php echo $this->tag_id_array[$key];?>);' style="font-size:<?php echo $step ?>px;" title=''><?php echo $key ?><sup><?php echo $frequency ?></sup></a>&nbsp; 
		<?php endforeach;?>
	</div>
	<br /><br />

<?php endif; ?>
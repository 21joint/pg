<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">

	function faq_show(id) {
		if($('faq_hide_'+id).style.display == 'block' || $('faq_hide_'+id).style.display == '') {
			$('faq_hide_'+id).style.display = 'none';
			$('faq_expand_'+id).style.display = 'block';

			$('faq_arrow_hide_'+id).style.display = 'none';
			$('faq_arrow_expand_'+id).style.display = 'block';
		} else {
			$('faq_hide_'+id).style.display = 'block';
			$('faq_expand_'+id).style.display = 'none';

			$('faq_arrow_hide_'+id).style.display = 'block';
			$('faq_arrow_expand_'+id).style.display = 'none';
		}
	}

	window.addEvent('domready', function() {

		$('.faq_details_hide').each(function(el){
			el.style.display = 'block';
		});

		$('.faq_details_expand').each(function(el){
			el.style.display = 'none';
		});

		if($('faq_hide'))
		$('faq_hide').hide();

	});
</script>
<li>
	<div class="faq_list_img">
		<div id='<?php echo "faq_arrow_hide_$item->faq_id"?>' class='faq_details_hide' >
			<?php if(empty($this->linked)): ?>
				<a href="javascript:void(0);" onClick="faq_show('<?php echo $item->faq_id;?>');" class="sitefaq_icon sitefaq_icon_exp"></a>
			<?php endif; ?>
		</div>
		<div id='<?php echo "faq_arrow_expand_$item->faq_id"?>' class='faq_details_expand sitefaq_icon sitefaq_icon_coll' style="display:none;">
			<a href="javascript:void(0);" onClick="faq_show('<?php echo $item->faq_id;?>');"></a>
		</div>
	</div>
	<div class="faq_list_info">
		<div class="faq_list_info_top">
			<?php if(empty($this->linked)): ?>
				<div class="faq_list_title faq_list_title_exp">
				<a href="<?php echo $item->getHref(); ?>" onClick="faq_show('<?php echo $item->faq_id;?>'); return false;"><?php echo $item->getTitle();?></a>
				</div></div>
				<div id='<?php echo "faq_hide_" . $item->faq_id ?>' class="faq_list_info_blurb seaocore_txt_light faq_details_hide">
					<?php echo Engine_Api::_()->sitefaq()->truncateText($item->getFullDescription(), 140); ?>
				</div>
				<div id='<?php echo "faq_expand_" . $item->faq_id ?>' class="faq_details_expand sitefaq_faq_body" style="display:none">

			<?php else: ?>
        </div>
				<div class="sitefaq_faq_body">
			<?php endif; ?>
				<?php echo $item->getFullDescription(); ?>
				<div class="faq_list_btm_link">
					<a href="javascript:void(0);" onClick="faq_show('<?php echo $item->faq_id;?>');"><?php echo $this->translate("[Hide]");?></a>
				</div>
		</div>
	</div>
</li>

<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: tutorial.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">

	function tutorial_show(id) {
		if($('tutorial_hide_'+id).style.display == 'block' || $('tutorial_hide_'+id).style.display == '') {
			$('tutorial_hide_'+id).style.display = 'none';
			$('tutorial_expand_'+id).style.display = 'block';

			$('tutorial_arrow_hide_'+id).style.display = 'none';
			$('tutorial_arrow_expand_'+id).style.display = 'block';
		} else {
			$('tutorial_hide_'+id).style.display = 'block';
			$('tutorial_expand_'+id).style.display = 'none';

			$('tutorial_arrow_hide_'+id).style.display = 'block';
			$('tutorial_arrow_expand_'+id).style.display = 'none';
		}
	}

	window.addEvent('domready', function() {

		$$('.tutorial_details_hide').each(function(el){
			el.style.display = 'block';
		});

		$$('.tutorial_details_expand').each(function(el){
			el.style.display = 'none';
		});

		if($('tutorial_hide'))
		$('tutorial_hide').setStyle('display', 'none');

	});
</script>
<li>
	<div class="tutorial_list_img">
		<div id='<?php echo "tutorial_arrow_hide_$item->tutorial_id"?>' class='tutorial_details_hide' >
			<?php if(empty($this->linked)): ?>
				<a href="javascript:void(0);" onClick="tutorial_show('<?php echo $item->tutorial_id;?>');" class="sitetutorial_icon sitetutorial_icon_exp"></a>
			<?php endif; ?>
		</div>
		<div id='<?php echo "tutorial_arrow_expand_$item->tutorial_id"?>' class='tutorial_details_expand sitetutorial_icon sitetutorial_icon_coll' style="display:none;">
			<a href="javascript:void(0);" onClick="tutorial_show('<?php echo $item->tutorial_id;?>');"></a>
		</div>
	</div>
	<div class="tutorial_list_info">
		<div class="tutorial_list_info_top">
			<?php if(empty($this->linked)): ?>
				<div class="tutorial_list_title tutorial_list_title_exp">
				<a href="<?php echo $item->getHref(); ?>" onClick="tutorial_show('<?php echo $item->tutorial_id;?>'); return false;"><?php echo $item->getTitle();?></a>
				</div></div>
				<div id='<?php echo "tutorial_hide_" . $item->tutorial_id ?>' class="tutorial_list_info_blurb seaocore_txt_light tutorial_details_hide">
					<?php echo Engine_Api::_()->sitetutorial()->truncateText($item->getFullDescription(), 140); ?>
				</div>
				<div id='<?php echo "tutorial_expand_" . $item->tutorial_id ?>' class="tutorial_details_expand sitetutorial_tutorial_body" style="display:none">

			<?php else: ?>
        </div>
				<div class="sitetutorial_tutorial_body">
			<?php endif; ?>
				<?php echo $item->getFullDescription(); ?>
				<div class="tutorial_list_btm_link">
					<a href="javascript:void(0);" onClick="tutorial_show('<?php echo $item->tutorial_id;?>');"><?php echo $this->translate("[Hide]");?></a>
				</div>
		</div>
	</div>
</li>
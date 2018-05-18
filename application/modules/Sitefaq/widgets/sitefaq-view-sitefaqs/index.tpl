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

<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
  	              . 'application/modules/Sitefaq/externals/styles/style_sitefaq.css');
?>
<script type="text/javascript">

	function helpfulAction(faq_id, viewer_id, helpful,option_id,count) {

    if(helpful == 1 && count != 0) {
			document.getElementById('show_option_'+faq_id).style.display = 'block';
      document.getElementById('showbox_'+faq_id).style.display = 'none';
    }

    if(option_id != '') {
     var helpful = 1;
    }    

    if(helpful == 2 || option_id != '' || count == 0) {
			(new Request.HTML({
				'url' : '<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>',
				'data' : {
					'format' : 'html',
					'helpful' : helpful,
					'faq_id': faq_id,
          'option_id' : option_id,
					'viewer_id': viewer_id,
					'statisticsHelpful': '<?php echo $this->statisticsHelpful; ?>' 
				},
				onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
					$('helpful_content').innerHTML = responseHTML;
          document.getElementById('show_option_'+faq_id).style.display = 'none';
          document.getElementById('showbox_'+faq_id).style.display = 'none';
          document.getElementById('showbox_'+faq_id).innerHTML = '<div class="success_message"><?php echo $this->translate('Thanks for your feedback!') ?></div>';
          document.getElementById('showbox_'+faq_id).style.display = 'block';
				}
			})).send();
    }
	}

</script>

<?php if($this->showBreadCrumb): ?>
	<div class="sitefaq_breadcrumbs">
		<div class="sitefaq_breadcrumbs_txt">

			<?php echo $this->htmlLink($this->url(array(), 'sitefaq_general'), $this->translate('FAQs Home')) ?>
			<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>'; ?>
			<?php echo $this->htmlLink($this->url(array('action' => 'browse'), 'sitefaq_general'), $this->translate('Browse FAQs')) ?>
			<?php if ($this->first_category_name != ''): ?>
				<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>'; ?>
			<?php endif; ?>

			<?php
				$this->first_category_name = $this->translate($this->first_category_name);
				$this->first_subcategory_name = $this->translate($this->first_subcategory_name);
				$this->first_subsubcategory_name = $this->translate($this->first_subsubcategory_name);
			?>
			<?php if ($this->first_category_name != '') :?>
				<?php echo $this->htmlLink($this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name)), 'sitefaq_general_category'), $this->translate($this->first_category_name)) ?>
				<?php if ($this->first_subcategory_name != ''):?> 
					<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>'; ?>
					<?php echo $this->htmlLink($this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name)), 'sitefaq_general_subcategory'), $this->translate($this->first_subcategory_name)) ?>
					<?php if(!empty($this->first_subsubcategory_name)):?>
						<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>';?>
						<?php echo $this->htmlLink($this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name),'subsubcategory' => $this->first_subsubcategory_id, 'subsubcategoryname' => $this->categoryTable->getCategorySlug($this->first_subsubcategory_name)), 'sitefaq_general_subsubcategory'),$this->translate($this->first_subsubcategory_name)) ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>	
	</div>
<?php endif; ?>

<div class="seaocore_gutter_view">
	<div class='seaocore_gutter_view_title sitefaq_view_title'>
		<h3><?php echo $this->sitefaq->getTitle(); ?></h3>
	</div>
  
  <div class="seaocore_gutter_view_body sitefaq_faq_body">
  	<?php echo $this->sitefaq->getFullDescription(); ?>
  </div>

	<!--CUSTOM FIELD WORK -->
	<div class="sitefaq_faq_custom">
		<?php echo html_entity_decode($this->fieldValueLoop($this->sitefaq, $this->fieldStructure)) ?>
	</div>
	<!--END CUSTOM FIELD WORK -->

	<?php if(!empty($this->helpful_allow) || (!empty($this->statisticsHelpful) && $this->sitefaq->helpful >= 0)): ?>
		<div id="helpful_content" class="sitefaq_helpful_content">
			<?php include APPLICATION_PATH . '/application/modules/Sitefaq/views/scripts/helpful_content.tpl'; ?>
		</div>
	<?php endif; ?>

	<br/>
</div>

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
	var show_setting = '<?php echo $this->show; ?>';
  function faq_show(id) {

		if($('subcat_'+id).style.display == 'none') {
			$('subcat_'+id).style.display = 'block';
			$('faq_hide_'+id).setStyle('display', 'block');
			$('faq_expand_'+id).hide();
		}
		else {
			$('subcat_'+id).style.display = 'none';
			$('faq_hide_'+id).hide();
			$('faq_expand_'+id).setStyle('display', 'block');
		}
	}

	window.addEvent('domready', function() {
		if(show_setting == 3) {
			$$('.sitefaq_hide').each(function(el){
				el.style.display = 'none';
			});
			$$('.sitefaq_category_box').each(function(el){
				el.style.display = 'none';
			});
		}
		else {
			$$('.sitefaq_expand').each(function(el){
				el.style.display = 'none';
			});
		}
	});
</script>

<div class="sitefaq_faq_category">
	<div>
	    <?php $k = 0; ?>
	    <?php for ($i = 0; $i <= count($this->categories); $i++): ?>
				<?php if(empty($this->show)):?>
					<div class="sitefaq_faq_category_list sitefaq_category_list2">
				<?php else: ?>
					<div class="sitefaq_faq_category_list sitefaq_category_list1">
				<?php endif;?>
					<?php 
						$category = "";
						if (isset($this->categories[$k]) && !empty($this->categories[$k])) {
							$category = $this->categories[$k];
						}
						$k++;
						if(empty($category)) { 
							break;
						}
					?>
						
					<div class="sitefaq_category_header">
						<a href="<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name'])), 'sitefaq_general_category'); ?>"><?php if(!empty($category['file_id'])): ?><img alt="" src="<?php echo $this->storage->get($category['file_id'], '')->getPhotoUrl(); ?>" /><?php endif;?><?php echo $this->translate($category['category_name']);?></a><?php if($this->show_count): ?><span> (<?php echo $category['count'] ?>)</span><?php endif;?>
	
						<div id="faq_expand_<?php echo $category['category_id'] ?>" class="sitefaq_expand show_hide_link">
							<a href='javascript:void(0);' onclick="javascript:faq_show('<?php echo $category['category_id'] ?>');" title="<?php echo $this->translate("Expand");?>"></a>
						</div>
						<div id="faq_hide_<?php echo $category['category_id'] ?>" class="sitefaq_hide show_hide_link">
							<a href='javascript:void(0);' onclick="javascript:faq_show('<?php echo $category['category_id'] ?>');" title="<?php echo $this->translate("Hide");?>"></a>
						</div>
	
					</div>
					<div id="subcat_<?php echo $category['category_id'] ?>" class="sitefaq_category_box">
						<?php $subcats_count = $count = 0; $total_subcats = Count($category['sub_categories']);?>
						<?php foreach ($category['sub_categories'] as $subcategory) : ?>
							<?php if($count % 2 == 0): $subcats_count = 1;?>
								<div class="sitefaq_subcategory_list_row">
							<?php endif; ?>
							<div class="sitefaq_subcategory_list">
								<?php $subcategoryname = $this->translate($subcategory['sub_cat_name']) ;
								?>
								<div class="sitefaq_subcategory_header">
										<a href="<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name']), 'subcategory' => $subcategory['sub_cat_id'], 'subcategoryname' => $this->tableCategory->getCategorySlug($this->translate($subcategory['sub_cat_name']))), 'sitefaq_general_subcategory')?>"><?php if(!empty($subcategory['file_id'])): ?><img alt="" src="<?php echo $this->storage->get($subcategory['file_id'], '')->getPhotoUrl(); ?>" /><?php endif;?><?php echo $this->translate($subcategoryname); ?></a><?php if($this->show_count): ?><span> (<?php echo $subcategory['count'] ?>)</span><?php endif; ?>
								</div>	
	
								<?php if($this->show == 2 || $this->show == 3): ?>
									<ul class="sitefaq_subcategory_box">
										<?php $subcategoryFaqs = $this->tableFaq->getFaqs($subcategory['sub_cat_id'], 'subcategory_id', 1, 0, 0, $this->faq_limit, 0); ?>
										<?php foreach ($subcategoryFaqs as $sitefaq):?>
											<li>
												<?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $category['category_id'], 'subcategory_id' => $subcategory['sub_cat_id']), 'sitefaq_view'), $this->sitefaq_api->truncateText($sitefaq->getTitle(), $this->title_truncation)) ?>
											</li>
										<?php endforeach;?>
									</ul>
								<?php elseif($this->show == 1): ?>
									<ul class="sitefaq_third_level_category">
										<?php foreach ($subcategory['subsub_categories'] as $subsubcategory):?>
	
											<?php $subsubcategoryname = $this->translate($subsubcategory['subsub_cat_name']); ?>
	
											<li>
												<a href="<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name']), 'subcategory' => $subcategory['sub_cat_id'], 'subcategoryname' => $this->tableCategory->getCategorySlug($this->translate($subcategory['sub_cat_name'])), 'subsubcategory' => $subsubcategory['subsub_cat_id'], 'subsubcategoryname' => $this->tableCategory->getCategorySlug($this->translate($subsubcategory['subsub_cat_name']))), 'sitefaq_general_subsubcategory')?>"><?php if(!empty($subsubcategory['file_id'])): ?><img alt="" src="<?php echo $this->storage->get($subsubcategory['file_id'], '')->getPhotoUrl(); ?>" /><?php endif;?><?php echo $this->translate($subsubcategoryname)?></a><?php if($this->show_count): ?><span> (<?php echo $subsubcategory['count'] ?>)</span><?php endif; ?>
											</li>
	
										<?php endforeach;?>
									</ul>
								<?php endif; ?>
							</div>
	
							<?php $categoryFaqs = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, $this->faq_limit, 0);?>
							<?php $categoryFaqsCount = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, 0, 1);?>
							<?php $total_faqs = 0; foreach ($categoryFaqs as $sitefaq): $total_faqs++?>
								<?php  
									$show_this_faq = 1;
									$decoded_category_ids =  Zend_Json_Decoder::decode($sitefaq->category_id);
									$decoded_subcategory_ids =  Zend_Json_Decoder::decode($sitefaq->subcategory_id);
									foreach($decoded_category_ids as $key => $value) {
										if($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
											$show_this_faq = 0;
										}
									}
	
									if(empty($show_this_faq)) {
										$total_faqs--;
										continue;
									}
								?>
							<?php endforeach;?>
							<?php if($total_faqs && ($this->show == 2 || $this->show == 3) && $count == $total_subcats - 1 && Count($categoryFaqs) && $total_subcats % 2 == 1): ?>
								<div id='others_odd' class="sitefaq_subcategory_list">
									<?php if($total_subcats > 0):?>
										<div class="sitefaq_subcategory_header">
											<?php echo $this->translate('Others').' <span>('.$categoryFaqsCount.')</span>';?>
										</div>
									<?php endif; ?>
	
									<ul class="sitefaq_subcategory_box">
										<?php $show_this_faq = 0; ?>
										<?php foreach ($categoryFaqs as $sitefaq) : ?>
											<?php  
												$show_this_faq = 1;
												$decoded_category_ids =  Zend_Json_Decoder::decode($sitefaq->category_id);
												$decoded_subcategory_ids =  Zend_Json_Decoder::decode($sitefaq->subcategory_id);
												foreach($decoded_category_ids as $key => $value) {
													if($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
														$show_this_faq = 0;
													}
												}
	
												if(empty($show_this_faq)) {
													continue;
												}
											?>
	
											<li>
												<?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $category['category_id']), 'sitefaq_view'), $this->sitefaq_api->truncateText($sitefaq->getTitle(), $this->title_truncation)) ?>
											</li>
										<?php endforeach;?>
									</ul>
								</div> 
							<?php endif; ?>
	
							<?php if($subcats_count == 2 || ($total_subcats % 2 == 1 && $count == $total_subcats - 1)): ?>
								</div>
							<?php endif; ?>
							<?php $count++; $subcats_count++; ?>
						<?php endforeach; ?>
	
						<?php $categoryFaqs = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, $this->faq_limit, 0);?>
						<?php $categoryFaqsCount = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, 0, 1);?>
						<?php $total_faqs = 0; foreach ($categoryFaqs as $sitefaq): $total_faqs++?>
							<?php  
								$show_this_faq = 1;
								$decoded_category_ids =  Zend_Json_Decoder::decode($sitefaq->category_id);
								$decoded_subcategory_ids =  Zend_Json_Decoder::decode($sitefaq->subcategory_id);
								foreach($decoded_category_ids as $key => $value) {
									if($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
										$show_this_faq = 0;
									}
								}
	
								if(empty($show_this_faq)) {
									$total_faqs--;
									continue;
								}
							?>
						<?php endforeach;?>
	
						<?php if($total_faqs && ($this->show == 2 || $this->show == 3) && Count($categoryFaqs) && $total_subcats % 2 == 0): ?>
							<div class="sitefaq_subcategory_list_row">
								<div class="sitefaq_subcategory_list">
	
									<?php if($total_subcats > 0):?>
										<div class="sitefaq_subcategory_header">
											<?php echo $this->translate('Others').' <span>('.$categoryFaqsCount.')</span>';?>
										</div>
									<?php endif; ?>
	
									<ul class="sitefaq_subcategory_box">
										<?php foreach ($categoryFaqs as $sitefaq):?>
											<?php  
												$show_this_faq = 1;
												$decoded_category_ids =  Zend_Json_Decoder::decode($sitefaq->category_id);
												$decoded_subcategory_ids =  Zend_Json_Decoder::decode($sitefaq->subcategory_id);
												foreach($decoded_category_ids as $key => $value) {
													if($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
														$show_this_faq = 0;
													}
												}
	
												if(empty($show_this_faq)) {
													continue;
												}
											?>
											<li>
												<?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $category['category_id']), 'sitefaq_view'), $this->sitefaq_api->truncateText($sitefaq->getTitle(), $this->title_truncation)) ?>
											</li>
										<?php endforeach;?>
									</ul>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
	    <?php endfor; ?>
		</div>    
		<div class="sitefaq_additional_links">
			<?php if($this->forumEnabled):?>
				<?php echo $this->htmlLink(array('route'=>'forum_general'), $this->translate("Community Forum"), array('class'=>'faq_icon_forum'));?>
			<?php endif;?>
			
			<?php if($this->feedbackEnabled):?>
				<?php echo $this->htmlLink(array('route'=>'feedback_browse'), $this->translate("Feedback"), array('class'=>'faq_icon_feedback'));?>
			<?php endif;?>
		</div>
	</div>	
</div>

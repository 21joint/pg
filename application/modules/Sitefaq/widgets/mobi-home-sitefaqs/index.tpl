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

<div class="sitefaq_mobi_faq_category">
  <?php $k = 0; ?>
  <?php for ($i = 0; $i <= count($this->categories); $i++) { ?>

		<div class="sitefaq_mobi_faq_category_list">
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
				
			<div class="sitefaq_mobi_category_header">
				<a href='<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name'])), 'sitefaq_general_category')?>'><?php if(!empty($category['file_id'])): ?>
					<img alt="" src="<?php echo $this->storage->get($category['file_id'], '')->getPhotoUrl(); ?>" />
				<?php endif;?><?php echo $this->translate($category['category_name']); ?></a><span>(<?php echo $category['count'] ?>)</span>
			</div>
			<div id="subcat_<?php echo $category['category_id'] ?>" class="sitefaq_mobi_category_box">
				<?php $subcats_count = $count = 0; $total_subcats = Count($category['sub_categories']);?>
				<?php foreach ($category['sub_categories'] as $subcategory) : ?>
					<div class="sitefaq_mobi_subcategory_list">
						<?php $subcategoryname = $this->translate($subcategory['sub_cat_name']) ; ?>
							<a href='<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name']), 'subcategory' => $subcategory['sub_cat_id'], 'subcategoryname' => $this->tableCategory->getCategorySlug($this->translate($subcategory['sub_cat_name']))), 'sitefaq_general_subcategory')?>'>
							<?php if(!empty($subcategory['file_id'])): ?>
								<img alt="" src="<?php echo $this->storage->get($subcategory['file_id'], '')->getPhotoUrl(); ?>" />
							<?php endif;?><?php echo $this->translate($subcategory['sub_cat_name']); ?><span> (<?php echo $subcategory['count'] ?>)</span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
  <?php } ?> 
</div>
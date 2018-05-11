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
<ul  class="ui-listview collapsible-listview" >
    <?php $k = 0; ?>
    <?php for ($i = 0; $i <= count($this->categories); $i++): ?>
        <?php
        $category = "";
        if (isset($this->categories[$k]) && !empty($this->categories[$k])) {
            $category = $this->categories[$k];
        }
        $k++;
        if (empty($category)) {
            break;
        }
        ?>
        <li class="ui-btn ui-btn-icon-right  ui-li-has-count ui-li-has-arrow ui-li ui-btn-up-c">
            <div class="collapsible_icon" >
                <span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span>
            </div>
            <div class="ui-btn-inner ui-li" >
                <!--Header-->
                <div class="ui-btn-text">
                    <a class="ui-link-inherit" href="<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name'])), 'sitefaq_general_category'); ?>"> 
                        <?php echo $this->translate($category['category_name']); ?>
                        <span class="ui-li-count ui-btn-up-c ui-btn-corner-all">
                            <?php echo $category['count'] ?>
                        </span>
                    </a>
                </div>
                <!--Header-->
                <span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span>
            </div>

            <ul class="collapsible">
                    <?php
                    $subcats_count = $count = 0;
                    $total_subcats = Count($category['sub_categories']);
                    ?>
                    <?php foreach ($category['sub_categories'] as $subcategory) : ?>
                     
                                <?php $subcategoryname = $this->translate($subcategory['sub_cat_name']);
                                ?>
                                <!--Sub header-->
                               <li data-role="list-divider" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-btn-up-c">
                                  <div class="collapsible_icon_none"><span class="ui-icon ui-icon-arrow-right ui-icon-shadow">&nbsp;</span></div>
                                  <div class="ui-btn-inner ui-li"><div class="ui-btn-text">
                                    <a class="ui-link-inherit" href="<?php echo $this->url(array('category' => $category['category_id'], 'categoryname' => $this->tableCategory->getCategorySlug($category['category_name']), 'subcategory' => $subcategory['sub_cat_id'], 'subcategoryname' => $this->tableCategory->getCategorySlug($this->translate($subcategory['sub_cat_name']))), 'sitefaq_general_subcategory') ?>">
                                          <?php echo $this->translate($subcategoryname); ?>
                                      <?php if ($this->show_count): ?>
                                          <span class="ui-li-count ui-btn-up-c ui-btn-corner-all fright">
                                            <?php echo $subcategory['count'] ?>
                                          </span>
                                      <?php endif; ?>
                                      </a>
                                  </div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div>
                               </li>	
                                <!--Sub header-->
                                <!--  UL of questions-->
                          
                                        <?php $subcategoryFaqs = $this->tableFaq->getFaqs($subcategory['sub_cat_id'], 'subcategory_id', 1, 0, 0, $this->faq_limit, 0); ?>
                                        <?php foreach ($subcategoryFaqs as $sitefaq): ?>
                                            <!--Question-->
                                            <li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li  ui-btn-up-c faq_category_ques">
                                              <div class="collapsible_icon_none" ><span class="ui-icon ui-icon-circle ui-icon-shadow">&nbsp;</span></div>
                     
                                              <div class="ui-btn-inner ui-li"><div class="ui-btn-text">
                                              <?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $category['category_id'], 'subcategory_id' => $subcategory['sub_cat_id']), 'sitefaq_view'), $this->sitefaq_api->truncateText($sitefaq->getTitle(), $this->title_truncation), array('class' => 'ui-link-inherit')) ?>
                                            </div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div>
                                            </li>
                                            <!--Question-->
                                        <?php endforeach; ?>
                    
                                <!-- UL of questions-->

                            <?php $categoryFaqs = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, $this->faq_limit, 0); ?>
                            <?php $categoryFaqsCount = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, 0, 1); ?>
                            <?php
                            $total_faqs = 0;
                            foreach ($categoryFaqs as $sitefaq): $total_faqs++
                                ?>
                                <?php
                                $show_this_faq = 1;
                                $decoded_category_ids = Zend_Json_Decoder::decode($sitefaq->category_id);
                                $decoded_subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
                                foreach ($decoded_category_ids as $key => $value) {
                                    if ($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
                                        $show_this_faq = 0;
                                    }
                                }

                                if (empty($show_this_faq)) {
                                    $total_faqs--;
                                    continue;
                                }
                                ?>
                            <?php endforeach; ?>
                            <?php if ($total_faqs && $count == $total_subcats - 1 && Count($categoryFaqs) && $total_subcats % 2 == 1): ?>
                              <?php if ($total_subcats > 0): ?>
                                <li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-btn-up-c">
                                  <div class="collapsible_icon_none"><span class="ui-icon ui-icon-arrow-right ui-icon-shadow">&nbsp;</span></div>
                                  <div class="ui-btn-inner ui-li"><div class="ui-li-static ui-li" style="text-align: left;">
                                    <?php echo $this->translate('Others'); ?>
                                      <?php if ($this->show_count): ?>
                                          <span class="ui-li-count ui-btn-up-c ui-btn-corner-all fright">
                                            <?php // echo $subcategory['$categoryFaqsCount'] ?>
                                            <?php echo $categoryFaqsCount; ?>
                                          </span>
                                      <?php endif; ?>
                                  </div>
                                  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div>
                                </li>
                              <?php endif; ?>


                                        <?php $show_this_faq = 0; ?>
                                        <?php foreach ($categoryFaqs as $sitefaq) : ?>
                                            <?php
                                            $show_this_faq = 1;
                                            $decoded_category_ids = Zend_Json_Decoder::decode($sitefaq->category_id);
                                            $decoded_subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
                                            foreach ($decoded_category_ids as $key => $value) {
                                                if ($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
                                                    $show_this_faq = 0;
                                                }
                                            }

                                            if (empty($show_this_faq)) {
                                                continue;
                                            }
                                            ?>

                                           <li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li  ui-btn-up-c faq_category_ques">
                                                
                                             
                                             
 <div class="collapsible_icon_none" ><span class="ui-icon ui-icon-circle ui-icon-shadow">&nbsp;</span></div>
                     
                                              <div class="ui-btn-inner ui-li"><div class="ui-btn-text">
                                              <?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $category['category_id']), 'sitefaq_view'), $this->sitefaq_api->truncateText($sitefaq->getTitle(), $this->title_truncation), array('class' => 'ui-link-inherit')) ?>
                                            </div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div>
                                            </li>
                                        <?php endforeach; ?>


                            <?php endif; ?>

                          
                        <?php
                        $count++;
                        $subcats_count++;
                        ?>
                    <?php endforeach; ?>

                    <?php $categoryFaqs = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, $this->faq_limit, 0); ?>
                    <?php $categoryFaqsCount = $this->tableFaq->getFaqs($category['category_id'], 'category_id', 1, 1, 0, 0, 1); ?>
                    <?php
                    $total_faqs = 0;
                    foreach ($categoryFaqs as $sitefaq): $total_faqs++
                        ?>
                        <?php
                        $show_this_faq = 1;
                        $decoded_category_ids = Zend_Json_Decoder::decode($sitefaq->category_id);
                        $decoded_subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
                        foreach ($decoded_category_ids as $key => $value) {
                            if ($value == $category['category_id'] && $decoded_subcategory_ids[$key] != 0) {
                                $show_this_faq = 0;
                            }
                        }

                        if (empty($show_this_faq)) {
                            $total_faqs--;
                            continue;
                        }
                        ?>
                    <?php endforeach; ?>
            </ul>
        </li>
    <?php endfor; ?>    	
</ul>
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
<?php if (!empty($this->first_category_name) || Count($this->paginator) > 0 || (isset($this->formValues['tag']) && !empty($this->formValues['tag']) && isset($this->formValues['tag_id']) && !empty($this->formValues['tag_id']))): ?>
<!--Breadcrumb Display work-->
<?php
$this->first_category_name = $this->translate($this->first_category_name);
$this->first_subcategory_name = $this->translate($this->first_subcategory_name);
$this->first_subsubcategory_name = $this->translate($this->first_subsubcategory_name);

$firstcategory = array();
$firstsubcategory = array();
$firstsubsubcategory = array();
if ($this->first_category_name != ''){ 
    if(!empty($this->first_subcategory_name) || !empty($this->first_subsubcategory_name)){
    $icon = "arrow-r";}else{ $icon = "arrow-d"; }
    
    $firstcategory = array("href" => $this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name)), "sitefaq_general_category"), "title" => $this->translate($this->first_category_name), "icon" => $icon);
}
if ($this->first_subcategory_name != '') { 
    if(!empty($this->first_subsubcategory_name)){
    $icon = "arrow-r";}else{ $icon = "arrow-d"; }
    
    $firstsubcategory = array("href" => $this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name)), 'sitefaq_general_subcategory'), "title" => $this->translate($this->first_subcategory_name), "icon" => $icon);
}
if (!empty($this->first_subsubcategory_name)) { 
    $firstsubsubcategory = array("href" => $this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name),'subsubcategory' => $this->first_subsubcategory_id, 'subsubcategoryname' => $this->categoryTable->getCategorySlug($this->first_subsubcategory_name)), 'sitefaq_general_subsubcategory'), "title" => $this->translate($this->first_subsubcategory_name), "icon" => "arrow-d");
}

if ($this->first_category_name != ''){
$breadcrumb = array(
    array("href" => $this->url(array('action' => 'browse'), 'sitefaq_general', false), "title" => "Browse FAQs", "icon" => "arrow-r"),
    $firstcategory, $firstsubcategory,$firstsubsubcategory,
);

echo $this->breadcrumb($breadcrumb);
}
?>
<!--Breadcrumb Display work-->

<?php endif; ?>

<?php if (count($this->paginator) > 0): ?>
    <ul  class="ui-listview collapsible-listview" >
        <?php foreach ($this->paginator as $sitefaq): ?>
            <li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c">
                <div class="collapsible_icon" >
                    <span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span>
                </div>
                <div class="ui-btn-inner ui-li" >
                    <div class="ui-btn-text">
                        <a class="ui-link-inherit" href="<?php echo $sitefaq->getHref() ?>"  >
                            <?php echo $this->translate($sitefaq->getTitle(true)); ?></a>
                    </div>
                    <span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span>
                </div>
                <div class="collapsible o_hidden">
                  <div class="browse_faq_cont">
                    <?php echo $this->sitefaq_api->truncateText($sitefaq->getFullDescription(), 100); ?>
                    <span class="browse_faq_stats clr dblock t_light">
                      <?php if ($this->statisticsComment): ?>
                          <?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?>,
                          <?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if ($this->statisticsView): ?>,<?php endif; ?>
                      <?php endif; ?>
                      <?php if ($this->statisticsView): ?>
                          <?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?>
                      <?php endif; ?>
                    </span>
                  </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if (count($this->paginator) > 1): ?>
        <div class="seaocore_pagination">
            <?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues, 'pageAsQuery' => true,)); ?>
        </div>
    <?php endif; ?>
<?php elseif (count($this->paginator) <= 0 && ((isset($this->formValues['search_form']) && !empty($this->formValues['search_form'])) || !empty($this->first_category_name) || (isset($this->formValues['search']) && !empty($this->formValues['search'])) || (isset($this->formValues['tag']) && !empty($this->formValues['tag'])))): ?>
    <div class="tip">
        <span>
            <?php echo $this->translate('No FAQs matching with that criteria could be found. Please try a different search.'); ?>
        </span>
    </div>
<?php else: ?>
    <div class="tip">
        <span>
            <?php echo $this->translate('No FAQs has been found.'); ?>
        </span>
    </div>
<?php endif; ?>

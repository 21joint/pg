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
<!--Breadcrumb display work-->
<?php
$this->first_category_name = $this->translate($this->first_category_name);
$this->first_subcategory_name = $this->translate($this->first_subcategory_name);
$this->first_subsubcategory_name = $this->translate($this->first_subsubcategory_name);

$firstcategory = array();
$firstsubcategory = array();
$firstsubsubcategory = array();
if ($this->first_category_name != '') {
    $firstcategory = array("href" => $this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name)), "sitefaq_general_category"), "title" => $this->translate($this->first_category_name), "icon" => "arrow-r");
}
if ($this->first_subcategory_name != '') {
    $firstsubcategory = array("href" => $this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name)), 'sitefaq_general_subcategory'), "title" => $this->translate($this->first_subcategory_name), "icon" => "arrow-r");
}
if (!empty($this->first_subsubcategory_name)) {
    $firstsubsubcategory = array("href" => $this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name), 'subsubcategory' => $this->first_subsubcategory_id, 'subsubcategoryname' => $this->categoryTable->getCategorySlug($this->first_subsubcategory_name)), 'sitefaq_general_subsubcategory'), "title" => $this->translate($this->first_subsubcategory_name), "icon" => "arrow-r");
}

$breadcrumb = array(
    array("href" => $this->url(array(), 'sitefaq_general', false), "title" => "FAQs Home", "icon" => "arrow-r"),
    array("href" => $this->url(array('action' => 'browse'), 'sitefaq_general', false), "title" => "Browse FAQs", "icon" => "arrow-r"),
    $firstcategory, $firstsubcategory, $firstsubsubcategory,
    array("title" => $this->sitefaq->getTitle(), "icon" => "arrow-d"),
);

echo $this->breadcrumb($breadcrumb);
?>
<!--END, Breadcrumb display work-->

<div class="ui-page-content">
    <div class="sm-ui-cont-head">
        <div class="sm-ui-cont-cont-info">
            <div class="sm-ui-cont-author-name">
                <?php echo $this->sitefaq->getTitle(); ?>
            </div>
            <!--Render widgets for Information -->
            <?php echo $this->content()->renderWidget("sitefaq.featured-view-sitefaqs"); ?>
            
            <!--Start, Information widget code-->
            <form id='filter_form' class='global_form_box' method='get' action='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) ?>' style='display: none;'>
                <input type="hidden" id="tag" name="tag" value=""/>
                <input type="hidden" id="tag_id" name="tag_id" value=""/>
            </form>
            	<!--GET TAGS-->
		<?php if(!empty($this->params['tags'])) {
			$this->sitefaqTags = $this->sitefaq->tags()->getTagMaps();
		}?>

            <div class="sm-ui-cont-cont-date">
                    <?php echo $this->translate('Posted by %s', $this->sitefaq->getOwner()->toString()); ?>
                         -
                    <?php echo $this->translate(array('%s view', '%s views', $this->sitefaq->view_count), $this->locale()->toNumber($this->sitefaq->view_count)) ?>
                <br/>
                <?php if (!empty($this->params['update'])): ?>
                    <?php echo $this->translate('Updated:') ?>
                    <?php echo $this->timestamp($this->sitefaq->modified_date); ?>
                <?php endif; ?>
                <?php if (isset($this->params['created']) && $this->params['created']): ?>
                    -
                    <?php echo $this->translate('Created about %s', $this->timestamp($this->sitefaq->creation_date)) ?>
                <?php endif; ?><br/>
                <?php if (isset($this->params['tags']) && $this->params['tags'] && count($this->sitefaqTags) > 0): ?>
                    <?php echo $this->translate('Topics Covered:') ?>
                    <?php foreach ($this->sitefaqTags as $tag): ?>
                        <?php if (!empty($tag->getTag()->text)): ?>
                            <?php $string = $this->string()->escapeJavascript($tag->getTag()->text); ?>
                            <a href='javascript:void(0);' onclick='tagAction("<?php echo $string; ?>", <?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- END,Information widget code-->

        </div>
    </div>
    <div class="sm-ui-cont-cont-des">
        <?php echo $this->sitefaq->getFullDescription(); ?>
    </div>
    <!--CUSTOM FIELD WORK -->
    <?php echo html_entity_decode($this->fieldValueLoop($this->sitefaq, $this->fieldStructure)) ?>
    <!--END CUSTOM FIELD WORK -->

    <?php if (!empty($this->helpful_allow) || (!empty($this->statisticsHelpful) && $this->sitefaq->helpful >= 0)): ?>
        <div id="helpful_content" class="sitefaq_helpful_content">
            <?php include APPLICATION_PATH . '/application/modules/Sitefaq/views/sitemobile/scripts/helpful_content.tpl'; ?>
        </div>
    <?php endif; ?>
</div>

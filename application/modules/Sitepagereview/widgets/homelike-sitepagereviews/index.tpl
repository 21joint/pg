<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<ul class="sitepage_sidebar_list">
  <?php foreach ($this->paginator as $review): ?>
    <?php  $this->partial()->setObjectKey('review');
			echo $this->partial('application/modules/Sitepagereview/views/scripts/partialWidget.tpl', $review);
		?>     
          <div class='sitepage_sidebar_list_details'>
						<?php $page_title = Engine_Api::_()->sitepagereview()->truncateText($review->page_title, 18); ?>
						<?php echo $this->translate("on ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($review->page_id, $review->owner_id, $review->getSlug()), $page_title, array('title' => $review->page_title,'class' => 'bold')) ?>
			    </div>
          <?php echo $this->translate(array('%s like', '%s likes', $review->like_count), $this->locale()->toNumber($review->like_count)) ?>,
          <?php echo $this->translate(array('%s view', '%s views', $review->view_count), $this->locale()->toNumber($review->view_count)) ?>
        </div>

        <div class='sitepage_sidebar_list_details'>
          <span title="<?php echo $review->rating . $this->translate(' rating'); ?>">
            <?php if (($review->rating > 0)): ?>
              <?php for ($x = 1; $x <= $review->rating; $x++): ?>
                <span class="rating_star_generic rating_star"></span>
              <?php endfor; ?>
              <?php if ((round($review->rating) - $review->rating) > 0): ?>
                <span class="rating_star_generic rating_star_half"></span>
              <?php endif; ?>
            <?php endif; ?>
          </span>
        </div> 
      </div>
    </li>
  <?php endforeach; ?>
  <li class="sitepage_sidebar_list_seeall">
		<a href='<?php echo $this->url(array('likedreview'=> 1), 'sitepagereview_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
	</li>
</ul>
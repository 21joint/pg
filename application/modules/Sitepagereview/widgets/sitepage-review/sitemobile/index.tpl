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
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1); ?>
<?php if ($this->paginator->getTotalItemCount()): ?>
<?php if(!$this->autoContentLoad) : ?>
  <form id='filter_form_page' class='global_form_box' method='get' action='<?php echo $this->url(array(), 'sitepagereview_browse', true) ?>' style='display: none;'>
    <input type="hidden" id="page" name="page"  value=""/>
  </form>
<div class="sm-content-list">
  <ul data-role="listview" data-inset="false"  id="browsepagereview_ul">
<?php endif;?>
    <?php foreach ($this->paginator as $review): ?>
      <li <?php if (Engine_Api::_()->sitemobile()->isApp()): ?>data-icon="angle-right"<?php else: ?>data-icon="arrow-r"<?php endif?>>
      <a href='<?php echo $review->getHref(); ?>'>   
          <?php $user = Engine_Api::_()->getItem('user', $review->owner_id); ?>
          <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $review->page_id); ?>
          <?php if (!empty($photo_review)): ?>
            <?php echo $this->itemPhoto($user, 'thumb.icon') ?>
          <?php else: ?>
            <?php echo $this->itemPhoto($sitepage_object, 'thumb.icon') ?>
          <?php endif; ?>
        
          <h3><?php echo Engine_Api::_()->sitepagereview()->truncateText($review->title, 60) ?></h3>
          <p><?php echo $this->translate("in ") ?>
            <b><?php echo $sitepage_object->title ?> </b>             
          </p>
        <?php $ratingData = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->profileRatingbyCategory($review->review_id); ?>
        				<div class="sitepagereview_overallrating sitepagereview_browse_overallrating">		
        <?php foreach ($ratingData as $reviewcat): ?>
                      <div class="sitepagereview_overallrating_rate">
                        <div class="title">
          <?php if (empty($reviewcat['reviewcat_name'])): ?>
 
<!--                              <b><?php echo $this->translate("Overall Rating"); ?></b>-->
          <?php endif; ?>
                        </div>
          <?php if (empty($reviewcat['reviewcat_name'])): ?>
						<p>
							<?php //foreach ($ratingData as $reviewcat): ?>
								<?php if( $ratingData[0]['rating'] > 0 ): ?>
									<?php for( $x=1; $x<=$ratingData[0]['rating']; $x++ ): ?>
										<span class="rating_star_generic rating_star"></span>
									<?php endfor; ?>
									<?php if( (round( $ratingData[0]['rating']) - $ratingData[0]['rating']) > 0): ?>
										<span class="rating_star_generic rating_star_half"></span>
									<?php endif; ?>
								<?php endif; ?>
							<?php //endforeach; ?>
            </p>   
          <?php endif; ?>
                      </div>
        <?php endforeach; ?>
                </div>

          <p>
            <?php echo $this->timestamp(strtotime($review->modified_date)) ?>
          -
           <?php echo $this->translate('Posted by') ?>
            <strong><?php echo $review->getOwner()->getTitle(); ?></strong></p>

  <?php if(false):?>
            <?php if ($review->featured == 1): ?>
            <span>
              <?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/icons/sitepagedocument_goldmedal1.png', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
            </span>
          <?php endif; ?>
            <p>
            <?php echo $this->translate(array('%s comment', '%s comments', $review->comment_count), $this->locale()->toNumber($review->comment_count)) ?>,
            <?php echo $this->translate(array('%s view', '%s views', $review->view_count), $this->locale()->toNumber($review->view_count)) ?>,
            <?php echo $this->translate(array('%s like', '%s likes', $review->like_count), $this->locale()->toNumber($review->like_count)) ?>
            </p>

            <p>
          <?php if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1)): ?>  
              <?php if ($review->recommend): ?>
                <?php echo $this->translate("Member's Recommendation:<b> Yes </b>"); ?>
              <?php else: ?>
                <?php echo $this->translate("Member's Recommendation:<b> No </b>"); ?>
              <?php endif; ?>            
          <?php endif; ?>
            </p>
  <?php endif; ?>
  </a>    
</li>
    <?php endforeach; ?>
<?php if(!$this->autoContentLoad) : ?>
  </ul>
  </div>
<?php endif;?>
<?php if( $this->paginator->count() > 1 && !Engine_Api::_()->sitemobile()->isApp() ): ?>
		<?php echo $this->paginationControl($this->paginator, null, null, array(
			'query' => $this->formValues,
		)); ?>
	<?php endif; ?>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('There are no search results to display.'); ?>
    </span>
  </div>
<?php endif; ?>
<script type='text/javascript'>        
<?php if (Engine_Api::_()->sitemobile()->isApp()) { ?>

  var browsePageReviewWidgetUrl = sm4.core.baseUrl + 'widget/index/mod/sitepagereview/name/sitepage-review';
         sm4.core.runonce.add(function() {    
              var activepage_id = sm4.activity.activityUpdateHandler.getIndexId();
              sm4.core.Module.core.activeParams[activepage_id] = {'currentPage' : '<?php echo sprintf('%d', $this->page) ?>', 'totalPages' : '<?php echo sprintf('%d', $this->totalPages) ?>', 'formValues' : '<?php echo json_encode($this->formValues);?>', 'contentUrl' : browsePageReviewWidgetUrl, 'activeRequest' : false, 'container' : 'browsepagereview_ul' };
             
          });
          
  <?php } ?>           
</script>

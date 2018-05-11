<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if (!Engine_Api::_()->sitemobile()->isApp()) :?>
<?php 
$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Reviews","icon"=>"arrow-d")
    );

echo $this->breadcrumb($breadcrumb);
?>
<?php endif;?>
<script type="text/javascript">
	function doRating(element_id, reviewcat_id, classstar) {
   
		$('#'+element_id + '_' + reviewcat_id).parent().parent().removeClass().addClass('rating-box ' + classstar);
		$('#review_rate_' + reviewcat_id).val($('#'+element_id + '_' + reviewcat_id).parent().attr("id"));
	}

	function doDefaultRating(element_id, reviewcat_id, classstar) {
		$('#'+element_id + '_' + reviewcat_id).parent().parent().removeClass().addClass('rating ' + classstar);
		$('#review_rate_' + reviewcat_id).val($('#'+element_id + '_' + reviewcat_id).parent().attr("id"));
	}
</script>
<?php 
//	$this->headLink()
//  ->appendStylesheet($this->layout()->staticBaseUrl
//    . 'application/modules/Sitepagereview/externals/styles/style_sitepagereview.css')
//	->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitepagereview/externals/styles/star-rating.css');
?>

<?php $rating_value_2 = 0;?>
<?php $rating_value_1 = 0;?>

<div class="layout_middle">
	<form id="sitepage_review_create" enctype="application/x-www-form-urlencoded" class="global_form" action='<?php echo $this->url(array('action'=>'create'));?>' method="post">
		<div>
			<div>
        <?php if (!Engine_Api::_()->sitemobile()->isApp()) :?>
				<h3><?php echo $this->translate("Write a Review");?></h3>
				<p><?php echo $this->translate("Give rating and write a review for ").$this->page_title_with_link.$this->translate(" below.") ?></p>
	       <?php else: ?>
        <h3><?php echo $this->translate("For %s", $this->sitepage->title);?></h3>
        <?php endif;?>
				<?php if($this->overallrating_required): ?>
					<ul class="form-errors">
						<li>
							<?php echo $this->translate("Overall Rating");?>
							<ul class="errors">
								<?php echo $this->translate("Please complete this field - it is required.");?>
							</ul>
						</li>
					</ul>
				<?php endif; ?>

				<?php if($this->showProsConsField): ?>
					<?php if($this->pros_required): ?>
						<ul class="form-errors">
							<li>
								<?php echo $this->translate("Pros");?>
								<ul class="errors">
									<?php echo $this->translate("Please complete this field - it is required.");?>
								</ul>
							</li>
						</ul>
					<?php endif; ?>

					<?php if($this->cons_required): ?>
						<ul class="form-errors">
							<li>
								<?php echo $this->translate("Cons");?>
								<ul class="errors">
									<?php echo $this->translate("Please complete this field - it is required.");?>
								</ul>
							</li>
						</ul>
					<?php endif; ?>
				<?php endif; ?>

				<?php if($this->title_required): ?>
					<ul class="form-errors">
						<li>
							<?php echo $this->translate("Review Title");?>
							<ul class="errors">
								<?php echo $this->translate("Please complete this field - it is required.");?>
							</ul>
						</li>
					</ul>
				<?php endif; ?>
	
				<?php if($this->body_required): ?>
					<ul class="form-errors">
						<li>
							<?php echo $this->translate("Review");?>
							<ul class="errors">
								<?php echo $this->translate("Please complete this field - it is required.");?>
							</ul>
						</li>
					</ul>
				<?php endif; ?>
	
				<div class="form-elements">
					
					<?php if(!empty($this->reviewRateData)):?>	
						<?php foreach($this->reviewRateData as $reviewRateData): ?>
							<?php if($reviewRateData['reviewcat_id'] == 0): ?>
								<?php $rating_value_2 = $reviewRateData['rating']; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php 
						switch($rating_value_2) {
							case 0:
									$rating_value = '';
									break;
							case 1:
									$rating_value = 'onestar';
									break;
							case 2:
									$rating_value = 'twostar';
									break;
							case 3:
									$rating_value = 'threestar';
									break;
							case 4:
									$rating_value = 'fourstar';
									break;
							case 5:
									$rating_value = 'fivestar';
									break;
						}
					?>

          <div id="overall_rating" class="form-wrapper">
						<div class="form-label">
							<label>
								<?php echo $this->translate("Overall Rating");?>
							</label>
						</div>	
						<div class="form-element">
							<ul id= 'rate_0' class='rating <?php echo $rating_value; ?>'  style="position:relative;">
								<li id="1" class="rate one"><a href="javascript:void(0);" onclick="doDefaultRating('star_1', '0', 'onestar');" title="1 Star"   id="star_1_0">1</a></li>
								<li id="2" class="rate two"><a href="javascript:void(0);"  onclick="doDefaultRating('star_2', '0', 'twostar');" title="2 Stars"   id="star_2_0">2</a></li>
								<li id="3" class="rate three"><a href="javascript:void(0);"  onclick="doDefaultRating('star_3', '0', 'threestar');" title="3 Stars" id="star_3_0">3</a></li>
								<li id="4" class="rate four"><a href="javascript:void(0);"  onclick="doDefaultRating('star_4', '0', 'fourstar');" title="4 Stars"   id="star_4_0">4</a></li>
								<li id="5" class="rate five"><a href="javascript:void(0);"  onclick="doDefaultRating('star_5', '0', 'fivestar');" title="5 Stars"   id="star_5_0">5</a></li>
							</ul>
							<input type="hidden" name='review_rate_0' id='review_rate_0' value='<?php echo $rating_value_2; ?>' />
						</div>
					</div>
	
					<?php if(!empty($this->total_reviewcats)): ?>
            <div id="rating-box">
              <?php foreach($this->reviewCategory as $reviewcat): ?>
                <?php if(!empty($this->reviewRateData)):?>	
                  <?php foreach($this->reviewRateData as $reviewRateData): ?>
                    <?php if($reviewRateData['reviewcat_id'] == $reviewcat->reviewcat_id): ?>
                      <?php $rating_value_1 = $reviewRateData['rating']; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>

                <?php 
                  switch($rating_value_1) {
                    case 0:
                        $rating_value = '';
                        break;
                    case 1:
                        $rating_value = 'onestar-box';
                        break;
                    case 2:
                        $rating_value = 'twostar-box';
                        break;
                    case 3:
                        $rating_value = 'threestar-box';
                        break;
                    case 4:
                        $rating_value = 'fourstar-box';
                        break;
                    case 5:
                        $rating_value = 'fivestar-box';
                        break;
                  }
                ?>

                <div class="form-wrapper">
                  <div class="form-label">
                    <label>
                      <?php echo $this->translate($reviewcat->reviewcat_name);?>
                    </label>
                  </div>	
                  <div class="form-element">
                    <ul id= 'rate_<?php echo $reviewcat->reviewcat_id; ?>' class='rating-box <?php echo $rating_value; ?>'  style="position:relative;margin-top:10px;">
                      <li id="1" class="rate one"><a href="javascript:void(0);" onclick="doRating('star_1', '<?php echo $reviewcat->reviewcat_id; ?>', 'onestar-box');" id="star_1_<?php echo $reviewcat->reviewcat_id; ?>">1</a></li>
                      <li id="2" class="rate two"><a href="javascript:void(0);" onclick="doRating('star_2', '<?php echo $reviewcat->reviewcat_id; ?>', 'twostar-box');" id="star_2_<?php echo $reviewcat->reviewcat_id; ?>">2</a></li>
                      <li id="3" class="rate three"><a href="javascript:void(0);"  onclick="doRating('star_3', '<?php echo $reviewcat->reviewcat_id; ?>', 'threestar-box');" id="star_3_<?php echo $reviewcat->reviewcat_id; ?>">3</a></li>
                      <li id="4" class="rate four"><a href="javascript:void(0);"  onclick="doRating('star_4', '<?php echo $reviewcat->reviewcat_id; ?>', 'fourstar-box');" id="star_4_<?php echo $reviewcat->reviewcat_id; ?>">4</a></li>
                      <li id="5" class="rate five"><a href="javascript:void(0);"  onclick="doRating('star_5', '<?php echo $reviewcat->reviewcat_id; ?>', 'fivestar-box');" id="star_5_<?php echo $reviewcat->reviewcat_id; ?>">5</a></li>
                    </ul>
                    <input type="hidden" name='review_rate_<?php echo $reviewcat->reviewcat_id; ?>' id='review_rate_<?php echo $reviewcat->reviewcat_id; ?>' value='<?php echo $rating_value_1; ?>' />
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
					<?php endif; ?>

					<?php if($this->showProsConsField): ?>
						<?php $maxlenght = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.limit.proscons', 75);?>
						<div id="pros-wrapper" class="form-wrapper">
							<div id="pros-label" class="form-label">
								<label for="title" class="required"><?php echo $this->translate("Pros");?></label>
							</div>
							<div id="pros-element" class="form-element">
								<?php if($maxlenght == 0): ?>
									<input type="text" name="pros" id="pros" value="<?php echo $this->escape($this->prefield_pros) ?>" />
								<?php else: ?>
									<input type="text" name="pros" id="pros" maxlength="<?php echo $maxlenght;?>" value="<?php echo $this->escape($this->prefield_pros) ?>" />
								<?php endif; ?>
								<p class="description"><?php echo $this->translate("What do you like about this Page?");?></p>
							</div>
						</div>
						
						<div id="cons-wrapper" class="form-wrapper">
							<div id="cons-label" class="form-label">
								<label for="title" class="required"><?php echo $this->translate("Cons");?></label>
							</div>
							<div id="cons-element" class="form-element">
								<?php if($maxlenght == 0): ?>
									<input type="text" name="cons" id="cons" value="<?php echo $this->escape($this->prefield_cons) ?>" />
								<?php else: ?>
									<input type="text" name="cons" id="cons" maxlength="<?php echo $maxlenght;?>" value="<?php echo $this->escape($this->prefield_cons) ?>" />
								<?php endif; ?>
								<p class="description"><?php echo $this->translate("What do you dislike about this Page?");?></p>
							</div>
						</div>
					<?php endif; ?>
					
					<div id="title-wrapper" class="form-wrapper">
						<div id="title-label" class="form-label">
							<label for="title" class="required"><?php echo $this->translate("Review Title");?></label>
						</div>
						<div id="title-element" class="form-element">
							<input type="text" name="title" id="title" maxlength=255 value="<?php echo $this->escape($this->prefield_title) ?>" />
						</div>
					</div>
	
					<div id="body-wrapper" class="form-wrapper">
						<div id="body-label" class="form-label">
							<label for="body" class="required"><?php echo $this->translate("Review");?></label>
						</div>
	
						<div id="body-element" class="form-element">
							<textarea name="body" id="body" cols="180" rows="24" class="sitepagereview_write_txtarea"><?php echo $this->escape($this->prefield_body) ?></textarea>
						</div>
					</div>
	
					<?php if($this->showRecommendField): ?>
						<div id="end_settings-wrapper">
							<div class="form-label">
								<label for="recommend"><?php echo $this->translate("Recommended");?></label>
							</div>
							<div class="form-element" id="end_settings-element">
								<p class="description"><?php echo $this->translate("Would you recommend ").$this->page_title_with_link . $this->translate(" to a friend?");?></p>
								<ul class="form-options-wrapper">
									<li>
                    <fieldset data-role="controlgroup" class="ui-controlgroup ui-controlgroup-vertical ui-corner-all">
                      <div class="ui-controlgroup-controls ">
                        <input type="radio" value="1" id="recommend-1" name="recommend" <?php if($this->prefield_recommand == 1) echo 'checked="checked"'?> />
                        <label for="recommend-1"><?php echo $this->translate("Yes");?></label>

                        <input type="radio" value="0" id="recommend-0" name="recommend" <?php if(empty($this->prefield_recommand)) echo 'checked="checked"'?> />
                        <label for="recommend-0"><?php echo $this->translate("No");?></label>
                      </div>
                    </fieldset>
									</li>
								</ul>
							</div>
						</div>
					<?php endif; ?>
	       
          <?php if (!Engine_Api::_()->sitemobile()->isApp()):?>
					<div class="form-wrapper">
						<div class="form-label">
						</div>	
						<div class="form-element">
							<button name="submit" id="submit" type="submit" data-theme="b">
								<?php echo $this->translate("Submit Review");?>
							</button>

            <div style="text-align: center"><?php echo $this->translate('or'); ?> </div>
            <a href="#" data-rel="back" data-role="button">
              <?php echo $this->translate('Cancel') ?>
            </a>
						</div>	
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</form>
</div>

<style type="text/css">
/*input + p.description {font-size: 11px !important;margin-bottom: 0 !important;}	
ul.rating{background-image: url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitepagereview/externals/images/star-matrix.png);}		
ul.rating li a:hover{background-image: url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitepagereview/externals/images/star-matrix.png);}			*/
</style>

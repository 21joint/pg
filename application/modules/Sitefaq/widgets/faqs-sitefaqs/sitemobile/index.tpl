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
<div class="sm-content-list">
         <ul class="seaocore_browse_list" data-role="listview" data-inset="false" >
            <?php foreach ($this->faqDatas as $sitefaq): ?>
                <li data-icon="arrow-r">
                    <a href="<?php echo $sitefaq->getHref(); ?>">
                            <?php if ($sitefaq->featured == 1): ?>
                                <span class="sitefaq_icon sitefaq_icon_featured"></span>
                            <?php endif; ?>
                        <h3> <?php echo $this->sitefaq_api->truncateText($sitefaq->getTitle(),$this->title_truncation); ?></h3>

                        <p>
                            <?php if (($sitefaq->rating > 0) && $this->statisticsRating): ?>
                                <?php
                                $currentRatingValue = $sitefaq->rating;
                                $difference = $currentRatingValue - (int) $currentRatingValue;
                                if ($difference < .5) {
                                    $finalRatingValue = (int) $currentRatingValue;
                                } else {
                                    $finalRatingValue = (int) $currentRatingValue + .5;
                                }
                                ?>
                                <span class="list_rating_star">
                                    <?php for ($x = 1; $x <= $sitefaq->rating; $x++): ?>
                                        <span class="rating_star_generic rating_star" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>"></span>
                                    <?php endfor; ?>
                                    <?php if ((round($sitefaq->rating) - $sitefaq->rating) > 0): ?>
                                        <span class="rating_star_generic rating_star_half" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>"></span>
                                    <?php endif; ?>
                                </span>	
                            <?php endif; ?>	
                        </p>
                        <p>
                            <?php if ($this->statisticsComment): ?>
                                <?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?> - 
                                <?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if ($this->statisticsView): ?> - <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($this->statisticsView): ?>
                                <?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?>
                            <?php endif; ?>
                        </p>
                        <p>
                            <?php if ($this->statisticsHelpful && $sitefaq->helpful >= 0): ?>
                                <?php echo '<b>' . $sitefaq->helpful . '%</b>' . $this->translate(' helpful.'); ?>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endforeach; ?>
            <?php if ($this->viewAll): ?>
                <div class="t_center t_l">
                    <?php if ($this->featured): ?>
                  <a href='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) . "?orderby=$this->popularity&featured=1" ?>'><b><?php echo $this->translate('View All'); ?></b></a>
                    <?php else: ?>
                        <a href='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) . "?orderby=$this->popularity" ?>'><b><?php echo $this->translate('View All'); ?></b></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </ul>
</div>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: helpful_content.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $option_count = count($this->options); ?>
<!--Displays the popup options on selecting NO--> 
<div data-role="popup" id="show_option_<?php echo $this->faq_id; ?>" style="display:none;" <?php echo $this->dataHtmlAttribs("popup_content", array('data-theme' => "c")); ?> data-tolerance="15"  data-overlay-theme="a" data-theme="none" aria-disabled="false" data-position-to="window">
    <div data-inset="true" style="min-width:150px;" class="sm-options-popup">	
        <b><?php echo $this->translate("Why not?"); ?></b>
        <?php foreach ($this->options as $item): ?>
            <a href='javascript:void(0);'  class = 'ui-btn-default ui-btn-action' onclick="helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '', '<?php echo $item->option_id; ?>', '<?php echo $option_count ?>','<?php echo $this->statisticsHelpful; ?>','<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>');"><?php echo $this->translate($item->reason); ?></a>
        <?php endforeach; ?>
    </div>
</div>
<!--END, Displays the popup options on selecting NO--> 


<?php if (!empty($this->statisticsHelpful) || !empty($this->helpful_allow)): ?>
<div id="showbox_<?php echo $this->faq_id; ?>" class="sm-widget-block">
        <?php if ($this->statisticsHelpful): ?>
            <div class="clr">
              <?php if ($this->totalHelpCount && $this->totalHelpCount != 200): ?>
                  <?php echo '<b>' . $this->totalHelpCount . '%</b>' . $this->translate(' users marked this FAQ as helpful.'); ?>
              <?php elseif ($this->totalHelpCount == 200): ?>
                  <?php echo '<b>0%</b>' . $this->translate(' users marked this FAQ as helpful.'); ?>
              <?php endif; ?>	
            </div>
        <?php endif; ?>
      
        <div id="showmaincontent_<?php echo $this->faq_id; ?>" class="t_l">
            <?php if (empty($this->viewer_id) && !empty($this->helpful_allow)): ?>
                <?php echo $this->translate("Was this answer helpful?"); ?>
                <?php echo $this->htmlLink(Array('route' => 'sitefaq_view', 'faq_id' => $this->faq_id, 'category_id' => $this->first_category_id, 'subcategory_id' => $this->first_subcategory_id, 'subsubcategory_id' => $this->first_subsubcategory_id, 'slug' => $this->faq_slug, 'anonymous' => 1), '<i class="ui-icon ui-icon-thumbs-up"></i> ' . $this->translate("Yes"), array('target' => '_parent', 'class' => 'ui-link-inherit')); ?>
                <?php echo $this->htmlLink(Array('route' => 'sitefaq_view', 'faq_id' => $this->faq_id, 'category_id' => $this->first_category_id, 'subcategory_id' => $this->first_subcategory_id, 'subsubcategory_id' => $this->first_subsubcategory_id, 'slug' => $this->faq_slug, 'anonymous' => 1), '<i class="ui-icon ui-icon-thumbs-down"></i> ' . $this->translate("No"), array('target' => '_parent', 'class' => 'ui-link-inherit')); ?>
            <?php elseif (!empty($this->helpful_allow)): ?>

               <?php echo $this->translate("Was this answer helpful?"); ?>
                
                <?php if ($this->previousHelpMark == 1): ?>
                    <a class="ui-link-inherit" href='javascript:void(0);' onclick="helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '2', '', '<?php echo $option_count ?>','<?php echo $this->statisticsHelpful; ?>','<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>');"><i class="ui-icon ui-icon-thumbs-up"></i> <?php echo $this->translate("Yes"); ?></a>
                    
                    <span class="t_light"><i class="ui-icon ui-icon-thumbs-down"></i> <?php echo $this->translate("No"); ?></span>
                    
                <?php elseif ($this->previousHelpMark == 2): ?>
                    <span class="t_light"><i class="ui-icon ui-icon-thumbs-up"></i> <?php echo $this->translate("Yes"); ?></span>
                    <a  data-rel="popup" class="ui-link-inherit" href= "#show_option_<?php echo $this->faq_id; ?>" onclick="helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '1', '', '<?php echo $option_count ?>','<?php echo $this->statisticsHelpful; ?>','<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>');"><i class="ui-icon ui-icon-thumbs-down"></i> <?php echo $this->translate("No"); ?></a>
                <?php else: ?>
                    <a class="ui-link-inherit"  href='javascript:void(0);' onclick="helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '2', '', '<?php echo $option_count ?>','<?php echo $this->statisticsHelpful; ?>','<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>');"><i class="ui-icon ui-icon-thumbs-up"></i> <?php echo $this->translate("Yes"); ?></a>
                    
                    <a data-rel="popup" class="ui-link-inherit" href="#show_option_<?php echo $this->faq_id; ?>" onclick="helpfulAction('<?php echo $this->faq_id; ?>', '<?php echo $this->viewer_id; ?>', '1', '', '<?php echo $option_count ?>','<?php echo $this->statisticsHelpful; ?>','<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>');"><i class="ui-icon ui-icon-thumbs-down"></i> <?php echo $this->translate("No"); ?></a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!--Display Vote count and the previous answer-->
        <div class="clr">
            <?php if ($this->statisticsHelpful && ($this->helpful_allow) && ($this->totalVoteCount)): ?>
            <?php echo $this->translate(array('%s vote', '%s votes', $this->totalVoteCount), $this->locale()->toNumber($this->totalVoteCount)) ?>
            <?php endif; ?>
            <?php if (!empty($this->helpful_allow)): ?>
                <?php if ($this->previousHelpMark): ?>
                    <?php if ($this->statisticsHelpful && ($this->totalVoteCount)): ?>
                        |
                    <?php endif; ?>
                    <?php echo $this->translate("Your previous answer: "); ?>
                    <?php if ($this->previousHelpMark == 2): ?>
                        <?php echo $this->translate("Yes"); ?>
                    <?php elseif ($this->previousHelpMark == 1): ?>
                        <?php echo $this->translate("No"); ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>	
        <!--END, Display Vote count and the previous answer-->
    </div>
<?php endif; ?>
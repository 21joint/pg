<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="ui-page-content">
    <div class="sm-ui-cont-head">
        <div class="sm-ui-cont-cont-info">
            <div class="sm-ui-cont-author-name">
                <h2 class="ui-title">
                    <?php if ($this->feedback->owner_id != 0): ?>
                        <?php echo $this->htmlLink($this->feedback->getOwner()->getHref(), $this->feedback->getOwner()->getTitle()) . $this->translate("'s Feedback : ") ?>
                    <?php else: ?>
                        <?php echo $this->translate('Anonymous Feedback : ') ?>
                    <?php endif; ?>	
                    <?php echo $this->feedback->getTitle(); ?>
                </h2>
            </div>
            <div class="sm-ui-cont-cont-date">               
                <?php if ($this->feedback->owner_id != 0 || !empty($this->feedback->owner_id)): ?>
                    <?php echo $this->translate('Posted by %s', $this->feedback->getOwner()->toString()); ?>
                    -
                    <?php echo $this->timestamp($this->feedback->creation_date); ?>
                <?php else: ?>
                    <?php echo $this->translate('Posted by Anonymous user '); ?>
                    -
                    <?php echo $this->timestamp($this->feedback->creation_date); ?>
                <?php endif; ?>
            </div>
            <div class="sm-ui-cont-cont-date">
                <?php echo $this->feedback->total_votes; ?>
                <?php echo $this->translate(' votes'); ?>
                -
                <?php echo $this->translate(array('%s view', '%s views', $this->feedback->views), $this->locale()->toNumber($this->feedback->views)) ?>
                -
                <?php echo $this->translate(array('%s picture', '%s pictures', $this->feedback->total_images), $this->locale()->toNumber($this->feedback->total_images)) ?>
            </div>
            <div class="sm-ui-cont-cont-date">
                <?php if ($this->category && (!empty($this->feedback->owner_id))): ?>
                    <?php echo $this->translate('Category:'); ?><?php echo $this->category->category_name ?>
                <?php elseif ($this->category && (empty($this->feedback->owner_id))): ?>  	
                    <?php echo $this->translate('Category:'); ?> <?php echo $this->category->category_name ?>
                <?php endif; ?>

                <?php if (!empty($this->show_tag)): ?>
                    <?php if (count($this->feedbackTags)): ?>
                        -
                        <?php echo $this->translate("Tag:"); ?>
                        <?php foreach ($this->feedbackTags as $tag): ?>
                            #<?php echo $tag->getTag()->text ?>&nbsp;
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="sm-ui-cont-cont-date">
                <?php echo $this->translate(array('%s comment', '%s comments', $this->feedback->comment_count), $this->locale()->toNumber($this->feedback->comment_count)) ?>
                <?php if ($this->participants_total): ?>
                    -
                    <?php echo $this->translate(array('%s participant', '%s participants', $this->participants_total), $this->locale()->toNumber($this->participants_total)) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="sm-ui-cont-cont-des">
        <?php echo $this->feedback->feedback_description; ?> 
    </div>


    <!--CUSTOM FIELD WORK -->
    <?php echo $this->fieldValueLoop($this->feedback, $this->fieldStructure) ?>
    <!--END CUSTOM FIELD WORK -->

    <?php if ($this->feedback->total_images): ?>
        <ul class="thumbs">
            <?php foreach ($this->images as $image): ?>
                <li class="thumbs_photo" style="height:120px;width:120px;"><span style="background-image: url('<?php echo $image->getPhotoUrl(); ?>'); background-size:cover"></span></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>


    <!--STATUS DISPLAY WORK-->

    <?php if (!empty($this->stat) || !empty($this->feedback->status_body)): ?>
        <div class = "sm-widget-block">
            <?php if ($this->stat): ?>
                <?php echo $this->translate('<b>Status:</b>'); ?><?php echo $this->stat->stat_name ?><br />
            <?php endif; ?>
            <?php if (!empty($this->feedback->status_body)): ?>
                <?php if (empty($this->stat)): ?><?php echo $this->translate('<b>Status:</b>'); ?><?php endif; ?>
                <p class='feedback_view_des'> <?php echo $this->feedback->status_body ?> </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <!--REPORT BUTTON WORK-->
    <div data-type="horizontal" data-role="controlgroup" >
        <?php if ($this->feedback_report == 1 && !empty($this->viewer_id)): ?>
            <?php
            echo $this->htmlLink(array(
                'module' => 'core',
                'controller' => 'report',
                'action' => 'create',
                'route' => 'default',
                'subject' => $this->report->getGuid(),
                'format' => 'smoothbox'
                    ), $this->translate("Inappropriate Content"), array(
                'class' => 'smoothbox',
                'data-role' => "button", 'data-icon' => "flag", "data-iconpos" => "left", "data-inset" => 'false', 'data-mini' => "true", 'data-corners' => "true", 'data-shadow' => "true"
            ));
            ?>
        <?php endif; ?>
    </div>
</div>

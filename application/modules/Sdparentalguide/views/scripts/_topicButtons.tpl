<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<div class="topic_buttons">
    <div>
        <a href="<?php echo $this->url(array('action' => 'create')); ?>" class='create_topic smoothbox'><button type='button'><?php echo $this->translate("Add Topic"); ?></button></a>
        <button type='button' onclick="bulkApprove(this,'1');"><?php echo $this->translate("Make Active"); ?></button>
        <button type='button' onclick="bulkAllowBadges(this,'1');"><?php echo $this->translate("Allow Badges"); ?></button>
    </div>
    <div>
        <button type='button' onclick="searchTopics(this);"><?php echo $this->translate("Search"); ?></button>
        <button type='button' onclick="bulkApprove(this,'0');"><?php echo $this->translate("Make Inactive"); ?></button>
        <button type='button' onclick="bulkAllowBadges(this,'0');" style='width: auto;'><?php echo $this->translate("Don't Allow Badges"); ?></button>
    </div>
</div>
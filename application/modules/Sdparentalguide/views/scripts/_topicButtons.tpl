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
        <a href="<?= $this->url(array('action' => 'create')); ?>" class='create_topic smoothbox'><button type='button'><?= $this->translate("Add Topic"); ?></button></a>
        <button type='button' onclick="bulkApprove(this,'1');"><?= $this->translate("Make Active"); ?></button>
        <button type='button' onclick="bulkAllowBadges(this,'1');"><?= $this->translate("Allow Badges"); ?></button>
    </div>
    <div>
        <button type='button' onclick="searchTopics(this);"><?= $this->translate("Search"); ?></button>
        <button type='button' onclick="bulkApprove(this,'0');"><?= $this->translate("Make Inactive"); ?></button>
        <button type='button' onclick="bulkAllowBadges(this,'0');" style='width: auto;'><?= $this->translate("Don't Allow Badges"); ?></button>
    </div>
</div>
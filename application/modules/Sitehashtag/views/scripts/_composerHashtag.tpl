<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    _composerHashtag.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
 <?php if(empty ($this->isAFFWIDGET)):
   return;
 endif ?>
<?php $contentTable = Engine_Api::_()->getDbtable('contents', 'sitehashtag'); ?>
<?php if($contentTable->getEnable('activity')): ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitehashtag/externals/scripts/composer_hashtag.js') ?>
    <script type="text/javascript">
      en4.core.runonce.add(function() {
       <?php if($this->forEdit) : ?>
           document.retrieve('editComposeInstance<?php echo $this->forEdit ?>').addPlugin(new Composer.Plugin.Sitehashtag({
          enabled:true
        }));
        document.retrieve('editComposeInstance<?php echo $this->forEdit ?>').setHighlighterContent();
       <?php else: ?>
        composeInstance.addPlugin(new Composer.Plugin.Sitehashtag({
          enabled:true
        }));
        <?php endif; ?>
      });
    </script>
<?php endif; ?>
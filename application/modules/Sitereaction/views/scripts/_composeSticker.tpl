<?php
 /**
* SocialEngine
*
* @category   Application_Extensions
* @package    Advancedactivity
* @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: _composeTag.tpl 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
?>
<?php if(empty ($this->isAFFWIDGET)) :  return;  endif; ?>
<?php $this->headScript()
     ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitereaction/externals/scripts/composer_sticker.js') ?>

<script type="text/javascript"> 
  en4.core.runonce.add(function() {
      var plugin = new Composer.Plugin.Sticker({
       title: '<?php echo $this->translate("Post a Sticker") ?>',
       attachment: null
    });
    composeInstance.addPlugin(plugin);
  });
</script>

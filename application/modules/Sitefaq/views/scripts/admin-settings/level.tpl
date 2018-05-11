<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: level.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  var fetchLevelSettings =function(level_id){
    window.location.href= en4.core.baseUrl+'admin/sitefaq/settings/level/id/'+level_id;
  }
</script>

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin'); ?></h2>

<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'>
		<?php	echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
	</div>
<?php endif; ?>

<div class='clear seaocore_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this) ?>
  </div>
</div>
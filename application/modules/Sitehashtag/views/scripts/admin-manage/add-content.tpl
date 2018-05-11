<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    add-content.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $active = 'advancedactivity_admin_main_hashtag';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminAAFNav.tpl'; ?>


<?php if (count($this->navigation)): ?>
    <div class = 'seaocore_admin_tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitehashtag', 'controller' => 'manage', 'action' => 'index'), $this->translate("Back to Manage Modules for Hashtags"), array('class'=>'sitelike_icon_back buttonlink')) ?>
<br style="clear:both;" /><br />

<div class="seaocore_settings_form">
	<div class='settings'>
		<?php		echo $this->form->render($this); ?>
	</div>
</div>

<script type="text/javascript">
    function setModuleName(module_name){
        window.location.href="<?php echo $this->url(array('module'=>'sitehashtag','controller'=>'manage', 'action'=>'add-content'),'admin_default',true)?>/module_name/"+module_name;
    }
</script>
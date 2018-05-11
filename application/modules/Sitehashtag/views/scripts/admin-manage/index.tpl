<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    index.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
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

<h3><?php echo "Manage Modules"; ?></h3>
<p>Below, you will be able to configure the content modules, for which you want to use the 'Hashtag' feature. You can enable / disable them accordingly via green button.<br/>
    1) Use hashtag in content modules via adding hashtag(#) before content's title. <a href="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitehashtag/externals/images/3_4.png" title="Hashtag While Content Creation" class="seaocore_icon_view" target="_blank"></a>&nbsp;&nbsp;<a href="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitehashtag/externals/images/4_3.png" title="Hashtag On Search Results Page" class="seaocore_icon_view" target="_blank"></a><br/> 
2) Use hashtag in Advanced Activity Feed via adding hashtag(#) before any unspaced word. <a href="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitehashtag/externals/images/1_0.png" class="seaocore_icon_view" target="_blank"></a><br/><br/>


<?php if(!empty($this->showAll)):?>
    <div class="tip mtop10">
        <span>
            <?php echo $this->translate("You have already enabled all modules from global setting.")?>    
        </span>
        
    </div>
<?php elseif(!empty($this->showNone)): ?>
    <div class="tip mtop10">
        <span>
            <?php echo $this->translate("You have already disabled all modules from global setting.")?>
        </span>
        
    </div>
<?php else:?>
<div class="mtop10">
  <a href="<?php echo $this->url(array('action' =>'add-content')) ?>" class="buttonlink seaocore_icon_add " title="<?php echo 'Add Content Module';?>"><?php echo 'Add Content Module';?></a>
</div><br />
<table class = 'admin_table' style="width:100%">
    <thead>
        <tr>
            <th><?php echo "Content Module" ?></th>
            <th class="center"><?php echo "Enable / Disable" ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->modules as $module): ?>
            <?php if (Engine_Api::_()->hasModuleBootstrap("$module->module_name")) {?>
                <tr>
                    <td><?php 
                    $moduleTable = Engine_Api::_()->getDbtable('modules', 'core');
                    $moduleRow = $moduleTable->fetchRow($moduleTable->select()->where('name = ?',$module->module_name));
                    if(!empty($moduleRow))
                        echo $moduleRow->title;?>
                    </td>
                    <td class="center">
                        <?php if ($module->enabled): ?>
                            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitehashtag', 'controller' => 'manage', 'action' => 'enabled', 'content_id' => $module->content_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif', '', array('title' => 'Disable content type')), array());?>
                        <?php else: ?>
                            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitehashtag', 'controller' => 'manage', 'action' => 'enabled', 'content_id' => $module->content_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/disapproved.gif', '', array('title' => 'Enable content type')), array());?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
              
    </tbody>
</table>
<?php endif;?>

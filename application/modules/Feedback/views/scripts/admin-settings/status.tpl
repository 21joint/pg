<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: status.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Feedback Plugin');?></h2>

<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'> <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?> </div>
<?php endif; ?>

<div class='clear'>
  <div class='settings'>
    <form class="global_form" style="width:60%;">
      <div>
        <h3><?php echo $this->translate('Feedback Status');?></h3>
        <p class="description"> <?php echo $this->translate('Create or Edit status values for the feedback.');?> </p>
        <table class='admin_table' width="100%">
          <thead>
            <tr>
              <th align="left"><?php echo $this->translate('Status Name');?></th>
              <th align="center"><?php echo $this->translate('Number of Times Used');?></th>
              <th align="center"><?php echo $this->translate('Options');?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($this->status as $stat): ?>
            <tr>
              <td align="left" style ="color:<?php echo $stat->stat_color ?>;"><?php echo $stat->stat_name?></td>
              <td class="admin_table_centered"><?php echo $stat->getUsedCount(); ?></td>
              <td class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'edit-stat', 'id' =>$stat->stat_id), $this->translate('edit'), array(
	                'class' => 'smoothbox', 
	              )) ?> | <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'delete-stat', 'id' => $stat->stat_id), $this->translate('delete'), array(
	                'class' => 'smoothbox',
	              )) ?> 
					  	</td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br/>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'add-stat'), $this->translate('Add New Status'), array(
		      	'class' => 'smoothbox buttonlink seaocore_icon_add')) ?> 
			</div>
    </form>
  </div>
</div>
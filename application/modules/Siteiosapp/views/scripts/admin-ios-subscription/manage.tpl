<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<h2>
    <?php echo $this->translate('iOS Mobile Application - iPhone and iPad'); ?>
</h2>

<?php if (count($this->navigation)): ?>
<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>
<?php endif; ?>

<?php if (count($this->subnavigation)): ?>
<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render() ?>
</div>
<?php endif; ?>

<div class='clear seaocore_settings_form'>
    <div class='settings'>
        <div>
            <h3><?php echo $this->translate("User Subscriptions") ?> </h3>
            <p class="form-description">
        <?php echo $this->translate("Below, you can view the details of users who have purchased your community subscription via iOS App.") ?>
            </p>
        </div>
    </div>
</div>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
<form id='saveorder_form' method='post' action='<?php echo $this->url(array('action' => 'manage')) ?>' style="overflow:hidden;">
    <input type='hidden'  name='order' id="order" value=''/>
    <table class='admin_table'>
        <thead>

        <th style='width: 1%;'>
				<?php echo $this->translate("Transaction ID") ?>
        </th>

        <th style='width: 1%;'>
				<?php echo $this->translate("User ID") ?>
        </th>

        <th style='width: 1%;'>
				<?php echo $this->translate("Plan ID") ?>
        </th>

        <th style='width: 1%;'>
				<?php echo $this->translate("Email") ?>
        </th>
        <th style='width: 1%;'>
				<?php echo $this->translate("Displayname") ?>
        </th>
        <th style='width: 1%;'>
				<?php echo $this->translate("Creation Date") ?>
        </th>

        <th style='width: 1%;'>
				<?php echo $this->translate("Options") ?>
        </th>
        </tr>
        </thead>
        <tbody>
				<?php foreach ( $this->paginator as $subscription) :
                                                        $transaction_id = $subscription['transaction_id'];
?>
        <input type='hidden'  name='order[]' value='<?php echo $subscription['transaction_id']; ?>'>
        <tr>
            <td>
	      <?php echo $subscription['transaction_id'] ?>
            </td>

            <td class='admin_table_centered'>
	      <?php echo $subscription['user_id'] ?>
            </td>

            <td class='admin_table_centered'>
	      <?php echo $subscription['package_id'] ?>
            </td>

            <td class="wrap">
              <?php echo $subscription['email'] ?>
            </td>
            <td class="nowrap">
              <?php echo $subscription['displayname'] ?>
            </td>
            <td class='nowrap'>
              <?php echo $subscription['creation_date'] ?>
            </td>

            <td class='admin_table_options'>
              <?php echo $this->htmlLink(array('module' => 'siteiosapp', 'controller' => 'ios-subscription', 'action' => 'view', 'transaction_id' => $subscription['transaction_id']), $this->translate('Receipt details'),array(
	                            'class' => 'smoothbox',
	                          )) ?> 
            </td>
        </tr>			
	<?php endforeach; ?>
        </tbody>
    </table>
</form>
<?php endif; ?>
<br />


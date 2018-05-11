<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate("Directory / Pages - Reviews and Ratings Extension") ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='seaocore_settings_form'>
	<div class='settings'>
		<form class="global_form">
			<div>
				<h3><?php echo $this->translate("Category Based Rating Parameters") ?> </h3>
				<p class="form-description">
					<?php echo $this->translate('Below, you can configure rating parameters for the various Page categories. By clicking on "Add", "Edit" and "Delete" respectively, you can add multiple new parameters, or edit and delete existing rating parameters. Hence, when a user would go to rate and review a Page belonging to a category, he will be able to rate the Page on the parameters configured by you for that category.<br /> This extremely useful feature enables gathering of refined ratings, reviews and feedback for the Pages in your community.') ?>
				</p>
				<?php if(count($this->paginator)>0):?>
					<table class='admin_table' width="100%">
						<thead>
							<tr>
								<th align="left" width="40%"><?php echo $this->translate("Category Name") ?></th>
								<th align="left" width="40%"><?php echo $this->translate("Review Parameters") ?></th>
								<th align="left" width="20%"><?php echo $this->translate("Options") ?></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($this->reviewcat_cat_array as $key => $reviewcat): ?>
								<tr>
									<td width="40%"><?php echo $this->translate($reviewcat[0]);?></td>
									<td width="40%">
										<ul class="admin-review-cat">
											<?php $reviewcat_exist = 0;?>
											<?php foreach ($reviewcat as $reviewcat_key => $reviewcat_name): ?>
												<?php if($reviewcat_key != 0):?>
													<?php $reviewcat_exist = 1;?>
													<li><?php echo $this->translate($reviewcat_name); ?></li>
												<?php endif;?>
											<?php endforeach; ?>
										</ul>
										<?php if($reviewcat_exist == 0):?>
											---
										<?php endif;?>
									</td>

									<td width="20%">
										<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagereview', 'controller' => 'ratingparameter', 'action' => 'create', 'category_id' => $key), $this->translate('Add'), array(
											'class' => 'smoothbox',
										)) ?> 

										<?php if($reviewcat_exist == 1):?>	
											| <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagereview', 'controller' => 'ratingparameter', 'action' => 'edit', 'category_id' => $key), $this->translate('Edit'), array(
												'class' => 'smoothbox',
											)) ?>

											| <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagereview', 'controller' => 'ratingparameter', 'action' => 'delete', 'category_id' => $key), $this->translate('Delete'), array(
												'class' => 'smoothbox',
											)) ?>
										<?php endif; ?>
									</td>

								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else:?>
				<br/>
				<div class="tip">
					<span><?php echo $this->translate("There are currently no categories to be mapped.") ?></span>
				</div>
				<?php endif;?>
			</div>
		</form>
	</div>
</div>
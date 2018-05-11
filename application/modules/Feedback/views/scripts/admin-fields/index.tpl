<?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php echo $this->render('_jsAdmin.tpl'); ?>

<h2><?php echo $this->translate("Feedback Plugin");?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
  </div>
<?php endif; ?>

<p>
	<?php echo $this->translate('Create custom fields/questions for feedback, which your members will be asked to fill while creating feedback. To reorder the custom questions, click on their names and drag them up or down.');?>
</p>

<br />

<div class="admin_fields_options">
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addquestion"><?php echo $this->translate("Add Question");?></a>
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addheading" style="display:none;"><?php echo $this->translate("Add Heading");?></a>
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_saveorder" style="display:none;"><?php echo $this->translate("Save Order");?></a>
</div>

<br />


<ul class="admin_fields">
  <?php foreach( $this->topLevelMaps as $field ): ?>
    <?php echo $this->adminFieldMeta($field) ?>
  <?php endforeach; ?>
</ul>

<br />
<br />
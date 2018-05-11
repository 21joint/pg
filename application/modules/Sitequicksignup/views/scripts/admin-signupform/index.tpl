<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
    <?php echo $this->translate('Quick & Single Step Signup Plugin') ?>
</h2>

<?php if (count($this->navigation)): ?>
    <div class='tabs'>
        <?php
        // Render the menu
        //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
    </div>
<?php endif; ?>

<?php
// Render the admin js
echo $this->render('_jsAdmin.tpl')
?>

<h2>Signup Profile Types</h2>
<p>
    Please select the profile fields for different profile types which you want to show in Quick Signup Form. You can create a new profile type or edit the profile fields for current profile types from <a href="<?php echo $this->baseUrl() ?>/admin/user/fields">here</a>.
</p>

<br />
<br />	

<div class="admin_fields_type">
    <h3>Profile Type:</h3>
    <?php echo $this->formSelect('profileType', $this->topLevelOption->option_id, array(), $this->topLevelOptions) ?>
</div>

<div class="admin_fields_options">
    <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_saveorder" style="display:none;">Save Order</a>
</div>
<br />
<ul class="admin_fields">
    <?php foreach ($this->secondLevelMaps as $map): ?>
        <?php echo $this->adminFieldMeta($map) ?>
    <?php endforeach; ?>
</ul>

<br />
<br />



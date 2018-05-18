<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    readme.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $active = 'advancedactivity_admin_main_hashtag';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminAAFNav.tpl'; ?>

<hr style=" border: solid; border-width: 1px; "> 

<div class="seaocore_admin_tabs">
    <ul class="navigation">
        <li class="active">
            <a href="<?php echo $this->baseUrl() . '/admin/sitehashtag/settings/readme' ?>" ><?php echo 'Please go through these important points and proceed by clicking the button at the bottom of this page.'; ?></a>

        </li>
    </ul>
</div>

<?php include_once APPLICATION_PATH . '/application/modules/Sitehashtag/views/scripts/admin-settings/faq_help.tpl'; ?>
<br />
<button onclick="form_submit();"><?php echo 'Proceed to enter License Key'; ?> </button>

<script type="text/javascript" >
    function form_submit() {
        var url = '<?php echo $this->url(array('module' => 'sitehashtag', 'controller' => 'settings'), 'admin_default', true) ?>';
        window.location.href = url;
    }
</script>
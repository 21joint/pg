<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: readme.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>


<h2 class="fleft">
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>

<div class='seaocore_admin_tabs clr'>
    <ul class="navigation">
        <li class="active">
            <a href="<?php echo $this->baseUrl() . '/admin/Sitecredit/settings/readme' ?>" ><?php echo $this->translate('Please go through these important points and proceed by clicking the button at the bottom of this page.') ?></a>
        </li>
    </ul>
</div>		

<?php
include_once APPLICATION_PATH .
'/application/modules/Sitecredit/views/scripts/admin-settings/faq_help.tpl';
?>
<br />
<button onclick="form_submit();"><?php echo $this->translate('Proceed to enter License Key') ?> </button>

<script type="text/javascript" >
    function form_submit() {

        var url = '<?php echo $this->url(array('module' => 'sitecredit', 'controller' => 'settings'), 'admin_default', true) ?>';
        window.location.href = url;
    }
</script>
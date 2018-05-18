<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: readme.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Directory / Pages - Inviter Extension') ?></h2>
<div class="tabs">
  <ul class="navigation">
    <li class="active">
      <a href="<?php echo $this->baseUrl() . '/admin/sitepageinvite/global/readme' ?>" ><?php echo $this->translate('Please go through these important points and proceed by clicking the button at the bottom of this page.') ?></a>

    </li>
  </ul></div>

<?php include_once APPLICATION_PATH . '/application/modules/Sitepageinvite/views/scripts/admin-global/faq_help.tpl'; ?>
<br />
<button onclick="form_submit();"><?php echo $this->translate('Proceed to enter License Key') ?> </button>

<script type="text/javascript" >

  function form_submit() {
	
    var url='<?php echo $this->url(array('module' => 'sitepageinvite', 'controller' => 'global', "action" => "global"), 'admin_default', true) ?>';
    window.location.href=url;
  }

</script>
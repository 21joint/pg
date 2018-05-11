<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Minify Plugin - Speed up your Website') ?></h2>

<?php if (count($this->navigation)): ?>
    <div class='seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<?php include_once APPLICATION_PATH . '/application/modules/Siteminify/views/scripts/admin-settings/faq_help.tpl'; ?>

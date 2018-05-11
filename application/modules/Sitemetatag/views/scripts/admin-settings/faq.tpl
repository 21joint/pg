<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 6590 2011-01-06 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>Social Meta Tags Plugin – Open Graph for Facebook, Google+, Pinterest and Twitter Cards</h2>
<?php if (count($this->navigation)): ?>
  <div class='seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<?php
include_once APPLICATION_PATH .
'/application/modules/Sitemetatag/views/scripts/admin-settings/faq_help.tpl';
?>

<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    faq.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $active = 'advancedactivity_admin_main_faq';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminAAFNav.tpl'; ?>

<?php $active = 'advancedactivity_admin_main_hashtag_faq';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminFAQNav.tpl'; ?>
 

<?php
include_once APPLICATION_PATH .
        '/application/modules/Sitehashtag/views/scripts/admin-settings/faq_help.tpl';
?>

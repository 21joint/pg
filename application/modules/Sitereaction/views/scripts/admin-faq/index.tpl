<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<!--ADD NAVIGATION-->
<?php $active = 'advancedactivity_admin_main_faq';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminAAFNav.tpl'; ?>

<?php $active = 'advancedactivity_admin_main_reaction_faq';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminFAQNav.tpl'; ?>
 

<?php include_once APPLICATION_PATH . '/application/modules/Sitereaction/views/scripts/_faqHelp.tpl'; ?>


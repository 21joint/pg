<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagelikebox
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 2011-10-10 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php   echo $this->translate('Directory / Pages - Embeddable Badges, Like Box Extension'); ?></h2>
<?php if (count($this->navigation)): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<?php  include_once APPLICATION_PATH . '/application/modules/Sitepagelikebox/views/scripts/admin-settings/faq_help.tpl'; ?>

<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroupmember
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2013-03-18 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
include_once APPLICATION_PATH . '/application/modules/Sitegroup/views/scripts/common_style_css.tpl';
?>
<div class="quicklinks">
	<ul>
		<li>
			<?php echo $this->htmlLink(array('route' => 'sitegroupmember_browse'), $this->translate('Browse Members'), array('class' => 'buttonlink icon_sitegroup_member')) ?>
		</li>
	</ul>
</div>		
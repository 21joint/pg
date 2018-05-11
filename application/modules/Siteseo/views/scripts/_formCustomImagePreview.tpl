<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _schema.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $front = Zend_Controller_Front::getInstance(); $request = $front->getRequest(); ?>
<?php $params = array('page_id' => $request->getParam('page_id', null)); ?>
<?php $pageInfo = Engine_Api::_()->getDbtable('pageinfo','siteseo')->getPageinfo($params); ?>
<?php if ($pageInfo): ?>
	<div id="preview-wrapper" class="form-wrapper">
		<div id="preview-label" class="form-label">
			<label for="preview" class="optional">Custom Image Preview</label>
		</div>
		<div id="preview-element" class="form-element">
			<a href="<?php echo $pageInfo->getPhotoUrl(); ?>" target="_blank">
				<img src="<?php echo $pageInfo->getPhotoUrl(); ?>" alt="" style="max-width: 300px; padding: 0px 4px 4px 0px;">
			</a>
		</div>
	</div>
<?php endif; ?>
<script>
	en4.core.runonce.add(function() {
		$('remove_photo-element').inject($('preview-element'));
		$('remove_photo-wrapper').destroy();
	});
</script>
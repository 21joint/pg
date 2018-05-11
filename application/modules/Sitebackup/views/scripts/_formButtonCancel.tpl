<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: _formButtonCancel.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>

<div id="submit-wrapper" class="form-wrapper">
  <div id="submit-label" class="form-label"> </div>
  <div id="submit-element" class="form-element">
    <button type="submit" id="done" name="done">
      <?php echo ( $this->element->getLabel() ? $this->element->getLabel() : $this->translate('Save Changes')) ?>
    </button>
    <?php echo $this->translate('or'); ?>
    <?php echo $this->htmlLink(array('route' => 'sitebackup_manage', 'action' => 'manage'), $this->translate('cancel')) ?>
  </div>
</div>
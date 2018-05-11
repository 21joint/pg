<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Sitegifplayer
* @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: index.tpl 2017-05-15 00:00:00Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
?>

<h2>
  <?php echo $this->translate('GIF Player Plugin') ?>
</h2>

<?php if( count($this->navigation) ): ?>
<div class='tabs'>
  <?php
  // Render the menu
  //->setUlClass()
  echo $this->navigation()->menu()->setContainer($this->navigation)->render()
  ?>
</div>
<?php endif; ?>

<div class='clear'>
  <div class='settings'>

    <?php echo $this->form->render($this); ?>
  </div>
</div>

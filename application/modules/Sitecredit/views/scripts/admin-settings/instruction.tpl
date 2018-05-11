<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: instruction.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2 class="fleft">
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>

<?php endif; ?>

<?php if( count($this->navigationSubMenu) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigationSubMenu)->render()
    ?>
  </div>

<?php endif; ?>
<div class='clear'>
  <div class='settings'>

    <?php echo $this->form->render($this); ?>
  </div>
</div>
<style>
  #ad_header2-element .description{
    max-width: 100%;
  }
  #ad_header2-label{
    float:none;
  }
  #ad_header1-element .description{
    max-width: 100%;
  }
  #ad_header1-label{
    float:none;
  }
</style>
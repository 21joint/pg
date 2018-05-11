<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
  include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepageform/externals/styles/style_sitepageform.css')
?>
<script type="text/javascript">
	
  window.addEvent('domready', function() { 
    $$('input[type=Checkbox]:([name=activeemail])').addEvent('click', function(e){
      $(this).getParent('.form-wrapper').getAllNext(':([id^=activeemailself-element])').setStyle('display', ($(this).get('value')>0?'none':'none'));
    });
    $('activeemail').addEvent('click', function(){
      $('activeemailself-wrapper').setStyle('display', ($(this).checked?'block':'none'));
    });
	
    $('activeemailself-wrapper').setStyle('display', ($('activeemail').checked?'block':'none'));
  });

</script>

<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>
<div class="sitepage_viewpages_head">
   <?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
  <?php if(!empty($this->can_edit)):?>
		<div class="fright">
			<a href='<?php echo $this->url(array('page_id' => $this->sitepage->page_id), 'sitepage_edit', true) ?>' class='buttonlink icon_sitepages_dashboard'><?php echo $this->translate('Dashboard');?></a>
		</div>
	<?php endif;?>
  <h2>	
    <?php $tab_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab', null); ?>
    <?php echo $this->sitepage->__toString() ?>	
    <?php echo $this->translate('&raquo; '); ?>
    <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$tab_id)), $this->translate('Form')) ?>
  </h2>
</div>

<?php if ($this->formSelectData->status == 0): ?>
  <div class="tip"><span>
      <?php echo $this->translate("The Form for your Page has been disabled by the site administrator. You may contact the administrator to get it enabled, or for any queries. Though your settings below will be saved, visitors to your Page will not see the form till it is enabled.") ?></span>
  </div>
<?php endif; ?>

<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adformcreate', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)): ?>
  <div class="layout_right" id="communityad_formindex">
		<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adformcreate', 3),"loaded_by_ajax"=>0,'widgetId'=>'page_formindex')); 			 
			?>
  </div>
<?php endif; ?>

<div class="layout_middle">
  <div class="sitepage_form fleft">
    <div>
      <div>
        <div class="sitepageform_form">
          <?php echo $this->createform->render($this) ?>
        </div>	

        <script type="text/javascript">
          var option_id = '<?php echo $this->option_id; ?>';
          var page_id = '<?php echo $this->sitepage->page_id; ?>';
        </script>
        <?php
        // Render the admin js
        echo $this->render('_jsField.tpl')
        ?>
        <div class="sitepageform_separator"></div>
        <?php $canAddquestions = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.add.question', 1);?>
        <?php if(count($this->secondLevelMaps) ||  ($canAddquestions)):?>
          <h3><?php echo $this->translate('Manage Questions') ?></h3>
          <p><?php echo $this->translate('Below, you can see all the questions added by you and our site administrators. Note: you can only delete the questions added by you.');?></p>
        <?php endif;?>
        <div class="seaocore_add mtop10">
          <?php if($canAddquestions):?>
						<a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addquestion"><?php echo $this->translate("Add a Question") ?></a>
          <?php endif;?>
          <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_saveorder" style="display:none;"><?php echo $this->translate("Save Order") ?></a>
        </div>
        <ul class="admin_fields">
          <?php foreach ($this->secondLevelMaps as $map): ?>
            <?php echo $this->adminFieldMeta($map) ?>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>		
</div>
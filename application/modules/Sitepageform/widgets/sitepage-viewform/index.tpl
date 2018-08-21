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
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php
	  /* Include the common user-end field switching javascript */
	  echo $this->partial('_jsSwitch.tpl', 'fields', array(
	      'topLevelId' => (int) @$this->topLevelId,
	      'topLevelValue' => (int) @$this->topLevelValue
	    ))
	?>
<script type="text/javascript" >
  var submitformajax = 1;
  var page_url = '<?php echo $this->page_url;?>';
</script>

<?php if (empty($this->isajax)) : ?>
	<div id="id_<?php echo $this->content_id; ?>">
<?php endif;?>

<?php if (!empty($this->show_content)) : ?>
  <?php if($this->showtoptitle == 1  && !$this->ispost):?>
		<div class="layout_simple_head" id="layout_form">	
      <?php echo $this->translate($this->sitepage_object->getTitle());?><?php echo $this->translate("'s Form");?>
		</div>
	<?php endif;?>	
	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adformwidget', 3) && !$this->ispost && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage_object)):?>
		<div class="layout_right" id="communityad_form">
			<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adformwidget', 3),"loaded_by_ajax"=>1,'widgetId'=>'page_form')); 			 
			?>
		</div>
		<div class="layout_middle">
	<?php endif;?>
  <div id="show_tab_content_child"> 
  <?php 
		if( empty($this->show_msg) ) {
			if(empty($this->success)){
				echo $this->form->render($this) ;
			}
			else {
				echo '<ul class="form-notices"><li>'.$this->translate('Thank you for sending us the details.').'</li></ul>';
			}
		}
    else {
    	echo '<div class="tip"><span>';
			echo $this->translate('You have not created any fields for the form on your page.');
			echo $this->translate('%1$sClick here%2$s to manage this form.', '<a href="'.$this->url(array("action" => "index","option_id" => $this->option_id,"page_id" => $this->page_id, "tab" => $this->content_id),"sitepageform_general", true).'">', '</a>');	
    	echo '</span></div>';
		}
	?>
  </div>
	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adformwidget', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage_object)):?>
		</div>
	<?php endif; ?>
<?php endif;?>
<?php if (empty($this->isajax)) : ?>
	</div>
<?php endif;?>

<script type="text/javascript">
  var adwithoutpackage = '<?php echo Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage_object) ?>';
	var is_ajax_divhide = '<?php echo $this->isajax;?>';
	var execute_Request_Form = '<?php echo $this->show_content;?>';
	var execute_Request_FormIsPost = '<?php echo $this->ispost;?>';
	var show_widgets = '<?php echo $this->widgets ?>';
	var slug = '<?php echo $this->slug; ?>';
	var pages_id = '<?php echo $this->page_id; ?>';
  var page_url = '<?php echo Engine_Api::_()->sitepage()->getPageUrl($this->page_id)?>';
	var user_id = '<?php echo $this->user_id; ?>';
	var FormtabId = '<?php echo $this->module_tabid;?>';
	var FormTabIdCurrent = '<?php echo $this->identity_temp; ?>';
	var form_ads_display = '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adformwidget', 3);?>';
  var page_communityad_integration = '<?php echo $page_communityad_integration; ?>';
	if (FormTabIdCurrent == FormtabId) {
		if(page_showtitle != 0) {
			if($('profile_status') && show_widgets == 1) {					
			 $('profile_status').innerHTML =	"<h2><?php echo $this->string()->escapeJavascript($this->sitepage_object->title)?><?php echo $this->translate(' &raquo; ');?><?php echo $this->translate('Form');?></h2>";	
			}
			if($('layout_form')) {
			  $('layout_form').style.display = 'block';
			}
		}
    hideWidgetsForModule('sitepageform');
		prev_tab_id = '<?php echo $this->content_id; ?>';
		prev_tab_class = 'layout_sitepageform_sitepage_viewform';
		execute_Request_Form = true;
		hideLeftContainer (form_ads_display, page_communityad_integration, adwithoutpackage);
	} else if (is_ajax_divhide != 1) {  	
		if($('global_content').getElement('.layout_sitepageform_sitepage_viewform')) {
			$('global_content').getElement('.layout_sitepageform_sitepage_viewform').style.display = 'none';
	  } 	
	}
	//});

	$$('.tab_<?php echo $this->identity_temp; ?>').addEvent('click', function() {
	  	$('global_content').getElement('.layout_sitepageform_sitepage_viewform').style.display = 'block';
		if(page_showtitle != 0) {
			if($('profile_status') && show_widgets == 1) {
				$('profile_status').innerHTML =	"<h2><?php echo $this->string()->escapeJavascript($this->sitepage_object->title)?><?php echo $this->translate(' &raquo; ');?><?php echo $this->translate('Form');?></h2>";	
			}
		  if($('layout_form')) {
			  $('layout_form').style.display = 'block';
			}
		}
    hideWidgetsForModule('sitepageform');
	  $('id_' + <?php echo $this->content_id ?>).style.display = "block";
    if ($('id_' + prev_tab_id) != null && prev_tab_id != 0 && prev_tab_id != '<?php echo $this->content_id; ?>') {
      $$('.'+ prev_tab_class).hide();
    }

   	if (prev_tab_id != '<?php echo $this->content_id; ?>' ) {
   		execute_Request_Form = false;     
   		prev_tab_id = '<?php echo $this->content_id; ?>';	 
   		prev_tab_class = 'layout_sitepageform_sitepage_viewform';
   	}
   	if(execute_Request_Form == false  ) {
   		ShowContent('<?php echo $this->content_id; ?>', execute_Request_Form, '<?php echo $this->identity_temp?>', 'form', 'sitepageform', 'sitepage-viewform', page_showtitle, '<?php echo Engine_Api::_()->sitepage()->getPageUrl($this->page_id)?>', form_ads_display, page_communityad_integration, adwithoutpackage);
   		execute_Request_Form = true;  
   	}

    else {
      if (execute_Request_FormIsPost == 1) {
				hideLeftContainer (form_ads_display, page_communityad_integration, adwithoutpackage);
      }
      var page = $('global_content').getElement('.global_form');
      var identity_temp_form = '<?php echo $this->identity_temp; ?>';
      if(identity_temp_form == '') {
      	identity_temp_form = 0;
      }
    }
    
		if('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1);?>' && form_ads_display == 0)
			{setLeftLayoutForPage();}		  
  })
</script>

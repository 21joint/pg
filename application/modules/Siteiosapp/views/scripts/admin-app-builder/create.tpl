<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    create.tpl 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/mooRainbow.css');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/mooRainbow.js');
?>

<h2>
    <?php echo $this->translate('iOS Mobile Application - iPhone and iPad') ?>
</h2>
<?php if (count($this->navigation)): ?>
<div class='seaocore_admin_tabs'>
        <?php
        // Render the menu
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
</div>
<?php endif; ?>
<?php if (count($this->subnavigation)): ?>
<div class='seaocore_admin_tabs'>
        <?php
        // Render the menu
        echo $this->navigation()->menu()->setContainer($this->subnavigation)->render()
        ?>
</div>
<?php endif; ?>
<?php if (count($this->subsubnavigation)): ?>
<div class='tabs'>
        <?php
        // Render the menu
        echo $this->navigation()->menu()->setContainer($this->subsubnavigation)->render()
        ?>
</div>
<?php endif; ?>
<?php if (!empty($this->doWeHaveLatestVersion)): ?>
    <?php
    foreach ($this->doWeHaveLatestVersion as $modName) {
        echo "<div class='tip' style='position:relative;'><span>" . "Note: You do not have the latest version of the '<span style='font-weight:bold;'>" . @ucfirst($modName) . "</span>'. Please <a href='" . $this->url(array('module' => 'seaocore', 'controller' => 'settings', 'action' => 'upgrade'), 'admin_default', true) . "'>click here</a> to upgrade it and other modules to the latest version to enable its integration with iOS Mobile Application - iPhone and iPad." . "</span></div>";
    }
    ?>
<?php endif; ?>
<?php if ($this->showDownloadTip): ?>
<div class="seaocore_tip">
    <span>
        Limit of "upload_max_filesize" is <?php echo $this->upload_max_filesize; ?>M and generated .tar file length is <?php echo $this->tarFileSize; ?>M. In this case you may get the problem to download the .tar file. Please contact your hosting company and increase the "upload_max_filesize" size or minimize the length of .tar file.
    </span>
</div>
<?php endif; ?>
<?php if (!empty($this->errorMessage)): ?>
<div class="seaocore_tip">
    <span>
            <?php echo $this->errorMessage; ?>
    </span>
</div>
<?php elseif(!isset($this->getEnabledModules) && empty($this->getEnabledModules)): ?>
<div class="seaocore_settings_form">
    <div class='settings'>
            <?php echo $this->form->render($this); ?>
    </div>
</div>
<?php elseif(isset($this->getEnabledModules) && !empty($this->getEnabledModules)): 
        if ($this->subTab == 2):
         include APPLICATION_PATH . '/application/modules/Siteiosapp/views/scripts/_manageModulesForGoogleAds.tpl'; 
elseif($this->subTab == 1):?>
<div class="seaocore_settings_form">
    <div class='settings'>
            <?php echo $this->form->render($this); ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<script type="text/javascript" >
    var tab = <?php echo $this->tab; ?>;
    var menumoduleArray = null;

    function selectPackage() {
       if ($("package") && $("package").value) {
          var url = '<?php echo $this->url(array('module' => 'siteiosapp', 'controller' => 'app-builder', 'action' => 'create', 'clientId' => $this->getUserInfo['clientId'], 'email' => $this->getUserInfo['email']), 'admin_default', true) ?>' + '/package/' + $("package").value;
          window.location.href = url;
       }
    }

    function openUplodedImage(url) {
       window.open(url, '_blank');
    }

    function showImage(id) {
       if ($(id))
          $(id).style.display = 'block';
    }

    function hideImage(id) {
       if ($(id))
          $(id).style.display = 'none';
    }

<?php if (!empty($this->downloadTar)): ?>
    window.addEvent('domready', function () {
       setTimeout("downloadTarFile()", 2000);
    });

    function downloadTarFile() {
       parent.window.location.href = '<?php echo $this->url(array('module' => 'siteiosapp', 'controller' => 'app-builder', 'action' => 'download-tar', 'package' => $this->package, 'downloadTar' => 1), 'admin_default', true); ?>';
    }
<?php endif; ?>

    function changeDashboardSetting()
    {
       $('secondController-wrapper').style.display = "none";
       $('secondController_dummy-wrapper').style.display = "none";
       $('secondControllerImage1-wrapper').style.display = "none";
       $('secondControllerImage2-wrapper').style.display = "none";
       $('secondControllerImage3-wrapper').style.display = "none";
       $('globalViewType-wrapper').style.display = "none";
       $('globalBrowseType-wrapper').style.display = "none";
       $('secondControllerImageDefault-wrapper').style.display = "none";

       $('thirdController-wrapper').style.display = "none";
       $('thirdControllerImage1-wrapper').style.display = "none";
       $('thirdControllerImage2-wrapper').style.display = "none";
       $('thirdControllerImage3-wrapper').style.display = "none";
       $('globalViewType1-wrapper').style.display = "none";
       $('globalBrowseType1-wrapper').style.display = "none";
       $('thirdControllerImageDefault-wrapper').style.display = "none";

       $('fourthController-wrapper').style.display = "none";
       $('fourthControllerImage1-wrapper').style.display = "none";
       $('fourthControllerImage2-wrapper').style.display = "none";
       $('fourthControllerImage3-wrapper').style.display = "none";
       $('globalViewType2-wrapper').style.display = "none";
       $('globalBrowseType2-wrapper').style.display = "none";
       $('fourthControllerImageDefault-wrapper').style.display = "none";

       if ($('app_dashboard_menu_setting').value > 0)
       {
          $('secondController-wrapper').style.display = "block";
          $('secondController_dummy-wrapper').style.display = "block";
          $('secondControllerImage1-wrapper').style.display = "block";
          $('secondControllerImage2-wrapper').style.display = "block";
          $('secondControllerImage3-wrapper').style.display = "block";
          $('secondControllerImageDefault-wrapper').style.display = "block";
          if ($('secondControllermenuname').value == "sitereview_listing")
          {
             $('globalViewType-wrapper').style.display = "block";
             $('globalBrowseType-wrapper').style.display = "block";
          } else
          {
             $('globalViewType-wrapper').style.display = "none";
             $('globalBrowseType-wrapper').style.display = "none";
          }

          $('thirdController-wrapper').style.display = "block";
          $('thirdControllerImage1-wrapper').style.display = "block";
          $('thirdControllerImage2-wrapper').style.display = "block";
          $('thirdControllerImage3-wrapper').style.display = "block";
          $('thirdControllerImageDefault-wrapper').style.display = "block";
          if ($('thirdControllermenuname').value == "sitereview_listing")
          {
             $('globalViewType1-wrapper').style.display = "block";
             $('globalBrowseType1-wrapper').style.display = "block";
          } else
          {
             $('globalViewType1-wrapper').style.display = "none";
             $('globalBrowseType1-wrapper').style.display = "none";
          }

          $('fourthController-wrapper').style.display = "block";
          $('fourthControllerImage1-wrapper').style.display = "block";
          $('fourthControllerImage2-wrapper').style.display = "block";
          $('fourthControllerImage3-wrapper').style.display = "block";
          $('fourthControllerImageDefault-wrapper').style.display = "block";
          if ($('fourthControllermenuname').value == "sitereview_listing")
          {
             $('globalViewType2-wrapper').style.display = "block";
             $('globalBrowseType2-wrapper').style.display = "block";
          } else
          {
             $('globalViewType2-wrapper').style.display = "none";
             $('globalBrowseType2-wrapper').style.display = "none";
          }

       } else
       {
          $('secondController-wrapper').style.display = "none";
          $('secondController_dummy-wrapper').style.display = "none";
          $('secondControllerImage1-wrapper').style.display = "none";
          $('secondControllerImage2-wrapper').style.display = "none";
          $('secondControllerImage3-wrapper').style.display = "none";
          $('globalViewType-wrapper').style.display = "none";
          $('globalBrowseType-wrapper').style.display = "none";
          $('secondControllerImageDefault-wrapper').style.display = "none";

          $('thirdController-wrapper').style.display = "none";
          $('thirdControllerImage1-wrapper').style.display = "none";
          $('thirdControllerImage2-wrapper').style.display = "none";
          $('thirdControllerImage3-wrapper').style.display = "none";
          $('globalViewType1-wrapper').style.display = "none";
          $('globalBrowseType1-wrapper').style.display = "none";
          $('thirdControllerImageDefault-wrapper').style.display = "none";

          $('fourthController-wrapper').style.display = "none";
          $('fourthControllerImage1-wrapper').style.display = "none";
          $('fourthControllerImage2-wrapper').style.display = "none";
          $('fourthControllerImage3-wrapper').style.display = "none";
          $('globalViewType2-wrapper').style.display = "none";
          $('globalBrowseType2-wrapper').style.display = "none";
          $('fourthControllerImageDefault-wrapper').style.display = "none";
       }
    }

    function listingTypeChanges()
    {
       var secondController = $('secondController').value;
       $('secondControllermenuname').value = menumoduleArray[secondController].name;
       $('secondControllerUrl').value = menumoduleArray[secondController].url;

       if ($('app_dashboard_menu_setting').value == 0)
          return false;

       if (menumoduleArray[secondController].name == 'sitereview_listing')
       {
          $('globalViewType-wrapper').style.display = "block";
          $('globalBrowseType-wrapper').style.display = "block";
       } else
       {
          $('globalViewType-wrapper').style.display = "none";
          $('globalBrowseType-wrapper').style.display = "none";
       }

       var thirdController = $('thirdController').value;
       $('thirdControllermenuname').value = menumoduleArray[thirdController].name;
       $('thirdControllerUrl').value = menumoduleArray[thirdController].url;

       if (menumoduleArray[thirdController].name == 'sitereview_listing')
       {
          $('globalViewType1-wrapper').style.display = "block";
          $('globalBrowseType1-wrapper').style.display = "block";
       } else
       {
          $('globalViewType1-wrapper').style.display = "none";
          $('globalBrowseType1-wrapper').style.display = "none";
       }

       var fourthController = $('fourthController').value;
       $('fourthControllermenuname').value = menumoduleArray[fourthController].name;
       $('fourthControllerUrl').value = menumoduleArray[fourthController].url;
       if (menumoduleArray[fourthController].name == 'sitereview_listing')
       {
          $('globalViewType2-wrapper').style.display = "block";
          $('globalBrowseType2-wrapper').style.display = "block";
       } else
       {
          $('globalViewType2-wrapper').style.display = "none";
          $('globalBrowseType2-wrapper').style.display = "none";
       }

    }

    window.addEvent('domready', function () {
       if (tab == 3)
          changeDashboardSetting();

       if (tab == 3 && menumoduleArray == null)
       {
          data = 1;
          var url = '<?php echo $this->url(array("action" => "getmodule")) ?>';
          var request = new Request.JSON({
             url: url,
             onSuccess: function (res) {
                menumoduleArray = new Array();
                for (var key in res) {
                   if (res.hasOwnProperty(key)) {
                      menumoduleArray[key] = res[key];
                   }
                }
                listingTypeChanges();
             },
          });
          request.send();
       }

       show_required_star();
       // showUdid();
       advertismentType();
       welcomeImageType();
    });

//    window.addEvent('onload', function () {
//        show_required_star();
//    }); 
//    
    function show_required_star() {
<?php foreach ($this->requiredFormFields as $element): ?>
    <?php if (isset($this->form->$element)): ?>
       if ($('<?php echo $element ?>-label') && $('<?php echo $element ?>-label').innerHTML)
          $('<?php echo $element ?>-label').innerHTML = '<label for="<?php echo $element ?>" class="required"> <?php echo $this->form->$element->getLabel(); ?> <span style="color:RED">*</span></label>';
    <?php endif; ?>
<?php endforeach; ?>;
    }

    function advertismentType() {
       if ($('adv_type-1') && $('adv_type-1').checked) {
          $('ad_placement_id-wrapper').style.display = 'block';
          $('google_ad_placement_id-wrapper').style.display = 'none';
       } else if ($('ad_placement_id-wrapper') && $('google_ad_placement_id-wrapper'))
       {
          $('ad_placement_id-wrapper').style.display = 'none';
          $('google_ad_placement_id-wrapper').style.display = 'block';
       }
    }

    function showUdid() {
       if ($('publish_app-0').checked) {
          $('phone_udid-wrapper').style.display = 'block';
       } else {
          $('phone_udid-wrapper').style.display = 'none';
       }
    }

    function promptMessage() {
       var a = prompt("Please enter your name", "Harry Potter");
       alert(a);
    }

    function welcomeImageType() {
       if ($('welcome_image_type-1') && $('welcome_image_type-1').checked) {
          $('animation_time-wrapper').style.display = 'none';
        <?php foreach ($this->dynamicImages as $element): ?>
    <?php if (isset($this->form->$element)): ?>
          if ($('<?php echo $element ?>-label') && $('<?php echo $element ?>-label').innerHTML) {
            <?php if (in_array($element,$this->requiredFormFields)):?>
             $('<?php echo $element ?>-label').innerHTML = '<label for="<?php echo $element ?>" class="image_type"> <?php echo $this->form->$element->getLabel(); ?> <span><b>PNG</b></span></label><span style="color:RED"> *</span></label>';
             <?php else: ?>
             $('<?php echo $element ?>-label').innerHTML = '<label for="<?php echo $element ?>" class="image_type"> <?php echo $this->form->$element->getLabel(); ?> <span><b>PNG</b></span></label>';
                    <?php endif; ?>
          }
    <?php endif; ?>
<?php endforeach; ?>;
       } else if ($('welcome_image_type-wrapper') && $('welcome_image_type-wrapper'))
       {
          $('animation_time-wrapper').style.display = 'block';
               <?php foreach ($this->dynamicImages as $element): ?>
    <?php if (isset($this->form->$element)): ?>
          if ($('<?php echo $element ?>-label') && $('<?php echo $element ?>-label').innerHTML) {
            <?php if (in_array($element,$this->requiredFormFields)):?>
             $('<?php echo $element ?>-label').innerHTML = '<label for="<?php echo $element ?>" class="image_type"> <?php echo $this->form->$element->getLabel(); ?> <span><b>GIF</b></span></label><span style="color:RED"> *</span></label>';
             <?php else: ?>
             $('<?php echo $element ?>-label').innerHTML = '<label for="<?php echo $element ?>" class="image_type"> <?php echo $this->form->$element->getLabel(); ?> <span><b>GIF</b></span></label>';
                    <?php endif; ?>
          }
    <?php endif; ?>
<?php endforeach; ?>;
       }
    }
</script>
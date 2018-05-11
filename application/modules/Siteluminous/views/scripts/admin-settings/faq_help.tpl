<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>

<div class="admin_seaocore_files_wrapper">
	 <ul class="admin_seaocore_files seaocore_faq">
     
   
    
    <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo "Q - I have installed the 'Luminous Theme' on my site. But the Landing Page of my site is not coming fine. What should I do?";?></a>
     <div class='faq' style='display: none;' id='faq_2'>
      <?php echo "A - Widgets placed in the Landing page are coming from '<a href='http://www.socialengineaddons.com/socialengine-advanced-events-plugin' target='_blank'>Advanced Events Plugin</a>' & '<a href='http://www.socialengineaddons.com/socialengine-groups-communities-plugin' target='_blank'>Groups / Communities Plugin</a>'. So, you need to have these plugins and featured content in these (If you want it to look like our demo). You will be able to change these setting from the Layout Editor as per your desire. Also, if you are not having these plugins you can place widgets of any other plugins there.<br />
[If you are placing any other widget on the landing page it will not have the CSS effect like you can see on our demo.]";?>
     </div>
    </li>

 <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo "Q - I want to change the color scheme of this theme. Is it possible with this Theme?";?></a>
     <div class='faq' style='display: none;' id='faq_6'>
      <?php echo "A - Yes to do so, please go to the 'Theme Customization' tab from the Admin panel of this theme. Now choose color scheme for your theme by selecting the given radio buttons. You can also select the 'Custom Colors' option to customize your theme according to your site. By this you can choose color according to your site from various available options.";?>
     </div>
    </li>
    
 <li>
   <a href="javascript:void(0);" onClick="faq_show('faq_16');"><?php echo "Q - My site is not coming fine after installing this theme. What might be the problem?";?></a>
     <div class='faq' style='display: none;' id='faq_16'>
      <?php 
        $url = $this->url(array('module' => 'siteluminous', 'controller' => 'settings', 'action' => 'place-customization-file'), 'admin_default', true);        
        ?>
      <?php 
        echo "A: It might be possible that Luminous Theme directory is missing ‘customization.css’ file.  For resolving this, you need to create customization.css file over here: '/application/themes/luminous/'. Please <a href='javascript:void(0)' onclick='Smoothbox.open(\"$url\");'>click here</a> if you want to create ‘customization.css’ file.";
      ?>
  </div>
    </li>
    
  <li>
   <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo "Q -  Can I add my custom CSS in this theme? If yes then how I can add it so that my changes do not get lost in case of theme up-gradations?";?></a>
    <div class='faq' style='display: none;' id='faq_10'>
      <?php 
        echo "Ans: Yes, you can add your custom CSS in this theme. We have created a new file customization.css for you in this theme, which enables you to add your customization changes for your website, you can write your CSS code over here and get your site look just the way you want it to. It will also not get lost in case of theme up-gradation.<br /><br />
You can find this file by following the below steps :<br />
- Go to the 'Layout' >> 'Theme Editor' section from the Admin panel of your site.<br />
- Now choose 'customization.css' from the ‘editing file’ dropdown. You may add the changes here which you want to do for your website.<br />
[If you are unable to find this file in the ‘editing file’ dropdown then please read the above FAQ.]";
      ?>
  </div>
    </li>  
   <li>
   <a href="javascript:void(0);" onClick="faq_show('faq_14');"><?php echo "Q - Fonts are not coming fine on my site. What might be the problem? How can I resolve this?";?></a>
     <div class='faq' style='display: none;' id='faq_14'>
      <?php 
        $url = $this->url(array('module' => 'siteluminous', 'controller' => 'settings', 'action' => 'place-htaccess-file'), 'admin_default', true); 
        $genralSettingUrl = $this->url(array('module' => 'core', 'controller' => 'settings', 'action' => 'general'), 'admin_default', true); 
        ?>
      <?php 
        echo "It is happening because you are using the 'Static File Base URL' setting in ‘<a href='$genralSettingUrl'>General Settings</a>’ section of admin panel. For resolving this, you need to create .htacces file over here: '/application/themes/luminous/'. Please <a href='javascript:void(0)' onclick='Smoothbox.open(\"$url\");'>click here</a> if you want to create .htaccess file.";
      ?>
  </div>
    </li>    
  <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_12');"><?php echo "Q - I have changed color of sign in and sign up buttons on my site, now transparency is not coming in colors as it was before. What should I do?";?></a>
     <div class='faq' style='display: none;' id='faq_12'>
      <?php echo "If you will change the sign in and sign up buttons color from admin side using theme customization tab then transparency in colors will not be visible in buttons.<br />

For this you need to do some changes mentioned below:<br />

1. Choose color from the color picker which you want for the sign in and sign up button for your site and copy the code generated in the text box.<br />

2. Now go to http://hex2rgba.devoth.com/ and enter the color code in “HEX value” box and click on “HEX 2 RGB(angel)” button.<br />
3. You will now get the RGB and RGBA format color in “RGB for CSS” box and “RGBA for CSS” box.
4. Now, choose the color code from “RGBA for CSS” and put this color code in file at below mentioned path:<br />

Directory_Name >> public/seaocore_themes >> luminousThemeConstants.css<br />
Now, search for this code: landingpage_signinbtn<br />
You will see the code like this: landingpage_signinbtn: #ff5f3f; landingpage_signupbtn: #ff5f3f; [Values may be change, its just for your reference]<br />

5. Replace it with the code like this: landingpage_signinbtn: “Paste RGBA color code”;<br />
landingpage_signupbtn: “Paste RGBA color code”; [It will look like this: landingpage_signinbtn: rgba(255, 95, 63, 0.5); landingpage_signupbtn: rgba(255, 95, 63, 0.5); ]<br /><br />
[<b>Note</b>: <i>You can configure the transparency of the buttons accordingly by changing the last value of RGBA code, for eg: rgba(255, 95, 63, 1) => rgba(255, 95, 63, 0.5)</i>]

";?>
     </div>
    </li>
 <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo "Q - Can I change the images rotating in the background on the landing page?";?></a>
     <div class='faq' style='display: none;' id='faq_5'>
      <?php echo "A - Yes, you can do so by following the below steps:<br />1. Go to the Admin panel of this theme.<br />2. Now from the “Images” section, upload the images you want for your landing page.<br />3. Now, these images will be visible on your Landing Page when you will place our widget “Landing Page Images” from the Layout Editor.";?>
     </div>
    </li>

 <li>
   <a href="javascript:void(0);" onClick="faq_show('faq_15');"><?php echo "Q - I am getting blur images for larger resolution on my 'Landing Page' under the 'Landing Page Images' widget. What should I do?";?></a>
     <div class='faq' style='display: none;' id='faq_15'>
      <?php 
        echo "A: Go to the 'Layout' >> 'Theme Editor' section from the Admin Panel of your site. Now choose 'landingpage.css' from the ‘editing file’ dropdown.<br/>Now add the below code and click on 'Save Changes':<br/>@media only screen and (min-width: 1360px) {div#slide-images{width:1200px; margin:0 auto;}";
      ?>
  </div>
    </li>
    <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo "Q - I do not want mini menu to be visible on the landing page for the logged out user. Can I do so?";?></a>
     <div class='faq' style='display: none;' id='faq_3'>
      <?php echo "A - Yes, please go to the “Layout Editor” >> “Site Header”, Now configure the settings of Advanced Mini Menu by clicking on the edit link. Select “No” for “Do you want to show this widget to non-logged-in users?” setting. [Dependent on <a href='http://www.socialengineaddons.com/socialengine-advanced-menus-plugin-interactive-attractive-navigation' target='_blank'>Advanced Menus Plugin - Interactive and Attractive Navigation</a>]";?>
     </div>
    </li>
       
   
     <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo "Q - How can I add menu in the Luminous - Footer Menu?";?></a>
     <div class='faq' style='display: none;' id='faq_1'>
      <?php echo "A - To do so, please follow the steps below:<br />1. Open Luminous - Footer Menu from the Layout >> Menu Editor section of your site.<br />2. Click on Add Item and enter the Label of the item.<br />3. Now enter URL for your added menu in the URL field.";?>
     </div>
    </li>
  <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo "Q - I want to change the text for the blocks placed on the landing page: 'Discover Events', 'Meet New People' & 'Engage'. How will I be able to do this?";?></a>
     <div class='faq' style='display: none;' id='faq_4'>
      <?php echo "A - Please go to the Global Settings section of this theme, please change the text accordingly from the tinymce editor placed there.";?>
     </div>
    </li>   

    <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo "Q - I want to change the footer text: 'Join the Media Community…...'. How can I do so?";?></a>
     <div class='faq' style='display: none;' id='faq_7'>
      <?php echo "A - Please follow the below steps to do so:<br />1. Go to the 'Layout' >> 'Language Manager' from the Admin panel of your site.<br />2. Now, click on 'Edit Phrases' link and search for the phrase 'Join the Media Community…...'.<br />3.Now add your phrase here which you want to replace with the above text and click on 'Save Changes'.<br />You will now be able to see the text you have added in place of the above text.

";?>
     </div>
    </li>
    
    <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo "Q - Can I add one more column in footer menu?";?></a>
     <div class='faq' style='display: none;' id='faq_8'>
      <?php echo "A - Yes you can do it easily by following the below steps:<br />1. Go to the 'Luminous - Footer Menu' from the menu editor available in the Admin panel of your site and click on 'Add Item' link.<br />2. Now fill the required details. Here while adding the URL, please add: javascript:void in place URL. 
You will now have another column added in the footer menu.";?>
     </div>
    </li>
  <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_11');"><?php echo "Q - I want to change the URL for the ‘Explore All’ link available on the Landing Page for Popular Groups. How can I do so?";?></a>
     <div class='faq' style='display: none;' id='faq_11'>
      <?php echo "A - To do so:<br />
1. Please go to the Layout Editor >> Home Page<br />
2. Now click on the edit link of the ‘AJAX Based Recently Posted, Popular, Random, Featured & Sponsored Groups’ widget.<br />
3. Now configure the ‘Enter Title Link’ setting by entering the URL as mentioned in description of the setting.<br />

You will now be redirected to this link when you will click on the ‘Explore All’ link.";?>
     </div>
    </li>
    
 <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_13');"><?php echo "Q -  I want to change the below given texts, shown on the landing page. How can I do so?<br/>   <ul style='list-style-type:disc; padding-left:25px;'>

     <li> Events & Groups that you'd love</li>
     <li>Discover new events in your town, interact with other party-goers and share the fun!";?></li>
   </ul>
     </a>
     <div class='faq' style='display: none;' id='faq_13'>
      <?php echo "A: Please follow the below steps to do so:<br />
1. Go to the 'Layout' >> 'Language Manager' from the admin panel of your site.<br />
2. Now, click on 'Edit Phrases' link and search for the phrases you mentioned above, one after another.<br />
3. Now add your phrase here which you want to replace with the above text and click on 'Save Changes'.<br />
You will now be able to see the text you have added in place of the above text.";?>
     </div>
    </li>   

    <li>
     <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo "Q - The CSS of this Theme is not coming on my site. What should I do ?";?></a>
     <div class='faq' style='display: none;' id='faq_9'>
      <?php echo "A - Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'.";?>
     </div>
    </li>
   

        
  </ul>
</div>

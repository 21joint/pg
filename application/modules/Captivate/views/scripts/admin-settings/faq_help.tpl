<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
    function faq_show(id) {
        if ($(id)) {
            if ($(id).style.display == 'block') {
                $(id).style.display = 'none';
            } else {
                $(id).style.display = 'block';
            }
        }
    }
<?php if ($this->faq_id): ?>
        window.addEvent('domready', function () {
            faq_show('<?php echo $this->faq_id; ?>');
        });
<?php endif; ?>
</script>
<div class="admin_seaocore_files_wrapper">
    <ul class="admin_seaocore_files seaocore_faq">

<li>
            <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo "My site is not coming fine after installing this theme. What might be the problem?"; ?></a>
            <div class='faq' style='display: none;' id='faq_1'>
                <?php
                $url = $this->url(array('module' => 'captivate', 'controller' => 'settings', 'action' => 'place-customization-file'), 'admin_default', true);
                ?>
                <?php
                echo "It might be possible that Captivate Theme directory is missing ‘customization.css’ file.  For resolving this, you need to create customization.css file over here: '/application/themes/captivate/'. Please <a href='javascript:void(0)' onclick='Smoothbox.open(\"$url\");'>click here</a> if you want to create ‘customization.css’ file.";
                ?>
            </div>
        </li>


        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo "Can I add my custom CSS in this theme? If yes, then how I can add it so that my changes do not get lost in case of theme up-gradations?"; ?></a>
            <div class='faq' style='display: none;' id='faq_4'>
                <?php echo "Yes, you can add your custom CSS in this theme. We have created a new file customization.css for you in this theme, which enables you to add your customization changes for your website, you can write your CSS code over here and get your site look just the way you want it to. It will also not get lost in case of theme up-gradation.You can find this file by following the below steps :<br />
1. Go to the 'Layout' >> 'Theme Editor' section from the Admin panel of your site.<br />
2. Now choose 'customization.css' from the ‘editing file’ dropdown. You may add the changes here which you want to do for your website.<br />
[Note: If you are unable to find this file in the ‘editing file’ dropdown then please read the above FAQ.]"; ?>
            </div>
        </li>


<li>
            <a href="javascript:void(0);" onClick="faq_show('faq_141');"><?php echo "Fonts are not coming fine on my site. What might be the problem? How can I resolve this?"; ?></a>
            <div class='faq' style='display: none;' id='faq_141'>
                <?php
                $url = $this->url(array('module' => 'captivate', 'controller' => 'settings', 'action' => 'place-htaccess-file'), 'admin_default', true);
                $genralSettingUrl = $this->url(array('module' => 'core', 'controller' => 'settings', 'action' => 'general'), 'admin_default', true);
                ?>
                <?php
                echo "It is happening because you are using the 'Static File Base URL' setting in ‘<a href='$genralSettingUrl'>General Settings</a>’ section of admin panel. For resolving this, you need to create .htacces file over here: '/application/themes/captivate/'. Please <a href='javascript:void(0)' onclick='Smoothbox.open(\"$url\");'>click here</a> if you want to create .htaccess file.";
                ?>
            </div>
        </li> 


<?php if (Engine_Api::_()->hasModuleBootstrap('sitemenu')) : ?>
            <li>
                <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo "Can I disable Captivate Theme main menu with 'More' navigation from header ?"; ?></a>
                <div class='faq' style='display: none;' id='faq_5'>
                    <?php echo "Yes, to do so follow below steps:<br />
1. Go to the 'Layout' >> 'Layout Editor' >> 'Site Header' from the admin panel of your site.<br />
2. Now remove this widget: 'Responsive Captivate Theme - Main Menu' and save  changes. <br />
[Note: In this case you are recommended to place Main Menu widget in Site Header.]"; ?>
                </div>
            </li>
        <?php endif; ?>


<li>
            <a href="javascript:void(0);" onClick="faq_show('faq_44');"><?php echo "If I want to enable 'Advanced Main Menu' widget in header instead of Responsive Captivate Theme - Main Menu', can I do so ?"; ?></a>
            <div class='faq' style='display: none;' id='faq_44'>
                <?php echo "Yes, If you want to enable 'Advanced Main Menu' widget in header instead of 'Responsive Captivate Theme - Main Menu', then please do the below changes:";?><br />
                <?php echo "1. Open 'Layout Editor' >> 'Site Header' and remove 'Responsive Captivate Theme - Main Menu' widget, place 'Advanced Main Menu' widget after the 'Advanced Mini Menu' widget and save the changes."; ?><br />
                <?php echo "2. Open 'Layout' >> 'Theme Editor' >> select 'customization.css' file from 'Editing File' dropdown and add below code in this file:
"; ?><br />
                <?php echo "div#global_wrapper {padding-top: 105px;} div.headline { background-color: transparent;} div.headline h2, div.headline h2 a, div.headline .tabs > ul > li > a {color: theme_font_color;}";?><br /> 
               <?php echo "3. Save the changes."; ?><br /> 
            </div>
        </li>

<li>
            <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo "I do not want mini menu to be visible on the landing page for the logged out users. Can I do so?"; ?></a>
            <div class='faq' style='display: none;' id='faq_3'>
                <?php echo "Yes, please go to the “Layout Editor” >> “Site Header”, Now configure the settings of Advanced Mini Menu by clicking on the edit link. Select “No” for “Do you want to show this widget to non-logged-in users?” setting. <br />
[Note: Dependent on <a target='_blank' href='https://www.socialengineaddons.com/socialengine-advanced-menus-plugin-interactive-attractive-navigation'>Advanced Menus Plugin - Interactive and Attractive Navigation</a>]"; ?>
            </div>
        </li>

 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_190');"><?php echo "Admin, Sign Out and a few other menu items are not visible to me in mini menu, how can I see them and how can I make it visible all the time as earlier ?"; ?></a>
            <div class='faq' style='display: none;' id='faq_190'>
                <?php echo "To make mini menu more spacious and attractive, we have added these items such as: Sign Out, Admin, Privacy and a few more under 'Settings' mini menu item. If you click on 'Settings' mini menu all these items would be visible to you.<br /> If you want to make it visible all the time as earlier then, please follow the below steps:<br />
1. Go to 'Member Settings Navigation Menu' from 'Menu Editor' in the admin panel of your site.<br />
2. Disable the menu times that you do not want to display under 'Settings' mini menu.<br />
3. Then go to the 'Mini Navigation Menu' from 'Menu Editor' and enable all the mini menu items that you want to be visible in menu of your site."; ?>
            </div>
        </li>
        
 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo "Can I change the images rotating in the background on the landing page?"; ?></a>
            <div class='faq' style='display: none;' id='faq_7'>
                <?php echo "Yes, you can do so by following the below steps:<br />
1. Go to the Admin panel of this theme.<br />
2. Go to the 'Images' tab and upload the images that you want for your landing page.<br />
3. Now go to 'Responsive Captivate Theme - Landing Page Images' widget settings and select the images that you want to be shown on landing page image rotator.<br />
[Note: You can upload multiple images to display them one after another as slideshow.]"; ?>
            </div>
        </li>

<li>
            <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo "I am getting blur images for larger resolution on my 'Landing Page' under the 'Landing Page Images' widget. What should I do?"; ?></a>
            <div class='faq' style='display: none;' id='faq_8'>
                <?php echo "1. Go to the 'Layout' >> 'Theme Editor' section from the Admin Panel of your site. <br />
2. Choose 'theme.css' from the ‘editing file’ dropdown.<br />
3. Now add the below code and click on 'Save Changes':<br />
@media only screen and (min-width: 1360px) {div#slide-images{width:1200px; 
margin:0 auto;}"; ?>
            </div>
        </li>


 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_11');"><?php echo "Can I disable the uploaded Landing Page Images from admin panel?"; ?></a>
            <div class='faq' style='display: none;' id='faq_11'>
                <?php echo "Yes, to do so please follow the below steps:<br />
1. Go to the 'Images' section available in the admin panel of this plugin. Here, you can see the list of all the images uploaded by you.<br />
2. Now, use the green button available along the images. By clicking on it, you can enable / disable the images as per your requirement."; ?>
            </div>
        </li>


 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_12');"><?php echo "How to change the ordering of images displaying on landing page?"; ?></a>
            <div class='faq' style='display: none;' id='faq_12'>
                <?php echo "Please follow the below steps to do so:<br />

1. Go to the 'Images' tab available in the admin panel of this plugin. Here, you can see the list of all the images uploaded by you.<br />
2. Now, drag and drop the images vertically to re-order them in sequence they should appear to members on the landing page of your website."; ?>
            </div>
        </li>

 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_13');"><?php echo "How I can hide 'Browse', 'Sign In' / 'Sign Up' and other available buttons / links showing on images?"; ?></a>
            <div class='faq' style='display: none;' id='faq_13'>
                <?php echo "To do so, please follow the below steps:<br />

1. Go to 'Layout' >> 'Layout Editor' available in the admin panel of your site.<br />
2. Now open the widgetized page where you want to disable Browse, Sign in/Sign up and other available buttons / links on images.<br />
3. Edit the ‘Responsive Captivate Theme - Landing Page Images’ widget and set ‘No’ for various settings that you want to disable on images and save your changes."; ?>
            </div>
        </li>

 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_14');"><?php echo 'How can I change the below text displaying on image rotator?<br />
"1. BRING PEOPLE TOGETHER"<br />
"2. Watch Videos, Explore Channels and Create & Share Playlists."<br />
'; ?></a>
            <div class='faq' style='display: none;' id='faq_14'>
                <?php echo "Please follow the below steps to do so:
<br />
1. Go to 'Layout' >> 'Layout Editor' available in the admin panel of your site.<br />
2. Open the widgetized page where ‘Responsive Captivate Theme - Landing Page Images’ widget is placed and edit this widget settings.<br />
3. Now configure the below settings text and save the changes:<br />
(i). Enter the title that you want to display on this image rotator.
<br />
(ii). Enter the description that you want to display on this image rotator."; ?>
            </div>
        </li>


        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_16');"><?php echo "How can I display logo in ‘Responsive Captivate Theme - Landing Page Images’ widget?"; ?></a>
            <div class='faq' style='display: none;' id='faq_16'>
                <?php echo "To do so, please follow the below steps:<br />
1. Go to 'Layout' >> 'Layout Editor' available in the admin panel of your site.<br />
2. Open the widgetized page where ‘Responsive Captivate Theme - Landing Page Images’ widget is placed and edit this widget settings.<br />
3. Now, choose ‘Yes’ for ‘Do you want to display your website's logo on the top-left side of the images rotator? and select the logo for your website. [You can upload new logo from ‘File and Media Manager’ section available in the admin panel of your website.]<br />
4. And save the changes.<br /> "; ?>
            </div>
        </li>

<li>
            <a href="javascript:void(0);" onClick="faq_show('faq_17');"><?php echo "I want to change the URL on 'Important Link' showing on images. How can I do so ?"; ?></a>
            <div class='faq' style='display: none;' id='faq_17'>
                <?php echo "To do so, please follow the below steps:<br />
1. Go to 'Layout' >> 'Layout Editor' available in the admin panel of your site.<br />
2. Open the widgetized page where ‘Responsive Captivate Theme - Landing Page Images’ widget is placed. Use ‘edit’ link to configure this widget.<br />
3. Now enable this setting: 'Do you want to show a button in the header area to display an important link of your website?' and configure the header button URL with the one you want to have as your important link and save the changes. "; ?>
            </div>
        </li>

 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo "I want to change the text for the blocks shown on clicking the Get Started / How It Works Button, on the landing page: 'Post & Watch Videos', 'Create & Explore Channels' and 'Create & Share Playlists'. How will I be able to do this?"; ?></a>
            <div class='faq' style='display: none;' id='faq_9'>
                <?php echo "Please go to the Global Settings section of this theme, please change the text showing under this setting: 'Action Button's Slide Down Content' accordingly from the TinyMCE editor placed over there."; ?>
            </div>
        </li>


  <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo "I want to display an image rotator on inner pages of my website. Is it possible with this theme?"; ?></a>
            <div class='faq' style='display: none;' id='faq_10'>
                <?php echo "Yes, you can easily do it by using our ‘Responsive Captivate Theme - Banner Images’ widget. If you want to to display banner image rotator on an inner page of your site. i.e. Member Home Page, please follow the below steps:
<br />
1. Upload your banner images from 'Banners' section available in the admin panel of this theme and set the sequence of banner images by dragging-and-dropping them vertically. Multiple banner images can be added to display them in a circular manner, i.e one after another.<br />
2. Place this widget: ‘Responsive Captivate Theme - Banner Images’ on the widgetized page of your site and edit this widget settings to configure various options related to how to show banner images on that page. <br />
3. Save Changes to reflect it.
"; ?>
            </div>
        </li>

       
 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_21');"><?php echo "I have placed Banner Images widget on a widgetized page, but it is not coming in full length. What might be the reason?"; ?></a>
            <div class='faq' style='display: none;' id='faq_21'>
                <?php echo "It might be happening because you have not placed this widget in the top container of your widgetized page. Please place this widget in the top container of your widgetized page and save the changes. Please <a target='_blank' href='https://www.socialengineaddons.com/sites/default/files/images/captivate_theme/page-block-pacement.png'> click here </a> to view the placement of this widget. "; ?>
            </div>
        </li>

        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_19');"><?php echo "How can I choose distinct banner images for my various widgetized pages?"; ?></a>
            <div class='faq' style='display: none;' id='faq_19'>
                <?php echo "You can easily do this by following the below steps:<br />        
1. Go to 'Layout' >> 'Layout Editor' available in the admin panel of your site.<br />    
2. Open the widgetized page where ‘Responsive Captivate Theme - Banner Images’ widget is placed.<br />       
3. Edit this widget and configure this setting: ‘Select the banners that you want to show in this widget?’ and select the banner image and save the changes.<br />       
[Note: You can upload new banners from ‘Banners’ tab available in the admin panel of this plugin.]"; ?>
            </div>
        </li>

 <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_20');"><?php echo 'How can I change the below texts that are shown on the banner images?<br />
"Albums and Videos that you\'d love"<br />
"The foremost source to explore albums and watch videos".
'; ?></a>
            <div class='faq' style='display: none;' id='faq_20'>
                <?php echo "To do so, please follow the below steps:<br />
1. Go to 'Layout' >> 'Layout Editor' available in the admin panel of your site.<br />
2. Open the widgetized page where ‘Responsive Captivate Theme - Banner Images’ widget is placed.<br />
3. Edit this widget and configure the below settings text and save changes:<br />
(i). Enter the title that you want to display on this banner.
<br />
(ii). Enter the description that you want to display on this banner."; ?>
            </div>
        </li>


        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_100');"><?php echo "I want to change the color scheme of this theme. Is it possible with this Theme?"; ?></a>
            <div class='faq' style='display: none;' id='faq_100'>
                <?php echo "Yes to do so, please go to the 'Theme Customization' tab from the Admin panel of this theme. Now choose color scheme for your theme by selecting the given radio buttons. You can also select the 'Custom Colors' option to customize your theme according to your site. By this you can choose color according to your site from various available options."; ?>
            </div>
        </li>
        
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_50');"><?php echo "I want to set a background image in the body of my site. Is it possible with this theme ?"; ?></a>
            <div class='faq' style='display: none;' id='faq_50'>
                <?php echo "Yes, you can set a background image in the body of your website to make your website more attractive and appealing. To do so, please go to the 'Theme Customization' tab from admin panel of this plugin, choose the custom color radio button, enable the option 'Website's Body Background Image' under Website's Body Background Image / Color setting and select the background image from 'Website's Body Background Image Dropdown'.<br />
[Note: You can upload a new file from: “Layout” > “File & Media Manager”]
"; ?>
            </div>
        </li>

        
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo "I have changed color of sign in and sign up buttons on my site, now transparency is not coming in colors as it was before. What should I do?"; ?></a>
            <div class='faq' style='display: none;' id='faq_6'>
                <?php echo "If you will change the sign in and sign up buttons color from admin side using theme customization tab then transparency in colors will not be visible in buttons. For this you need to do some changes mentioned below:<br />
1. Choose color from the color picker which you want for the sign in and sign up button for your site and copy the code generated in the text box.<br />
2. Now go to <a href='http://hex2rgba.devoth.com/' target='_blank'>http://hex2rgba.devoth.com</a> and enter the color code in “HEX value” box and click on “HEX 2 RGB(angel)” button.<br />
3. You will now get the RGB and RGBA format color in “RGB for CSS” box and “RGBA for CSS” box. <br />
4. Now, choose the color code from “RGBA for CSS” and put this color code in file at below mentioned path:<br />
Directory_Name >> public/seaocore_themes >> captivateThemeConstants.css <br />
Now, search for this code: landingpage_signinbtn <br />
You will see the code like this: landingpage_signinbtn: #ff5f3f; landingpage_signupbtn: #ff5f3f; [Values may be change, its just for your reference]<br />
5. Replace it with the code like this: landingpage_signinbtn: “Paste RGBA color code”;landingpage_signupbtn: “Paste RGBA color code”; [It will look like this: landingpage_signinbtn: rgba(255, 95, 63, 0.5); landingpage_signupbtn: rgba(255, 95, 63, 0.5); ]<br />
[Note: You can configure the transparency of the buttons accordingly by changing the last value of RGBA code, for eg: rgba(255, 95, 63, 1) => rgba(255, 95, 63, 0.5)]"; ?>
            </div>
        </li>

  

        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_23');"><?php echo "I want to change this footer text: 'Explore & Watch videos that…...'. How can I do so?"; ?></a>
            <div class='faq' style='display: none;' id='faq_23'>
                <?php echo "To do so, please follow the below steps:<br />
1. Go to the 'Footer Templates' tab available in the admin panel of Captivate Theme.<br />
2. Now, go to the 'Footer HTML Block Title & Description' setting and edit this text using provided TinyMce editor with this setting.<br />
3. And save the changes."; ?>
            </div>
        </li>

    <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_22');"><?php echo 'I want to display this footer text: “Explore & Watch videos that…...” in French language. Is it possible to do so?"
'; ?></a>
            <div class='faq' style='display: none;' id='faq_22'>
                <?php echo "Yes, to do so please follow the below steps:<br />
1. Go to the 'Layout' >> 'Language Manager' from the Admin panel of your site.<br />
2. Click on 'Edit Phrases' link and search the text that is currently showing.<br />
3. Now add your phrase in French and Save Changes. Similarly you can edit and display the text in any other languages as per your requirement."; ?>
            </div>
        </li>

        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_24');"><?php echo "Can I add one more column in footer menu?"; ?></a>
            <div class='faq' style='display: none;' id='faq_24'>
                <?php echo "Yes, you can do it by following the below steps:<br />
1. <a href='admin/menus/index?name=captivate_footer' target='_blank'>Click here</a> to go to the 'Responsive Captivate Theme - Footer Menu'.<br />
2. Click on 'Add Item' link.<br />
3. Now fill the required details and enter: 'javascript:void' in URL text field and save changes."; ?>
            </div>
        </li>

        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_25');"><?php echo "How can I add a menu in the 'Responsive Captivate - Footer Menu'?"; ?></a>
            <div class='faq' style='display: none;' id='faq_25'>
                <?php echo "To do so, please follow the steps below:<br />
1. <a href='admin/menus/index?name=captivate_footer' target='_blank'>Click here</a> to go to the 'Responsive Captivate Theme - Footer Menu'.<br />
2. Click on 'Add Item' and enter the details like: 'Label', 'URL', etc. and save changes."; ?>
            </div>
        </li>

        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_26');"><?php echo 'Few pages of my web site are not coming fine because theme CSS is not loading on my site. What should I do ?'; ?></a>
            <div class='faq' style='display: none;' id='faq_26'>
                <?php echo "Please enable 'Development Mode' for your website from the 'Admin Panel' home page and check the pages which were not coming fine. It would be showing fine now and if everything seems fine change to 'Production Mode' again."; ?>
            </div>
        </li>

    </ul>
</div>

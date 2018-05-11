<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteandroidapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Settings.php 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteandroidapp_Form_Admin_AppBuilder_Settings extends Engine_Form {

    protected $_doWeHaveLatestVersion;
    protected $_enabledTabName;

    public function getDoWeHaveLatestVersion() {
        return $this->_doWeHaveLatestVersion;
    }

    public function setDoWeHaveLatestVersion($doWeHavelatestVersion) {
        $this->_doWeHaveLatestVersion = $doWeHavelatestVersion;
        return $this;
    }

    public function getEnabledTabName() {
        return $this->_enabledTabName;
    }

    public function setEnabledTabName($enabledTabName) {
        $this->_enabledTabName = $enabledTabName;
        return $this;
    }

    public function init() {
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $package = Zend_Controller_Front::getInstance()->getRequest()->getParam('package', '');
        $tab = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab', 1);
        if (empty($package)) {
            $this->addElement('Select', 'package', array(
                'label' => 'Select Your Mobile Apps Subscription Plan',
                'required' => true,
                'allowEmpty' => false,
                'multiOptions' => array(
                    '' => '',
                    'starter' => 'Mobile Starter',
                    'pro' => 'Mobile Pro'
                ),
                'value' => $package,
                'onchange' => 'selectPackage()',
            ));
        } else {
            $APIModules = array(
                'album',
                'blog',
                'classified',
                'event',
                'forum',
                'group',
                'music',
                'poll',
                'video'
            );
            $modules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
            $enabledModules = array_intersect($APIModules, $modules);

            foreach ($enabledModules as $module) {
                $multiOptions[$module] = Engine_Api::_()->getDbTable('integrated', 'seaocore')->getModuleTitle($module);
            }

            $this->addElement('Dummy', 'required_fields', array(
                'label' => Zend_Registry::get('Zend_Translate')->_('Fields with asterisk (<span style="color:RED">*</span>) are mandatory.')
            ));
            $this->required_fields->getDecorator('Label')->setOptions(array('escape' => false));

            if ($tab == 1) {

                if (($package === 'pro')) {
                    $this->addElement('Dummy', 'google_play_details', array(
                        'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>Google Play Developer Credentials and Access</h3></center><br /><span style="font-weight: bold;">Important NOTE: </span>For us to be able to submit your App to Google Play, please ensure that you have Turned Off "2-Step Verification" for your Google Developer / Gmail Account. Please follow the steps mentioned in <a href="https://youtu.be/G8DtPEn1FpI" target="_blank">this video</a> for this. If you do not turn this off, then we will not be able to access your Google Developer Account to submit your App.<br />If you do not want to turn off 2-Step Verification for your account, then we will provide to you the APK file for the App, using which you will yourself be able to submit your App to the Google Play Store.')
                    ));
                    $this->google_play_details->getDecorator('Label')->setOptions(array('escape' => false));

                    $this->addElement('Radio', 'publish_app', array(
                        'label' => 'Submitting App to Google Play Store',
                        'description' => "How do you want your Android App to be submitted to the Google Play Store?",
                        'multiOptions' => array(
                            1 => 'I want the SocialEngineAddOns Support Team to submit my app.',
                            0 => 'I want my app’s “.apk” file to be sent to me, and I will myself submit the app.'
                        ),
                        'value' => 1,
                        'onchange' => 'gmailAppSubmission()'
                    ));
                    $this->publish_app->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                    $this->addElement('Radio', 'provide_google_play_account_details', array(
                        'label' => 'Google Play Developer Credentials / Access',
                        'multiOptions' => array(
                            1 => 'I will provide my Google Play Developer Account Details for app submission.',
                            0 => 'I will give permission to socialengineaddons@gmail.com email address, to access my Google Play Developer Console.'
                        ),
                        'value' => 1,
                        'onchange' => 'gmailPlayStorePermission()'
                    ));

                    $this->addElement('Text', 'gmail_permission_done', array(
                        'decorators' => array(array('ViewScript', array(
                                    'viewScript' => '_gmailPermission.tpl',
                                    'class' => 'form element'
                                )))
                    ));
//                $this->addElement('Checkbox', 'gmail_permission_done', array(
//                    'description' => 'Give Permission to Access Google Play Developer Account',
//                    'label' => 'Please follow this <a href="https://youtu.be/U52t96CwisI" target="_blank">Video Tutorial</a> to give permission to our <span style="font-weight: bold;">socialengineaddons@gmail.com</span> email address, to access your Google Play Developer Console.',
////                    'value' => 0
//                ));
//                $this->gmail_permission_done->getDecorator('Label')->setOptions(array('escape' => false));

                    $this->addElement('Text', 'google_play_login_email', array(
                        'label' => 'Google Play Login Email',
                        'description' => '<span style="font-weight:bold;">Turned Off "2-Step Verification" for your Google Developer / Gmail Account</span>',
//                    'required' => true,
//                    'allowEmpty' => false,
                        'validators' => array(array('EmailAddress', 1)),
                        'filters' => array('StringTrim')
                    ));
                    $this->google_play_login_email->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                    $this->addElement('Text', 'google_play_login_password', array(
                        'label' => 'Google Play Login Password',
//                    'required' => true,
//                    'allowEmpty' => false
                    ));
                }

                $this->addElement('Text', 'google_analytics_tracking_id', array(
                    'label' => ' Google Analytics Tracking ID',
                    'description' => 'Google Analytics is a very useful tool to measure user activity and engagement in your app. To enable this tracking, enter the Google Analytics Tracking ID for your app. [To get this ID, please follow this <a href = "https://youtu.be/_2u9I9HWkns" target = "_blank">video tutorial</a>.]'
                ));
                $this->google_analytics_tracking_id->getDecorator('description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));



                $this->addElement('Dummy', 'app_details', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>App Submission Details</h3></center>'),
                ));
                $this->app_details->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Text', 'package_name', array(
                    'label' => 'App Package Name',
                    'description' => "Enter the desired App Package Name for your app. Package Name is the URL component of your App in the Google Play Store, and should be unique on it. A common and simple way of creating your package name is by reversing your website’s domain and adding \".app\" at the end (example: com.example.app). Click on the below formed URL after adding your desired Package Name in the text box, to check whether your chosen Package Name is available. If clicking on the URL opens up an app, then that package name is unavailable and already in use, and if you see a message like: \"We're sorry, the requested URL was not found on this server.\", then you can use that app package name.<br/><span id='package_name_url'><a href='https://market.android.com/details?id=PACKAGE_NAME' target='_blank'>https://market.android.com/details?id=PACKAGE_NAME</a></span>",
//                'required' => true,
//                'allowEmpty' => false,
                    'onkeyup' => 'addPackageUrl();'
                ));
                $this->package_name->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'project_number', array(
                    'label' => 'Android Project Number',
                    'description' => "Enter project number from <a href='https://console.developers.google.com' target='_blank'>Google Account</a> , by this Push Notification will get enable for your App. <a href='https://youtu.be/0PNvUN9YfZY' target='_blank'>Click here</a> to know how you can create Project Number for your App.",
//                'required' => true,
//                'allowEmpty' => false
                ));
                $this->project_number->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('File', 'firebase_google_services', array(
                    'label' => 'Google Services File',
                    'description' => "This file is required for Push Notifications, Google ads etc. Upload the file that you've downloaded while following <a href='https://youtu.be/0PNvUN9YfZY' target='_blank'>video</a> for Project Number.",
                ));
                $this->firebase_google_services->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'title', array(
                    'label' => 'App Title',
                    'description' => 'Name / Title of your app that will be displayed under your app icon on mobile device. It should be of 0 to 30 characters.<a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/Title.png" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>',
                    'required' => true,
                    'allowEmpty' => false,
                    'maxlength' => 30,
                    'validators' => array(
                        array('stringLength', false, array(0, 30))
                    )
                ));
                $this->title->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Textarea', 'short_description', array(
                    'label' => 'App Short Description',
                    'description' => 'This description will be displayed on your app’s profile page at Google Play Store. Characters limit is 0 to 80 chars.<a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/description.png" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>',
                    'required' => true,
                    'allowEmpty' => false,
                    'validators' => array(
                        array('stringLength', false, array(0, 80))
                    )
                ));
                $this->short_description->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Textarea', 'description', array(
                    'label' => 'App Description',
                    'description' => 'This description will be displayed on your app’s profile page at the Google Play Store. 0 to 4000 chars. (See tips for creating policy compliant description: <a href=\'https://support.google.com/googleplay/android-developer/answer/113474\' target=\'_blank\'>https://support.google.com/googleplay/android-developer/answer/113474</a>)',
                    'required' => true,
                    'allowEmpty' => false,
                    'validators' => array(
                        array('stringLength', false, array(0, 4000))
                    )
                ));
                $this->description->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'default_language', array(
                    'label' => 'Default Language for the App',
                    'description' => 'We will be using this to select the default language for your App while submitting it in the App Store. We will not be doing any translations.',
                    'required' => true,
                    'allowEmpty' => false
                ));

                $this->addElement('Select', 'category', array(
                    'label' => 'App Category',
                    'description' => 'Select the category of your app that should be displayed on your app’s profile page at Google Play Store. (For ex. If you app is Bikes based, then category of your app should be Sports.)',
                    'required' => true,
                    'allowEmpty' => false,
                    'multiOptions' => array(
                        'book_&_reference' => 'Books & Reference',
                        'business' => 'Business',
                        'comics' => 'Comics',
                        'communication' => 'Communication',
                        'education' => 'Education',
                        'entertainment' => 'Entertainment',
                        'finance' => 'Finance',
                        'health_&_fitness' => 'Health & Fitness',
                        'libraries_&_demo' => 'Libraries & Demo',
                        'lifestyle' => 'Lifestyle',
                        'media_&_video' => 'Media & Video',
                        'medical' => 'Medical',
                        'music_&_audio' => 'Music & Audio',
                        'news_&_magazines' => 'News & Magazines',
                        'personalization' => 'Personalization',
                        'photography' => 'Photography',
                        'productivity' => 'Productivity',
                        'shopping' => 'Shopping',
                        'social' => 'Social',
                        'sports' => 'Sports',
                        'tools' => 'Tools',
                        'transportation' => 'Transportation',
                        'traval_&_local' => 'Travel & Local',
                        'weather' => 'Weather'
                    ),
                ));
                $this->category->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Select', 'content_rating', array(
                    'label' => 'Content Rating',
                    'description' => 'Note: As per <a href=\'https://support.google.com/googleplay/android-developer/answer/188189#ugc\' target=\'_blank\'>Google Play content rating policy</a>, the Content Rating of your app cannot be one of the other 2 options: "Low Maturity" and "Everyone" as it will enable communication between users, and hence requires "Medium Maturity" or higher content rating.',
                    'required' => true,
                    'allowEmpty' => false,
                    'multiOptions' => array(
                        '' => '',
                        'high_maturity' => 'High Maturity',
                        'medium_maturity' => 'Medium Maturity'
                    ),
                ));
                $this->content_rating->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Dummy', 'contact_details', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>Contact Details</h3></center>'),
                ));
                $this->contact_details->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Text', 'website_contact_details', array(
                    'label' => 'Website',
                    'description' => "Website to be associated with this App's listing on Google Play.",
                    'required' => true,
                    'allowEmpty' => false,
                ));

                $this->addElement('Text', 'email_contact_details', array(
                    'label' => 'Email',
                    'description' => "Email address to be associated with this App's listing on Google Play.",
                    'required' => true,
                    'allowEmpty' => false,
                    'validators' => array(array('EmailAddress', 1)),
                    'filters' => array('StringTrim')
                ));

                // Deepak add URI validator here.
                $this->addElement('Text', 'policy_url', array(
                    'label' => 'Privacy Policy URL',
                    'description' => "URL of the privacy policy for this app.",
                    'required' => true,
                    'allowEmpty' => false,
                ));

                $this->addElement('Dummy', 'app_default_settings', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>Default App Settings</h3></center>'),
                ));
                $this->app_default_settings->getDecorator('Label')->setOptions(array('escape' => false));

                $url = $view->url(array('module' => 'siteapi', 'controller' => 'settings'), 'admin_default', true);
                $this->addElement('Radio', 'siteapi_validate_ssl', array(
                    'label' => 'Select for https',
                    'description' => 'To create mobile application for your website, please select appropriate option. [Note: I want to run my website on https, what should I do ? <a href="' . $url . '#siteapi_ssl_verification" target="_blank">click here</a> to read about this. If you are still working to run your website on https then you should wait to send us mobile application request until site run on https]',
                    'multiOptions' => array(
                        0 => 'I do not want to run my website on https',
                        1 => 'I am running my website on https'
                    ),
                    'required' => true,
                    'allowEmpty' => false
                ));
                $this->siteapi_validate_ssl->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $consumerCalling = $view->url(array('module' => 'siteapi', 'controller' => 'consumers', 'action' => 'manage'), 'admin_default', true);
                $this->addElement('Text', 'api_consumer_key', array(
                    'label' => 'API Consumer Key',
                    'description' => 'Enter the correct API Consumer Key for this Android App that you have created from the "API Consumers" section in the Admin Panel of "REST API Plugin". This is required for communication between your server and mobile apps. Please <a href="' . $consumerCalling . '" target="_blank">click here</a>, If you have not configured yet.<br /><b>Note:</b> We recommend that the API of your website should be used on SSL. For details, see "API communication on SSL" field of <a href="' . $url . '">administration</a> of "SocialEngine REST API Plugin".',
                    'required' => true,
                    'allowEmpty' => false
                ));
                $this->api_consumer_key->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'api_consumer_secret', array(
                    'label' => 'API Consumer Secret',
                    'description' => 'Enter the correct API Consumer Secret for this Android App that you have created from the "API Consumers" section in the Admin Panel of "REST API Plugin". This is required for communication between your server and mobile apps. Please <a href="' . $consumerCalling . '" target="_blank">click here</a>, If you have not configured yet.',
                    'required' => true,
                    'allowEmpty' => false
                ));
                $this->api_consumer_secret->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                if (isset($multiOptions) && !empty($multiOptions)) {
                    $this->addElement('Multiselect', 'enabledModules', array(
                        'label' => 'Modules in App',
                        'description' => 'Below you can choose the modules from your website that you want to be available in your Android App. [Modules (of SocialEngineAddOns or 3rd-party) which are not getting listed below can be made available in your app <a href="https://www.socialengineaddons.com/page/enabling-socialengineaddons-3rd-party-plugins-ios-android-apps-webview
" target="_blank">via WebView</a>.]',
                        'multiOptions' => $multiOptions,
                        'value' => array_keys($multiOptions)
                    ));
                    $this->enabledModules->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
                }

                $this->addElement('Radio', 'app_webview', array(
                    'label' => 'WebView Type',
                    'description' => "Please select the type of WebView you want to use in your app, for browsing Non-Integrated SocialEngineAddOns Plugins and 3rd Party Plugins. [Note: Difference between these webview types is their Design and Speed. It is recommended to use 'Chrome Extension for Webview' <a target='_blank' class='mleft5' title='View Screenshot' href='application/modules/Siteandroidapp/externals/images/Chrome.png' target='_blank'><img src='application/modules/Siteandroidapp/externals/images/eye.png' /></a> as it is comparatively fast than 'Self-Designed In-App Webview' <a target='_blank' class='mleft5' title='View Screenshot' href='application/modules/Siteandroidapp/externals/images/In-app.png' target='_blank'><img src='application/modules/Siteandroidapp/externals/images/eye.png' /></a> & provides options like Find & Open in Chrome.]",
                    'multiOptions' => array(
                        0 => 'Self-Designed In-App Webview',
                        1 => 'Chrome Extension for Webview'
                    ),
                    'value' => 0
                ));
                $this->app_webview->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
                
                 $this->addElement('Radio', 'isshow_app_name', array(
                    'label' => 'Header UI in app',
                     'description' => 'Select the header type you want to display in the app. You can choose Display only the Search Bar<a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/App_Header_with_Search_only.png" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a> or App Name in the header with search icon <a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/App_Header_with_Title.png" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>',
                    'multiOptions' => array(
                        0 => 'Display only the Search Bar in the complete app header',
                        1 => 'App Name in the header with search icon'
                    ),
                    'value' => 0
                ));
                $this->getElement('isshow_app_name')->getDecorator('Description')->setEscape(false);
                
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('primemessenger')) {
                    $validateMessengerSubscription = $this->validateMessengerSubscription();
                    if ($validateMessengerSubscription) {
                        $this->addElement('Checkbox', 'enable_primemessenger', array(
                            'label' => 'Enable Prime Messenger for the App',
                            'description' => 'Prime Messenger',
                            'value' => 1
                        ));
                        $this->enable_primemessenger->getDecorator('Label')->setOptions(array('placement' => 'APPEND', 'escape' => false));
                    }
                }

                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestore')) {
                    $this->addElement('Checkbox', 'header_cart_display', array(
                        'label' => 'Display Cart Icon in App Header on Home Page.<a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/Header.png" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>',
                        'description' => 'Display Cart Icon',
                        'value' => 1
                    ));
                    $this->header_cart_display->getDecorator('Label')->setOptions(array('placement' => 'APPEND', 'escape' => false));
                }


                $this->addElement('Checkbox', 'rate_us', array(
                    'label' => 'Enabling this feature will display a Rate Us pop-up to app users at appropriate instances.',
                    'description' => "Enable Rate Us feature ?",
                ));

                $this->addElement('Radio', 'showcase_app', array(
                    'label' => 'Allow Showcasing Apps ?',
                    'description' => "Do you want to allow us to showcase your app on our SocialEngineAddOns website, like blog posts and newsletters.",
                    'multiOptions' => array(
                        0 => "No, don't showcase my app on SocialEngineAddOns website.",
                        1 => "Yes, showcase my app on SocialEngineAddOns website."
                    ),
                    'value' => 0
                ));
                $this->showcase_app->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $mapGuidelines = $view->url(array('module' => 'seaocore', 'controller' => 'settings', 'action' => 'map-guidelines'), 'admin_default', true);
                $this->addElement('Text', 'map_key', array(
                    'label' => 'Google Places API Key',
                    'description' => 'This is used for location related features (like Check-in etc. ) in the app. Please visit the <a href="' . $mapGuidelines . '" target="_blank">Guidelines for configuring Google Places API key</a>.<br />[Note: We recommend you to enable the billing feature for this API calling as google allow free usage only up to a certain limit, after which it does not give the required response resulting in blank pages. Please <a href="https://support.google.com/cloud/answer/6158867" target="_blank">click here</a> to know more.]',
                    'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.google.map.key')
                ));
                $this->map_key->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'commit_chat_package', array(
                    'label' => 'CometChat Package Name',
                    'description' => 'Enter the package name of your CometChat. If you are not having it then please contact to the CometChat team. [Note: To integrate your CometChat app with your SocialEngine app, you should have your CometChat app already built and published on Google Play Store.]'
                ));

                $this->addElement('Text', 'twitter_app_id', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_twitterLogin.tpl',
                                'class' => 'form element'
                            )))
                ));

                $this->addElement('Checkbox', 'twitter_post', array(
                    'description' => 'Twitter Share',
                    'label' => 'Enable sharing of content from Status Box to Twitter. It will work only if you have enabled Twitter Login in your app.'
                ));

                $this->addElement('Text', 'facebook_app_id', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_facebookLogin.tpl',
                                'class' => 'form element'
                            )))
                ));

                $this->addElement('Checkbox', 'facebook_post', array(
                    'label' => 'Enable sharing of content from Status Box to Facebook profile. It will work only if you have configured "Publish to Facebook" setting (from Admin > Settings > Facebook Integration) on your website. To enable this functionality in app, you need to get "Publish_actions" permission approved from Facebook.Please <a href="https://www.socialengineaddons.com/page/facebook-permission-ios-android-mobile-app" target="_blank">click here</a> for the steps of permission approval.',
                    'description' => 'Facebook Share',
                ));
                $this->facebook_post->getDecorator('Label')->setOptions(array('placement' => 'APPEND', 'escape' => false));

                $this->addElement('Text', 'facebook_access_permission', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_facebookAccessPermission.tpl',
                                'class' => 'form element'
                            )))
                ));

//            $facebookIntegrationPage = $view->url(array('module' => 'user', 'controller' => 'settings', 'action' => 'facebook'), 'admin_default', true);
//            $this->addElement('Checkbox', 'facebook_app_id', array(
//              'description' => 'Facebook Login',
//              'label' => 'Enable login using Facebook [You should have enabled Facebook Integration <a href="' . $facebookIntegrationPage . '">from here</a>. You can use the SocialEngineAddOns "Facebook Application Configuration and Submission Service" if required.]'
//            ));
//
//            
//            $this->facebook_app_id->getDecorator('label')->setOptions(array('escape' => false));
//            $this->addElement('Text', 'facebook_app_id', array(
//                'label' => 'Facebook Login',
//                'description' => 'Enter the valid facebook id. Please integrate SocialEngine to Facebook. To do so, create an Application through the <a href="http://www.facebook.com/developers/apps.php" target="_blank">Facebook Developers</a> page.',
//                'required' => true,
//                'allowEmpty' => false,
//            ));
//            $this->facebook_app_id->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
//            $this->addElement('Text', 'twitter_key', array(
//                'label' => 'Twitter Key',
//                'description' => 'Enter the valid twitter key. Please integrate SocialEngine to Twitter. To do so, create an Application through the <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter Developers</a> page. You will need to select "Read & Write" in order to allow posting to Twitter.',
//                'required' => true,
//                'allowEmpty' => false,
//            ));
//            $this->twitter_key->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
//
//            $this->addElement('Text', 'twitter_secret', array(
//                'label' => 'Twitter Secret',
//                'description' => 'Enter the valid twitter secret. Please integrate SocialEngine to Twitter. To do so, create an Application through the <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter Developers</a> page. You will need to select "Read & Write" in order to allow posting to Twitter.',
//                'required' => true,
//                'allowEmpty' => false,
//            ));
//            $this->twitter_secret->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));


                $this->addElement('Button', 'submit', array(
                    'label' => 'Save Changes and Continue',
                    'type' => 'submit',
                    'ignore' => true,
//                    'order' => 500,
                ));
            } else if ($tab == 2) {
                $this->addElement('Dummy', 'app_color_code', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>App Branding Colors</h3></center>'),
                ));
                $this->app_color_code->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Text', 'app_header_color_primary', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_themeHeaderColorPrimary.tpl',
                                'class' => 'form element'
                            )))
                ));

                $this->addElement('Text', 'app_header_color_dark', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_themeHeaderColorDark.tpl',
                                'class' => 'form element'
                            )))
                ));

                $this->addElement('Text', 'app_header_text_color', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_themeHeaderTextColor.tpl',
                                'class' => 'form element'
                            )))
                ));

                // notification icon background color
                $this->addElement('Text', 'notification_icon_background_color', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_themeNotificationIconBackgroundColor.tpl',
                                'class' => 'form element'
                            ))),
                ));


                $this->addElement('Text', 'app_header_color_accent', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_themeHeaderColorAccent.tpl',
                                'class' => 'form element'
                            )))
                ));

                $this->addElement('Dummy', 'graphic_assets', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>Graphic Assets</h3><br />[NOTE: You can download sample Graphic Assets for your reference from here: <a href="http://www.socialengineaddons.com/sites/default/files/Sample_Graphics_Assets_Android_App.tar">http://www.socialengineaddons.com/sites/default/files/Sample_Graphics_Assets_Android_App.tar</a>. If you would like us to build the required graphic assets for your iOS and Android apps, then please order our service: "<a href="http://www.socialengineaddons.com/services/building-graphic-assets-ios-android-mobile-apps" target="_blank">Building Graphic Assets for Mobile Apps</a>".]</center>'),
                ));
                $this->graphic_assets->getDecorator('Label')->setOptions(array('escape' => false));


                $this->addElement('Select', 'font', array(
                    'label' => 'App Font Style',
                    'description' => 'Select the font style for your App with below dropdown. <a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/fonts-preview.png" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>',
                    'required' => true,
                    'allowEmpty' => false,
                    'multiOptions' => array(
                        'Roboto-Regular' => 'Roboto Regular (Default)',
                        'Abel-Regular' => 'Abel Regular',
                        'Amaranth-Regular' => 'Amaranth Regular',
                        'Audiowide-Regular' => 'Audiowide Regular',
                        'Bellerose' => 'Bellerose Light',
                        'CLOSCP__' => 'Closecall PM',
                        'Comfortaa-Regular' => 'Comfortaa Regular',
                        'Dosis-Regular' => 'Dosis Book',
                        'Exo-Regular' => 'Exo Regular',
                        'FortuneCity' => 'FortuneCity Regular',
                        'HappyMonkey-Regular' => 'Happy Monkey Regular',
                        'Joyful Juliana' => 'Joyful Juliana Regular',
                        'Lato-Regular' => 'Lato Regular',
                        'Oxygen-Regular' => 'Oxygen Regular',
                        'Philosopher-Regular' => 'Philosopher Regular',
                        'Playball-Regular' => 'Playball Regular',
                        'Play-Regular' => 'Play Regular',
                        'PT_Sans-Narrow-Web-Regular' => 'PT Sans Narrow Regular',
                        'Sansation_Regular' => 'Sansation Regular',
                        'Walkway rounded' => 'Walkway Rounded Regular',
                        'Walkway UltraBold' => 'Walkway UltraBond Regular',
                        'KGAlwaysAGoodTime' => 'KG Always A Good Time',
                        'MavenPro-Regular' => 'MavenPro'
                    ),
                    'value' => 'Roboto-Regular'
                ));
                $this->font->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));


                $this->addElement('File', 'feature_graphics', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('Feature Graphic'),
                    'description' => '1024 x 500 pixels, 24-bit PNG (no alpha)',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                // $this->addElement('Text', 'body_default_font_size', array(
                //     'label' => Zend_Registry::get('Zend_Translate')->_('Default body font size'),
                //     'description' => Zend_Registry::get('Zend_Translate')->_('The overall font size across the app.'),
                //     'value' => '14',
                // ));

                $this->addElement('Dummy', 'app_icon', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>App Icons</h3>'),
                ));
                $this->app_icon->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('File', 'hi_res_icon', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('Hi-res icon'),
                    'description' => '512 x 512 pixels, 32-bit PNG (with alpha)',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'app_icon_2', array(
                    'label' => 'Icon in 48 x 48 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'notification_icon', array(
                    'label' => 'Notification Icon in 48 x 48 pixels, PNG',
                    'description' => 'Icon should be of white color with transparent background.',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

//                $this->addElement('File', 'comet_chat_icon', array(
//                    'label' => 'Comet Chat Icon in 48 x 48 pixels, PNG',
//                    'validators' => array(
//                        array('Extension', false, 'png')
//                    )
//                ));

                $this->addElement('File', 'app_icon_3', array(
                    'label' => 'Icon in 72 x 72 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'app_icon_4', array(
                    'label' => 'Icon in 96 x 96 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'app_icon_5', array(
                    'label' => 'Icon in 180 x 180 pixels, PNG',
                    'validators' => array(
                    )
                ));

                $this->addElement('Dummy', 'splash_screen', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>Splash Screen Images</h3>'),));
                $this->splash_screen->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Radio', 'welcome_image_type', array(
                    'label' => 'Splash Screen Image Type',
                    'description' => ' Choose the type of image you want to show in your app on App Launch. You can either upload PNG image <a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/Splash.PNG" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a> or GIF Images <a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/Splash.gif" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>',
                    'multiOptions' => array(
                        1 => 'PNG',
                        0 => 'GIF'
                    ),
                    'value' => 1,
                    'onchange' => 'welcomeImageType()'
                ));
                 $this->welcome_image_type->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'animation_time', array(
                    'label' => 'Animation Time of GIF (in Seconds)',
                    'description' => 'Enter the GIF Image Animation Time. Suggested time for animation is "4-5 seconds"',
                    'value' => 4,
                ));

                $this->addElement('Dummy', 'splash_screen_portrait', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>Splash Screen Images - Portrait</h3>'),
                    'description' => Zend_Registry::get('Zend_Translate')->_('This image is displayed as a first screen on the app launch.'),
                ));
                $this->splash_screen_portrait->getDecorator('Label')->setOptions(array('escape' => false));
                $this->splash_screen_portrait->getDecorator('Description')->setOptions(array('escape' => false));

                $this->addElement('File', 'splash_portrait_1', array(
                    'label' => 'Image in 200 x 320 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));


                $this->addElement('File', 'splash_portrait_2', array(
                    'label' => 'Image in 320 x 480 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'splash_portrait_3', array(
                    'label' => 'Image in 480 x 800 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'splash_portrait_4', array(
                    'label' => 'Image in 720 x 1280 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('Dummy', 'splash_screen_landscape', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>Splash Screen Images - Landscape</h3>'),
                ));
                $this->splash_screen_landscape->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('File', 'splash_landscape_1', array(
                    'label' => 'Image in 320 x 200 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));


                $this->addElement('File', 'splash_landscape_2', array(
                    'label' => 'Image in 480 x 320 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'splash_landscape_3', array(
                    'label' => 'Image in 800 x 480 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'splash_landscape_4', array(
                    'label' => 'Image in 1280 x 720 pixels,',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('Button', 'submit', array(
                    'label' => 'Save Changes and Continue',
                    'type' => 'submit',
                    'ignore' => true,
//                    'order' => 500,
                ));
            } else if ($tab == 3) {
                $this->addElement('Dummy', 'slideshow_first_slide', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>Introductory Slideshow Details - First Slide</h3>'),
                    'description' => Zend_Registry::get('Zend_Translate')->_('Are the ones displayed on the welcome screen <a target="_blank" class="mleft5" title="View Screenshot" href="application/modules/Siteandroidapp/externals/images/slideshows.PNG" target="_blank"><img src="application/modules/Siteandroidapp/externals/images/eye.png" /></a>. At least one slideshow image is mandatory.')
                ));
                $this->slideshow_first_slide->getDecorator('Label')->setOptions(array('escape' => false));
                $this->slideshow_first_slide->getDecorator('Description')->setOptions(array('escape' => false));

                $this->addElement('Text', 'slideshow_slide_1_title', array(
                    'label' => 'Title',
//                'required' => true,
//                'allowEmpty' => false,
                ));

                $this->addElement('Textarea', 'slideshow_slide_1_description', array(
                    'label' => 'Description',
//                'required' => true,
//                'allowEmpty' => false,
                ));

                $this->addElement('File', 'slideshow_slide_image_1_1', array(
                    'label' => 'Image in 480 x 800 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'slideshow_slide_image_1_2', array(
                    'label' => 'Image in 720 x 1280 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));


                $this->addElement('Dummy', 'slideshow_second_slide', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>Introductory Slideshow Details - Second Slide</h3>'),
                ));
                $this->slideshow_second_slide->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Text', 'slideshow_slide_2_title', array(
                    'label' => 'Title',
//                'required' => true,
//                'allowEmpty' => false,
                ));

                $this->addElement('Textarea', 'slideshow_slide_2_description', array(
                    'label' => 'Description',
//                'required' => true,
//                'allowEmpty' => false,
                ));

                $this->addElement('File', 'slideshow_slide_image_2_1', array(
                    'label' => 'Image in 480 x 800 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'slideshow_slide_image_2_2', array(
                    'label' => 'Image in 720 x 1280 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('Dummy', 'slideshow_third_slide', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<h3>Introductory Slideshow Details - Third Slide</h3>'),
                ));
                $this->slideshow_third_slide->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Text', 'slideshow_slide_3_title', array(
                    'label' => 'Title',
//                'required' => true,
//                'allowEmpty' => false,
                ));

                $this->addElement('Textarea', 'slideshow_slide_3_description', array(
                    'label' => 'Description',
//                'required' => true,
//                'allowEmpty' => false,
                ));

                $this->addElement('File', 'slideshow_slide_image_3_1', array(
                    'label' => 'Image in 480 x 800 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('File', 'slideshow_slide_image_3_2', array(
                    'label' => 'Image in 720 x 1280 pixels, PNG',
                    'validators' => array(
                        array('Extension', false, 'png')
                    )
                ));

                $this->addElement('Button', 'submit', array(
                    'label' => 'Save Changes and Continue',
                    'type' => 'submit',
                    'ignore' => true,
//                    'order' => 500,
                ));
            } else if ($tab == 4) {

                // Language File Upload Options
                $this->addElement('Dummy', 'language_assets', array(
                    'Label' => Zend_Registry::get('Zend_Translate')->_('<h3>Language Assets [Optional]</h3><br />This section is useful for you only if you want your app to be multi-lingual (in multiple languages), or if you want to change any existing English phrases in your app.<br />You can download sample "Language File" for your reference from here: "<a href="http://mobiledemo.socialengineaddons.com/public/admin/Sample_Android_English_Language.csv">http://mobiledemo.socialengineaddons.com/public/admin/Sample_Android_English_Language.csv</a>". Below, you can upload the language files.<br /><br />'
                            . 'You need to add your changes at right side phrases of CSV file. For example if you want to change "Browse as a Guest" of French csv file then do changes at right side of ";" <br /><br /> '
                            . '<span style="font-weight:bold;">Text before your changes</span><br />'
                            . '&nbsp;&nbsp;&nbsp;"browse_as_guest";"Browse as a Guest"<br /><br />'
                            . '<span style="font-weight:bold;">Text after your changes in French</span><br />'
                            . '&nbsp;&nbsp;&nbsp;"browse_as_guest";"Parcourir en tant qu\'invité"'
                            . '<br /><br /> [Note:<br>
1.Default language of your app will be the default language of your website (the one you set from Language Manager). <br>
2. To add a new language in the app, please add required language pack from Language Manager. Once you add there, same language pack will display here in this section.
]<br>'),
                ));
                $this->language_assets->getDecorator('Label')->setOptions(array('escape' => false));

                $getLanguages = Engine_Api::_()->getApi('Core', 'siteapi')->getLanguages(true);
                if (isset($getLanguages)) {

                    foreach ($getLanguages as $key => $label) {

                        $this->addElement('File', $key, array(
                            'label' => 'Upload Language File For: [' . $label . ']',
                            'validators' => array(
                                array('Extension', false, 'csv')
                            )
                        ));
                    }
                }
                // Language work end

                $this->addElement('Button', 'submit', array(
                    'label' => 'Save Changes and Continue',
                    'type' => 'submit',
                    'ignore' => true,
                ));
            } else if ($tab == 5) {
                $communityPluginEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad');
                if ($communityPluginEnabled)
                    $description = "Please select the type of advertisment you want to enable for your app.In addition to the above selected Ad Type, you will also be able to choose to show “Community Ads” and “Sponsered Stories” in the Ad Placement areas.[Note: We recommend you to use Facebook Ads, as they are comparitively faster than Google Ads]";
                else
                    $description = "Please select the type of advertisment you want to enable for your app.[Note: We recommend you to use Facebook Ads, as they are comparitively faster than Google Ads]";
                $this->addElement('Radio', 'adv_type', array(
                    'label' => 'Advertisment Type',
                    'description' => $description,
//                    'multiOptions' => array(
//                        1 => 'Facebook Ads',
//                        0 => 'Google Ads.'
//                    ),
                    'multiOptions' => array(
                        1 => 'Facebook Ads',
                    ),
                    'value' => 1,
                    'onchange' => 'advertismentType()'
                ));
                $this->addElement('Text', 'google_ad_placement_id', array(
                    'decorators' => array(array('ViewScript', array(
                                'viewScript' => '_advertismentType.tpl',
                                'class' => 'form element',
                            )))
                ));

                $this->addElement('Text', 'ad_placement_id', array(
                    'label' => 'Advertising - Ad Placement ID',
                    'description' => 'Display Facebook ads at desired screens in your app and earn revenue off your app. To enable ads in app, please enter "Placement ID" for your Facebook app. [Please follow <a href = "https://youtu.be/Y31ZwKIvkNE" target = "_blank">video tutorial</a> to configure "Placement ID" from "Audience Network" section of your Facebook app.]'
                ));
                $this->ad_placement_id->getDecorator('description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));


                $this->addElement('Button', 'submit', array(
                    'label' => 'Save Changes and Continue',
                    'type' => 'submit',
                    'ignore' => true,
                ));
            } else if ($tab == 6) {
                $this->removeElement('required_fields');

                if (!empty($this->_doWeHaveLatestVersion)) {
                    // Language File Upload Options
                    $this->addElement('Dummy', 'download_text', array(
                        'Label' => Zend_Registry::get('Zend_Translate')->_('<div class="seaocore_tip"><span><p>You do not have the latest version of the above listed plugins, Please upgrade all listed modules to the latest version to enable its integration with Android Mobile Application.</p></span></div><br />'),
                    ));
                    $this->download_text->getDecorator('Label')->setOptions(array('escape' => false));
                } else {
                    // Language File Upload Options
                    $this->addElement('Dummy', 'download_text', array(
                        'Label' => Zend_Registry::get('Zend_Translate')->_('<h3>Download Tar File</h3><p>
                Before downloading this file, please ensure that you have filled all the App Submission Details correctly. This tar file contains all the details required for building your Android App.
            </p><br />'),
                    ));
                    $this->download_text->getDecorator('Label')->setOptions(array('escape' => false));
                }

//                $submitElementOptions = array(
//                    'label' => 'Save Changes',
//                    'type' => 'submit',
//                    'ignore' => true,
//                    'decorators' => array('ViewHelper')
//                );
//
//                $this->addElement('Button', 'submit', $submitElementOptions);

                $getWebsiteName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
                $websiteStr = str_replace(".", "-", $getWebsiteName);
                if (empty($this->_doWeHaveLatestVersion) && (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . 'android-' . $websiteStr . '-app-builder'))) {
                    // Element: Download TAR
                    $this->addElement('Cancel', 'download_tar', array(
                        'label' => 'Download tar',
//                        'prependText' => ' or ',
                        'type' => 'cancel',
                        'ignore' => true,
//                        'link' => true,
//                        'class' => 'Smoothbox',
                        'onclick' => 'Smoothbox.open("' . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'download')) . '")',
//                        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'download')),
                        'decorators' => array('ViewHelper'),
                    ));

//                    // DisplayGroup: buttons
//                    $this->addDisplayGroup(array('submit', 'download_tar'), 'buttons', array(
//                        'decorators' => array(
//                            'FormElements',
//                            'DivDivDivWrapper',
//                        )
//                    ));
                }
            } else if ($tab == 7) {
                $this->addElement('Dummy', 'people_suggestion', array(
                    'label' => Zend_Registry::get('Zend_Translate')->_('<center><h3>People You May Know Suggestions</h3></center>'),
                ));
                $this->people_suggestion->getDecorator('Label')->setOptions(array('escape' => false));

                $this->addElement('Radio', 'display_suggestion', array(
                    'label' => 'Display suggestion',
                    'description' => "Do you want to display ‘People You May Know’ suggestions in activity feed?",
                    'multiOptions' => array(
                        1 => 'Yes',
                        0 => 'No'
                    ),
                    'value' => 1,
                ));
                $this->display_suggestion->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Dummy', 'placement_heading', array(
                    'description' => Zend_Registry::get('Zend_Translate')->_("This Placement count and Advertisement Placement should not be same, otherwise only one of them will be visible in Activity Feed. Make sure that it's not N, as you have set this position for ads as well."),
                ));

                $this->addElement('Text', 'suggestion_placement', array(
                    'label' => 'Placement',
                    'description' => "After how many feeds you want to show people you may know suggestion ?",
                    'value' => 5,
                ));
                $this->suggestion_placement->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Text', 'suggestion_count', array(
                    'label' => 'Number of suggestion',
                    'description' => "How many suggestions you want to display in Activity Feed in the suggestions block?",
                    'value' => 3,
                ));
                $this->suggestion_count->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

                $this->addElement('Button', 'submit', array(
                    'label' => 'Save Changes and Continue',
                    'type' => 'submit',
                    'ignore' => true,
//                    'order' => 500,
                ));
            }
        }
    }

     protected function validateMessengerSubscription() {
//        $apiURL = 'https://staging.primemessenger.com/rest/primemessenger/V1/api/hasMobileApps';
        $apiURL = 'https://primemessenger.com/rest/primemessenger/V1/api/hasMobileApps';
        $website = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));

        $apiKey = Engine_Api::_()->getApi('settings', 'core')->getSetting('primemessenger.apikey', '');
        $subdomain = Engine_Api::_()->getApi('settings', 'core')->getSetting('primemessenger.subdomain', '');

        if (empty($apiKey) || empty($subdomain))
            return false;

        //Todo -: Get Ref Id from Prime messenger plugin and call isactive function 
        $tempAdminMenuPost = array(
            'subdomain' => $subdomain,
            'key' => base64_encode($apiKey)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $tempAdminMenuPost);
        ob_start();
        curl_exec($ch);
        curl_close($ch);
        $getCurlResponse = ob_get_contents();
        ob_end_clean();

        if (strstr($getCurlResponse, 'error::'))
            return false;

        return $getCurlResponse;
    }

}

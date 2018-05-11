<!-- SOME MULTICURRENCY RELATED WORK START HERE -->
<?php $enabled = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitemulticurrency.enabled', 1); ?>
<?php $isMulticurrencyEnable = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemulticurrency'); ?>
<?php $currencyInHeader = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitemulticurrency.headermenu.enabled', 1); ?>

<?php
$infoCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitemulticurrency.currency.information', 'currencySymbol');
$viewCurrency = false;
$allowedCurr = array();
?>


<?php if (Engine_Api::_()->hasModuleBootstrap('sitemulticurrency') && !empty($isMulticurrencyEnable) && !empty($enabled) && !empty($currencyInHeader) && count(Engine_Api::_()->getDbTable('currencyrates', 'sitemulticurrency')->getAllowedCurrencies()) > 1): ?>

    <?php $allowedCurr = Engine_Api::_()->getDbTable('currencyrates', 'sitemulticurrency')->getAllowedCurrencies(); ?>

    <?php
    $viewCurrency = $infoCurrency;
    $selectedOption = Engine_Api::_()->sitemulticurrency()->getSelectedCurrency();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $local = ($viewer_id) ? $viewer->locale : 'auto';
    $localeObject = new Zend_Locale($local);

    $selectedCountry = Engine_Api::_()->sitemulticurrency()->getSelectedCountry();
    ?>

    <script type="text/javascript">

        function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ')
                    c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0)
                    return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        //SET THE SELECTED CURRENCY COUNTRY IMAGE ICON ON PAGE LOAD
        window.addEvent('domready', function () {

            country = '<?php echo $selectedCountry; ?>';

            var info = '<?php echo $infoCurrency; ?>';

            if (info == 'countryFlag') {
                $('flag_display').src = $('flag_display').src + country + '.png';
            } else {
                var curr = '<?php echo $selectedOption; ?>';
                document.getElementById('currency_symbol').innerHTML = curr;
                //$('currency_symbol').innerHTML = curr;
            }

        });
    </script>

<?php endif; ?>
<!-- MULTICURRENCY RELATED WORK END HERE -->
<?php if (Engine_Api::_()->hasModuleBootstrap('sitelogin')) :
                                    Zend_Registry::set('siteloginSignupPopUp', 1); 
                        endif;  ?>
<?php $tempMenuCount = 0; ?>
<?php $searchWidgetDisplay = false; ?>
<?php $locationMenuCount = 0; ?>
<?php $renderedChangeMyLocationWidget = false; ?> 
<?php if (empty($this->disable_content)): ?>
    <div class="layout_core_menu_mini">
        <div id='core_menu_mini_menu'>
            <ul>
                <?php foreach ($this->menuItems as $menuItemArray): ?> 
                    <?php $miniMenuName = $menuItemArray->name; ?>

                    <?php if ($miniMenuName == 'sitemenu_mini_currency'): ?>
                        <?php if (empty($enabled) || empty($isMulticurrencyEnable) || empty($currencyInHeader) || count($allowedCurr) <= 1): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (empty($miniMenuName)) : ?>
                        <?php $miniMenuName = @end(@explode(' ', $menuItemArray->class)); ?>
                    <?php endif; ?>

                    <?php if (!empty($this->changeMyLocation) && empty($renderedChangeMyLocationWidget) && ++$locationMenuCount == $this->changeMyLocationPosition) : $renderedChangeMyLocationWidget = true; ?>
                        <li>
                            <?php echo $this->content()->renderWidget('seaocore.change-my-location', array('detactLocation' => 0, 'updateUserLocation' => 0, 'showLocationPrivacy' => 0, 'showSeperateLink' => 0, 'placedInMiniMenu' => 1, 'locationbox_width' => $this->locationbox_width, 'widgetContentId' => $this->identity)) ?>
                        </li>
                    <?php endif; ?>                  

                    <?php if (!empty($this->searchType) && empty($searchWidgetDisplay) && ++$tempMenuCount == $this->searchPosition) : ?>
                        <?php $searchWidgetDisplay = true; ?>
                        <?php // $advancedMenuProductSearch = ($this->searchType == 1) ? 0 : 1; ?>
                        <li>
                            <?php echo $this->content()->renderWidget('sitemenu.searchbox-sitemenu', array('isMainMenu' => 0, 'advancedMenuProductSearch' => $this->searchType, "advsearch_search_box_width" => $this->searchbox_width, 'showLocationBasedContent' => $this->showLocationBasedContent)) ?>
                        </li>
                    <?php endif; ?>

                    <!--FOR LOGIN / LOGOUT / SIGNUP-->
                    <?php if ($miniMenuName == 'core_mini_auth' || $miniMenuName == 'core_mini_signup' || $miniMenuName == 'captivate_core_mini_signin') : ?>
                        <?php if ($miniMenuName == 'core_mini_auth' || $miniMenuName == 'captivate_core_mini_signin') : ?>
                            <?php $tempFunctionName = 'login'; ?>
                        <?php elseif ($miniMenuName == 'core_mini_signup'): ?>
                            <?php $tempFunctionName = 'signup'; ?>
                        <?php endif; ?>
                        <li class="updates_pulldown">
                            <a 
                            <?php if (($miniMenuName == 'core_mini_auth' || $miniMenuName == 'captivate_core_mini_signin') && !empty($this->sitemenuEnableLoginLightbox) && (empty($this->isUserLoginPage) && empty($this->isUserSignupPage))) : ?>
                                    class="updates_signin" onclick="advancedMenuUserLoginOrSignUp('<?php echo $tempFunctionName ?>', '<?php echo $this->isUserLoginPage ?>', '<?php echo $this->isUserSignupPage ?>');
                                                            return false;"
                                <?php elseif ($miniMenuName == 'core_mini_signup' && !empty($this->sitemenuEnableSignupLightbox) && (empty($this->isUserSignupPage) && empty($this->isUserLoginPage))): ?>  
                                    class="updates_signup" onclick="advancedMenuUserLoginOrSignUp('<?php echo $tempFunctionName ?>', '<?php echo $this->isUserLoginPage ?>', '<?php echo $this->isUserSignupPage ?>');
                                                            return false;" 
                                <?php endif; ?>
                                href="<?php echo $menuItemArray->getHref() ?>">          
                                <span><?php echo $this->translate($menuItemArray->getLabel()) ?></span>
                            </a>
                        </li>

                        <!--FOR USER PROFILE-->
                    <?php elseif ($miniMenuName == 'core_mini_profile') : ?>
                        <li class="updates_pulldown">
                            <?php echo $this->htmlLink($this->viewer->getHref(), $this->itemPhoto($this->viewer, 'thumb.icon'), array('title' => $this->translate('My Profile'))); ?>
                        </li>

                    <?php elseif ($miniMenuName == 'core_mini_admin') : ?>
                        <?php if (!empty($this->sitemenu_show_icon) && ((isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])) || !empty($menuItemArray->icon))) : ?>
                            <?php if (!empty($menuItemArray->params['icon'])) : ?>
                                <?php $tempImageUrl = $menuItemArray->params['icon']; ?>
                            <?php elseif (!empty($menuItemArray->icon)) : ?>
                                <?php $tempImageUrl = $menuItemArray->icon; ?>
                            <?php endif; ?>
                        <?php elseif (!empty($this->sitemenu_show_icon)) : ?>
                            <?php $tempImageUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/admin-icon.png"; ?>
                        <?php endif; ?>
                        <li class="updates_pulldown">
                            <a href="<?php echo $menuItemArray->getHref(); ?>" title="<?php echo $this->translate($menuItemArray->getLabel()) ?>">          
                                <?php if (!empty($tempImageUrl)) : ?>
                                    <img src="<?php echo $tempImageUrl ?>" />
                                <?php else: ?>
                                    <span><?php echo $this->translate($menuItemArray->getLabel()) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!--FOR REMAINING MENUS-->
                    <?php elseif ($miniMenuName == 'sitemenu_mini_magentocart' || $miniMenuName == 'sitemenu_mini_currency' || $miniMenuName == 'sitemenu_mini_cart' || $miniMenuName == 'sitemenu_mini_notification' || $miniMenuName == 'sitemenu_mini_friend_request' || $miniMenuName == 'core_mini_messages' || $miniMenuName == 'core_mini_settings') : ?>
                        <?php $showSuggestion = 0; ?>
                        <?php $miniMenuAction = $menuItemArray->action; ?>
                        <?php $miniMenuLabelName = $menuItemArray->getLabel(); ?>
                        <?php $miniMenuImageTitleName = $menuItemArray->getLabel(); ?>

                        <?php if ($miniMenuName == 'core_mini_messages') : ?>
                            <?php $miniMenuAction = 'message'; ?>
                            <?php if (strstr($miniMenuLabelName, 'Messages')): ?>
                                <?php $miniMenuLabelName = "Messages" ?>
                            <?php elseif (strstr($miniMenuLabelName, 'MESSAGES')): ?>
                                <?php $miniMenuLabelName = "MESSAGES" ?>
                            <?php endif; ?>
                            <?php $miniMenuImageTitleName = "Messages"; ?>
                        <?php elseif ($miniMenuName == 'core_mini_settings') : ?>
                            <?php $miniMenuAction = 'setting'; ?>
                        <?php endif; ?>
                        <?php if ($miniMenuName == 'sitemenu_mini_friend_request') : ?>
                            <?php $showSuggestion = $this->show_suggestion; ?>
                        <?php endif; ?>

                        <?php
                        $extClass = '';
                        if ($viewCurrency && $miniMenuName == 'sitemenu_mini_currency') {
                            $extClass = $viewCurrency;
                        }
                        ?>
                        <li id="<?php echo $miniMenuName . '_updates_pulldown' ?>" class="updates_pulldown <?php echo $extClass; ?>" onclick="showAdvancedMiniMenuIconContent('<?php echo $miniMenuName; ?>', this, '<?php echo $miniMenuAction; ?>', '<?php echo $this->noOfUpdates; ?>', '<?php echo $showSuggestion ?>');">
                            <div class="seaocore_pulldown_wrapper pulldown_contents_wrapper">
                                <div class="seaocore_pulldown_arrow"></div>
                                <div class="seaocore_pulldown_contents" id="<?php echo $miniMenuName ?>_pulldown_contents" onclick="advancedMiniMenuContentHide('<?php echo $miniMenuName; ?>');">
                                    <ul class="notifications_menu">
                                        <li>
                                            <div class="sitestoreproduct_mini_cart_loading txt_center">
                                                <img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitemenu/externals/images/loading.gif" title=" <?php echo $this->translate("Loading ...") ?>">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <?php $miniMenuHref = ''; ?>
                            <?php if ($miniMenuName == 'sitemenu_mini_cart') : ?>
                                <?php $miniMenuHref = $this->url(array("action" => "cart"), 'sitestoreproduct_product_general', true); ?>
                                <?php $tempIconUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/cart-icon.png"; ?>
                                <?php if (!empty($this->itemCount)) : ?>
                                    <span class="seaocore_pulldown_count" id="new_item_count"><?php echo $this->itemCount ?></span>
                                <?php else: ?>
                                    <span id="new_item_count"></span>
                                <?php endif; ?>
                            <?php elseif ($miniMenuName == 'sitemenu_mini_magentocart') : ?>
                                <?php $miniMenuHref = $this->url(array("action" => "index"), 'sitemagento_cart', true);?>
                                <?php $tempIconUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/cart-icon.png"; ?>
                                
                                <?php if (!empty($this->productCount)) : ?>
                                    <span class="sitemagento_seaocore_pulldown_count" id="sitemagento_product_item_count"><?php echo $this->productCount ?></span>
                                <?php else: ?>
                                    <span id="sitemagento_product_item_count"></span>
                                <?php endif; ?>
                            <?php elseif ($miniMenuName == 'sitemenu_mini_currency') : ?> 

                                <?php $miniMenuHref = $this->url(array("action" => "currency"), 'sitemulticurrency_currency_general', true); ?>
                                <?php
                                //PLEASE CHNAGE ICON WHEN IT WILL BE DESIGNED
                                $tempIconUrl = ''; //$this->layout()->staticBaseUrl."application/modules/Sitemenu/externals/images/cart-icon.png";
                                ?> 


                            <?php elseif ($miniMenuName == 'sitemenu_mini_notification') : ?>
                                <?php $miniMenuHref = $this->url(array(""), 'recent_activity', true); ?>
                                <?php
                                if ($this->sitemenu_show_icon && isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])):
                                    $tempIconUrl = $menuItemArray->params['icon'];
                                elseif ($this->sitemenu_show_icon):
                                    $tempIconUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/update-icon.png";
                                endif;
                                ?>
                                <?php if (!empty($this->sitemenu_show_icon)): ?>
                                    <?php if (!empty($menuItemArray->newNotificationCount)) : ?>
                                        <span class="seaocore_pulldown_count" id="new_notification_count"><?php echo $menuItemArray->newNotificationCount ?></span>
                                    <?php else: ?>
                                        <span id="new_notification_count"></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php elseif ($miniMenuName == 'sitemenu_mini_friend_request') : ?>
                                <?php $miniMenuHref = $this->url(array(""), 'recent_activity', true); ?>
                                <?php
                                if ($this->sitemenu_show_icon && isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])):
                                    $tempIconUrl = $menuItemArray->params['icon'];
                                elseif ($this->sitemenu_show_icon):
                                    $tempIconUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/member-request-icon.png";
                                endif;
                                ?>
                                <?php if (!empty($this->sitemenu_show_icon)): ?>
                                    <?php if (!empty($menuItemArray->newFriendRequestCount)) : ?>
                                        <span class="seaocore_pulldown_count" id="new_friend_request_count"> <?php echo $menuItemArray->newFriendRequestCount ?></span>
                                    <?php else : ?>
                                        <span id="new_friend_request_count"></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php elseif ($miniMenuName == 'core_mini_messages') : ?>
                                <?php $miniMenuHref = $this->url(array("action" => "inbox"), 'messages_general', true); ?>
                                <?php
                                if ($this->sitemenu_show_icon && isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])):
                                    $tempIconUrl = $menuItemArray->params['icon'];
                                elseif ($this->sitemenu_show_icon):
                                    $tempIconUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/message-icon.png";
                                endif;
                                ?>
                                <?php if (!empty($this->sitemenu_show_icon)): ?>
                                    <?php if (!empty($this->newMessageCount)) : ?>
                                        <span class="seaocore_pulldown_count" id="new_message_count"><?php echo $this->newMessageCount; ?></span>
                                    <?php else: ?>
                                        <span id="new_message_count"></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php elseif ($miniMenuName == 'core_mini_settings') : ?>
                                <?php $miniMenuHref = $this->url(array("controller" => "settings", "action" => "general"), 'user_extended', true); ?>
                                <?php
                                if ($this->sitemenu_show_icon && isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])):
                                    $tempIconUrl = $menuItemArray->params['icon'];
                                elseif ($this->sitemenu_show_icon):
                                    $tempIconUrl = $this->layout()->staticBaseUrl . "application/modules/Sitemenu/externals/images/setting-icon.png";
                                endif;
                                ?>
                            <?php endif; ?>
                                        
            <!--<a href="javascript:void(0)" title="<?php // echo $this->translate($miniMenuImageTitleName)         ?>">-->
                            <a href="<?php
                            if (!empty($miniMenuHref)) : echo $miniMenuHref;
                            else: echo 'javascript:void(0)';
                            endif;
                            ?>" title="<?php echo $this->translate($miniMenuImageTitleName) ?>" <?php if (!empty($miniMenuHref)) : ?>onclick="return false;" <?php endif; ?>>


                                <?php if (($this->sitemenu_show_icon && !empty($tempIconUrl)) || $miniMenuName == 'sitemenu_mini_cart' || $miniMenuName == 'sitemenu_mini_currency' || $miniMenuName == 'sitemenu_mini_magentocart'): ?>

                                    <?php if ($miniMenuName == 'sitemenu_mini_currency'): ?>

                                        <?php if ($infoCurrency == 'currencySymbol'): ?>
                                            <span id="currency_symbol"></span>
                                        <?php else : ?> 
                                            <img id="flag_display" src="<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sitemulticurrency/externals/images/currency/' ?>">
                                        <?php endif; ?>

                                        <i class="fa fa-caret-down" ></i>

                                    <?php else: ?>              
                                        <img src="<?php echo $tempIconUrl ?>"/>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span><?php echo $this->translate($miniMenuLabelName); ?> </span>
                                    <?php if (empty($this->sitemenu_show_icon)) : ?>

                                        <?php if ($miniMenuName == 'sitemenu_mini_notification') : ?>
                                            <span id="new_notification_count_parent">
                                                <?php if (!empty($menuItemArray->newNotificationCount)) : ?>(<?php endif; ?>
                                                <span id="new_notification_count">
                                                    <?php
                                                    if (!empty($menuItemArray->newNotificationCount)) : echo $menuItemArray->newNotificationCount;
                                                    endif;
                                                    ?>
                                                </span>
                                                <?php if (!empty($menuItemArray->newNotificationCount)) : ?>)<?php endif; ?>
                                            </span>

                                        <?php elseif ($miniMenuName == 'sitemenu_mini_friend_request') : ?>
                                            <span id="new_friend_request_count_parent">
                                                <?php if (!empty($menuItemArray->newFriendRequestCount)) : ?>(<?php endif; ?>
                                                <span id="new_friend_request_count">
                                                    <?php
                                                    if (!empty($menuItemArray->newFriendRequestCount)) : echo $menuItemArray->newFriendRequestCount;
                                                    endif;
                                                    ?>
                                                </span>
                                                <?php if (!empty($menuItemArray->newFriendRequestCount)) : ?>)<?php endif; ?>
                                            </span>

                                        <?php elseif ($miniMenuName == 'core_mini_messages') : ?>
                                            <span id="new_message_count_parent">
                                                <?php if (!empty($menuItemArray->newMessageCount)) : ?>(<?php endif; ?>
                                                <span id="new_message_count">
                                                    <?php
                                                    if (!empty($menuItemArray->newMessageCount)) : echo $this->newMessageCount;
                                                    endif;
                                                    ?>
                                                </span>
                                                <?php if (!empty($menuItemArray->newMessageCount)) : ?>)<?php endif; ?>
                                            </span> 

                                        <?php elseif ($miniMenuName == 'core_mini_messages') : ?>

                                            <?php echo $this->form->render($this); ?>  
                                        <?php endif; ?>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </a>
                        </li>

                    <?php elseif ($miniMenuName == 'core_mini_siteeventticketmytickets'): ?>  
                        <li class="icon_siteevents_my_tickets">
                            <a href="<?php echo $menuItemArray->getHref(); ?>" title="<?php echo $this->translate($menuItemArray->getLabel()) ?>" <?php if (isset($menuItemArray->target) && $menuItemArray->target == '_blank'): ?>target="_blank"<?php endif; ?>>          
                                <?php if (!empty($this->sitemenu_show_icon) && ((isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])) || !empty($menuItemArray->icon))) : ?>
                                    <?php if (!empty($menuItemArray->params['icon'])) : ?>
                                        <?php $tempImageUrl = $menuItemArray->params['icon']; ?>
                                    <?php elseif (!empty($menuItemArray->icon)) : ?>
                                        <?php $tempImageUrl = $menuItemArray->icon; ?>
                                    <?php endif; ?>
                                    <img src="<?php echo $tempImageUrl ?>" />
                                <?php else: ?>
                                    <span><?php echo $this->translate($menuItemArray->getLabel()) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>          

                        <!--FOR OTHER MENUS-->
                    <?php else: ?>
                        <li class="updates_pulldown">
                            <a href="<?php echo $menuItemArray->getHref(); ?>" title="<?php echo $this->translate($menuItemArray->getLabel()) ?>" <?php if (isset($menuItemArray->target) && $menuItemArray->target == '_blank'): ?>target="_blank"<?php endif; ?>>          
                                <?php if (!empty($this->sitemenu_show_icon) && ((isset($menuItemArray->params['icon']) && !empty($menuItemArray->params['icon'])) || !empty($menuItemArray->icon))) : ?>
                                    <?php if (!empty($menuItemArray->params['icon'])) : ?>
                                        <?php $tempImageUrl = $menuItemArray->params['icon']; ?>
                                    <?php elseif (!empty($menuItemArray->icon)) : ?>
                                        <?php $tempImageUrl = $menuItemArray->icon; ?>
                                    <?php endif; ?>
                                    <img src="<?php echo $tempImageUrl ?>" />
                                <?php else: ?>
                                    <span><?php echo $this->translate($menuItemArray->getLabel()) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!--If search box is not created in loop -->
                <?php if (!empty($this->searchType) && empty($searchWidgetDisplay)): ?>
                    <?php // $advancedMenuProductSearch = ($this->searchType == 1) ? 0 : 1;    ?>
                    <li>
                        <?php echo $this->content()->renderWidget('sitemenu.searchbox-sitemenu', array('isMainMenu' => 0, 'advancedMenuProductSearch' => $this->searchType, "advsearch_search_box_width" => $this->searchbox_width, 'showLocationBasedContent' => $this->showLocationBasedContent)) ?>
                    </li>
                <?php endif; ?>

                <?php if (!empty($this->changeMyLocation) && empty($renderedChangeMyLocationWidget)): ?>
                    <li>
                        <?php echo $this->content()->renderWidget('seaocore.change-my-location', array('detactLocation' => 0, 'updateUserLocation' => 0, 'showLocationPrivacy' => 0, 'showSeperateLink' => 0, 'placedInMiniMenu' => 1, 'widgetContentId' => $this->identity)) ?>
                    </li>
                <?php endif; ?> 

            </ul>
        </div>
    </div>
<?php endif; ?>

<!--Sign in/ Sign up smooth box work-->
<?php
if (empty($this->sitemenu_mini_menu_widget) && (empty($this->viewer_id) && empty($this->isUserLoginPage) && empty($this->isUserSignupPage) && (!empty($this->sitemenuEnableLoginLightbox) || !empty($this->sitemenuEnableSignupLightbox)))) :
    if (Engine_Api::_()->hasModuleBootstrap('sitelogin')) :
    Zend_Registry::set('siteloginSignupPopUp', 1); 
    endif; 
    echo $this->partial(
            '_addLoginSignupPopupContent.tpl', 'sitemenu', array(
        'isUserLoginPage' => $this->isUserLoginPage,
        'isUserSignupPage' => $this->isUserSignupPage,
        'isPost' => $this->isPost,
        'sitemenuEnableLoginLightbox' => $this->sitemenuEnableLoginLightbox,
        'sitemenuEnableSignupLightbox' => $this->sitemenuEnableSignupLightbox
    ));
endif;
?>

<script type="text/javascript">
    window.addEvent('domready', function () {
        manageMiniMenus();

// FOR CHECK NEW UPDATES OF FRIEND REQUEST, NOTIFICATION AND MESSAGE
<?php if (!empty($this->viewer_id)) : ?>
            setInterval(function () {
                checkNewUpdates();
            }, 50000);
<?php endif; ?>
    });
</script>

<style type="text/css">
    /*Global search and Product search width setting in Mini menu*/
    #core_menu_mini_menu #global_search_field{
        width: <?php echo $this->miniSearchWidth ?>px !important;
    } 
</style>

<style type="text/css">
    /*Global search and Product search width setting in Mini menu*/
    .layout_page_header .layout_seaocore_change_my_location>div>a{
        width: <?php echo $this->locationbox_width ?>px !important;
    } 

    #core_menu_mini_menu .seaocore_change_location a {
        max-width : inherit;
    }
</style>
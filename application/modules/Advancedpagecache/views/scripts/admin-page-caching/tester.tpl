<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Advancedpagecache
* @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: tester.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
?>
<?php include APPLICATION_PATH . "/application/modules/Advancedpagecache/views/scripts/admin_head.tpl";?>

<div class="seaocore_settings_form">
    <div class="settings">
        <div>
            <div>
                <h3>Speed Analyzer</h3>
                <p class="form-description">You can analyze the speed of your website here. Please enter the url for which you want to check the page response time with and without this plugin. Testing features work only in Production mode.</p>

                <div class="form-elements advancepagecache_tester">
                    <div id="error_div" style="display:none;">
                        <ul class="form-errors">
                            <li>
                                <ul class="errors">
                                    <li>
                                        Please enter a valid url to proceed further
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>  
                    <input type="text" name="partial_lifetime" id="partial_lifetime" value="" width="70%">
                    <button name="submit" id="submit" onclick="tester();">Start</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="advancepagecache_free_loader" style="display:none;">
    <img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Advancedpagecache/externals/images/loading.gif" alt="Loading" />
    <h2>Checking...</h2>
</div>

<div id="advancepagecache_without_cache"></div>
<script type="text/javascript">
    function tester()
    {
        if ($('partial_lifetime') && !ValidURL($('partial_lifetime').value)) {
            document.getElementById("error_div").style.display = "block";
        } else {
            document.getElementById("error_div").style.display = "none";
            var requestUrl = $('partial_lifetime').value;
            (new Request.HTML({
                'url': en4.core.baseUrl + 'admin/advancedpagecache/page-caching/calculate',
                'data': {
                    'url': requestUrl,
                    'format': 'html',
                },
                onRequest: function () {
                    $('advancepagecache_free_loader').style.display = "block";
                    $('advancepagecache_without_cache').style.display = "none";
                },
                onComplete: function (responseTree, responseElements, responseHTML, responseJavaScript) {
                    $('advancepagecache_free_loader').style.display = "none";
                    $('advancepagecache_without_cache').style.display = "block";
                    $('advancepagecache_without_cache').innerHTML = responseHTML;
                }
            })).send();
        }

    }
    function ValidURL(str) {
        var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        if (!regex.test(str)) {
            return false;
        } else {
            return true;
        }
    }
    window.onload = function() {
        var protocol = location.protocol;
        var slashes = protocol.concat("//");
        var host = slashes.concat(window.location.hostname);
        $('partial_lifetime').value=host+en4.core.baseUrl;
        <?php if( APPLICATION_ENV != 'production' ): ?>
            document.getElementById("submit").disabled = true;
        <?php else : ?>
            tester(); 
        <?php endif; ?>
    };
</script>

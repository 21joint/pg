<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _signupFields.tpl 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
/* Include the common user-end field switching javascript */
echo $this->partial('_jsSwitch.tpl', 'fields', array(
    'topLevelId' => $this->form->getTopLevelId(),
    'topLevelValue' => $this->form->getTopLevelValue(),
));
?>
<?php
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/jquery.min.js');
?>

<?php if(!$formLoaded) :  echo $this->form->render($this); endif; ?>



<style>
    #signup_account_form #name-wrapper {
        display: none;
    }
</style>

<script type="text/javascript">
    jQuery.noConflict();
//<![CDATA[
    window.addEvent('load', function () {
        if ($('username') && $('profile_address')) {
            $('profile_address').innerHTML = $('profile_address')
                    .innerHTML
                    .replace('<?php echo /* $this->translate( */'yourname'/* ) */ ?>',
                            '<span id="profile_address_text"><?php echo $this->translate('yourname') ?></span>');

            $('username').addEvent('keyup', function () {
                var text = '<?php echo $this->translate('yourname') ?>';
                if (this.value != '') {
                    text = this.value;
                }

                $('profile_address_text').innerHTML = text.replace(/[^a-z0-9]/gi, '');
            });
            // trigger on page-load
            if ($('username').value.length) {
                $('username').fireEvent('keyup');
            }
        }
    });
//]]>
</script>

<script type="text/javascript">

    if (jQuery('#global_page_core-index-index').find('#signup_account_form')) {
        jQuery('#global_page_core-index-index').find('#signup_account_form').find("input[type=text]").each(function () {
            $(this).placeholder = jQuery($(this).getParent()).prev().find('label').text();
        });

        jQuery('#global_page_core-index-index').find('#signup_account_form').find("input[type=email]").each(function () {
            $(this).placeholder = jQuery($(this).getParent()).prev().find('label').text();
        });

        jQuery('#global_page_core-index-index').find('#signup_account_form').find("input[type=password]").each(function () {
            $(this).placeholder = jQuery($(this).getParent()).prev().find('label').text();
        });

        jQuery('#global_page_core-index-index').find('#signup_account_form').find("select").each(function () {
            if (jQuery('#' + jQuery($(this)).attr('id') + " option:first").html() == '') {
                jQuery('#' + jQuery($(this)).attr('id') + " option:first").html(jQuery($(this).getParent()).prev().find('label').text());
            }
        });

        jQuery(document).ready(function () {
            jQuery('#signup_account_form').find('input,textarea,select').filter(':visible:first').focus();

            jQuery.each(jQuery('#signup_account_form')[0].elements, function (index, elem) {
                jQuery(elem).attr("tabindex", index);
            });
        });
    }

</script>
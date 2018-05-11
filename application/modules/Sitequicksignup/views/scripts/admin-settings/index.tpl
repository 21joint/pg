<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
    <?php echo $this->translate('Quick & Single Step Signup Plugin') ?>
</h2>

<?php if (count($this->navigation)): ?>
    <div class='tabs'>
        <?php
        // Render the menu
        //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
    </div>
<?php endif; ?>

<div class='clear'>
    <div class='seaocore_settings_form'>
        <div class='settings'>
            <?php echo $this->form->render($this); ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    allowTitle('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.allow.title', 1); ?>');
    allowDescription('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.allow.description', 1); ?>');
    hideFields('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.allow.quick.signup', 1); ?>');

    function hideFields(option) {

        if (option == 1) {
            $('sitequicksignup_confirm_email-wrapper').style.display = 'block';
            $('sitequicksignup_confirm_password-wrapper').style.display = 'block';
            allowTitle('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.allow.title', 1); ?>');
            allowDescription('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.allow.description', 1); ?>');
            $('sitequicksignup_allow_title-wrapper').style.display = 'block';
            $('sitequicksignup_allow_description-wrapper').style.display = 'block';
            $('sitequicksignup_subscription_enabled-wrapper').style.display = 'block';
            $('sitequicksignup_welcome_popup_enabled-wrapper').style.display = 'block';
        } else {
            $('sitequicksignup_confirm_email-wrapper').style.display = 'none';
            $('sitequicksignup_confirm_password-wrapper').style.display = 'none';

            $('sitequicksignup_field_description-wrapper').style.display = 'none';

            $('sitequicksignup_title-wrapper').style.display = 'none';

            $('sitequicksignup_allow_title-wrapper').style.display = 'none';


            $('sitequicksignup_description-wrapper').style.display = 'none';

            $('sitequicksignup_allow_description-wrapper').style.display = 'none';
            $('sitequicksignup_subscription_enabled-wrapper').style.display = 'none';
            $('sitequicksignup_welcome_popup_enabled-wrapper').style.display = 'none';
        }

    }

    function allowTitle(option) {
        if (option == 1) {
            $('sitequicksignup_title-wrapper').style.display = 'block';
        } else {
            $('sitequicksignup_title-wrapper').style.display = 'none';
        }
    }


    function allowDescription(option) {
        if (option == 1) {
            $('sitequicksignup_description-wrapper').style.display = 'block';
        } else {
            $('sitequicksignup_description-wrapper').style.display = 'none';
        }
    }

</script>
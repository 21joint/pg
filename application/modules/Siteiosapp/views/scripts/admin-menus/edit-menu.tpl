<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    edit-menu.tpl 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if (isset($this->error) && !empty($this->error)): ?>
    <div class="seaocore_tip">
        <span>
            <?php
                $url = $this->url(array('module' => 'siteiosapp', 'controller' => 'app-builder'), 'admin_default', true);
                echo 'You have not configured CometChat package name yet. Please add package name to "<a href=\'' . $url . '\'>App Submission Info</a>" page.'; 
            ?>
        </span>
    </div>
<?php else: ?>
    <div class="seaocore_settings_form" style="width: 580px;">
        <div class='settings'>
            <?php echo $this->form->render($this); ?>
        </div>
    </div>
<?php endif; ?>

<script type="text/javascript">
    window.addEvent('domready', function () {
        manageTypeFormElements();
    });

    function manageTypeFormElements() {
        if ($('type').value) {
            if ($('type').value == 'category') {
                $('header_label-wrapper').style.display = 'none';
                $('url-wrapper').style.display = 'none';
                $('icon-wrapper').style.display = 'none';
            } else if ($('type').value == 'menu') {
                $('header_label-wrapper').style.display = 'block';
                $('url-wrapper').style.display = 'block';
                $('icon-wrapper').style.display = 'block';
            }
        }
//        if ($("expire-0") && $("expire-0").checked) {
//            $('expire_limit-wrapper').style.display = 'none';
//        } else {
//            $('expire_limit-wrapper').style.display = 'block';
//        }
    }
</script>
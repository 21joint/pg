<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo "Responsive Captivate Theme" ?></h2>

<?php if (count($this->navigation)): ?>
    <div class='tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<?php
$this->form->setDescription("Below you can manage footer templates.");
$this->form->getDecorator('Description')->setOption('escape', false);
?>
<div class='seaocore_settings_form'>
    <div class='settings'>
        <?php echo $this->form->render($this); ?>
    </div>
</div>
<?php
$coreSettings = Engine_Api::_()->getApi('settings', 'core');
$captivateFooterBackground = $coreSettings->getSetting('captivate.footer.background', 1);
$captivateFooterShowLogo = $coreSettings->getSetting('captivate.footer.show.logo', 1);
$captivateFooterShowFooterHtmlBlock = $coreSettings->getSetting('captivate.footer.show.footer.html.block', 1);
$showCaptivateFooterTemplate = $coreSettings->getSetting('captivate.footer.templates', 2);
$localeMultiOptions = Engine_Api::_()->captivate()->getLanguageArray();
$total_allowed_languages = Count($localeMultiOptions);
if (!$captivateFooterShowFooterHtmlBlock) {
    if (!empty($localeMultiOptions)) {
        foreach ($localeMultiOptions as $key => $label) {
            ?>
            <script type="text/javascript">
                $("captivate_footer_lending_page_block_<?php echo $key; ?>-wrapper").style.display = 'none';
            </script>
            <?php
        }
    }
}
?>

<script type="text/javascript">
    window.addEvent('domready', function () {
        showFooterBackgroundImage('<?php echo $captivateFooterBackground; ?>');
        showFooterLogo('<?php echo $captivateFooterShowLogo; ?>');
        displayFooterHtmlBlock('<?php echo $showCaptivateFooterTemplate; ?>')
    });

    function showFooterBackgroundImage(val) {

        if (val == 1) {
            $('captivate_footer_backgroundimage-wrapper').style.display = 'none';
        } else {
            $('captivate_footer_backgroundimage-wrapper').style.display = 'block';
        }

    }
    function showFooterLogo(val) {
        if (val == 1) {
            $('captivate_footer_select_logo-wrapper').style.display = 'block';
        } else {
            $('captivate_footer_select_logo-wrapper').style.display = 'none';
        }
    }

    function displayFooterHtmlBlock(val) {

        if (<?php echo $showCaptivateFooterTemplate; ?> == 1 || <?php echo $showCaptivateFooterTemplate; ?> == 3) {
            val == 0;
        }

        if (val == 2) {
<?php
if (!empty($localeMultiOptions)) {
    foreach ($localeMultiOptions as $key => $label) {
        ?>
                    $("captivate_footer_lending_page_block_<?php echo $key; ?>-wrapper").style.display = 'block';
    <?php
    }
}
?>

        } else {
<?php
if (!empty($localeMultiOptions)) {
    foreach ($localeMultiOptions as $key => $label) {
        ?>
                    $("captivate_footer_lending_page_block_<?php echo $key; ?>-wrapper").style.display = 'none';
    <?php
    }
}
?>
        }

    }
</script>

<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var userPreferences = {
            requestParams :{"title":"<?php echo $this->translate('User Preferences'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_preferences')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', userPreferences);
</script>
<?php endif; ?>

<?php if($this->showContent): ?>
    <div class="text d-block text-danger p-2 w-100" id="errorForm"></div>
    <div class="text d-block text-success p-2 w-100" id="successForm"></div>
    <?php echo $this->form; ?>

    <script>
        en4.core.runonce.add(function() {
            var form = document.getElementsByClassName('ajax-form-' + <?php echo $this->identity; ?>)[0];
            en4.gg.ggAjaxForm(form, 'preference');
        });
    </script>
<?php endif; ?>

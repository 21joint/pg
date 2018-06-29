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

    <?php echo $this->form; ?>

<script>    
en4.core.runonce.add(function() {
    var form = document.getElementsByClassName('ajax-form-' + <?php echo $this->identity; ?>)[0];
    en4.gg.ggAjaxForm(form, 'preference');
});

function showAllCategories(element){
    var value = $(element).checked;
    $$(".sd_listing_category").set("checked",value);
}  

</script>

<?php endif; ?>

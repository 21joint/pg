<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var theoriesAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Theories'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_struggles')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', struggleAccountParams);

        // when item is clicked, filter by ID
        // function filterAjaxTheoriesContent(itemParams) {
        //     en4.gg.ajaxTabContent.attachEvent(itemParams, theoriesAccountParams);
        // }
</script>
<?php endif; ?>


<?php if($this->showContent): ?>



        



<?php endif; ?>
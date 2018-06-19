<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var theoriesAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Theories'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_theories')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>',theoriesAccountParams);

        // when item is clicked, filter by ID
        function filterAjaxTheoriesContent(itemParams) {
            en4.gg.ajaxTabContent.attachEvent(itemParams, theoriesAccountParams);
        }
</script>
<?php endif; ?>


<?php if($this->showContent): ?>
<div class="container mb-4 pl-0 pr-0">
    <div class="browse_main_holder bg-white">
        <div class="main_box px-lg-5 pb-3 px-md-5  px-sm-1 ">

            <?php echo $this->partial('ajax/theories/_theories.tpl', 'sdparentalguide', array(
                'item' => $this->viewer,
            )); ?>
        </div>
    </div>
</div>

<?php endif; ?>
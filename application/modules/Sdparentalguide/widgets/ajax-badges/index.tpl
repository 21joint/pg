

<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var badgesAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Badges'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_badges')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', badgesAccountParams);

        // when item is clicked, filter by ID
        // function filterAjaxStruggleContent(itemParams) {
        //     en4.gg.ajaxTabContent.attachEvent(itemParams, badgesAccountParams);
        // }
</script>
<?php endif; ?>


<?php if($this->showContent): ?>
    <div class="container mb-4">
        <div class="badges_main_holder">
            <div class="main_box">
                        
                <?php echo $this->partial('ajax/badges/_badges.tpl', 'sdparentalguide', array(
                    'item' => $item,
                )); ?>
                    
            </div>
        </div>
    </div>
<?php endif; ?>



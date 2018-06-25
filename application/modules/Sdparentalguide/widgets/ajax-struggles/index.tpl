

<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var struggleAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Struggles'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_struggles')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', struggleAccountParams);

        // when item is clicked, filter by ID
        function filterAjaxStruggleContent(itemParams) {
            en4.gg.ajaxTabContent.attachEvent(itemParams, struggleAccountParams);
        }
</script>
<?php endif; ?>


<?php if($this->showContent): ?>
<div class="container mb-4 pl-0 pr-0">
    <div class="browse_main_holder bg-white">
        <div class="main_box px-lg-5 pb-3 px-md-5  px-sm-1 py-5">
            <ul class="topic_holder ">
                <?php if($this->paginator->getTotalItemCount() <= 0): ?>

                    <div class="tip py-5">
                        <span style="margin-bottom:0">
                            <?php echo $this->translate("No stuggles") ?>
                        </span>
                    </div>

                <?php else: ?>
                    <?php foreach($this->paginator as $item):?>
                        <?php echo $this->partial('ajax/struggles/_struggles.tpl', 'sdparentalguide', array(
                            'item' => $item,
                        )); ?>
                    <?php endforeach; ?>
                <?php endif;?>
            </ul>
            
            <?php echo $this->partial('ajax/struggles/_pagination.tpl', 'sdparentalguide', array(
                'paginator' => $this->paginator,
            )); ?>

        </div>
    </div>
</div>
<?php endif; ?>
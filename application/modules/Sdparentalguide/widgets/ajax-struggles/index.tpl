<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var struggleAccountParams = {
            requestParams :{"title":"<?= $this->translate('Struggles'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_struggles')
        }
        en4.gg.ajaxTab.attachEvent('<?= $this->identity ?>', struggleAccountParams);

        // when item is clicked, filter by ID
        function filterAjaxStruggleContent(itemParams) {
            en4.gg.ajaxTabContent.attachEvent(itemParams, struggleAccountParams);
        }
</script>
<?php endif; ?>


<?php if($this->showContent): ?>
<div class="container mb-4 pl-0 pr-0 w-100">
    <div class="browse_main_holder bg-white">
        <div class="main_box px-lg-5 pb-3 px-md-5  px-sm-1 py-5">
            <ul class="topic_holder">
                <?php if($this->paginator->getTotalItemCount() <= 0): ?>

                    <div class="tip-message py-5 ml-3">
                        <span class="mb-0">
                            <?= $this->translate("No Stuggles") ?>
                        </span>
                    </div>

                <?php else: ?>
                    <?php foreach($this->paginator as $item):?>

                        <?= $this->partial('ajax/struggles/_struggles.tpl', 'sdparentalguide', array(
                            'item' => $item,
                        )); ?>
                    <?php endforeach; ?>
                <?php endif;?>
            </ul>
            
            <?= $this->partial('ajax/struggles/_pagination.tpl', 'sdparentalguide', array(
                'paginator' => $this->paginator,
            )); ?>

        </div>
    </div>
</div>
<?php endif; ?>
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
    <div class="container mb-4 pl-0 pr-0 w-100">
        <div class="browse_main_holder bg-white pb-4">
            <div class="main_box px-lg-5 pb-3 px-md-5  px-sm-1 py-5">
                <?php if($this->paginator->getTotalItemCount() <= 0): ?>
                <div class="tip-message py-5 ml-3">
                    <span class="mb-0">
                        <?php echo $this->translate("No Theories") ?>
                    </span>
                </div>
                <?php else: ?>
                <div class="holder-my-theories">

                    <div class="bottom-holder">
                        <div class="title-holder d-flex mb-4">
                            <h4 class="py-4 w-100 m-0 left-side"><?php echo $this->translate('Struggle');?></h4>
                            <h4 class="py-4 w-100 m-0 right-side d-none d-sm-block"><?php echo $this->translate('Theory');?></h4>
                        </div>
                        
                        <?php foreach($this->paginator as $item):?>
                            <?php echo $this->partial('ajax/theories/_theories.tpl', 'sdparentalguide', array(
                                'item' => $item,
                                'subject' => $this->subject,
                            )); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php echo $this->partial('ajax/theories/_pagination.tpl', 'sdparentalguide', array(
                    'paginator' => $this->paginator,
                )); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
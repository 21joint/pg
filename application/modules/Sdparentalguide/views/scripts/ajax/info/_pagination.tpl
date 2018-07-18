<?php if($this->paginator->getTotalItemCount() > 0): ?>
    <script type="text/javascript">
        // execute javascript only when paginator is available
        en4.core.runonce.add(function(){
            $('sdparentalguide_pagination_info_previous').style.display = '<?= ($this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
            $('sdparentalguide_pagination_info_next').style.display = '<?= ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';
        });
    </script>

    <div class="px-3 pagination-holder-narrow">
            <div id="sdparentalguide_pagination_struggles_previous" class="paginator_previous">
                <a  class="d-flex  align-items-center fs-18" href="javascript:void(0);" onclick="filterAjaxInfoContent( {'page': <?= $this->paginator->getCurrentPageNumber() - 1 ?>} )">
                    <svg class="pr-2" style="height:16px;width:40px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 16"><title>arrow_left</title><path fill="currentColor" d="M8,16l1.4-1.4L3.8,9H68V7H3.8L9.4,1.4,8,0,0,8Z"/></svg>
                    <?= $this->translate('Previous'); ?>
                    
                </a>
            </div>
            <div id="sdparentalguide_pagination_struggles_next" class="paginator_next">
                <a class="d-flex  align-items-center fs-18" href="javascript:void(0);" onclick="filterAjaxInfoContent( {'page': <?= $this->paginator->getCurrentPageNumber() + 1 ?>} )">
                    <?= $this->translate('Next'); ?>
                    <svg class="pl-2" style="height:16px;width:40px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 16"><path fill="currentColor" d="M60,0,58.6,1.4,64.2,7H0V9H64.2l-5.6,5.6L60,16l8-8Z"/></svg>
                </a>
            </div>
    </div> <!-- pagination --> 
<?php endif; ?>
<?php if($this->paginator->getTotalItemCount() > 0): ?>
    <script type="text/javascript">
        // execute javascript only when paginator is available
        en4.core.runonce.add(function(){
            $('sdparentalguide_pagination_theories_previous').style.display = '<?php echo ($this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
            $('sdparentalguide_pagination_theories_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';
        });
    </script>

    <div class="px-3 pagination-holder-narrow py-3">
            <div id="sdparentalguide_pagination_theories_previous" class="paginator_previous">
                <a  class="d-flex  align-items-center fs-18" href="javascript:void(0);" onclick="filterAjaxTheoriesContent( {'page': <?php echo $this->paginator->getCurrentPageNumber() - 1 ?>} )">
                    <svg class="pr-2" style="height:16px;width:40px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 16"><title>arrow_left</title><path fill="currentColor" d="M8,16l1.4-1.4L3.8,9H68V7H3.8L9.4,1.4,8,0,0,8Z"/></svg>
                    <?php echo $this->translate('Previous'); ?>
                    
                </a>
            </div>
            <div id="sdparentalguide_pagination_theories_next" class="paginator_next">
                <a class="d-flex  align-items-center fs-18" href="javascript:void(0);" onclick="filterAjaxTheoriesContent( {'page': <?php echo $this->paginator->getCurrentPageNumber() + 1 ?>} )">
                    <?php echo $this->translate('Next'); ?>
                    <svg class="pl-2" style="height:16px;width:40px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 16"><path fill="currentColor" d="M60,0,58.6,1.4,64.2,7H0V9H64.2l-5.6,5.6L60,16l8-8Z"/></svg>
                </a>
            </div>
    </div> <!-- pagination --> 
<?php endif; ?>
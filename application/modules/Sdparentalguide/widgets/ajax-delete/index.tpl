<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var deleteAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Delete Account'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_delete')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', deleteAccountParams);
</script>
<?php endif; ?>

<?php if($this->showContent): ?>
<div class="container">
    <div class="row mx-lg-3 mx-xl-3 mx-sm-0">
        <?php echo $this->form->render($this); ?>
    </div>
</div>
<?php endif; ?>


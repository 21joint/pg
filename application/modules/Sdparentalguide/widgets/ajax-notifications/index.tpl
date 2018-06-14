<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var passwordParams = {
            requestParams :{"title":"<?php echo $this->translate('Notifications'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_notifications')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', passwordParams);
</script>
<?php endif; ?>

<?php if($this->showContent): ?>
<div class="container">
    <div class="row mx-lg-3 mx-xl-3 mx-sm-0">
        <div class="text d-block text-danger p-2 w-100" id="errorForm"></div>
        <div class="text d-block text-success p-2 w-100" id="successForm"></div>
        <?php echo $this->form->render($this); ?>
    </div>
</div>

<script>
en4.core.runonce.add(function() {
    var form = document.getElementsByClassName('ajax-form-' + <?php echo $this->identity; ?>)[0];
    en4.gg.ggAjaxForm(form, 'notifications');
});
</script>
<?php endif; ?>
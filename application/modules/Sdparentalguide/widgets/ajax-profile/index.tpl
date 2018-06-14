<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var profileParams = {
            requestParams :{"title":"<?php echo $this->translate('Personal Info'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_profile')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', profileParams);
</script>
<?php endif; ?>

<?php if($this->showContent): ?>
<div class="container">
    <div class="row mx-lg-3 mx-xl-3 mx-sm-0">

        <div class="text d-block text-danger p-2 w-100" id="errorForm"></div>
        <div class="text d-block text-success p-2 w-100" id="successForm"></div>
        
        <?php
        /* Include the common user-end field switching javascript */
        echo $this->partial('_jsSwitch.tpl', 'fields', array(
            'topLevelId' => (int) @$this->topLevelId,
            'topLevelValue' => (int) @$this->topLevelValue
            ))
        ?>
        <?php
        $this->headTranslate(array(
            'Everyone', 'All Members', 'Friends', 'Only Me',
        ));
        ?>
        <?php echo $this->form->render($this) ?>
        
    </div>
</div>

<script>
window.addEvent('domready', function() {
    en4.user.buildFieldPrivacySelector($$('.global_form *[data-field-id]'));
});
en4.core.runonce.add(function() {
    var form = document.getElementsByClassName('ajax-form-' + <?php echo $this->identity; ?>)[0];
    en4.gg.ggAjaxForm(form, 'edit-profile');
});
</script>
<?php endif; ?>
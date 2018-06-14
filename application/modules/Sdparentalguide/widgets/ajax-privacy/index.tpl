<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var passwordParams = {
            requestParams :{"title":"<?php echo $this->translate('Privacy'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_privacy')
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
        
        <div id="blockedUserList" style="display:none;">
            <ul>
                <?php foreach ($this->blockedUsers as $user): ?>
                <?php if($user instanceof User_Model_User && $user->getIdentity()) :?>
                    <li>[
                    <?php echo $this->htmlLink(array('controller' => 'block', 'action' => 'remove', 'user_id' => $user->getIdentity()), 'Unblock', array('class'=>'smoothbox')) ?>
                    ] <?php echo $user->getTitle() ?></li>
                <?php endif;?>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</div>

<script>
en4.core.runonce.add(function() {
    var form = document.getElementsByClassName('ajax-form-' + <?php echo $this->identity; ?>)[0];
    en4.gg.ggAjaxForm(form, 'privacy');
    //console.log(form.elements);

    var formValues = [];
    for(var i = 0; i < form.elements.length; i++) {
        //console.log(form.elements[i], form.elements[i].name);
        formValues.push({key: form.elements[i].name, value: form.elements[i].value});
    }

    console.log(formValues);


});
</script>
<?php endif; ?>
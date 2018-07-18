<?php if($this->loaded_by_ajax):?>
<script type="text/javascript">
var profileParams = {
    requestParams :{"title":"<?= $this->translate('Personal Info'); ?>", "titleCount":""},
    responseContainer : $$('.layout_sdparentalguide_ajax_profile')
}
en4.gg.ajaxTab.attachEvent('<?= $this->identity ?>', profileParams);

// container tabs
en4.core.runonce.add(function() {
    var tabContainerSwitch = window.tabContainerSwitch = function(element) {
      if( element.tagName.toLowerCase() == 'a' ) {
        element = element.getParent('li');
      }

      var myContainer = element.getParent('.tabs_parent').getParent();
      element.getParent('.tabs_parent').addClass('tab_collapsed');
      myContainer.getChildren('div:not(.tabs_alt)').setStyle('display', 'none');
      myContainer.getElements('ul > li').removeClass('active');
      element.get('class').split(' ').each(function(className){
        className = className.trim();
        if( className.match(/^tab_[0-9]+$/) ) {
          myContainer.getChildren('div.' + className).setStyle('display', null);
          element.addClass('active');
        }
      });
      
    }
    var moreTabSwitch = window.moreTabSwitch = function(el) {
      el.toggleClass('tab_open');
      el.toggleClass('tab_closed');
    }
    $$('.tab_collapsed_action').addEvent('click', function(event) {
      event.target.getParent('.tabs_alt').toggleClass('tab_collapsed');
    });
});

</script>
<?php endif; ?>

<?php if($this->showContent): ?>
<div class="container p-0">
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
        <?= $this->form->render($this) ?>
        
    </div>
</div>

<script>
window.addEvent('domready', function() {
    en4.user.buildFieldPrivacySelector($$('.global_form *[data-field-id]'));
});
en4.core.runonce.add(function() {
    var form = document.getElementsByClassName('ajax-form-' + <?= $this->identity; ?>)[0];
    en4.gg.ggAjaxForm(form, 'edit-profile');
    form.style.width = '80%';
    form.style.margin = '0 auto';
});
</script>
<?php endif; ?>
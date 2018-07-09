<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>
<style>
    body {
        font-family: Open-Sans;
        background: #F7FCFC;
    }
    div.layout_page_user_signup_index .left-side {
        display: none !important;
    }
    #extfox-settings {
        font-family: 'Open-Sans', sans-serif;
    }
</style>


<div class="bg-white mb-2">
    <div class="holder py-3 px-5">
        <h1 class="text-primary font-weight-light">
            <?php echo $this->translate('Tell Us About You and Your Family'); ?>
        </h1>
        <p class="desc font-weight-light small">
            <?php echo $this->translate('Sdparentalguide_Form_Signup_FAMILY_Description'); ?>
        </p>
    </div>
</div>

<div class="bg-white p-5 mb-2">
    <div class="form-group">
        <div class="form-wrapper-heading h5 pb-2 text-muted">
            <?php echo $this->translate('Do you have any children?'); ?>
        </div>
        
        <div class="form-element">
            ...
        </div>

    </div>
</div>

<script>
    var rightSidePreferences = document.getElementsByClassName("right-side")[0];
    rightSidePreferences.classList.remove('col-xl-6', 'col-lg-6');
    rightSidePreferences.classList.add('col-xl-12', 'col-lg-12','col-12','px-0',);
    rightSidePreferences.firstElementChild.classList.add('col-xl-12', 'col-lg-12');
    rightSidePreferences.firstElementChild.classList.remove('col-xl-7','col-lg-7');
</script>
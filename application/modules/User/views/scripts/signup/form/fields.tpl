<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: fields.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<style>

  form.extfox-auth {
    max-height:100%;
    overflow-y: scroll !important;
  }
 
  form.extfox-auth::-webkit-scrollbar {
    display:block;
  }
</style>



<?php
  /* Include the common user-end field switching javascript */
  echo $this->partial('_jsSwitch.tpl', 'fields', array(
    'topLevelId' => $this->form->getTopLevelId(),
    'topLevelValue' => $this->form->getTopLevelValue(),
  ));
?>

<?php echo $this->form->render($this) ?>


<script>


  let holderForm = document.getElementsByClassName('right-side')[0].children[1];

  holderForm.classList.add('col-xl-12','col-lg-12');
  holderForm.classList.remove('col-xl-7','col-lg-7');

  // form 
  holderForm.children[2].classList.remove('w-100');
  holderForm.children[2].classList.add('col-xl-12','col-lg-12','px-0');
  let formChilder = holderForm.children[2];
  formChilder.children[0].classList.add('col-xl-7','col-lg-7','ml-auto','mr-auto','px-0');
 

</script>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: photo.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<style>
    .right-side {
        padding:0;
        width:95%;
        margin:0 auto;
    }
    .holder-form {
        height:100%;
    }

@media only screen and (max-width: 768px) {
    form {
        max-height:100%!important;
        padding: 90px 0px 50px;
    }
}
  
</style>

<?php echo $this->form->render($this); ?>
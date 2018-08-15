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
    .right-side > div {
        align-items: center;
    }
    .holder-form {
        height:100%;
    }
    form{
        align-items: center;
        overflow-y:scroll;
        
    }

    .extfox-auth .form-elements .form-wrapper label {
        font-weight:600;
    }
    #Filedata-element #Filedata {
       padding:15px 15px;
       border: 1px solid #F5F5F5;
       width:40%;
    }
@media only screen and (max-width: 768px) {
    form {
        max-height:100%!important;
        padding: 90px 0px 50px;
    }
    #current-element div img#lassoImg {
        width:100%;
    }
    #Filedata-element #Filedata {
        width:70%;
    }
}
  
</style>

<?php echo $this->form->render($this); ?>
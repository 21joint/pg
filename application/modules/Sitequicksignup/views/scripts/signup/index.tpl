<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
    <?php echo ( $this->title ? $this->translate($this->title) : '' ) ?>
</h2>

<script type="text/javascript">
    function skipForm() {
        document.getElementById("skip").value = "skipForm";
        $('SignupForm').submit();
    }
    function finishForm() {
        document.getElementById("nextStep").value = "finish";
    }
</script>

<?php
echo $this->partial($this->script[0], $this->script[1], array(
    'form' => $this->form
))
?>

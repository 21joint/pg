<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<?php if(!$this->smoothboxClose): ?>
<?php echo $this->form->render($this) ?>


<style type='text/css'>
#birthdate-element select {
    display:none !important;
}    
</style>
<?php else: ?>
<div class="global_form_popup_message">
    <?php echo $this->translate("Your changes have been saved."); ?>
</div>
<?php $member = $this->member; ?>
<script type="text/javascript">
en4.core.runonce.add(function(){
    try{
        var editUrl = "<?php echo $this->url(array('action' => 'edit','id' => $member->family_member_id),'sdparentalguide_family',true); ?>";
        var tbody = window.parent.$("SignupForm").getElement("table tbody");
        var tr = new Element("tr",{
            'html': '<td><?php echo $member->getRelationship(); ?></td><td><?php echo $member->getGender(); ?></td><td><?php echo $this->locale()->toDate($member->dob,array("timezone" => "UTC")); ?></td>'+
                    "<td><a class='smoothbox' href='"+editUrl+"'><?php echo $this->translate('Edit'); ?><a></td><input type='hidden' name='members[]' value='<?php echo $member->family_member_id; ?>'/>",
            'id': 'family_member_<?php echo $member->family_member_id; ?>'
        });
        tr.inject(tbody,"bottom");
        window.parent.Smoothbox.bind(tbody);
        setTimeout(function(){ window.parent.Smoothbox.close(); },1500);
    }catch(e){ console.log(e); }
});
</script>

<?php endif; ?>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<div class="form-wrapper" id="members-wrapper">
    <div class="form-element" id="members-element">
        <table class='admin_table' style="width: 100%;">
            <thead>
              <tr>
                <th style='width: 40%;'><?php echo $this->translate("Relationship") ?></th>
                <th style='width: 20%;'><?php echo $this->translate("Gender") ?></th>
                <th style='width: 20%;'><?php echo $this->translate("Birthdate") ?></th>
                <th style='width: 20%;'><?php echo $this->translate("Edit/Delete") ?></th>
              </tr>
            </thead>
            <tbody>
                <?php foreach($this->members as $member): ?>
                <tr id="family_member_<?php echo $member->family_member_id; ?>">
                    <td><?php echo $member->getRelationship(); ?></td>
                    <td><?php echo $member->getGender(); ?></td>
                    <td><?php echo $this->locale()->toDate($member->dob,array("timezone" => "UTC")); ?></td>
                    <td><a class='smoothbox' href='<?php echo $this->url(array('action' => 'edit','id' => $member->family_member_id),'sdparentalguide_family',true); ?>'><?php echo $this->translate('Edit'); ?><a>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(Engine_Api::_()->core()->hasSubject()): ?>
            <?php $user = Engine_Api::_()->core()->getSubject(); ?>
            <a class='smoothbox' href="<?php echo $this->url(array('action' => 'add','user_id' => $user->getIdentity()),'sdparentalguide_family',true); ?>" style="margin-top: 15px;display:block;"><button><?php echo $this->translate("Add a Family Member") ?></button></a>
        <?php else: ?>
            <a class='smoothbox' href="<?php echo $this->url(array('action' => 'add'),'sdparentalguide_family',true); ?>" style="margin-top: 15px;display:block;"><button><?php echo $this->translate("Add a Family Member") ?></button></a>
        <?php endif; ?>
    </div>
</div>
<style type="text/css">
#members-element {
    float: none;
    margin: 0px auto;    
}    
#buttons-wrapper {
    text-align: center;
    margin-top: 15px;
}
</style>
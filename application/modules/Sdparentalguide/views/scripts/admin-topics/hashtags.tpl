<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<div class='sd_content_wrapper'>
<h2 style="text-align: center;"><?php echo $this->translate("Related Hashtags"); ?></h2>
<a href="javascript:void(0);" onclick="window.parent.Smoothbox.close();" style="position: absolute;top:0px;right:5px;font-size:25px;"><i class="fa fa-times"></i></a>
<h3><?php echo $this->topic->getTitle(); ?></h3>

<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Hashtag"); ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Action") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if($this->paginator->getTotalItemCount() > 0): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $item['text']; ?>
            </td>
            <td class='admin_table_centered'>
               
            </td>            
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
      
        <tr class="tip">
            <td><?php echo $this->translate("No Related hashtags found."); ?></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <br />
</form>
</div>
</div>

<style type='text/css'>
.sd_content_wrapper {
    width: 500px;
    padding: 10px;
}    
</style>
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
<h2 style="text-align: center;"><?= $this->translate("Related Listings"); ?></h2>
<a href="javascript:void(0);" onclick="window.parent.Smoothbox.close();" style="position: absolute;top:0px;right:5px;font-size:25px;"><i class="fa fa-times"></i></a>
<h3><?= $this->topic->getTitle(); ?></h3>

<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?= $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()" style="max-height: 400px;">
  <table class='admin_table' style="width: 600px;">
    <thead>
      <tr>
        <th style='width: 40%;' class='admin_table_centered'><?= $this->translate("Listing"); ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?= $this->translate("Listing Type") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?= $this->translate("Listing Category") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?= $this->translate("Listing Sub-Category") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Action") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if($this->paginator->getTotalItemCount() > 0): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php $catTable = Engine_Api::_()->getDbTable("categories","sitereview");; ?>
        <?php foreach( $this->paginator as $item ):?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?= $this->htmlLink($item->getHref(),
                  $this->string()->truncate($item->getTitle(), 32),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered'>
               <?= $item->getListingType()->getTitle(); ?>
            </td>
            <td class='admin_table_centered'>
               <?php if(($category = $item->getCategory())): ?> 
                    <?= $category->getTitle(); ?>
                <?php endif; ?>
            </td>
            <td class='admin_table_centered'>
               <?php if(($subcategory = $catTable->getCategory($item->subcategory_id))): ?> 
                    <?= $subcategory->getTitle(); ?>
                <?php endif; ?>
            </td>
            <td class='admin_table_centered'>
               
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
      
        <tr class="tip">
            <td><?= $this->translate("No Related listings found."); ?></td>
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
    //width: 500px;
    padding: 10px;
}    
</style>

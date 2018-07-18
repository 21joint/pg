<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<h2><?= $this->translate("Parental Guidance Customizations") ?></h2>

<style type='text/css'>
.admin_table tr td a {
    padding-left: 5px;
}
.search_search #fieldset-searchgrp label{
    display: none;
}
.search_search .search_buttons {
    margin-top: 4px;
}
.create_search {
    margin-top: 5px;
    display: inline-block;
}
#global_content{
    border: 1px solid #ddd;
    padding: 10px;
}
.search_alias {
    margin-bottom: 30px;
}
.search_alias{
    text-align: center;
}
.search_alias>div{
    text-align: center;
    display: inline-block;
    vertical-align: middle;
    width: 40%;
}
</style>


<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='sd_layout_left'>
  <?php if( count($this->navigation2) ): ?>
    <div class='tabs_left'>
      <?php
        // Render the menu
        //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation2)->render()
      ?>
    </div>
<?php endif; ?>
</div>

<div class='sd_layout_middle'>
    
<div class='admin_search search_alias'>
    <div class="sd-term-title">
        <h3><?= $this->searchterm->name; ?></h3>
    </div>
    <div class="search_buttons">
        <a href="<?= $this->url(array('action' => 'create', 'searchterm_id' => $this->searchterm->getIdentity())); ?>" class='create_alias smoothbox'><button type='button'><?= $this->translate("New Alias"); ?></button></a>
    </div>
</div>

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?= $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 50%;' class='admin_table_centered'><?= $this->translate("Alias"); ?></th>
        <th style='width: 50%;' class='admin_table_centered'><?= $this->translate("Action") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?= $item->name; ?>
            </td>
            <td class='admin_table_centered'>
                <a class='smoothbox' href='<?= $this->url(array( 'action' => 'edit','searchtermsalias_id' => $item->getIdentity()));?>'>
                    <?= $this->translate("Edit") ?>
                </a>
                <a class='smoothbox' href='<?= $this->url(array('action' => 'delete','searchtermsalias_id' => $item->getIdentity()));?>'>
                    <?= $this->translate("Delete") ?>
                </a>
            </td>            
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <br />
</form>
</div>


<br />

<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?= $this->translate(array("%s Alias Terms found", "%s Aliases Terms found", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
  <div>
    <?= $this->paginationControl($this->paginator, null, null, array(
      'pageAsQuery' => true,
      'query' => $this->formValues,
      //'params' => $this->formValues,
    )); ?>
  </div>
</div>

</div>
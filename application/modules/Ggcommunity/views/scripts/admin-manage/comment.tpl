<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Comments
 */
?>

<h2>
  <?php echo $this->translate('Guidance Guide Community Plugin') ?>
</h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<p>
  <?php echo $this->translate("") ?>
</p>

<div class='admin_search'>
  <?php echo $this->form->render($this) ?>
</div>

<br />
<br />
<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s comment found", "%s comments found", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
</div>
<br />

<?php if( count($this->paginator) ): ?>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
<table class='admin_table'>
  <thead>
    <tr>
      <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
      <th class='admin_table_short'>ID</th>
      <th><?php echo $this->translate("Description") ?></th>
      <th><?php echo $this->translate("Owner") ?></th>
      <th><?php echo $this->translate("Resource Type") ?></th>
      <th><?php echo $this->translate("Resource ID") ?></th>
      <th><?php echo $this->translate("Options") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->paginator as $item): ?>
      <tr>
        <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity(); ?>' value="<?php echo $item->getIdentity(); ?>" /></td>
        <td><?php echo $item->getIdentity() ?></td>
        <td><?php echo $item->body ?></td>
        <td>
          <a href="<?php echo $item->getOwner()->getHref(); ?>">
            <?php echo $item->getOwner()->getTitle() ?>
          </a>
        </td>
        <td><?php echo $item->parent_type ?></td>
        <td><?php echo $item->parent_id ?></td>
        <td>
          <?php echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->getIdentity(), 'type'=>'ggcommunity_comment'),
                $this->translate("Delete"),
                array('class' => 'smoothbox')) 
          ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<br />

<div class='buttons'>
  <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
</div>
</form>

<br/>
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>

<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no comments by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>




<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Answer
 */
?>

<script type="text/javascript">

function multiDelete()
{
  return confirm("<?php echo $this->translate('Are you sure you want to delete the selected answer entries?');?>");
}

function selectAll()
{
  var i;
  var multidelete_form = $('multidelete_form');
  var inputs = multidelete_form.elements;
  for (i = 1; i < inputs.length; i++) {
    if (!inputs[i].disabled) {
      inputs[i].checked = inputs[0].checked;
    }
  }
}
</script>

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
    <?php echo $this->translate(array("%s answer found", "%s answers found", $count),
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
      <th><?php echo $this->translate("Question Title") ?></th>
      <th><?php echo $this->translate("Up Votes") ?></th>
      <th><?php echo $this->translate("Down Votes") ?></th>
      <th><?php echo $this->translate("Comment Count") ?></th>
      <th><?php echo $this->translate("Options") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->paginator as $item): ?>
      <tr>
        <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity(); ?>' value="<?php echo $item->getIdentity(); ?>" /></td>
        <td><?php echo $item->getIdentity() ?></td>
        <td><?php echo $item->body ?></td>
        <td><?php $question = Engine_Api::_()->getItem('ggcommunity_question', $item->parent_id);
        echo $question->getTitle() ?></td>
        <td><?php echo $item->up_vote_count ?></td>
        <td><?php echo $item->down_vote_count ?></td>
        <td><?php echo $item->comment_count ?></td>
        <td>
          <?php echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->getIdentity(), 'type'=>'ggcommunity_answer'),
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
      <?php echo $this->translate("There are no answers by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>



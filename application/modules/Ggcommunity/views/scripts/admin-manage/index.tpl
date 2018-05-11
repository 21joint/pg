<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Manage
 */
?>

<script type="text/javascript">

function multiDelete()
{
  return confirm("<?php echo $this->translate('Are you sure you want to delete the selected questions?');?>");
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
  <?php echo $this->translate("GGCOMMUNITY_VIEWS_SCRIPTS_ADMINMANAGE_INDEX_DESCRIPTION") ?>
</p>

<div class='admin_search'>
  <?php echo $this->form->render($this) ?>
</div>

<br />
<br />

<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s question found", "%s questions found", $count),
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
      <th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('question_id', 'DESC');"><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_ID") ?></a></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_USERNAME") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_FIRST_NAME") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_LAST_NAME") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_EMAIL") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_MEMBER_LEVEL") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_QUESTION_TITLE") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_UPVOTE") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_DOWNVOTE") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_VIEWCOUNT") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_ANSWERCOUNT") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_CLOSEDATE") ?></th>
      <th><?php echo $this->translate("GGCOMMUNITY_MANAGE_QUESTION_OPTIONS") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->paginator as $item): ?>
      <tr>
        <?php $question = Engine_Api::_()->getItem('ggcommunity_question', $item->question_id);?>
        <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity(); ?>' value="<?php echo $item->getIdentity(); ?>" /></td>
        <td><?php echo $item->getIdentity() ?></td>
        <td><a href="<?php echo $item->getOwner()->getHref();?>"><?php echo $item->getOwner()->username ?></a></td>
        <td>
          <?php $fullname = $item->getOwner()->displayname;
          $first = substr($fullname,0, strpos($fullname, ' ') );
          echo $first; ?>
        </td>
        <td>
          <?php $last = substr($fullname,strpos($fullname, ' ')+1 );
          echo $last; ?>
        </td>
        <td><?php echo $item->getOwner()->email ?></td>
        <td><?php echo $item->getOwner()->level_id ?></td>
        <td><?php echo $item->getTitle() ?></td>
        <td><?php echo $this->locale()->toNumber($item->up_vote_count) ?></td>
        <td><?php echo $this->locale()->toNumber($item->down_vote_count) ?></td> 
        <td><?php echo $this->locale()->toNumber($question->view_count) ?></td>
        <td><?php echo $this->locale()->toNumber($item->answer_count) ?></td>
        <td>
          <?php if($item->date_closed != '0000-00-00 00:00:00'): ?>
            <?php echo $this->locale()->toDateTime(strtotime($item->date_closed)) ?>
          <?php endif; ?>
        </td>
        <td>
          <?php 
            // $type is between approved,  featured, sponsored, (0/1/2)
            //  $type_id is between 0/1 for un-approved/approved, non-sponsored/sponsored and non-featured/featured
            if($question->approved == 1) {
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'option', 'id' => $item->question_id, 'type' => 0, 'type_id'=> 1),
                  $this->translate("Unapproved"),
                array('class' => 'smoothbox p-l-10')
              );
            } else {
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'option', 'id' => $item->question_id, 'type' => 0, 'type_id'=> 0),
                  $this->translate("Approved"),
                array('class' => 'smoothbox p-l-10')
              );
            }
          ?>
          
          <?php 
            if($item->featured == 1) {
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'option', 'id' => $item->question_id, 'type' => 1, 'type_id'=> 1),
                  $this->translate("Un-Featured"),
                array('class' => 'smoothbox p-l-10')
              );
            } else {
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'option', 'id' => $item->question_id, 'type' => 1, 'type_id'=> 0),
                $this->translate("Featured"),
                array('class' => 'smoothbox p-l-10')
              ) ;
            }
          ?>
          
          <?php 
            if($item->sponsored == 1) {
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'option', 'id' => $item->question_id, 'type' => 2, 'type_id'=> 1),
                  $this->translate("Un-Sponsored"),
                array('class' => 'smoothbox p-l-10')
              );
            } else {
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'option', 'id' => $item->question_id, 'type' => 2, 'type_id'=> 0),
                $this->translate("Sponsored"),
                array('class' => 'smoothbox p-l-10')
              ) ;
            } 
          ?>
          
          <?php echo $this->htmlLink(array('route' => 'question_options','action' => 'edit', 'question_id'=> $question->getIdentity()), $this->translate(' Edit'), array('class' => 'p-l-10')) ?>
          
          <?php echo $this->htmlLink(
                array('route' => 'default', 'module' => 'ggcommunity', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->question_id, 'type' => 'ggcommunity_question'),
                $this->translate("Delete"),
                array('class' => 'smoothbox p-l-10')) ?>
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
<?php echo $this->paginationControl($this->paginator, null, null, array(
  'pageAsQuery' => true,
  'query' => $this->formValues,
)); ?>
</div>

<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no "."GGCOMMUNITY_QUESTIONS" ." questions by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>

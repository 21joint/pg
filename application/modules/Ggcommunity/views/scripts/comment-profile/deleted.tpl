

<?php if($this->type == 'ggcommunity_answer'):?>

  <script type="text/javascript">
    parent.$('comment_<?php echo $this->subject->getIdentity()?>').destroy();
    //decrement count_comments
    var count_comments = parent.$('comment_counter_<?php echo $this->object->getIdentity(); ?>');
    count_comments.innerHTML = '<?php echo $this->translate(array("Comment | %s", "Comments | %s", $this->comment_count ),$this->locale()->toNumber($this->comment_count )) ?>';
    setTimeout(function()
    {
      parent.Smoothbox.close();
    }, <?php echo ( $this->smoothboxClose === true ? 1000 : $this->smoothboxClose ); ?>);
  </script>
<?php else: ?>

<script type="text/javascript">
    var comments_holder = parent.$('comments_box_<?php echo $this->object->getIdentity()?>');
    var deleted_comment = comments_holder.getElementById('comment_<?php echo $this->subject->getIdentity()?>').destroy();
    //decrement count_comments
    var count_comments = parent.$('count_question_comments');
    count_comments.innerHTML = '<?php echo $this->translate(array("Comment | %s", "Comments | %s", $this->comment_count ),$this->locale()->toNumber($this->comment_count )) ?>';
    setTimeout(function()
    {
      parent.Smoothbox.close();
    }, <?php echo ( $this->smoothboxClose === true ? 1000 : $this->smoothboxClose ); ?>);
  </script>
<?php endif; ?>
<div class="global_form_popup_message">
    <?php echo $this->message; ?>
</div>


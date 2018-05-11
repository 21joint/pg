<script type="text/javascript">
  parent.$('answer_holder_box_<?php echo $this->subject->getIdentity()?>').destroy();

  //decrement count_answers
  var count_answers = parent.$('count_answers');
  count_answers.innerHTML = '<?php echo $this->translate(array("%s Answer", "%s Answers", $this->count),$this->locale()->toNumber($this->count)) ?>';

  setTimeout(function()
  {
    parent.Smoothbox.close();
  }, <?php echo ( $this->smoothboxClose === true ? 3000 : $this->smoothboxClose ); ?>);
</script>
  <div class="global_form_popup_message">
      <?php echo $this->message ?>
  </div>
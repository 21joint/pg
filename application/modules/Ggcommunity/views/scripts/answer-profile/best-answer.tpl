<script type="text/javascript">
  <?php if($this->best && $this->best->getIdentity() > 0): ?>
    // answers that was marked as best before this action
    var holder_old = parent.$('answer_holder_box_<?php echo $this->best->getIdentity()?>');
    var box_old = holder_old.firstElementChild;
     

    if(box_old.classList.contains('green')) {
      box_old.classList.remove('green');
      box_old.classList.add('white');
      box_old.getElementsByClassName('best_answer')[0].innerHTML = 
      '<?php echo $this->htmlLink(array('route'=>'default', 'module'=>'ggcommunity', 'controller'=>'answer-profile', 'action'=>'best', 'answer_id'=>$this->best->getIdentity()), $this->translate("* Chose Answer"), array('class'=>'mark-best primary smoothbox')); ?>';
      Smoothbox.bind(box_old);
    }
  <?php endif; ?>
  

  // new answer that is marked as best with this action
  var holder_new = parent.$('answer_holder_box_<?php echo $this->subject->getIdentity()?>');
  var box = holder_new.firstElementChild;
  box.className += ' green';
  box.getElementsByClassName('best_answer')[0].innerHTML = '* Chosen Theory';
  // get answers_box and new selected answer pin it to the top
  var all_answers_box = parent.$('answers_box');
  all_answers_box.insertBefore(holder_new, all_answers_box.childNodes[0]);

 
  //box.prependTo(all_answers_box);
  setTimeout(function()
  {
    parent.Smoothbox.close();
  }, <?php echo ( $this->smoothboxClose === true ? 1000 : $this->smoothboxClose ); ?>);
</script>

<div class="global_form_popup_message">
    <?php echo $this->message ?>
</div>
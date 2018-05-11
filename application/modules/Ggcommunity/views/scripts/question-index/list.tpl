
<?php echo $this->form->render($this); ?>

<?php foreach($this->paginator as $item ): ?>
<div class="all_question">
    <?php echo $item->title; ?>
</div>

<?php endforeach; ?>

<div>
<?php echo $this->paginationControl($this->paginator, null, null, array(
  'pageAsQuery' => true,
  'query' => $this->formValues,
)); ?>
</div>








	

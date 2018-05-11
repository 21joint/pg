
<?php $item = $this->subject; ?>

<?php echo $this->partial('_vote_box.tpl', 'ggcommunity', array(
    'item' => $item,
    'viewer' => $this->viewer,
)); ?>

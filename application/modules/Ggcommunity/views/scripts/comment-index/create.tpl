<div class="item-main-description border-bottom p-10" id="comment_<?php echo $this->comment->getIdentity();?>">
    <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
            'item' => $this->comment,
            'viewer' =>$this->viewer,
    )); ?>
</div>
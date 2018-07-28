<div class="new_struggle_widget <?php echo ($this->new_struggle != 1 ? 'right_widget' : 'bottom_widget');?>">
    <div class="widget_holder">
        <div class="ask_new_struggle hide-for-small-only">
            <p class="new_struggle_description"><?php echo $this->translate("Can't find what you are looking for?");?>
                <b><?php echo $this->translate('Ask it.');?></b>
            </p>
        </div>
        <?php if($this->new_struggle == 1):?>
            <?php  if($this->permissions['create_question'] != 0):?>
                <a href="<?php echo $this->url(array(),'create_struggles', true);?>" class="new_struggle_link large-3 small-6">
                    <p class="get-started"><?php echo $this->translate('Get Started'); ?></p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="77px" viewBox="-14021.501 642 77.548 16"><defs><style>.a{fill:#8ae693;}</style></defs><path class="a" d="M49.7,16.283V80.852H43.927a1.112,1.112,0,0,0-.905,1.76l6.887,9.64a1.111,1.111,0,0,0,1.81,0l6.887-9.64a1.108,1.108,0,0,0,.208-.647A1.113,1.113,0,0,0,57.7,80.852H51.927V16.283a1.113,1.113,0,0,0-2.227,0Zm5.837,66.8L50.814,89.69,46.09,83.079Z" transform="translate(-14036.671 700.814) rotate(-90)"/></svg>
                </a>
            <?php else: ?>
                <a href="javasript:void(0);" class="new_struggle_link large-3 small-6" disabled="disabled">
                    <p class="get-started"><?php echo $this->translate('Get Started'); ?></p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="77px" viewBox="-14021.501 642 77.548 16"><defs><style>.a{fill:#8ae693;}</style></defs><path class="a" d="M49.7,16.283V80.852H43.927a1.112,1.112,0,0,0-.905,1.76l6.887,9.64a1.111,1.111,0,0,0,1.81,0l6.887-9.64a1.108,1.108,0,0,0,.208-.647A1.113,1.113,0,0,0,57.7,80.852H51.927V16.283a1.113,1.113,0,0,0-2.227,0Zm5.837,66.8L50.814,89.69,46.09,83.079Z" transform="translate(-14036.671 700.814) rotate(-90)"/></svg>
                </a>
            <?php endif;?>
        <?php else:?>
            <?php  if($this->permissions['create_question'] != 0):?>
                <a href="<?php echo $this->url(array(),'create_struggles', true);?>" class="new_struggle_link active_button">
                    <?php echo $this->translate('Post a New Struggle');?>
                </a>
            <?php else: ?>
                <div class="holder-tooltip large-12">
                    <a href="javascript:void(0);" class="new_struggle_link inactive_button" disabled="disabled">
                        <?php echo $this->translate('Post a New Struggle');?>
                    </a>
                    <div class="new_struggle_tooltip">
                        <?php $url=$this->url(array(), 'create_struggles', true);?>
                        <p class="tooltip_heading"><?php echo $this->translate('We are sorry');?></p>
                        <p class="tooltip_description"><?php echo $this->translate('But you need to be <b>Level X</b> to be able to post new struggle. %1$sLearn more%2$s', '<a href="'.$url.'">', '</a>');?>
                    </div>
                </div>
            <?php endif;?>     
        <?php endif;?>

    </div>
</div>
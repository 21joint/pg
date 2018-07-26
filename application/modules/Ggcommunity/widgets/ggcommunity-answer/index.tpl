<?php $question = $this->subject;?>
<div class="answer_widget">
    <ul class="wa-list">
        <li class="wa-item display-flex p-10">
            <svg aria-hidden="true" data-prefix="fal" style="margin-right:5px;" data-icon="clock" width="15" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm216 248c0 118.7-96.1 216-216 216-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216zm-148.9 88.3l-81.2-59c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h14c6.6 0 12 5.4 12 12v146.3l70.5 51.3c5.4 3.9 6.5 11.4 2.6 16.8l-8.2 11.3c-3.9 5.3-11.4 6.5-16.8 2.6z" class=""></path></svg>
            <p class="aw_asked state-mid large-3"><?php echo $this->translate('asked'); ?></p>
            <p class="aw_asked_before state-right"> <?php echo  Engine_Api::_()->ggcommunity()->time_elapsed_string($question->creation_date);?></p>
        </li>
        <li class="aw-item display-flex p-10">
            <svg aria-hidden="true" data-prefix="fas" style="margin-right:5px;" width="15" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#5CC7CE" d="M569.354 231.631C512.969 135.949 407.81 72 288 72 168.14 72 63.004 135.994 6.646 231.631a47.999 47.999 0 0 0 0 48.739C63.031 376.051 168.19 440 288 440c119.86 0 224.996-63.994 281.354-159.631a47.997 47.997 0 0 0 0-48.738zM288 392c-75.162 0-136-60.827-136-136 0-75.162 60.826-136 136-136 75.162 0 136 60.826 136 136 0 75.162-60.826 136-136 136zm104-136c0 57.438-46.562 104-104 104s-104-46.562-104-104c0-17.708 4.431-34.379 12.236-48.973l-.001.032c0 23.651 19.173 42.823 42.824 42.823s42.824-19.173 42.824-42.823c0-23.651-19.173-42.824-42.824-42.824l-.032.001C253.621 156.431 270.292 152 288 152c57.438 0 104 46.562 104 104z" class=""></path></svg>
            <p class="aw_views state-mid large-3"><?php echo $this->translate('viewed'); ?></p>
            <p class="aw_views_count state-right">
                <?php echo $this->translate(array("%s time", "%s times", $question->view_count),
                $this->locale()->toNumber($question->view_count)) ?>
            </p>
        </li>
        <li class="aw-item display-flex p-10">
            <svg aria-hidden="true" data-prefix="fas" data-icon="power-off" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15" style="margin-right:5px;"><path fill="#5CC7CE" d="M400 54.1c63 45 104 118.6 104 201.9 0 136.8-110.8 247.7-247.5 248C120 504.3 8.2 393 8 256.4 7.9 173.1 48.9 99.3 111.8 54.2c11.7-8.3 28-4.8 35 7.7L162.6 90c5.9 10.5 3.1 23.8-6.6 31-41.5 30.8-68 79.6-68 134.9-.1 92.3 74.5 168.1 168 168.1 91.6 0 168.6-74.2 168-169.1-.3-51.8-24.7-101.8-68.1-134-9.7-7.2-12.4-20.5-6.5-30.9l15.8-28.1c7-12.4 23.2-16.1 34.8-7.8zM296 264V24c0-13.3-10.7-24-24-24h-32c-13.3 0-24 10.7-24 24v240c0 13.3 10.7 24 24 24h32c13.3 0 24-10.7 24-24z" class=""></path></svg>
            <p class="aw_status state-mid  large-3"><?php echo $this->translate('status'); ?></p>
            <p class="aw_status_info state-right"> 
                <?php if($question->date_closed > date('y-m-d h:m:s')) {
                    echo $this->translate('Open until ') . date('m/d/Y', strtotime( $question->date_closed));
                } else {
                    echo $this->translate('Open');
                }?>
            </p>
        </li>
    </ul>
    
</div>
<div class="answer_button">
    <a href="javascript:void(0);" class="aw_btn_answer" onclick="scrollBottom()">
        <?php echo $this->translate('Answer this Struggle'); ?>
    </a>
</div>

<script>
    function scrollBottom(time) {
        $("count_answers").click();
        window.scrollTo(0,$("create-answer-form").getPosition().y-70);        
    }

</script>
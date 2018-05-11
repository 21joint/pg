<?php $question = $this->subject; ?>
<div class="related_widget">
    <h5><?php echo $this->title;?></h5>
    <ul class="rw_list">
       
        <?php foreach($this->paginator as $item):?>
            <li class="rw-item p-10">
                <?php $question = $item->getParent(); ?>
                <a href="<?php echo $this->url(array('question_id'=>$question->getIdentity()),'question_profile', true); ?>" class="rw_question_link">
                    <p class="rw_title" ><?php echo $question->title; ?></p>
                    <div class="rw_votes_comment large-2 small-2 medium-2">
                        <div class="rw_votes">
                            <svg aria-hidden="true" data-prefix="fas" width="12px" style="margin-right:3px;" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#17becb" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" ></path></svg>
                            <p class="rw_up_vote_count"><?php echo $question->up_vote_count; ?></p>
                        </div>
                        <div class="rw_comments">
                            <svg aria-hidden="true" width="12px"  style="margin-right:3px;"  data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-comments fa-w-18 fa-9x"><path fill="#17becb" d="M224 358.857c-37.599 0-73.027-6.763-104.143-18.7-31.375 24.549-69.869 39.508-110.764 43.796a8.632 8.632 0 0 1-.89.047c-3.736 0-7.111-2.498-8.017-6.061-.98-3.961 2.088-6.399 5.126-9.305 15.017-14.439 33.222-25.79 40.342-74.297C17.015 266.886 0 232.622 0 195.429 0 105.16 100.297 32 224 32s224 73.159 224 163.429c-.001 90.332-100.297 163.428-224 163.428zm347.067 107.174c-13.944-13.127-30.849-23.446-37.46-67.543 68.808-64.568 52.171-156.935-37.674-207.065.031 1.334.066 2.667.066 4.006 0 122.493-129.583 216.394-284.252 211.222 38.121 30.961 93.989 50.492 156.252 50.492 34.914 0 67.811-6.148 96.704-17 29.134 22.317 64.878 35.916 102.853 39.814 3.786.395 7.363-1.973 8.27-5.467.911-3.601-1.938-5.817-4.759-8.459z" class=""></path></svg>
                            <p class="rw_comment_count"><?php echo $question->comment_count; ?></p>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>    
    </ul>   
</div>

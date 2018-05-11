
<div class="question-box">
   <div class="holder-box white question-top-box">
        <div class="holder-all">
            <div class="question-title">
                <h1 class="title"><?php echo $this->subject->getTitle(); ?></h1>
            </div>
            <?php $topics = json_decode($this->subject->topic, true);?>
            <div class="question-topics-box flex-start">
                <?php foreach($topics as $topic):?>
                    <?php $topic_item = Engine_Api::_()->getItem('sdparentalguide_topic', $topic['topic_id']); ?>
                        <a href="javascript:void(0)" class="btn tags small"><?php echo $topic_item; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
   </div>
   <div class="holder-box white" id="question-main-box">
       <div class="item-main-description">
          <?php echo $this->partial('_ggcommunity_box.tpl', 'ggcommunity', array(
                  'item' => $this->subject,
                  'viewer' => $this->viewer,
          )); ?>
            <?php if($this->subject->draft == 0):?>
                <div class="question-full-options large-11 medium-12 columns large-offset-1 medium-offset-1">
                    <div class="left-options  large-6 medium-6 small-11 large-offset-0 medium-offset-0 small-offset-1">
                        
                        <a href="javascript:void(0)" id="count_answers" class="active btn small primary " onclick="switchTab('answer',<?php echo $this->subject->getIdentity();?>)">
                        
                            <?php echo $this->translate(array("Theory | %s", "Theories | %s"  , $this->subject->answer_count),
                            $this->locale()->toNumber($this->subject->answer_count)) ?>
                        
                        </a>
   
                        <?php echo $this->userPermission('comment_question', $this->subject); ?>
                        <?php echo $this->userPermission('edit_question', $this->subject);?>
                        
                    </div>
                    <div class="right-options medium-offset-3 large-offset-3 large-3 medium-3 small-7">
                        <!-- Load social chare icons here -->
                        <div class="dropdown-menu-more" id="dropper">
                            <a href="javascript:void(0);">
                                <svg width="16" aria-hidden="true" data-prefix="fal" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16 fa-9x"><path fill="#fff" d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 96h416c8.8 0 16 7.2 16 16v41.4c-21.9 18.5-53.2 44-150.6 121.3-16.9 13.4-50.2 45.7-73.4 45.3-23.2.4-56.6-31.9-73.4-45.3C85.2 197.4 53.9 171.9 32 153.4V112c0-8.8 7.2-16 16-16zm416 320H48c-8.8 0-16-7.2-16-16V195c22.8 18.7 58.8 47.6 130.7 104.7 20.5 16.4 56.7 52.5 93.3 52.3 36.4.3 72.3-35.5 93.3-52.3 71.9-57.1 107.9-86 130.7-104.7v205c0 8.8-7.2 16-16 16z" class=""></path></svg>
                            </a>
                            <a href="javascript:void(0);">
                                <svg width="16" aria-hidden="true" data-prefix="fab" data-icon="reddit-alien" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-reddit-alien fa-w-16 fa-9x"><path fill="#fff" d="M440.3 203.5c-15 0-28.2 6.2-37.9 15.9-35.7-24.7-83.8-40.6-137.1-42.3L293 52.3l88.2 19.8c0 21.6 17.6 39.2 39.2 39.2 22 0 39.7-18.1 39.7-39.7s-17.6-39.7-39.7-39.7c-15.4 0-28.7 9.3-35.3 22l-97.4-21.6c-4.9-1.3-9.7 2.2-11 7.1L246.3 177c-52.9 2.2-100.5 18.1-136.3 42.8-9.7-10.1-23.4-16.3-38.4-16.3-55.6 0-73.8 74.6-22.9 100.1-1.8 7.9-2.6 16.3-2.6 24.7 0 83.8 94.4 151.7 210.3 151.7 116.4 0 210.8-67.9 210.8-151.7 0-8.4-.9-17.2-3.1-25.1 49.9-25.6 31.5-99.7-23.8-99.7zM129.4 308.9c0-22 17.6-39.7 39.7-39.7 21.6 0 39.2 17.6 39.2 39.7 0 21.6-17.6 39.2-39.2 39.2-22 .1-39.7-17.6-39.7-39.2zm214.3 93.5c-36.4 36.4-139.1 36.4-175.5 0-4-3.5-4-9.7 0-13.7 3.5-3.5 9.7-3.5 13.2 0 27.8 28.5 120 29 149 0 3.5-3.5 9.7-3.5 13.2 0 4.1 4 4.1 10.2.1 13.7zm-.8-54.2c-21.6 0-39.2-17.6-39.2-39.2 0-22 17.6-39.7 39.2-39.7 22 0 39.7 17.6 39.7 39.7-.1 21.5-17.7 39.2-39.7 39.2z" class=""></path></svg>
                            </a>
                            <a href="javascript:void(0);">
                                <svg width="16" aria-hidden="true" data-prefix="fab" data-icon="google-plus-g" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-google-plus-g fa-w-20 fa-9x"><path fill="#fff" d="M386.061 228.496c1.834 9.692 3.143 19.384 3.143 31.956C389.204 370.205 315.599 448 204.8 448c-106.084 0-192-85.915-192-192s85.916-192 192-192c51.864 0 95.083 18.859 128.611 50.292l-52.126 50.03c-14.145-13.621-39.028-29.599-76.485-29.599-65.484 0-118.92 54.221-118.92 121.277 0 67.056 53.436 121.277 118.92 121.277 75.961 0 104.513-54.745 108.965-82.773H204.8v-66.009h181.261zm185.406 6.437V179.2h-56.001v55.733h-55.733v56.001h55.733v55.733h56.001v-55.733H627.2v-56.001h-55.733z" class=""></path></svg>
                            </a>

                            </div>
                        </div>
                        <script>

                            function toggle_visibility(id) {
                                var e = document.getElementById(id);
                                
                                if(e.style.display == 'flex')
                                    e.style.display = 'none';
                                else
                                    e.style.display = 'flex';
                            }

                            function switchTab(tab, id) {
                                var comment_box = document.getElementById('comments_holder_box_'+id);
                                var answer_box = document.getElementById('item_container_'+id);
                                var comment_link = document.getElementById('count_question_comments');
                                var answer_link = document.getElementById('count_answers');
                            
                                if(tab == 'comment') {
                                    if(comment_box.classList.contains('none') && !answer_box.classList.contains('none')) {
                                    answer_box.classList.add('none');
                                    answer_link.classList.remove('primary');
                                    answer_link.classList.add('answer');
                                    comment_box.classList.remove('none');
                                    comment_link.classList.add('btn', 'primary');
                                    } 
                                
                                } else {
                                    if(answer_box.classList.contains('none') && !comment_box.classList.contains('none')) {
                                    comment_box.classList.add('none');
                                    comment_link.classList.remove('primary');
                                    answer_box.classList.remove('none');
                                    answer_link.classList.add('primary');
                                    answer_link.classList.remove('answer');
                                    } 
                                }
                            }
                    </script>
                </div>
            <?php endif;?>
       </div>
   </div>
</div> <!-- End of Question Box-->
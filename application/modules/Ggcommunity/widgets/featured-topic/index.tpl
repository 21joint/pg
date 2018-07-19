<div class="featured_topic_widget">
    <h5 class="widget_name"><?php echo $this->title;?></h5>
    <ul class="ftw-list">
        <?php $x = 1;?>
        <?php foreach($this->paginator as $topic): ?>    
            <li class="ftw-item p-10 large-6">
                <div class="counter-holder">
                    <?php echo $x++; ?>.
                </div>
                <div class="description-holder">
                    <!-- Turn paragraph to link -->
                    <a class="ftw_title" data-ref="<?php echo $topic->topic_id ?>"><?php echo $topic->name ?></a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>   
</div>

<script type="text/javascript">
    // Every Topic Link should Redirect to their own Topic Page
    // IDs of every item are stored in data-ref attribute
    document.querySelectorAll(".ftw_title").forEach(function(curr_topic){
        var current_topic = curr_topic;
        var topicID_w = current_topic.dataset.ref;
        curr_topic.href = en4.core.baseUrl + "topics?topicID=" + topicID_w;
    });
</script>
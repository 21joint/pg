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
                    <p class="ftw_title" ><?php echo $topic->name?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>   
</div>
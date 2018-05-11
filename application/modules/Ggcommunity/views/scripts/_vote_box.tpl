<?php 
    $item = $this->item;
    $owner = $item->getOwner();
    $item_type = $item->getType();
    $viewer = $this->viewer;
?>
<!-- Item vote options this should be another partial -->
<div class="question_votes_holder" id="vote_<?php echo $item_type.'_' . $item->getIdentity(); ?>">
    <div class="vote-options">
        <?php $vote = Engine_Api::_()->ggcommunity()->getVote($item, $viewer);?>
        <?php if($vote && $vote->vote_type == 1):?>
            <a href="javascript:void(0)" class="vote-up primary" disabled="disabled" >
                <svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path  d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" ></path></svg>
            </a>
        <?php endif;?>
        <?php if( ($vote && $vote->vote_type != 1) || !$vote):?>
            <a href="javascript:void(0)" class="vote-up" onclick="en4.ggcommunity.vote('<?php echo $item_type;?>',<?php echo $item->getIdentity();?> ,1)" >
                <svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path  d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z" ></path></svg>
            </a>
        <?php endif;?>
        <p class="question-vote">
            <?php echo $item->up_vote_count; ?>
        </p>
        <?php if($vote && $vote->vote_type == 0):?>
            <a href="javascript:void(0)" class="vote-down primary" disabled="disabled" >
                <svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" ><path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-143.6-28.9L288 302.6V120c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v182.6l-72.4-75.5c-9.3-9.7-24.8-9.9-34.3-.4l-10.9 11c-9.4 9.4-9.4 24.6 0 33.9L239 404.3c9.4 9.4 24.6 9.4 33.9 0l132.7-132.7c9.4-9.4 9.4-24.6 0-33.9l-10.9-11c-9.5-9.5-25-9.3-34.3.4z"></path></svg>
            </a>  
        <?php endif;?>
        <?php if(($vote && $vote->vote_type != 0) || !$vote):?>
            <a href="javascript:void(0)" class="vote-down" onclick="en4.ggcommunity.vote('<?php echo $item_type;?>',<?php echo $item->getIdentity();?> ,0)" >
                <svg aria-hidden="true" data-prefix="fas" data-icon="arrow-circle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path  d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-143.6-28.9L288 302.6V120c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v182.6l-72.4-75.5c-9.3-9.7-24.8-9.9-34.3-.4l-10.9 11c-9.4 9.4-9.4 24.6 0 33.9L239 404.3c9.4 9.4 24.6 9.4 33.9 0l132.7-132.7c9.4-9.4 9.4-24.6 0-33.9l-10.9-11c-9.5-9.5-25-9.3-34.3.4z"></path></svg>
            </a> 
        <?php endif; ?>
    </div>
</div> <!-- End vote partial, move it to new partial this code-->
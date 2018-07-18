<?php 
    $item = $this->item;
    $owner = $item->getOwner();
    $item_type = $item->getType();
    $viewer = $this->viewer;
?>
<?php if($item): ?>
    <div class="question-main-description display-flex">
        
        <div class="question-main-left large-1 columns medium-1 small-2">
            <div class="question-owner-photo">
                <a href="<?php echo $owner->getHref(); ?>">
                    <?php echo $this->itemPhoto($owner, 'thumb.icon', array('class'=> 'owner_thumb')) ?>
                </a>
                
            </div>
            <?php if($item_type != 'ggcommunity_comment'): ?>
                <?php echo $this->partial('_vote_box.tpl', 'ggcommunity', array(
                    'item' => $item,
                    'viewer' => $viewer,
                )); ?>
            <?php endif; ?>
        </div> <!-- holder left side -->

        <div class="question-main-right large-11 medium-11 small-9">

            <div class="question-main-top-holder ">

                <div class="question-main-top-info display-flex">
                    
                    <div class="question-main-top-box large-10 medium-10 small-10 flex-start">
                        <div class="question-owner-name m-r-10">
                            <?php echo $this->htmlLink($owner->getHref(), $owner->getTitle(), array('class' => 'owner-name')); ?>
                        </div>
                        <div class="question-approve-time m-r-10">
                            <p class="approve-time">
                                <?php echo date('D \a\t  h:i A', strtotime($item->creation_date) ); ?>
                            </p>
                        </div>
                        <?php if($item_type == 'ggcommunity_answer'): ?>
                            <p class="best_answer">
                                <?php if($item->accepted == 1):?>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-right:5px;" width="13px" height="13px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 38"><defs><linearGradient id="z" x1="-173.22" y1="1009.42" x2="-172.4" y2="1010.06" gradientTransform="matrix(13.72, 0, 0, -11.03, 2403.25, 11146.77)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#2b3336"/><stop offset="1" stop-color="#333d40"/></linearGradient><linearGradient id="x" x1="-304.1" y1="1050.64" x2="-303.1" y2="1050.64" gradientTransform="matrix(16.54, 0, 0, -10.11, 5029.61, 10635.32)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#333d40"/><stop offset="1" stop-color="#2b3336"/></linearGradient></defs><title>dark_star</title><path d="M38.29,8.11l-7.17,9.44L25,9.36,36.9,6.64C38.65,6.23,39.15,6.93,38.29,8.11Z" fill="url(#z)"/><path d="M16.54,11.4.87,15.33c-1,.23-1.22,1.29-.3,1.7L15.86,21.5Z" fill="url(#x)"/><path d="M14.56,36.53l2-35.61c0-1,1-1.25,1.68-.43L39.58,27.06c.79,1,.57,2.58-1.63,1.72L25.08,23.86,17,37.19a1.3,1.3,0,0,1-2.49-.66Z" fill="#333d40"/></svg>
                                    <?php echo $this->translate('Chosen Theory ')?> 

                                <?php else:?>
                                    <?php echo $this->userPermission('best_answer', $item); ?>
                                <?php endif; ?> 
                            </p>
                        <?php endif;?>   
                    </div>
                    <div class="question-main-top large-2 medium-2 small-2 flex-end" id="hide">
                        <a href="javascript:void(0)" id="dot-options" class="dot-options relative" onclick="en4.ggcommunity.open_options('<?php echo $item_type?>', <?php echo $item->getIdentity()?>)">
                            <svg aria-hidden="true" width="16px" data-prefix="fal" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path  d="M192 256c0 17.7-14.3 32-32 32s-32-14.3-32-32 14.3-32 32-32 32 14.3 32 32zm88-32c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-240 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z" class=""></path></svg>
                        </a>
                        <div class="holder-options-box hidden absolute" id="hidden_options_<?php echo $item_type .'_'.$item->getIdentity();?>">
                            <ul class="options-list">
                                <?php if($item_type == 'ggcommunity_comment'): ?>
                                    <li class="list-inline edit-list-item">
                                        <?php echo $this->userPermission('edit_comment', $item); ?>
                                    </li>
                                <?php endif; ?>
                                <li class="list-inline delete-list-item">
                                    <?php echo $this->userPermission("delete_$item_type", $item);?>
                                </li>
                                <li class="list-inline report-list-item">
                                    <a href="<?php echo $this->url(array('module' => 'core','controller' => 'report', 'action' => 'create', 'subject'=>$owner->getGuid(), 'format'=>'smoothbox'), 'default', true) ?>" class="report-item option-item display-flex smoothbox">
                                        <svg width="18" aria-hidden="true" data-prefix="fal" data-icon="flag" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M344.348 74.667C287.742 74.667 242.446 40 172.522 40c-28.487 0-53.675 5.322-76.965 14.449C99.553 24.713 75.808-1.127 46.071.038 21.532.999 1.433 20.75.076 45.271-1.146 67.34 12.553 86.382 32 93.258V500c0 6.627 5.373 12 12 12h8c6.627 0 12-5.373 12-12V378.398c31.423-14.539 72.066-29.064 135.652-29.064 56.606 0 101.902 34.667 171.826 34.667 51.31 0 91.933-17.238 130.008-42.953 6.589-4.45 10.514-11.909 10.514-19.86V59.521c0-17.549-18.206-29.152-34.122-21.76-36.78 17.084-86.263 36.906-133.53 36.906zM48 28c11.028 0 20 8.972 20 20s-8.972 20-20 20-20-8.972-20-20 8.972-20 20-20zm432 289.333C456.883 334.03 415.452 352 371.478 352c-63.615 0-108.247-34.667-171.826-34.667-46.016 0-102.279 10.186-135.652 26V106.667C87.117 89.971 128.548 72 172.522 72c63.615 0 108.247 34.667 171.826 34.667 45.92 0 102.217-18.813 135.652-34.667v245.333z"></path></svg>
                                        <?php echo $this->translate('Report'); ?> 
                                    </a>
                                </li>
                                <?php if(!$owner->isSelf($viewer)):?> 
                                    <li class="list-inline block-list-item">
                                        <a href="<?php echo $this->url(array('module' => 'user','controller' => 'block', 'action' => 'add', 'user_id'=>$owner->getIdentity()), 'default', true) ?>" class="block-item option-item display-flex smoothbox">
                                            <svg aria-hidden="true" data-prefix="fas" data-icon="ban" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#5CC7CE" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z" class=""></path></svg>
                                            <?php echo $this->translate('Block'); ?>
                                        </a>
                                    </li>
                                <?php endif;?> 
                                <script>
                                    document.addEventListener('click',function() {
                                        var options_holder = document.getElementById("hidden_options_<?php echo $item_type . "_" . $item->getIdentity();?>");
                                       
                                        if( (options_holder != null) && (!options_holder.classList.contains('hidden')) ) {
                                            options_holder.classList.add('hidden');
                                            options_holder.classList.remove('increase-index');
                                        } 
                                    });

                                </script>
                            </ul>
                        </div> <!-- end of box with delete, report and block options-->
                    </div>

                </div> <!-- end of question-main-top -->

               

            </div> <!-- end of question-main-top-holder -->


            <div class="question-main-middle">

                <div class="question-body <?php echo $item_type; ?>" id="<?php echo $item_type . '_' . $item->getIdentity();?>">
                    <div class="item_body" id="item_body_<?php echo $item->getIdentity()?>">
                        <?php echo $this->viewMore($item->body); ?>
                    </div>
                    <?php if($item_type != 'ggcommunity_question'): ?>
                        <div class="edit_item_<?php echo $item->getIdentity();?>">
                            <?php echo $this->editAnswer($item); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($item_type == 'ggcommunity_question' && $item->photo_id > 0):?>
                    <div class="question-main-photo">
                        <?php echo $this->itemPhoto($item, 'thumb.question', array('class'=> 'item-photo')) ?>
                    </div>
                <?php endif; ?>
            
            </div>

            <?php if($item_type == 'ggcommunity_answer'): ?>
                <div class="question-full-options">
                    <div class="right-options">
                        <?php echo $this->userPermission('edit_answer',$item); ?>
                        <?php echo $this->userPermission('comment_answer', $item); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div> <!-- question-main-right -->

    </div> <!-- question-main-description -->
<?php endif; ?>

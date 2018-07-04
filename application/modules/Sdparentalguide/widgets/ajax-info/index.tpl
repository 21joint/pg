<?php if($this->loaded_by_ajax):?>
    <script type="text/javascript">
        var infoAccountParams = {
            requestParams :{"title":"<?php echo $this->translate('Info'); ?>", "titleCount":""},
            responseContainer : $$('.layout_sdparentalguide_ajax_info')
        }
        en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', infoAccountParams);
</script>
<?php endif; ?>


<?php if($this->showContent): ?>

<div class="container mb-4 pl-0 pr-0">
    <div class="wrapper  pl-0 pr-0">
        <div class="left-side bg-white p-xl-4 p-2 p-lg-4 mr-1">
            <?php echo $this->fieldValueLoop($this->subject, $this->fieldStructure) ?>
        </div>
        <div class="right-side bg-white py-3 px-4">
           <div class="title-holder">
               <h4 class="pb-3"><?php echo $this->translate('Tell us about your kids');?></h4>
           </div>
           <ul>
                
                <?php if($this->paginator->getTotalItemCount() < 1): ?>
                <div class="tip-message py-2 w-100">
                    <span class="mb-0">
                        <?php echo $this->translate('No family members added yet.'); ?>
                    </span>
                </div>
                <?php else: ?>
                <?php foreach($this->paginator as $item):?>
                <!-- female -->
                <li class="d-flex py-3 align-items-center">
                    <div class="left-side col-xl-2 col-lg-2 col-2 pl-0 pr-0 mr-4">
                            <div class="<?php echo ($item->gender === 1) ? 'male-side' : ''?>
                            <?php echo ($item->gender === 2) ? 'female-side' : ''?>
                            <?php echo ($item->gender === 3) ? 'teenge-side' : ''?>
                             d-flex align-items-center justify-content-center">
                                <?php if($item->gender === 1):?>
                                    <svg id="8f924d2f-7155-47e2-8780-3cdd44f1dbe7" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M20.44,3a.56.56,0,0,1,.56.56v3.7a.51.51,0,0,1-.35.52.47.47,0,0,1-.59-.14l-.8-.75-3.8,3.75a6.65,6.65,0,0,1,1,3.61,6.51,6.51,0,0,1-.91,3.38,6.88,6.88,0,0,1-2.46,2.46,6.69,6.69,0,0,1-6.75,0,6.88,6.88,0,0,1-2.46-2.46,6.69,6.69,0,0,1,0-6.75A6.88,6.88,0,0,1,6.38,8.41,6.51,6.51,0,0,1,9.75,7.5a6.65,6.65,0,0,1,3.61,1l3.75-3.8-.8-.8a.5.5,0,0,1-.09-.59A.51.51,0,0,1,16.73,3ZM9.75,18a3.76,3.76,0,0,0,3.75-3.75A3.76,3.76,0,0,0,9.75,10.5,3.76,3.76,0,0,0,6,14.25,3.76,3.76,0,0,0,9.75,18Z" fill="#fff"/></svg>
                                <?php elseif($item->gender === 2):?>
                                    <svg id="1d586a36-69c9-43a9-b3c9-6e87bc4008b6" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#fff"/></svg>
                                <?php else: ?>
                                    <svg id="52bdd332-89b3-411f-86a0-72961d843f36" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" transform="translate(0 0)" fill="none"/><path d="M12,0A12,12,0,1,0,24,12,12,12,0,0,0,12,0Zm0,21.6A9.6,9.6,0,1,1,21.6,12,9.6,9.6,0,0,1,12,21.6Zm4.2-10.8A1.8,1.8,0,1,0,14.4,9a1.8,1.8,0,0,0,1.8,1.8Zm-8.4,0A1.8,1.8,0,1,0,6,9a1.8,1.8,0,0,0,1.8,1.8ZM12,18.6a6.6,6.6,0,0,0,6.13-4.2H5.87A6.6,6.6,0,0,0,12,18.6Z" transform="translate(0 0)" fill="#fff"/></svg>
                                <?php endif; ?>
                            </div>
                    </div>
                    <div class="right-side">
                        <div class="top-holder">
                            <?php 
                                $times = Engine_Api::_()->sdparentalguide()->getDateTime($item->dob);
                            ?>
                            <p class="name">
                                <?php echo $times['baby']; ?>
                                <span class="ml-1"><?php echo $times['duration']; ?></span>
                            </p>
                        </div>
                        <div class="bottom-holder">
                            <p class="gender">
                                <?php echo ($item->gender === 1) ? 'male' : 'female'; ?>
                            </p>
                        </div>
                    </div>
                </li>
                <?php endforeach;?>
                <?php endif; ?>

           </ul>
        </div>
    </div>
</div>
   

<?php endif; ?>
<?php echo $this->form->render($this); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/core.js"); ?>
<script type='text/javascript'>
en4.core.runonce.add(function(){
    $("delete_form").addEvent("submit",function(event){
        event.preventDefault();
        var requestData = {};
        requestData.contentType = "<?php echo $this->subject->resource_type; ?>";
        requestData.contentID = "<?php echo $this->subject->resource_id; ?>";
        requestData.commentID = '<?php echo $this->subject->getIdentity(); ?>';

        var loader = en4.pgservicelayer.loader.clone();
        loader.addClass("sd_loader");
        var url = en4.core.baseUrl+"api/v1/comment";
        var container = $(this);

        var request = new Request.JSON({
            url: url,
            method: 'delete',
            emulation: false,
            data: requestData,
            onRequest: function(){ loader.inject(container,"after");window.parent.Smoothbox.instance.doAutoResize(); }, //When request is sent.
            onError: function(){ loader.destroy(); }, //When request throws an error.
            onCancel: function(){ loader.destroy(); }, //When request is cancelled.
            onSuccess: function(responseJSON){ //When request is succeeded.
                loader.destroy(); 
                if(responseJSON.status_code == 204){
                    if(window.parent.$("comment_<?php echo $this->subject->getIdentity(); ?>")){
                        window.parent.$("comment_<?php echo $this->subject->getIdentity(); ?>").destroy();
                    }
                    try{
                        <?php if($this->subject->resource_type == 'ggcommunity_question'): ?>
                        var comment_counter = window.parent.$('count_question_comments');
                        <?php else: ?>
                        var comment_counter = window.parent.$('comment_counter_<?php echo $this->subject->resource_id; ?>');
                        <?php endif; ?>
                        var counter = comment_counter.innerHTML.trim();
                        var comments = parseInt(counter.substr(counter.indexOf("| ")+2));
                        var increment = comments-1;
                        if(increment == 1){
                            comment_counter.innerHTML = 'Comment | ' + increment ;
                        }else{
                            comment_counter.innerHTML = 'Comments | ' + increment ;
                        }
                        if(increment == 0){
                            comment_counter.innerHTML = 'Comment';
                        }
                    }catch(e){ window.parent.location.reload(); }
                    window.parent.Smoothbox.close();
                }else{
                    alert(responseJSON.message);
                }
            }
        });
        request.send(requestData);
    });
});    
</script>
<?php echo $this->form->render($this); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/core.js"); ?>
<script type='text/javascript'>
en4.core.runonce.add(function(){
    $("delete_form").addEvent("submit",function(event){
        event.preventDefault();
        var requestData = {};
        requestData.questionID = "<?php echo $this->subject->parent_id; ?>";
        requestData.answerID = '<?php echo $this->subject->getIdentity(); ?>';

        var loader = en4.pgservicelayer.loader.clone();
        loader.addClass("sd_loader");
        var url = en4.core.baseUrl+"api/v1/answer";
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
                    if(window.parent.$("answer_holder_box_<?php echo $this->subject->getIdentity(); ?>")){
                        window.parent.$("answer_holder_box_<?php echo $this->subject->getIdentity(); ?>").destroy();
                    }
                    try{
                        var comment_counter = window.parent.$('count_answers');
                        var counter = comment_counter.innerHTML.trim();
                        var comments = parseInt(counter.substr(counter.indexOf("| ")+2));
                        var increment = comments-1;
                        if(increment == 1){
                            comment_counter.innerHTML = 'Theory | ' + increment ;
                        }else{
                            comment_counter.innerHTML = 'Theories | ' + increment ;
                        }
                        if(increment == 1){
                            comment_counter.innerHTML = 'Theory';
                        }
                        
                    }catch(e){ console.log(e); }                    
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
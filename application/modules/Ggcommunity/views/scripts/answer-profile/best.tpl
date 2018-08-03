<?php echo $this->form->render($this); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/core.js"); ?>
<script type='text/javascript'>
en4.core.runonce.add(function(){
    $("best_form").addEvent("submit",function(event){
        event.preventDefault();
        var requestData = {};
        requestData.questionID = "<?php echo $this->subject->parent_id; ?>";
        requestData.answerChosen = 1;
        requestData.answerID = '<?php echo $this->subject->getIdentity(); ?>';

        var loader = en4.pgservicelayer.loader.clone();
        loader.addClass("sd_loader");
        var url = en4.core.baseUrl+"api/v1/answer";
        var container = $(this);

        var request = new Request.JSON({
            url: url,
            method: 'put',
            emulation: false,
            data: requestData,
            onRequest: function(){ loader.inject(container,"after");window.parent.Smoothbox.instance.doAutoResize(); }, //When request is sent.
            onError: function(){ loader.destroy(); }, //When request throws an error.
            onCancel: function(){ loader.destroy(); }, //When request is cancelled.
            onSuccess: function(responseJSON){ //When request is succeeded.
                loader.destroy(); 
                if(responseJSON.status_code == 200){
                    window.parent.location.reload();
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
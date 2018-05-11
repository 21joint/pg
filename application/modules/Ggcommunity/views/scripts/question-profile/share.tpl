<?php echo $this->form->render($this); ?>

<script>
    function copy_url() {

        var copyText = document.getElementById("url");
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("Copy");
        
        setTimeout(function()
        {
            parent.Smoothbox.close();
        }, <?php echo ( $this->smoothboxClose === true ? 7000 : $this->smoothboxClose ); ?>);
       
    }
    
</script>
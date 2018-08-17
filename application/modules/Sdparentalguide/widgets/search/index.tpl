<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<div id="sd-response"></div>

<script type="text/javascript">

    // Getting the Params from the URL
    var url_get = "<?php echo $_SERVER['REQUEST_URI']; ?>";
    var search_param = url_get.split('=')[1];
    console.log(search_param);
    
    // Calling Search Results Function
    loadSearchResults(search_param);

    // Search Results Function
    function loadSearchResults(search_param){
        // Request data can be linked to form inputs
        var requestData = {};
        requestData.limit = 10;
        requestData.page = 1;
        
        var loader = en4.core.loader.clone();
        loader.addClass("sd_loader mt-5");
        var url = en4.core.baseUrl+"api/v1/search?search="+search_param;
        
        var request = new Request.JSON({
            url: url,
            method: 'get',
            data: requestData,
            onRequest: function(){ loader.inject($("sd-response")); }, // When request is sent.
            onError: function(){ loader.destroy(); }, // When request throws an error.
            onCancel: function(){ loader.destroy(); }, // When request is cancelled.
            onSuccess: function(responseJSON){ // When request is succeeded.
                loader.destroy(); 
                console.log(responseJSON);
                // var reviewsContent = document.querySelector('.');

                if(responseJSON.status_code == 200){
                    
                    // *******************************************************************
                    // Sort by results[i].contentType -> If they match Review or Question!
                    // *******************************************************************

                    // var html = "";
                    var results = responseJSON.body.Results;
                    // Testing... Delete after done
                    console.log(results);
                }else{
                    console.log(responseJSON.message);
                }
            }
        });
        request.send();
    }
</script>
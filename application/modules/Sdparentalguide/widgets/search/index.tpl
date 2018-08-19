<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<div class="search_results_search container d-flex justify-content-around bg-white px-0 py-3 mb-2">
    <input id="search_again_param" type="text"/>
    <button id="search_again">Search</button>
</div>
<div class="container">
    <div class="d-flex justify-content-between align-items-start">
        <div class="search_results_filter bg-white px-0">
            123123
        </div>
        <div class="search_results_content px-0">
            <div id="sd-response" class="container text-center"></div>
            <div class="search_results_reviews bg-white"></div>
            <div class="search_results_questions text-primary bg-white"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Getting the Params from the URL
    var url_get = "<?php echo $_SERVER['REQUEST_URI']; ?>";
    var search_param = url_get.split('=')[1];
    console.log(search_param);
    
    // Calling Search Results Function
    loadSearchResults(search_param);

    // Calling Search Results Function on New Entry
    var search_again;
    document.getElementById('search_again').addEventListener('click', function(){
        search_again = document.getElementById('search_again_param').value;
        loadSearchResults(search_again);
    });
    document.getElementById('search_again_param').addEventListener('keydown', function(e){
        if(e.keyCode == 13){
            search_again = document.getElementById('search_again_param').value;
            loadSearchResults(search_again);
        }
    });

    // Search Results Function
    function loadSearchResults(search_param){
        // Request data can be linked to form inputs
        var requestData = {};
        requestData.limit = 10;
        requestData.page = 1;

        // Search Field Populated
        document.getElementById('search_again_param').value = search_param;
        
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
                var searchContentReviews = document.querySelector('.search_results_reviews');
                var searchContentQuestions = document.querySelector('.search_results_questions');

                if(responseJSON.status_code == 200){
                    var html_reviews = "";
                    var html_questions = "";
                    var results = responseJSON.body.Results;
                    // Testing... Delete after done
                    console.log(results);
                    for(var i = 0; i < results.length; i++){
                        if(results[i].contentType == 'Review'){
                            html_reviews += '<h1>'+results[i].contentObject.title+'</h1>';
                        }else if(results[i].contentType == 'Question'){
                            html_questions += '<h2>'+results[i].contentObject.title+'</h2>';
                        }
                    }
                    searchContentReviews.innerHTML = html_reviews;
                    searchContentQuestions.innerHTML = html_questions;
                }else{
                    console.log(responseJSON.message);
                }
            }
        });
        request.send();
    }
</script>
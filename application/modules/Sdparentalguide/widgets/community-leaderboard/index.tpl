<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<div id="leaderboard_wrapper">
    <div id="leaderboard">
        <div class="leaderboard_title d-flex justify-content-between align-items-centar">
            <h3>Leaderboard</h3>
            <ul class="d-flex align">
                <li class="leaderboard_nav">Overall</li>
                <li class="leaderboard_nav">Week</li>
                <li class="leaderboard_nav">Month</li>
            </ul>
        </div>
        <div class="leaderboard_main">
            
        </div> 
        <div class="leaderboard_content">
            
        </div>  
        <div id="sd-response"class="container d-flex justify-content-center"></div>
    </div>  
</div>

<script type='text/javascript'>
//Dom ready
en4.core.runonce.add(function(){
    loadLeaderboardResults();
});


document.querySelectorAll('.leaderboard_nav').forEach(function(nav , i) {
    nav.addEventListener('click', function() {
        loadLeaderboardResults(this.innerText);       
    });
});


function loadLeaderboardResults(att = "Overall") {
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.contributionRangeType = att; //Possible values "Overall", "Week", "Month"
    requestData.orderBy = 'contributionPoints'; //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
    requestData.limit = 10;
    requestData.page = 1;

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader mt-5");
    var url = en4.core.baseUrl+"v1/ranking";

    var request = new Request.JSON({
        url: url,
        data: requestData,
        onRequest: function(){ loader.inject($("sd-response")); }, //When request is sent.
        onError: function(){ loader.destroy(); }, //When request throws an error.
        onCancel: function(){ loader.destroy(); }, //When request is cancelled.
        onSuccess: function(responseJSON){ //When request is succeeded.
            loader.destroy(); 

            var leaderboardContent = document.querySelector('.leaderboard_content');

            if(responseJSON.status_code == 200){

                var html = "";
                var results = responseJSON.body.Results;
                for(var i = 0; i < results.length; i++) {
                    html += `<div class="leaderboard_item d-flex">
                                <div>
                                    ${i+1}
                                </div>
                                <div>
                                    <img src="${results[i].avatarPhoto.photoURLIcon}"/>
                                </div>
                                <div>
                                    <h4>${results[i].displayName}</h4>
                                </div>
                                <div>
                                    ${results[i].contribution}
                                </div>
                            </div>`;
                }
                leaderboardContent.innerHTML = html;
            }else{
                leaderboardContent.innerHTML = responseJSON.message;
            }
        }
    });
    request.send();
}    
</script>
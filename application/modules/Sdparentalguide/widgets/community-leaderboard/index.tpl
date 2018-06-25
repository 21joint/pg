<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<pre id='sd-response'></pre>
<script type='text/javascript'>
//Dom ready
en4.core.runonce.add(function(){
    loadLeaderboardResults();
});
function loadLeaderboardResults(){
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.contributionRangeType = 'Overall'; //Possible values "Overall", "Week", "Month"
    requestData.orderBy = 'contributionPoints'; //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
    requestData.limit = 10;
    requestData.page = 1;
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    
    var request = new Request.JSON({
        url: "<?php echo $this->url(array('action' => 'ranking-service'),'sdparentalguide_api',true); ?>",
        data: requestData,
        onRequest: function(){ loader.inject($("sd-response"),"after"); }, //When request is sent.
        onError: function(){ loader.destroy(); }, //When request throws an error.
        onCancel: function(){ loader.destroy(); }, //When request is cancelled.
        onSuccess: function(responseJSON){ //When request is succeeded.
            loader.destroy(); 
            if(responseJSON.status_code == 200){
                $("sd-response").set("html",JSON.stringify(responseJSON.body, undefined, 2));                
            }else{
                $("sd-response").set("html",responseJSON.message);
            }
        }
    });
    request.send();
}    
</script>
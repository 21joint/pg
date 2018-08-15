<div class="reviews_component my-3">
    <div class="reviews_component_title border-bottom">
        <h3 class="font-weight-bold m-0"><?php echo $this->translate('Featured Reviews'); ?></h3>
    </div>
    <div id="sd-response" class="container d-flex justify-content-center">
        <!-- Loader goes here -->
    </div>
    <div class="reviews_component_content mt-3 p-0">
        <!-- Content of ajax call goes here -->
    </div>
</div>

<?php if($this->loaded_by_ajax):?>
<script type="text/javascript">
var myReviewAccountParams = {
    requestParams :{"title":"<?php echo $this->title; ?>", "titleCount":"", "authorID":"<?php echo $this->subject->getIdentity(); ?>", "format":"json"},
    responseContainer : $$('.reviews_component_content'),
    requestUrl: en4.core.baseUrl+"api/v1/review",
    successHandler: handleMyAJaxReviewResponse
}
en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', myReviewAccountParams);
function handleMyAJaxReviewResponse(responseJSON){
    // $$('.layout_sdparentalguide_ajax_my_reviews').set("html","");
    // new Element("pre",{ id: 'sd-response' }).inject($$('.layout_sdparentalguide_ajax_my_reviews')[0],"bottom");
    var reviewsContent = document.querySelector('.reviews_component_content');
    if(responseJSON.status_code == 200){
        var results = responseJSON.body.Results;
         var html = "";
         for(var i = 0; i < results.length; i++){
             html += results[i].title;
         }
         reviewsContent.innerHTML = html;
         
        // $("sd-response").set("html",JSON.stringify(responseJSON.body, undefined, 2));                
    }else{
        $("sd-response").set("html",responseJSON.message);
    }
}
</script>
<?php endif; ?>

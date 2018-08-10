<?php if($this->loaded_by_ajax):?>
<script type="text/javascript">
var myReviewAccountParams = {
    requestParams :{"title":"<?php echo $this->title; ?>", "titleCount":"", "authorID":"<?php echo $this->subject->getIdentity(); ?>", "format":"json"},
    responseContainer : $$('.layout_sdparentalguide_ajax_my_reviews'),
    requestUrl: en4.core.baseUrl+"api/v1/review",
    successHandler: handleMyAJaxReviewResponse
}
en4.gg.ajaxTab.attachEvent('<?php echo $this->identity ?>', myReviewAccountParams);
function handleMyAJaxReviewResponse(responseJSON){
    $$('.layout_sdparentalguide_ajax_my_reviews').set("html","");
    new Element("pre",{ id: 'sd-response' }).inject($$('.layout_sdparentalguide_ajax_my_reviews')[0],"bottom");
    if(responseJSON.status_code == 200){
        $("sd-response").set("html",JSON.stringify(responseJSON.body, undefined, 2));                
    }else{
        $("sd-response").set("html",responseJSON.message);
    }
}
</script>
<?php endif; ?>

<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<?php echo $this->form->render($this); ?>

<script type="text/javascript"> 
function loadSubCategories(){
    var categoryId = $("category_id").get("value");
    if(categoryId.length <= 0){
        $("subcategory_id-wrapper").setStyle("display","none");
        return;
    }
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = '<?php echo $this->url(array('action' => 'get-subcategories'), 'sdparentalguide_general', true) ?>';
    var req = new Request.JSON({
      url: url,
      data: {
          category_id: categoryId,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($("category_id"),"after");
      },
      onFail: function(){
          loader.destroy();
      },
      onCancel: function(){
          loader.destroy();
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          $("subcategory_id").empty();
          if(responseJSON.status && responseJSON.categories.length > 0){
              var options = "<option value='0'><?php echo $this->translate('Sub Category'); ?></option>";
              Object.each(responseJSON.categories,function(category){
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
              });
              $("subcategory_id").set("html",options);
              $("subcategory_id-wrapper").setStyle("display","block");
          }else{
              $("subcategory_id-wrapper").setStyle("display","none");
          }
      }
    });
    req.send();
}
function loadCategories(element){
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader");
  $("category_id-wrapper").setStyle("display","none");
  $$(".sd_listing_search .sd_inline_field.subcategory").setStyle("display","none");
  var url = '<?php echo $this->url(array('action' => 'get-categories'), 'sdparentalguide_general', true) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          listing_type: $(element).get("value"),
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onFail: function(){
          loader.destroy();
      },
      onCancel: function(){
          loader.destroy();
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          $("category_id").empty();
          if(responseJSON.status && responseJSON.categories.length > 0){
              $("category_id").set("html","");
              var options = "<option value='0'><?php echo $this->translate('Category'); ?></option>";
              Object.each(responseJSON.categories,function(category){
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
              });
              $("category_id").set("html",options);
              $("category_id-wrapper").setStyle("display","block");
          }
      }
  });
  req.send();
}
en4.core.runonce.add(function(){
    if($("category_id").get("value").length <= 0){
        $("category_id-wrapper").setStyle("display","none");
    }
    if($("subcategory_id").get("value").length <= 0){
        $("subcategory_id-wrapper").setStyle("display","none");
    }
});
function previewIcon(element){
    var files = element.files;
    var parent = $(element).getParent();
    if(files.length <= 0){
        return;
    }
    
    var reader = new FileReader();
    reader.onload = function(){
        var dataURL = reader.result;
        if(parent.getElement(".icon_preview")){
            parent.getElement(".icon_preview").destroy();
        }
        var div = new Element("div",{
            'class': 'icon_preview',
        });
        var img = new Element("img",{
            'src': dataURL
        });
        img.inject(div,"bottom");
        div.inject(parent,"bottom");
        window.parent.Smoothbox.instance.doAutoResize();
    };
    reader.readAsDataURL(files[0]);
    
}
</script>

<style type="text/css">
.icon_preview {
    margin-top: 10px;
}
.icon_preview img {
    max-width: 150px;
}    
</style>
<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<?php $api = Engine_Api::_()->sdparentalguide(); ?>
<?php if($this->listings->getTotalItemCount() > 0): ?>
<ul class="sd_browse_listings" id="sd_browse_listings">
    <?php foreach ($this->listings as $sitereview): ?>
    <?php $owner = $sitereview->getOwner(); if(empty($owner)) { continue; } ?>
    <?php $firstName = $api->getFirstName($owner); $lastName = $api->getLastName($owner); ?>
    <li data-id='<?php echo $sitereview->getIdentity(); ?>'>
        <div class="sd_listing_thumb flexslider">
            <?php echo $this->partial('application/modules/Sdparentalguide/views/scripts/_listingPhotos.tpl', array('sitereview' => $sitereview)); ?>            
        </div>
        <div class='seaocore_sidebar_list_info'>
            <div class="sd_sidebar_list_info_header">
                <div class='seaocore_sidebar_list_title'>
                  <?php echo $this->htmlLink($sitereview->getHref(), Engine_Api::_()->seaocore()->seaocoreTruncateText($sitereview->getTitle(), $this->truncation), array('title' => $sitereview->getTitle())) ?>
                </div>
                <div class='seaocore_sidebar_list_owner'>
                    <span class="listing_owner_title">
                        <?php echo $this->htmlLink($owner->getHref(), $owner->username.", ".$firstName.", ".$lastName) ?>,
                    </span>
                    <?php if($sitereview->category_id): ?>
                        <span class="listing_category"><?php echo $sitereview->getCategory(); ?></span>
                        <?php $subCategory = Engine_Api::_()->getItem('sitereview_category', $sitereview->subcategory_id); ?>
                        <?php if($subCategory): ?>
                            | <span class="listing_category"><?php echo $subCategory; ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="listing_date"><?php echo $this->locale()->toDate($sitereview->creation_date); ?></span>                    
                </div>
            </div>
             <?php if($this->truncationDescription): ?>
              <div class="seaocore_description">
                <?php echo $this->viewMore(strip_tags($sitereview->body), $this->truncationDescription,10000) ?>
              </div>  
            <?php endif;  ?>
        </div>
        <div class="sd_listing_grading">
            <?php $form = new Sdparentalguide_Form_Listing_Grading(array('item' => $sitereview)); ?>
            <?php echo $form->render(); ?>
            <script type="text/javascript">
               en4.core.runonce.add(function(){
                   tinymce.init({
                        mode: "exact",
                        plugins: "table,fullscreen,preview,paste,code,textcolor,link,lists,autosave,colorpicker,imagetools,advlist,searchreplace,emoticons,codesample",
                        theme: "modern",
                        menubar: false,
                        statusbar: false,
                        toolbar1: "undo,redo,removeformat,pastetext,fontselect,fontsizeselect,bold,italic,underline,alignleft,aligncenter,alignright,alignjustify,bullist,numlist,outdent,indent,blockquote",
                        toolbar2: "",
                        toolbar3: "",
                        image_advtab: true,
                        element_format: "html",
                        autosave_ask_before_unload: false,
                        autosave_retention: "300m",
                        height: "225px",
                        convert_urls: false,
                        upload_url: "",
                        browser_spellcheck: true,
                        language: "en",
                        directionality: "ltr",
                        elements: "notes_<?php echo $sitereview->getIdentity(); ?>"
                    });
               });
            </script>
        </div>
    </li>
    <?php endforeach; ?>
    
    <?php echo $this->paginationControl($this->listings, null, array('_paginator.tpl','sdparentalguide'), array(
          'pageAsQuery' => true,
          'query' => $this->formValues
        )); ?>
</ul>
<?php else: ?>
<div class='tip'>
    <span><?php echo $this->translate("No listings found matching your search criteria"); ?></span>
</div>
<?php endif; ?>

<?php echo $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl.'application/modules/Sdparentalguide/externals/scripts/flexslider/flexslider.css'); ?>
<script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sdparentalguide/externals/scripts/flexslider/jquery.min.js"></script>
<script type="text/javascript">try{ var sdjq = $.noConflict(); }catch(e){  }</script>
<script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sdparentalguide/externals/scripts/flexslider/jquery.flexslider-min.js"></script>
<script type="text/javascript">
en4.core.runonce.add(function(){
    sdjq('.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: 200,
        itemMargin: 5,
        pausePlay: false,
        allowOneSlide: true,
    });
});  
</script>

<script type="text/javascript">
function searchListings(element){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';
    var form = $(element).getParent("form"); 
    var data = form.toQueryString().parseQueryString();
    data.format = 'html';
    var req = new Request.HTML({
        url: url,
        data : data,
        onRequest: function(){
            loader.inject($(element),"after");
        },
        evalScripts: true,
        onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
            loader.destroy();
            var div = new Element("div",{
                html: responseHTML
            });
            var newContainer = div.getElement(".layout_sdparentalguide_browse_listings");
            var container = $$(".layout_sdparentalguide_browse_listings")[0];
            if(!newContainer){
                return;
            }
            newContainer.inject(container,"after");
            container.destroy();
            tinymce.remove();
            en4.core.runonce.trigger();
        }
    });
    req.send();
}
function saveNSearchListings(element){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';
    var form = $(element).getParent("form"); 
    var data = form.toQueryString().parseQueryString();
    data.format = 'html';
    data.forms = getFormsData();
    
    var req = new Request.HTML({
        url: url,
        data : data,
        onRequest: function(){
            loader.inject($(element),"after");
        },
        evalScripts: true,
        onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
            loader.destroy();
            var div = new Element("div",{
                html: responseHTML
            });
            var newContainer = div.getElement(".layout_sdparentalguide_browse_listings");
            var container = $$(".layout_sdparentalguide_browse_listings")[0];
            if(!newContainer){
                return;
            }
            newContainer.inject(container,"after");
            container.destroy();
            tinymce.remove();
            en4.core.runonce.trigger();
        }
    });
    req.send();
}
function getFormsData(){
    var forms = $$(".sd_browse_listings form");
    if(forms.length <= 0){
        return [];
    }
    var formsData = [];
    forms.each(function(form,i){
        var data = getFormData(form);
        try{
            data.notes = tinymce.editors[i].getContent();
        }catch(e){ console.log(e); }
        
        formsData.push(data);
    });
    return formsData;
}
function getFormData(form){
    var fields = form.getElements(".sd_inline_field");
    var formData = form.toQueryString().parseQueryString();
    fields.each(function(field){
        var checkbox = field.getElement("input:checked");
        if(checkbox){
            var name = checkbox.name;
            formData[name] = 1;
        }
    });
    return formData;
}
function showLargeImage(element){
    var imgSrc = $(element).get("data-thumb");
    var loaderImg = en4.core.loader.src;
    var content = new Element("div",{
        'html': '<img src="'+loaderImg+'"/>',
        'class': 'sd_large_image',
    });
    Smoothbox.open(content,{
        mode: 'Inline',
    });
    var myImage = Asset.image(imgSrc, {
        id: 'myImage',
        title: 'myImage',
        onLoad: function(){
            if($$(".sd_large_image").length > 0){
                $$(".sd_large_image").getElement("img")[0].src = imgSrc;
                Smoothbox.instance.doAutoResize();
            }
        }
    });
}
function markAllGrading(element){
    var parent = $(element).getParent(".form-wrapper");
    if(!parent){
        return;
    }
    var checkboxes = parent.getElements("input[type=checkbox]");
    if(checkboxes.length <= 0){
        return;
    }
    if($(element).checked){
        checkboxes.set("checked","checked");
    }else{
        checkboxes.set("checked",null);
    }
}
</script>
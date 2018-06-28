<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<?php
    $this->headScript()
        ->appendFile($this->baseUrl() .  '/externals/masonry/mooMasonry.js');
?>

<style type="text/css">

.left-side {
    display: none !important;
}

</style>

<?php if(count($this->listingTypes) > 0): ?>
<?php $catTable = Engine_Api::_()->getDbTable("categories","sitereview"); ?>
<ul class="sd_signup_interests my-0 col-lg-9 mx-auto">
    <?php foreach($this->listingTypes as $listingType): ?>
    <?php $categories = $catTable->getCategoriesList($listingType->listingtype_id,0); ?>
    <?php if(count($categories) <= 0){ continue; } ?>
   
    <li class="mt-1 py-4">
        <div class="holder-image position-relative mb-3 col-xl-11 col-11 mx-auto col-lg-11 px-0">
            <div class="holder-category-title bg-primary d-flex position-absolute align-items-center justify-content-center py-3 text-white">
                <span><?php echo $listingType->getTitle(); ?></span>
            </div>

            <div class="holder-select-all position-absolute ">
                <a class="d-flex  bg-success  align-items-center justify-content-center py-3 text-white" onclick="markListingCategories(this);"><?php echo $this->translate('Select all');?></a>
            </div>
        </div>

        
        <div class="holder-listing-categories col-xl-11 col-lg-11 col-11 mx-auto ">
            <div  class="sd_listing_categories">

                <?php foreach($categories as $category): ?>
                    <?php if(empty($category->getTitle())){ continue; } ?>
                    <div class="holder">
                        <input type="checkbox"  name="categories[]" <?php if(in_array($category->category_id,$this->savedCategories)){ echo 'checked=checked'; } ?> class="sd_listing_category" value="<?php echo $category->category_id; ?>" id="category-<?php echo $category->category_id; ?>"/>
                        <label class="label-check" for="category-<?php echo $category->category_id; ?>">
                            <?php echo $category->getTitle(); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>

<?php endif; ?>


<script type="text/javascript">
en4.core.runonce.add(function(){
    var e = $$(".sd_signup_interests");
    e.pinBoardSeaoMasonry({
        singleMode: true, 
        itemSelector: '.sd-interest-list'
    });
    setTimeout(function() {
        var e = $$(".sd_signup_interests");
        e.pinBoardSeaoMasonry({
            singleMode: true, 
            itemSelector: '.sd-interest-list'
        });
    }, 200);
    setTimeout(function() {
        var e = $$(".sd_signup_interests");
        e.pinBoardSeaoMasonry({
            singleMode: true, 
            itemSelector: '.sd-interest-list'
        });
    }, 500);
    setTimeout(function() {
        var e = $$(".sd_signup_interests");
        e.pinBoardSeaoMasonry({
            singleMode: true, 
            itemSelector: '.sd-interest-list'
        });
    }, 1000);
});
window.addEvent("resize",function(){
    setTimeout(function() {
        var e = $$(".sd_signup_interests");
        e.pinBoardSeaoMasonry({
            singleMode: true, 
            itemSelector: '.sd-interest-list'
        });
    }, 500);
});
function markListingCategories(element){
    
    var parent = $(element).getParent("li");
    

    var checkboxes = parent.getElements("input[type=checkbox]");
    console.log(parent);
    console.log(checkboxes);
    
    if(checkboxes.length <= 0){
        return;
    }
    if(parent.hasClass("checked_all")){
        checkboxes.set("checked",null);
       
       
    }else{
        checkboxes.set("checked",true);  
        
    }
    parent.toggleClass("checked_all");
}


var right = document.getElementsByClassName("right-side")[0];
right.classList.remove('col-xl-6', 'col-lg-6');
right.classList.add('col-xl-12', 'col-lg-12','col-12','px-0',);
right.firstElementChild.classList.add('col-xl-12', 'col-lg-12');
right.firstElementChild.classList.remove('col-xl-7','col-lg-7');




</script>
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
.sd_signup_interests>li{
    padding: 30px;
    box-sizing: border-box;
    vertical-align: top;
}
.sd_listing_categories {
    text-align: left;
}
.sd_listing_categories input{
    display: none;
}
.sd_listing_categories .sd-checkbox{
    width: 12px;
    height: 12px;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 3px;
    vertical-align: middle;
    margin-top: -2px;
    margin-right: 5px;
    background-color: #dedede;
    position: relative;
}
.sd_listing_categories input:checked+span:before {
    content: "";
    display: inline-block;
    width: 10px;
    height: 10px;
    position: absolute;
    top: 1px;
    left: 1px;
    background-color: #1bc1d6;
    border-radius: 3px;
}
.sd_listing_categories label {
    cursor: pointer;
}
.sd_listing_photo img {
    cursor: pointer;
}
</style>

<?php if(count($this->listingTypes) > 0): ?>
<?php $catTable = Engine_Api::_()->getDbTable("categories","sitereview"); ?>
<ul class="sd_signup_interests">
    <?php foreach($this->listingTypes as $listingType): ?>
    <?php $categories = $catTable->getCategoriesList($listingType->listingtype_id,0); ?>
    <?php if(count($categories) <= 0){ continue; } ?>
    <li class="sd-interest-list">
        <div class="sd_listing_photo">
            <?php echo $this->itemPhoto($listingType,'thumb.profile',$listingType->getTitle(),array('title' => $listingType->getTitle(),'onclick' => 'markListingCategories(this);')); ?>
        </div>
        <ul class="sd_listing_categories">
            <?php foreach($categories as $category): ?>
            <?php if(empty($category->getTitle())){ continue; } ?>
            <li>
                <label for="category-<?php echo $category->category_id; ?>">
                    <input type="checkbox" name="categories[]" <?php if(in_array($category->category_id,$this->savedCategories)){ echo 'checked=checked'; } ?> class="sd_listing_category" value="<?php echo $category->category_id; ?>" id="category-<?php echo $category->category_id; ?>"/>
                    <span class="sd-checkbox"></span><?php echo $category->getTitle(); ?>
                </label>
            </li>
            <?php endforeach; ?>
        </ul>
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
</script>
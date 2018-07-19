<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<div class="prg-review--create">
  <div class="page-section bg-white">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9">
          <form action="">
            <div class="row">
              <div class="col-12">
                <label class="text-primary font-weight-600" for="rv_title">Review Title</label>
              </div>
              <div class="col-12">
              <textarea name="rv_title"
                        id="rv_title"
                        rows="2"
                        class="form-control py-1 px-0 ff-open--sans"></textarea>
                <div class="text-asphalt text-right mt-1 prg-chars--left"><span>112 / 120</span></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label class="text-primary font-weight-600" for="rv_category">Review Category</label>
                <select style="width: 100%;" name="rv_category" id="rv_category">
                  <option>Parenting</option>
                  <option value="Category 1">Category 1</option>
                  <option value="Category 2">Category 2</option>
                  <option value="Category 3">Category 3</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="text-primary font-weight-600" for="rv_subcategory">Sub-Category</label>
                <select style="width: 100%;" name="rv_subcategory" id="rv_subcategory">
                  <option>Parenting</option>
                  <option value="Sub-Category 1">Sub-Category 1</option>
                  <option value="Sub-Category 2">Sub-Category 2</option>
                  <option value="Sub-Category 3">Sub-Category 3</option>
                </select>

              </div>
              <div class="col-md-4">
                <label class="text-primary font-weight-600" for="rv_lvl2category">2nd Level Category</label>
                <select style="width: 100%;" name="rv_lvl2category" id="rv_lvl2category">
                  <option>Parenting</option>
                  <option value="Sub-Category 1">2nd Level Category 1</option>
                  <option value="Sub-Category 2">2nd Level Category 2</option>
                  <option value="Sub-Category 3">2nd Level Category 3</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <label class="text-primary font-weight-600" for="rv_description">Description</label>
                <input type="text" id="rv_description"/>
              </div>
              <div class="col-12">
                <input type="checkbox" id="rv_show_diaper__bag"/>
                <label class="text-primary font-weight-600" for="rv_show_diaper__bag"></label>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-3"></div>
      </div>
    </div>
  </div>
</div>
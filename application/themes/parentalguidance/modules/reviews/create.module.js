import {getCategories} from '../../middleware/api.service';
import {Rating} from '../../components/rating';
import {Select} from "../../components/select";

$(document).ready(function () {
  init();
});


function init() {
  getCategories({}, function (cats) {
    $('#rv_category').html(function () {
      let _html = '';
      for (let i = 0; i < cats.length; i++) {
        let cat = cats[i];
        _html += `<option value="${cat.typeID}" ${i === 0 ? 'selected' : ''}>${cat.type}</option>`
      }
      return _html;
    })
  });

  $('[data-input=rate]').each(function (i, inputEl) {
    $(inputEl).html(Rating.renderRateInput());
  });

  $('[data-view="reviews-create"]').html(`
    
    <!-- CREATE REVIEW -->
  <div class="prg-review--create">
    <div class="page-section bg-white">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-9">
            <form action="">
              <div class="form-row">
                <div class="col-12">
                  <label class="text-primary font-weight-600" for="rv_title">Review
                    Title</label>
                </div>
                <div class="col-12">
                  <textarea name="rv_title"
                            id="rv_title"
                            rows="2"
                            class="form-control py-1 px-0 ff-open--sans">What are the most dangerous trends or practices in parenting that most parents do without</textarea>
                  <div class="text-asphalt text-right mt-1 prg-chars--left"><span>112 / 120</span>
                  </div>
                </div>
              </div>
              <div class="form-row mt-4">
                <div class="col-md-4">
                  ${ new Select().render()}
                  <label class="text-primary font-weight-600" for="rv_category">Review
                    Category</label>
                  <select style="width: 100%;" name="rv_category"
                          id="rv_category" data-url="/categorization">
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="text-primary font-weight-600"
                         for="rv_subcategory">Sub-Category</label>
                  <select style="width: 100%;" name="rv_subcategory"
                          id="rv_subcategory">
                    <option>Parenting</option>
                    <option value="Sub-Category 1">Sub-Category 1</option>
                    <option value="Sub-Category 2">Sub-Category 2</option>
                    <option value="Sub-Category 3">Sub-Category 3</option>
                  </select>

                </div>
                <div class="col-md-4">
                  <label class="text-primary font-weight-600"
                         for="rv_lvl2category">2nd Level Category</label>
                  <select style="width: 100%;" name="rv_lvl2category"
                          id="rv_lvl2category">
                    <option>Parenting</option>
                    <option value="Sub-Category 1">2nd Level Category 1</option>
                    <option value="Sub-Category 2">2nd Level Category 2</option>
                    <option value="Sub-Category 3">2nd Level Category 3</option>
                  </select>
                </div>
              </div>
              <div class="form-row mt-4">
                <div class="col-12">
                  <label class="text-primary font-weight-600"
                         for="rv_description">Description</label>
                  <textarea id="rv_description" type="text"
                            class="froala-editor"></textarea>
                </div>
                <div class="col-12 mt-2">
                  <input type="checkbox" id="rv_show_diaper__bag"/>
                  <label class="font-weight-600 align-middle"
                         for="rv_show_diaper__bag">Show this diaper bag on browse
                    page and in varios blocks.</label>
                </div>
              </div>
              <div class="form-row mt-4">
                <div class="col-12">
                  <div class="label text-primary font-weight-600">Rate this
                    product
                  </div>
                  <div class="prg-input--stars" data-input="rate">

                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-3"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- CREATE REVIEW ///-->
  `)
}

function form() {

}

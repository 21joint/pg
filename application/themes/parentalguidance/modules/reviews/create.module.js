import {getCategories} from '../../middleware/api.service';
import {renderRateInput} from '../../components/rating/rating';

(function ($) {
  getCategories({}, function (cats) {
    $('#rv_category')
      .html(function () {
        let _html = '';
        for (let i = 0; i < cats.length; i++) {
          let cat = cats[i];
          _html += `<option value="${cat.typeID}" ${i === 0 ? 'selected' : ''}>${cat.type}</option>`
        }
        return _html;
      })
  });

  $('[data-input=rate]').each(function (i, inputEl) {
    $(inputEl).html(renderRateInput());
  });

})(jQuery);

import 'jquery-lazy';
import {getReviews} from '../../middleware/api.service';
import {renderCard} from "../../components/card";

function init() {

  getReviews({
    container: '#featuredReviewsGrid',
    type: 'review'
  }, function (data) {
    const _contents = data.Results;
    $.each(_contents, function (i, content) {
      let _cardHtml = renderCard(content, {
        contentType: data.contentType
      });
      let _cEl = $(_cardHtml).find('.card');
      _cEl.addClass('card-loading');
      $('#featuredReviewsGrid').append(_cardHtml);
      setTimeout(function () {
        _cEl.removeClass('card-loading');
      }, i * 100);
    });
    $('[data-lazy-image]').Lazy({
      effect: 'fadeIn',
      visibleOnly: false,
      asyncLoader: function (element, response) {
        setTimeout(function () {
          element.css({
            'background-image': 'url(' + element.data('lazyImage') + ')',
          });
          response(true);
        }, 300);
      }
    });
  });

}

$(document).ready(function () {
  init();
});

import 'jquery-lazy';
import {getReviews} from '../../middleware/api.service';
import {renderCard} from "../../components/card/card";

(function ($) {

  getReviews({
    container: '#featuredReviewsGrid',
    type: 'review'
  }, function (reviews) {
    $.each(reviews, function (i, review) {
      let _cardHtml = renderCard(review, {
        type: 'review'
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
      },
      customLoaderName: function (element) {
        console.log(element)
      },
    });
  });

})(jQuery);

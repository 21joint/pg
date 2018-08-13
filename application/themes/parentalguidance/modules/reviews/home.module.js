import {getReviews} from '../../services/api.service';
import {renderCard} from "../../components/card/card";

// _reviews.data = res.body.Results;
// _reviews.html = '';
// _reviews.cards = [];
//

(function ($) {

  getReviews({
    container: '#featuredReviewsGrid',
    type: 'review'
  }, function (reviews) {
    $.each(reviews, function (i, review) {
      console.info(`Review :${i}.`, review);
      let _cardHtml = renderCard(review, {
        type: 'review'
      });
      let _cEl = $(_cardHtml).find('.card');
      _cEl.addClass('card-loading');
      $('#featuredReviewsGrid').append(_cardHtml);
      setTimeout(function () {
        _cEl.removeClass('card-loading');
      }, i * 100);
    })
  });

})(jQuery);

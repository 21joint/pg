import Reviews from '../../services/reviews.service';
import jQuery from 'jquery';


(function ($) {
  $(document).ready(function () {

    Reviews.get({
      container: '#featuredReviewsGrid'
    }, function (res) {
      $.each(res.cards, function (i, card) {
        $('#featuredReviewsGrid').append(card.$html);
        let _cEl = card.$html.find('.card');
        _cEl.addClass('card-loading');
        setTimeout(function () {
          _cEl.removeClass('card-loading');
        }, i * 100);
      })
    });
  });
})(jQuery);
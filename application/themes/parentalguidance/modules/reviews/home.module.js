import Api from '../../services/api.service';


(function ($) {

  Api.getReviews({
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

})(jQuery);

import {getGuides} from '../../middleware/api.service';


(function ($) {

  getGuides({
    container: '#guidesGrid',
    type: 'guide'
  }, function (res) {
    $.each(res.cards, function (i, card) {
      $('#guidesGrid').append(card.$html);
      let _cEl = card.$html.find('.card');
      _cEl.addClass('card-loading');
      setTimeout(function () {
        _cEl.removeClass('card-loading');
      }, i * 100);
    })
  });

})(jQuery);

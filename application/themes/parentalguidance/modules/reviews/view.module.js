import Api from '../../services/api.service';

import 'bootstrap/js/src/dropdown';

(function ($) {
  let revId = window.location.search.split('=')[1];

  Api.getReview({
    id: revId,
    container: '.prg-review--single'
  }, function (data) {
    console.log(data);
    $('.prg-review--single---descr').html(data.longDescription);
    $('.prg-review--single---title').text(data.title);
    $('.prg-review--single---heroimage').css('background-image', 'url(' + data.coverPhoto.photoURL + ')');
    $('.prg-review--single---gallery').html(function () {
      let _html = '';

      _html += '<ul class="d-flex flex-wrap list-unstyled">\n';

      for (let _photo = 0; _photo < data.reviewPhotos.length; ++_photo) {
        _html += '<li class="col-4 p-1"><div class="embed-responsive embed-responsive-1by1"><img class="embed-responsive-item" src="' + data.reviewPhotos[_photo].photoURL + '" alt="">\n';
        _html += '</div></li>\n';
      }
      _html += '</ul>\n';
      return _html;
    })
  })

})(jQuery);

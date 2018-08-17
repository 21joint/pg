import 'jquery-lazy';
import {getReview} from '../../middleware/api.service';
import {renderRate, renderRateInput} from "../../components/rating/rating";

(function ($) {
  let revId = window.location.pathname.slice(window.location.pathname.lastIndexOf('/') + 1);

  getReview({
    id: revId,
    container: '.prg-review--single'
  }, function (review) {
    console.log(review);
    $('[data-render]').each(function (i, el) {
      let _loader = new Function($(this).data('load')),
        _render = new Function($(this).data('render'));

      console.log(_loader())
      if (typeof _loader() === 'function') {
        $(el).html(_loader()(review));
      }
      else {
        $(el).html(_render);
      }
    });
  });

  function galleryLoader(review, options) {
    let _html = '';

    _html = `<ul class="d-flex flex-wrap list-unstyled m-0">`;

    _html += '<ul class="d-flex flex-wrap list-unstyled m-0">\n';

    for (let _photo = 0; _photo < review.reviewPhotos.length; ++_photo) {
      _html += '<li class="col-4 p-2"><div class="embed-responsive embed-responsive-1by1"><img class="embed-responsive-item lazy" data-lazy-image="' + review.reviewPhotos[_photo].photoURL + '" data-loader="asyncLoader" alt="">\n';
      _html += '</div></li>\n';
    }
    _html += '</ul>\n';
    return _html;
  }

  function buildItem(photo) {
    return `<li class="col-4 p-2">
                <div class="embed-responsive embed-responsive-1by1">
                  <img class="embed-responsive-item lazy" 
                        data-lazy-image="${photo.photoURL}" 
                        data-loader="asyncLoader" 
                        alt="Photo" />
                </div>
              </li>`;
  };

})(jQuery);

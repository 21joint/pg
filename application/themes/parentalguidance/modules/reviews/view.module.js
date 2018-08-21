import 'jquery-lazy';
import {getReview} from '../../middleware/api.service';

import {Gallery} from '../../components/gallery';

(function () {
  let revId = window.location.pathname.slice(window.location.pathname.lastIndexOf('/') + 1);
  getReview({
    id: revId,
    container: '.prg-review--single'
  }, function (review) {
    console.log(review);
    $('[data-render]').each(function (i, el) {
      let _loader, _render;

      _loader = new Function(`Gallery[${this.dataset.load}]`);
      _render = new Function(`Gallery[${this.dataset.render}]`);
      console.log(_loader());
      if (typeof _loader() === 'function') {
        $(el).html(Gallery._loader()(review.reviewPhotos));
      }
      else {
        $(el).html(_render);
      }
    });
  });

})();




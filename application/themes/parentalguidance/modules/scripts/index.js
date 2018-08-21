import {API_PROXY, OAUTH} from '../../../../../package';

import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/popover';
import 'select2';

$(document).ready(init);

function init() {


  $('[data-toggle="popover"]').popover({
    container: 'body'
  });

  $('select').each(function (i, el) {
    let $self = $(el);
    let options = {
      minimumResultsForSearch: -1,
      placeholder: $self.attr('placeholder')
    };

    if (!!$self.data('url')) {
      options.ajax = {
        url: API_PROXY + $self.data('url') + '?' + OAUTH,
        dataType: 'json',
        processResults: function (data) {
          // Tranforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data.body
          };
        }
      }
    }

    $self
      .select2(options)
      .on('select2:select', function (e) {
        // Do something
        console.log(e);
      });
  });

  $('.prg-hamburger--btn').on('click', function (e) {
    $('body').toggleClass('prg-nav--open');
  });

}

if (module.hot) {
  module.hot.accept()
}

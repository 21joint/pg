import './scss/styles.scss';
// VENDOR SCRIPTS
import 'popper.js';
import 'bootstrap/js/src/util';
import 'bootstrap/js/src/modal';
import 'bootstrap/js/src/dropdown';
import 'select2';
import './common';
// CORE JS
// import '../../modules/Core/externals/scripts/core';
// REVIEWS
import '../../modules/Sitelogin/index';
// import './widgets/reviews-home/index';
// import './widgets/reviews-view/index';
import '../../modules/Sdparentalguide/widgets/listing-rating/index';
import './reviews-home.module';



(function ($) {

  en4.prg = !!en4.prg ? en4.prg : {};
  $(document).ready(function () {
    $('select').each(function () {
      let $self = $(this);
      $self.select2({
        minimumResultsForSearch: -1,
        placeholder: $self.attr('placeholder')
      });
    });
    $('.prg-hamburger--btn').on('click', function (e) {
      $('body').toggleClass('prg-nav--open');
    });
  });

})(jQuery);

import '../../themes/guidanceguide/scss/styles.scss';
import './widgets/header/index';


(function ($) {
  $(document).ready(function () {
    $('select').each(function (i, sl) {
      $(sl).select2({
        minimumResultsForSearch: -1,
        placeholder: $(sl).attr('placeholder')
      });
    })
  });
  $(document).ajaxStart();
})(jQuery);
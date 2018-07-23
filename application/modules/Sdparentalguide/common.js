import '../../themes/guidanceguide/scss/styles.scss';
import './widgets/header/index';


(function (jQuery) {
  jQuery(document).ready(function () {
    jQuery('select').each(function (i, sl) {
      jQuery(sl).select2({
        minimumResultsForSearch: -1,
        placeholder: jQuery(sl).attr('placeholder')
      });
    })
  });
  jQuery('#prgAuthModal').on('hide.bs.modal', function (e) {
    window.location.href = jQuery(e.relatedTarget).attr('href');
  }).modal();
})(jQuery);
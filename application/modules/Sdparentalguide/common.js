import '../../themes/guidanceguide/scss/styles.scss';


(function (jQuery) {
  jQuery(document).ready(function () {
    jQuery('select').each(function (i, sl) {
      jQuery(sl).select2({
        minimumResultsForSearch: -1,
        placeholder: jQuery(sl).attr('placeholder')
      });
    });
    jQuery('.prg-hamburger--btn').on('click', function (e) {
      jQuery('body').toggleClass('prg-nav--open');
    });
  });

})(jQuery);
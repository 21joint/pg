import '../../themes/guidanceguide/scss/styles.scss';



(function ($) {
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
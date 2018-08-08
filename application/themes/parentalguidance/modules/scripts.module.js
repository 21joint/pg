(function ($) {

  en4.prg = !!en4.prg ? en4.prg : {};


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

})(jQuery);

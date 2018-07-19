(function($) {
  $(document).ready(function () {
    $('.prg-hamburger--btn').on('click', function(e) {
      $('body').toggleClass('prg-nav--open');
    })
  });
})(jQuery);
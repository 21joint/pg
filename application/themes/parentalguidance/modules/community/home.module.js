(function ($) {
// FAQ on click display question and transform plus to close
  $(".faq_toggle").each(function (index, toggle) {
    $(toggle).on('click', function (event) {
      $(event.target).parent().find('h4').toggleClass('text-primary');
      $(event.target).parent().parent().find(".faq_text").toggleClass("faq_text_disp");
      $(event.target).parent().toggleClass("mb-3");
      $(event.target).toggleClass("faq_transform");
    });
  });

})(jQuery)

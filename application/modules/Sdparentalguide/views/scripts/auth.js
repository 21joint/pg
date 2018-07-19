import jQuery from 'jquery';

jQuery(document).ready(function () {
  jQuery('#prgAuthModal').on('hide.bs.modal', function (e) {
    window.location.href = jQuery(e.relatedTarget).attr('href');
  }).modal();
});
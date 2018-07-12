import jQuery from 'jquery';


jQuery(document).ready(function() {
  jQuery('#reviewSingleModal').on('show.bs.modal', function (e) {
    var url = e.relatedTarget.dataset.call;
    jQuery.ajax({
      method: 'GET',
      url: url,
      success: function(res) {
        jQuery(e.relatedTarget).modal();
        console.log(JSON.parse(res));
      },
      error: (error) => {
        console.error(error);
      }
    });
  });
});
import jQuery from 'jquery';


jQuery(document).ready(function() {
  jQuery('#reviewSingleModal').on('show.bs.modal', function (e) {
    console.log(e.relatedTarget);
    e.preventDefault();
    var url = e.relatedTarget.dataset.call;
    jQuery.ajax({
      method: 'GET',
      url: url,
      success: function(res) {
        console.log(JSON.parse(res));
      }

    })
  });
});
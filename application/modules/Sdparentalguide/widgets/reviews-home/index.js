import jQuery from 'jquery';


jQuery(document).ready(function () {
  let $reviewSingleModal = jQuery('#reviewSingleModal');
  $reviewSingleModal.on('show.bs.modal', function (e) {
    let url = e.relatedTarget.dataset.call;
    $reviewSingleModal.addClass('modal-loading');
    jQuery.ajax({
      method: 'GET',
      url: url,
      success: function (res) {
        let data = JSON.parse(res);
        console.info(data);
        $reviewSingleModal
          .find('.prg-review--single---heroimage').css('background-image', 'url(' + data.body.Results[0].coverPhoto.photoURL + ')');
        $reviewSingleModal.find('.prg-review--single---gallery').html(function () {
          let _html = '';

          _html += '<ul class="row no-gutters">\n';

          for (let _photo = 0; _photo < data.body.Results[0].reviewPhotos.length; ++_photo) {
            _html += '<li class="col-4 p-1"><img class="img-fluid w-100" src="' + data.body.Results[0].reviewPhotos[_photo].photoURL + '" alt="">\n';
            _html += '</li>\n';
          }

          _html += '</ul>\n';
          return _html;
        })
      },
      error: function (error) {
        console.error(error);
      },
      complete: function () {
        $reviewSingleModal.removeClass('modal-loading');
      }
    });
  });
});
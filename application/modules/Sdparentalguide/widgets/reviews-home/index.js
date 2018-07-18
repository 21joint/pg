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

        let revData = JSON.parse(res).body.Results[0];
        console.log(revData);
        $reviewSingleModal.find('.prg-review--single---descr').html(revData.longDescription);
        $reviewSingleModal.find('.prg-review--single---title').text(revData.title);
        $reviewSingleModal.find('.prg-review--single---heroimage').css('background-image', 'url(' + revData.coverPhoto.photoURL + ')');
        $reviewSingleModal.find('.prg-review--single---gallery').html(function () {
          let _html = '';

          _html += '<ul class="d-flex flex-wrap list-unstyled">\n';

          for (let _photo = 0; _photo < revData.reviewPhotos.length; ++_photo) {
            _html += '<li class="col-4 p-1"><div class="embed-responsive embed-responsive-1by1"><img class="embed-responsive-item" src="' + revData.reviewPhotos[_photo].photoURL + '" alt="">\n';
            _html += '</div></li>\n';
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
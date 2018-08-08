import './rating.scss';

export default {
  build: function (rating, max) {
    let html = '';
    for (var i = 0; i < rating; i++) {
      html +=
        '<li class="list-inline-item align-middle">\n' +
        '<svg class="card-star--icon">\n' +
        '<use href="#prgStarIcon"></use>\n' +
        '</svg>\n' +
        '</li>'
    }
    if (max - rating > 0) {
      for (var j = 0; j < max - rating; j++) {
        html +=
          '<li class="list-inline-item align-middle">\n' +
          '<svg style="opacity: .4" class="card-star--icon">\n' +
          '<use href="#prgStarIcon"></use>\n' +
          '</svg>\n' +
          '</li>'
      }
    }
    return html;
  }
};

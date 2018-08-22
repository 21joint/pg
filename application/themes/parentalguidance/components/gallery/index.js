class Gallery {
  constructor(items) {
    this.items = items;
    this._render = photo =>
      `<li class="col-4 p-2">
          <div class="embed-responsive embed-responsive-1by1">
            <img class="embed-responsive-item lazy" 
                  data-lazy-image="${photo.photoURL}" 
                  data-loader="asyncLoader" 
                  alt="Photo" />
          </div>
        </li>`;
  }

  render(options) {
    let _html = '';

    _html += `<ul class="d-flex flex-wrap list-unstyled m-0">`;

    for (let j = 0; j < this.items.length; ++j) {
      let _photo = this.items[j];
      _html += this._render(_photo);
    }

    _html += `</ul>`;

    return _html;
  }
}

export {Gallery}


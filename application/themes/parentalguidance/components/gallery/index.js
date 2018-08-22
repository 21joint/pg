export class Gallery {
  constructor(items) {
    this.items = items;
  }

  static renderItem(item) {
    return `<li class="col-4 p-2">
          <div class="embed-responsive embed-responsive-1by1">
            <img class="embed-responsive-item lazy" 
                  data-lazy-image="${item.photoURL}" 
                  data-loader="asyncLoader" 
                  alt="Photo" />
          </div>
        </li>`
  }

  render(options) {
    let _html = '';

    _html += `<ul class="d-flex flex-wrap list-unstyled m-0">`;

    if (this.items.length <= 1) {

    }

    for (let j = 0; j < this.items.length; j++) {
      let item = this.items[j];
      _html += Gallery.renderItem(item);
    }

    _html += `</ul>`;

    return _html;
  }
}


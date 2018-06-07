function hoverBoxImage() {
  
  $$('.item_photo_user').each( function (element) {

      element.getParent().getParent().classList.add('d-inline-block')
      let elementHover = element.getParent().getParent().getParent().getElement('.extfox-widgets');
      let closeElement = elementHover.getElement('.close a');

      element.getParent().removeEvents('click').addEvent('click', function(e) {
        e.stop();

        hideHoverBoxes();
        
        if( elementHover.classList.contains('active') ) {
          elementHover.classList.remove('active');
        } else {
          elementHover.classList.add('active');
        }

        // get close buttons
        closeElement.removeEvents('click').addEvent('click', function(e) {
          elementHover.classList.remove('active');
        });

      });

      /* element.getParent().addEventListener("mouseenter", function( e ) {
        if( elementHover.classList.contains('active') ) {
          elementHover.classList.remove('active');
         } else {
          elementHover.classList.add('active');
        }
      }); */

  });
}

function hideHoverBoxes() {
  $$('.extfox-widgets.active').each( function (element) {
    element.classList.remove('active');
  });
}

window.addEvent('load', function () {
  hoverBoxImage();
});


var tab_content_id_extfox = 0;


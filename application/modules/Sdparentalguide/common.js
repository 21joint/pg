import jQuery from 'jquery';
import 'select2';


jQuery(document).ready(function () {
  jQuery('select').each(function (i, sl) {
    jQuery(sl).select2({
      minimumResultsForSearch: -1,
      placeholder: jQuery(sl).attr('placeholder')
    });
  })
});
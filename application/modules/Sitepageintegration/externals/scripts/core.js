	var Savevalues = function() {
		if( requestActive ) return;
		if( typeof manage_admin_formsubmit != 'undefined' && manage_admin_formsubmit == 1 ) {
			if($('resource_id').value == '' )
			{
				submitformajax = 1;
				return;
			}
			else
			{
				manage_admin_formsubmit = 1;
			}
		}
		requestActive = true;
		var pageurl = $('global_content').getElement('.global_form').action;
		if ($('subject') && pages_id) {
			$('subject').value = 'sitepage_page_' + pages_id;
			
		}
		currentValues = formElement.toQueryString();
		$('show_tab_content_child').innerHTML = '<center><img src="'+en4.core.staticBaseUrl+'application/modules/Sitepage/externals/images/spinner_temp.gif" /></center>';
		if (typeof page_url != 'undefined') {
			var param = (currentValues ? currentValues + '&' : '') + 'is_ajax=1&format=html&page_url=' + page_url;
		}
		else {
			var param = (currentValues ? currentValues + '&' : '') + 'is_ajax=1&format=html';
		}
		
		var request = new Request.HTML({
			url: pageurl,
			onSuccess :  function(responseTree, responseElements, responseHTML, responseJavaScript)  {
				if ($('show_tab_content')) {
					$('show_tab_content').innerHTML =responseHTML;
			}
			
			else if ($('show_tab_content_child')) {
				$('show_tab_content_child').innerHTML =responseHTML;
		}
		InitiateAction ();
		requestActive = false;
		if (window.activ_autosuggest) {
			activ_autosuggest ();
		}
	}
	});
				request.send(param);
 };


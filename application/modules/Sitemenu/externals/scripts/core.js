(function () {
  var $cPopup = $(`<div class="cookie-popup">
		<div class="cookie-popup--inner">
			<div class="container-fluid">
				<div class="flex-row align-items-center">
					<div class="flex-col">
						<div class="cookie-popup--massege">
							<p class="m-0">Cookies are used for measurement, ads and optimization. By continuing to use our site you agree to our <a href="https://www.visithoustontexas.com/privacy-policy/" target="_blank">privacy policy.</a></p>
						</div>
					</div>
					<div class="flex-col-auto">
						<div class="cookie-popup--close">
							<a class="btn btn-primary border-0 cookie-btn--close" href="#" data-sv-close-banner>accept</a>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>`),
    closePopup = function () {
      $cPopup.removeClass('revealed').delay(500).queue($cPopup.hide());
    };
  $('.cookie-popup .btn-close').on('click', function () {
    $cPopup.addClass('revealed');
  });

  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  if (!getCookie('bw__cookies')) {
    setCookie('bw__cookies', new Date().getTime(), '360')
  }
  else {
    $cPopup.addClass('revealed');
  }
})();

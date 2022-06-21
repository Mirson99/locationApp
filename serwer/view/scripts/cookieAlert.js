function setCookiesNotificationCookie() {

	var date = new Date();
	date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));

    document.cookie = "CookiesPolicyNotification=1; expires=" + date.toGMTString() + "; path=/";
}

function getCookieValue (name) {
  var cookie = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
  if (cookie) {
	  return cookie.pop();
  } else {
	  return '';
  }
}

if (getCookieValue('CookiesPolicyNotification') != 1) {
	window.addEventListener('load', function () {
		openmodal('cookieModal');
	});
}
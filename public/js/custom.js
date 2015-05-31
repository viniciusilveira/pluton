/**
 * Funções js do projeto
 */
(function($) {
	$.verifySettingsURL = function(url, controller, action) {
		var url_ajax = action;
		if (url.search("settings") != -1) {
			url_ajax = controller + "/" + action;
		}

		return url_ajax;
	}
})(jQuery);
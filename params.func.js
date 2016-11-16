	function getParams(script_name, param_name) {
		// Find all script tags
		var scripts = document.getElementsByTagName("script");
		// Look through them trying to find ourselves
		for (var i = 0; i < scripts.length; i++) {
			if (scripts[i].src.indexOf("/" + script_name) > -1) {
				// Get an array of key=value strings of params
				if (scripts[i].src.indexOf("?") == false) {
					return;
				}
				var pa = scripts[i].src.split("?").pop().split("&");

				var p = {};
				for (var j = 0; j < pa.length; j++) {
					var kv = pa[j].split("=");
					p[kv[0]] = kv[1];
				}
				return typeof (p[param_name]) !== 'undefined' ? p[param_name] : "";
			}
		}
		// No scripts match
		return "";
	}

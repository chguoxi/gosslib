<?php
	function getSummary($content){
		$partner[0] = '/&lt;\w{1,}&gt;/i';
		$partner[1] = '/&lt;\/\w{1,}&gt;/i';
		$partner[2] = '/&lt;\w{1,}\s*\/&gt;/i';
		$content = preg_replace($partner, '', $content);
		$content = strip_tags($content);
		$content = msubstr($content, 0, 255);
		return $content;
	}
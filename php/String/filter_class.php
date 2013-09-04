<?php 
class Filter {
	static $rules = array(
			'int'=>'/^[0-9]+$/i',
			'string'=>'/^[\S\s]+$/i',
			'boolean'=>'/^(0|1)$/i',
			'date'=>'/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/i',
			'time'=>'/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/i',
			'timestamp'=>'/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/i',
			'mail'=>'/^[a-z0-9_\-\.]+@[a-z0-9]+[a-z0-9\-\.]+[a-z]{2,3}$/i',
			'domain'=>'/^(([a-z0-9]{1}[a-z0-9\-]*[a-z0-9]{1}\.|[a-z]{1}\.))+[a-z]{2,3}$/i',
			'phone'=>'/^[0-9]+\([0-9]+\)[0-9]+$/i'
	);
	static $filtered = array();
	public function addData(Array $data) {
		$filtered=Array();
		foreach($data as $type) {
			if(strtoupper($type)=="POST") $T=$_POST;
			if(strtoupper($type)=="GET") $T=$_GET;
			if(strtoupper($type)=="REQUEST") $T=$_REQUEST;
			if(strtoupper($type)=="COOKIE") $T=$_COOKIE;
			foreach($T as $key=>$value) {
				if(eregi('_',$key)) {
					$val=explode("_",$key);
					$filterType=$val[0];
					if(self::check($filterType,$value)) {
						self::$filtered[strtolower($type)][$key]=$value;
					}
				}
			}
		}
	}
	public function addRule($name,$regExp) {
		self::$rules[$name]=$regExp;
	}
	public function get($n) {
		if(array_key_exists('get',self::$filtered)) {
			if(array_key_exists($n,self::$filtered['get'])) {
				return self::$filtered['get'][$n];
			} else {
				return false;
			}
		}
	}
	public function post($n) {
		if(array_key_exists('get',self::$filtered)) {
			if(array_key_exists($n,self::$filtered['post'])) {
				return self::$filtered['post'][$n];
			} else {
				return false;
			}
		}
	}
	public function request($n) {
		if(array_key_exists('get',self::$filtered)) {
			if(array_key_exists($n,self::$filtered['request'])) {
				return self::$filtered['request'][$n];
			} else {
				return false;
			}
		}
	}
	public function cookie($n) {
		if(array_key_exists('get',self::$filtered)) {
			if(array_key_exists($n,self::$filtered['cookie'])) {
				return self::$filtered['cookie'][$n];
			} else {
				return false;
			}
		}
	}
	private function check($filterType,$value) {
		if(array_key_exists($filterType,self::$rules))
			return preg_match(self::$rules[$filterType],$value);
	}
}


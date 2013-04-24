<?php
/**
 * 要查询的电话号码归属地
 * http://www.baidu.com/api.php?m=Tel&p=13281098058
 * {"province":"\u56db\u5ddd","city":"\u6210\u90fd","supplier":"\u8054\u901a"}
 * supplier 运营商 city 城市 province 省份
 * Some rights reserved：abc3210.com
 * Contact email:admin@abc3210.com
 */
class Mobile {
    public function index() {
		$mobile = $_GET['p'];  //要查询的电话号码
		$ko = $_GET['ko'];  //要查询的端口 1 为淘宝，2为财付通
		$data='';
		if($mobile){
		   if($ko==1){
			  $data=$this->taobao($mobile);
		   }else{
		      $data=$this->getPhoneInfo($mobile);
		   }
		}
		exit($data);
    }
	
	public function taobao($mobile=0){//淘宝接口
		$url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobile."&t=".time();  //根据淘宝的数据库调用返回值
		$content = file_get_contents($url);
		$data['province'] = substr($content, "56", "4");  //截取字符串
		$data['supplier'] = substr($content, "81", "4");
		return '{"province":"'.$data['province'].'","supplier":"'.$data['supplier'].'"}';
	}
	
	public function getPhoneInfo($mobile=0){//财付通接口
	    $doc = new DOMDocument(); 
		$xmlurl='http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile='.$mobile.'&f.xml';
		$doc->load($xmlurl); //读取xml文件 
		$xmls = $doc->getElementsByTagName("root"); //取得root标签的对象数组 
		foreach( $xmls as $xml ) { 
			$province = $xml->getElementsByTagName( "province" ); //省份
			$data['province'] = $this->delspace($province->item(0)->nodeValue); //省份
			$city = $xml->getElementsByTagName( "city" ); 
			$data['city']= $this->delspace($city->item(0)->nodeValue); //城市
			$supplier = $xml->getElementsByTagName( "supplier" ); 
			$data['supplier'] = $this->delspace($supplier->item(0)->nodeValue); //联通 移动 电信
		}
		return $data;
	}
	
	public function getPhoneCity($mobile=0){
		$phoneInfo = $this->getPhoneInfo($mobile);
		return $phoneInfo['city'];
	}
	//过滤空格回车
	private function delspace($pcon){
		 $pcon = preg_replace("/ /","",$pcon);
		 $pcon = preg_replace("/&nbsp;/","",$pcon);
		 $pcon = preg_replace("/　/","",$pcon);
		 $pcon = preg_replace("/\r\n/","",$pcon);
		 $pcon = str_replace(chr(13),"",$pcon);
		 $pcon = str_replace(chr(10),"",$pcon);
		 $pcon = str_replace(chr(9),"",$pcon);
		 return $pcon;
	}
	
}
?>

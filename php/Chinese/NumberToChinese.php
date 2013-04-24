<?php
/**
 * 数字转换为中文
 * @author goss,<goss@messoft.com.cn>
 */
class NumberToChinese {
	/**
	 * 数字转中文
	 * @param integer $num
	 * @param integer $m
	 * @return string
	 */
	public static function N2C($num, $m = 1) {
		switch($m) {
			case 0:
				$CNum = array(
				array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖'),
				array('','拾','佰','仟'),
				array('','萬','億','萬億')
				);
				break;
			default:
				$CNum = array(
				array('零','一','二','三','四','五','六','七','八','九'),
				array('','十','百','千'),
				array('','万','亿','万亿')
				);
				break;
		}
		
		if (is_integer ( $num )) {
			$int = ( string ) $num;
		} else if (is_numeric ( $num )) {
			$num = explode ( '.', ( string ) floatval ( $num ) );
			$int = $num [0];
			$fl = isset ( $num [1] ) ? $num [1] : FALSE;
		}
		// 长度
		$len = strlen ( $int );
		// 中文
		$chinese = array ();
		// 反转的数字
		$str = strrev ( $int );
		for($i = 0; $i < $len; $i += 4) {
			$s = array (
					0 => $str [$i],
					1 => $str [$i + 1],
					2 => $str [$i + 2],
					3 => $str [$i + 3] 
			);
			$j = '';
			// 千位
			if ($s [3] !== '') {
				$s [3] = ( int ) $s [3];
				if ($s [3] !== 0) {
					$j .= $CNum [0] [$s [3]] . $CNum [1] [3];
				} else {
					if ($s [2] != 0 || $s [1] != 0 || $s [0] != 0) {
						$j .= $CNum [0] [0];
					}
				}
			}
			// 百位
			if ($s [2] !== '') {
				$s [2] = ( int ) $s [2];
				if ($s [2] !== 0) {
					$j .= $CNum [0] [$s [2]] . $CNum [1] [2];
				} else {
					if ($s [3] != 0 && ($s [1] != 0 || $s [0] != 0)) {
						$j .= $CNum [0] [0];
					}
				}
			}
			// 十位
			if ($s [1] !== '') {
				$s [1] = ( int ) $s [1];
				if ($s [1] !== 0) {
					$j .= $CNum [0] [$s [1]] . $CNum [1] [1];
				} else {
					if ($s [0] != 0 && $s [2] != 0) {
						$j .= $CNum [0] [$s [1]];
					}
				}
			}
			// 个位
			if ($s [0] !== '') {
				$s [0] = ( int ) $s [0];
				if ($s [0] !== 0) {
					$j .= $CNum [0] [$s [0]] . $CNum [1] [0];
				} else {
					
					// $j .= $CNum[0][0];
				}
			}
			
			$j .= $CNum [2] [$i / 4];
			array_unshift ( $chinese, $j );
		}
		$chs = implode ( '', $chinese );
		if ($fl) {
			$chs .= '点';
			for($i = 0, $j = strlen ( $fl ); $i < $j; $i ++) {
				$t = ( int ) $fl [$i];
				$chs .= $str [0] [$t];
			}
		}
		return $chs;
	}
	
	/**
	 * 中文大写数字转换为数字
	 * @param string $str
	 * @return integer
	 */
	public static function C2N($str = '') {
		$unit = array (
				'亿' => 100000000,
				'万' => 10000,
				'千' => 1000,
				'仟' => 1000,
				'百' => 100,
				'十' => 10 
		);
		$num = array (
				'一' => 1,
				'二' => 2,
				'三' => 3,
				'四' => 4,
				'五' => 5,
				'六' => 6,
				'七' => 7,
				'八' => 8,
				'九' => 9 
		);
		$str = str_replace ( array_keys ( $num ), $num, $str );
		$result = array ();
		$number = '';
		preg_match_all ( '/[0-9]千[0-9]百[0-9]十[0-9]|[0-9]百[0-9]十[0-9]|[0-9]十[0-9]|[0-9]/ism', $str, $pnum );
		foreach ( $pnum [0] as $val ) {
			$tmp = '';
			for($i = 0; $i < mb_strlen ( $val, 'utf-8' ); $i ++) {
				$s = mb_substr ( $val, $i, 1, 'utf-8' );
				if (! is_numeric ( $s )) {
					$k = $unit [$s];
					if (strlen ( $tmp ) >= strlen ( $k )) {
						preg_match ( '/([0-9]*)([0-9]{' . (strlen ( $k ) - 1) . '})([0-9])/ism', $tmp, $n );
						$tmp = ($n [1] + $n [3]) . $n [2];
					} else {
						$tmp = $tmp * $k;
					}
				} else if ($i == (mb_strlen ( $val, 'utf-8' ) - 1)) {
					$tmp += $s;
				} else {
					$tmp .= $s;
				}
			}
			$nnum [] = $tmp;
		}
		$result = str_replace ( array_keys ( $unit ), ';', str_replace ( $pnum [0], $nnum, $str ) );
		foreach ( explode ( ';', $result ) as $val ) {
			$number .= sprintf ( '%04d', $val );
		}
		return sprintf ( '%2u', $number );
	}
	
}

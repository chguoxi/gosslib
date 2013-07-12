<?php
/**
 * goss�����,��ȡ�ļ����е��ļ�
 * 
 * ��ȡ�ļ�����ָ�����͵��ļ�,�����ļ����е����ļ��в���
 * 
 * @author goss,<goss@lunluoren.com>
 * @copyright	Copyright (c) 2006 - 2012, Lunluoren.com
 * @param string $dir Ŀ���ļ���
 * @param array $dest_type ��Ҫ��ȡ���ļ���׺
 * @return array
 */

function read_dir($dir, $dest_type=array('jpg','png','gif')){
	$files = glob($dir.'/*');
	if (!isset($destfile)){
		$destfile = array();
	}
	if (count($dest_type) == 0 || empty($dest_type)){
		$dest_type = array('*');
	}
	foreach ($files as $file){
		if (is_dir($file)){
			$sub_destfile = read_dir($file,$dest_type);
			if (is_array($sub_destfile)){
				$destfile = array_merge($sub_destfile,$destfile);
			}
		}
		else{
			$info = pathinfo($file);
			$extend = strtolower($info["extension"]);
			if ( in_array($extend, $dest_type) || array_shift($dest_type)=='*' ){
				array_push($destfile, $file);
			}
			else {
				continue;
			}
		}
	}
	return $destfile;
}
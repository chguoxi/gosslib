<?php
/**
 * goss代码库,提取文件夹中的文件
 * 
 * 提取文件夹中指定类型的文件,遍历文件夹中的子文件夹查找
 * 
 * @author goss,<goss@lunluoren.com>
 * @copyright	Copyright (c) 2006 - 2012, Lunluoren.com
 * @param string $dir 目标文件夹
 * @param array $dest_type 需要提取的文件后缀
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
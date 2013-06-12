<?
class upload
{
	var $directory_name;
	var $max_filesize;
	var $error;

	var $user_tmp_name;
	var $user_file_name;
	var $user_file_size;
	var $user_file_type;
	var $user_full_name;
	var $thumb_name;
	
	public function set_directory($dir_name = ".") {
		$this->directory_name = $dir_name;
	}
	
	public function set_max_size($max_file = 300000) {
		$this->max_filesize = $max_file;
	}
	
	public function error() {
		return $this->error;
	}
	
	public function is_ok() {
		if (isset ( $this->error ))
			return FALSE;
		else
			return TRUE;
	}
	
	public function set_tmp_name($temp_name) {
		$this->user_tmp_name = $temp_name;
	}
	
	public function set_file_size($file_size) {
		$this->user_file_size = $file_size;
	}
	
	public function set_file_type($file_type) {
		$this->user_file_type = $file_type;
	}

 	public function set_file_name($file)
	{
		$this->user_file_name = $file;
		$this->user_full_name = $this->directory_name."/".$this->user_file_name;
	}
	
	public function resize($max_width = 0, $max_height = 0) {
		if (preg_match ( "/\.png$/", $this->user_full_name )) {
			$img = imagecreatefrompng ( $this->user_full_name );
		}
		
		if (preg_match ( "/\.(jpg|jpeg)$/", $this->user_full_name )) {
			$img = imagecreatefromjpeg ( $this->user_full_name );
		}
		
		if (preg_match ( "/\.gif$/", $this->user_full_name )) {
			$img = imagecreatefromgif ( $this->user_full_name );
		}
		
		$FullImage_width = imagesx ( $img );
		$FullImage_height = imagesy ( $img );
		
		if (isset ( $max_width ) && isset ( $max_height ) && $max_width != 0 && $max_height != 0) {
			$new_width = $max_width;
			$new_height = $max_height;
		} else if (isset ( $max_width ) && $max_width != 0) {
			$new_width = $max_width;
			$new_height = (( int ) ($new_width * $FullImage_height) / $FullImage_width);
		} else if (isset ( $max_height ) && $max_height != 0) {
			$new_height = $max_height;
			$new_width = (( int ) ($new_height * $FullImage_width) / $FullImage_height);
		} else {
			$new_height = $FullImage_height;
			$new_width = $FullImage_width;
		}
		
		$full_id = imagecreatetruecolor ( $new_width, $new_height );
		// Check transparent gif and pngs
		if (preg_match ( "/\.png$/", $this->user_full_name ) || preg_match ( "/\.gif$/", $this->user_full_name )) {
			$trnprt_indx = imagecolortransparent ( $img );
			$trnprt_color = imagecolorsforindex ( $img, $trnprt_indx );
			$trnprt_indx = imagecolorallocate ( $full_id, $trnprt_color ['red'], $trnprt_color ['green'], $trnprt_color ['blue'] );
			imagefill ( $full_id, 0, 0, $trnprt_indx );
			imagecolortransparent ( $full_id, $trnprt_indx );
		}
		imagecopyresampled ( $full_id, $img, 0, 0, 0, 0, $new_width, $new_height, $FullImage_width, $FullImage_height );
		
		if (preg_match ( "/\.(jpg|jpeg)$/", $this->user_full_name )) {
			$full = imagejpeg ( $full_id, $this->user_full_name, 100 );
		}
		
		if (preg_match ( "/\.png$/", $this->user_full_name )) {
			$full = imagepng ( $full_id, $this->user_full_name );
		}
		
		if (preg_match ( "/\.gif$/", $this->user_full_name )) {
			$full = imagegif ( $full_id, $this->user_full_name );
		}
		imagedestroy ( $full_id );
		unset ( $max_width );
		unset ( $max_height );
	}
	
	public function start_copy() {
		if (! isset ( $this->user_file_name ))
			$this->error = "You must define filename!";
		
		if ($this->user_file_size <= 0)
			$this->error = "File size error (0): $this->user_file_size KB<br>";
		
		if ($this->user_file_size > $this->max_filesize)
			$this->error = "File size error (1): $this->user_file_size KB<br>";
		
		if (! isset ( $this->error )) {
			$filename = basename ( $this->user_file_name );
			
			if (! empty ( $this->directory_name ))
				$destination = $this->user_full_name;
			else
				$destination = $filename;
			
			if (! is_uploaded_file ( $this->user_tmp_name ))
				$this->error = "File " . $this->user_tmp_name . " is not uploaded correctly.";
			
			if (! @move_uploaded_file ( $this->user_tmp_name, $destination ))
				$this->error = "Impossible to copy " . $this->user_file_name . " from $userfile to destination directory.";
		}
	}
	
	public function set_thumbnail_name($thumbname) {
		if (preg_match ( "/\.png$/", $this->user_full_name ))
			$this->thumb_name = $this->directory_name . "/" . $thumbname . ".png";
		if (preg_match ( "/\.(jpg|jpeg)$/", $this->user_full_name ))
			$this->thumb_name = $this->directory_name . "/" . $thumbname . ".jpg";
		if (preg_match ( "/\.gif$/", $this->user_full_name ))
			$this->thumb_name = $this->directory_name . "/" . $thumbname . ".gif";
	}
	
	public function create_thumbnail() {
		if (! copy ( $this->user_full_name, $this->thumb_name )) {
			echo "<br>" . $this->user_full_name . ", " . $this->thumb_name . "<br>";
			echo "failed to copy $file...<br />\n";
		}
	}
	
	public function set_thumbnail_size($max_width = 0, $max_height = 0) {
		if (preg_match ( "/\.png$/", $this->thumb_name )) {
			$img = ImageCreateFromPNG ( $this->thumb_name );
		}
		
		if (preg_match ( "/\.(jpg|jpeg)$/", $this->thumb_name )) {
			$img = ImageCreateFromJPEG ( $this->thumb_name );
		}
		
		if (preg_match ( "/\.gif$/", $this->thumb_name )) {
			$img = ImageCreateFromGif ( $this->thumb_name );
		}
		
		$FullImage_width = imagesx ( $img );
		$FullImage_height = imagesy ( $img );
		
		if (isset ( $max_width ) && isset ( $max_height ) && $max_width != 0 && $max_height != 0) {
			$new_width = $max_width;
			$new_height = $max_height;
		} else if (isset ( $max_width ) && $max_width != 0) {
			$new_width = $max_width;
			$new_height = (( int ) ($new_width * $FullImage_height) / $FullImage_width);
		} else if (isset ( $max_height ) && $max_height != 0) {
			$new_height = $max_height;
			$new_width = (( int ) ($new_height * $FullImage_width) / $FullImage_height);
		} else {
			$new_height = $FullImage_height;
			$new_width = $FullImage_width;
		}
		$full_id = ImageCreateTrueColor ( $new_width, $new_height );
		
		// Check transparent gif and pngs
		if (preg_match ( "/\.png$/", $this->user_full_name ) || preg_match ( "/\.gif$/", $this->user_full_name )) {
			$trnprt_indx = imagecolortransparent ( $img );
			$trnprt_color = imagecolorsforindex ( $img, $trnprt_indx );
			$trnprt_indx = imagecolorallocate ( $full_id, $trnprt_color ['red'], $trnprt_color ['green'], $trnprt_color ['blue'] );
			imagefill ( $full_id, 0, 0, $trnprt_indx );
			imagecolortransparent ( $full_id, $trnprt_indx );
		}
		ImageCopyResampled ( $full_id, $img, 0, 0, 0, 0, $new_width, $new_height, $FullImage_width, $FullImage_height );
		
		if (preg_match ( "/\.(jpg|jpeg)$/", $this->thumb_name )) {
			$full = ImageJPEG ( $full_id, $this->thumb_name, 100 );
		}
		
		if (preg_match ( "/\.png$/", $this->thumb_name )) {
			$full = ImagePNG ( $full_id, $this->thumb_name );
		}
		
		if (preg_match ( "/\.gif$/", $this->thumb_name )) {
			$full = ImageGIF ( $full_id, $this->thumb_name );
		}
		ImageDestroy ( $full_id );
		unset ( $max_width );
		unset ( $max_height );
	}
}
?>

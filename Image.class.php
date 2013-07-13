function img_resize($src, $dest, $width, $height){
  $rgb = 0xFFFFFF;
  $quality = 100;
  $new_size = 100;
  $xratio = 100;
  $yratio = 100;
  
  if (!file_exists($src)){
    return false;
  } elseif (($size = getimagesize($src)) === false){
  	return false;
  }

  if($size['1']>$height)$xratio=$height/$size['1']*100;
  if($size['0']>$width)$yratio=$width/$size['0']*100;
  $new_size = min($xratio, $yratio);

  if (!function_exists($icfunc = 'imagecreatefrom'.strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1)))){
  	return false;
  }

  $new_size    = (($new_size > 100) ? 40 : $new_size);
  $width       = $size[0] * $new_size / 100;
  $height      = $size[1] * $new_size / 100;
  $x_ratio     = $width / $size[0];
  $y_ratio     = $height / $size[1];
  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);
  $new_width   = ($use_x_ratio  ? $width  : floor($size[0] * $ratio));
  $new_height  = (!$use_x_ratio ? $height : floor($size[1] * $ratio));
  $new_left    = ($use_x_ratio  ? 0 : floor(($width - $new_width) / 2));
  $new_top     = (!$use_x_ratio ? 0 : floor(($height - $new_height) / 2));
  $isrc        = $icfunc($src);
  
  
	if($size['mime']=="image/png"){
		$src = imagecreatefrompng($src);
		/*do {
        	$r = rand(0, 255);
        	$g = rand(0, 255);
        	$b = rand(0, 255);
        }while(imagecolorexact($src, $r, $g, $b) < 0);
		imagecolorexact($src, $r, $g, $b);*/
		$r = 255; $g = 255; $b = 255;
		$idest = imagecreatetruecolor($width, $height);
		$alphacolor = imagecolorallocatealpha($idest, $r, $g, $b, 127);
		imagealphablending($idest, false);
		imagesavealpha($idest, true);
		//imagefilledrectangle($idest, 0, 0, $width, $height, $alphacolor);
		//imagefill($idest, 0, 0, $alphacolor);
		
	  	imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
  		if($dest){
			imagepng($idest, $dest);
		}else{
			header("Content-Type:".$size['mime']);
			imagepng($idest);
		}
	}else{
		$idest = imagecreatetruecolor($width, $height);
		imagefill($idest, 0, 0, $rgb);
		imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
		if($dest){
			imagejpeg($idest, $dest, $quality);
		}else{
			header("Content-Type:".$size['mime']);
			imagejpeg($idest);
		}
	}
  
	imagedestroy($isrc);
	imagedestroy($idest);
	return true;
}

function img_crop($src, $dest, $width, $height,$left_top=""){
  $rgb = 0xFFFFFF;
  $quality = 100;
  $new_size = 100;
  $xratio = 100;
  $yratio = 100;
  
  if (!file_exists($src)){
  	return false;
  } elseif (($size = getimagesize($src)) === false){
  	return false;
  }
  if($size[0]==$width&&$size[1]==$height){
	  copy($src, $dest);
	  return true;  
  }
  if($size['1']>$width)$xratio=$width/$size['1']*100;
  if($size['0']>$height)$yratio=$height/$size['0']*100;
  $new_size = max($xratio, $yratio);
  if (!function_exists($icfunc = 'imagecreatefrom'.strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1)))){
  	return false;
  }

  $new_size    = (($new_size > 100) ? 40 : $new_size);

  $x_ratio     = $width / $size[0];
  $y_ratio     = $height / $size[1];
  $ratio       = max($x_ratio, $y_ratio);
  //if($ratio>1)$ratio=1;
  $use_x_ratio = ($x_ratio == $ratio);
  $new_width   = ($use_x_ratio  ? $width  : floor($size[0] * $ratio));
  $new_height  = (!$use_x_ratio ? $height : floor($size[1] * $ratio));
  if(!$left_top){
	  $new_left    = ($use_x_ratio  ? 0 : floor(($width - $new_width) / 2));
	  $new_top     = (!$use_x_ratio ? 0 : floor(($height - $new_height) / 2));
  }else{
  	  $new_left = 0;
	  $new_top = 0;
  }
  $isrc        = $icfunc($src);
  
  if($size['mime']=="image/png"){
		$src = imagecreatefrompng($src);
		do {
        	$r = rand(0, 255);
        	$g = rand(0, 255);
        	$b = rand(0, 255);
        }while(imagecolorexact($src, $r, $g, $b) < 0);
		$idest = imagecreatetruecolor($width, $height);
		$alphacolor = imagecolorallocatealpha($idest, $r, $g, $b, 127);
		imagealphablending($idest, false);
		imagesavealpha($idest, true);
		//imagefilledrectangle($idest, 0, 0, $width, $height, $alphacolor);
		//imagefill($idest, 0, 0, $alphacolor);
		
	  	imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
  		if($dest){
			imagepng($idest, $dest);
		}else{
			header("Content-Type:".$size['mime']);
			imagepng($idest);
		}
	}else{
		$idest = imagecreatetruecolor($width, $height);
		imagefill($idest, 0, 0, $rgb);
		imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
		if($dest){
			imagejpeg($idest, $dest, $quality);
		}else{
			header("Content-Type:".$size['mime']);
			imagejpeg($idest);
		}
	}
  imagedestroy($isrc);
  imagedestroy($idest);
return true;
}


function html2rgb($color){
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

function imageCreateCorners($source, $dest, $param, $type="") {
  if(!is_array($source)){
		# test source image
		if (file_exists($source)) {
			$res = is_array($info = getimagesize($source));
		} 
		else $res = false;
	
		# open image
		if ($res) {
			$w = $info[0];
			$h = $info[1];
			switch ($info['mime']) {
			case 'image/jpeg': $src = imagecreatefromjpeg($source);
				break;
			case 'image/gif': $src = imagecreatefromgif($source);
				break;
			case 'image/png': $src = imagecreatefrompng($source);
				break;
			default: 
				$res = false;
			}
		}
		# find unique color
      	do {
        	$r = rand(0, 255);
        	$g = rand(0, 255);
        	$b = rand(0, 255);
        }while(imagecolorexact($src, $r, $g, $b) < 0);
	}else{
		//$source = array('color','x','y');
		$rgb = html2rgb($source[0]);
		$r = $rgb[0]; $g = $rgb[1]; $b = $rgb[2];
		$w = $source[1];
		$h = $source[2];
		$src = imagecreate($w, $h);
		imagecolorallocate($src, $r, $g, $b);
		//imagefill($src);
		$res = true;
	}
    # create corners
    if ($res) {

      $q = 2; # change this if you want
      //$radius *= $q;

      $nw = $w*$q;
      $nh = $h*$q;

      $img = imagecreatetruecolor($nw, $nh);
      $alphacolor = imagecolorallocatealpha($img, $r, $g, $b, 127);
      imagealphablending($img, false);
      imagesavealpha($img, true);
      imagefilledrectangle($img, 0, 0, $nw, $nh, $alphacolor);

      imagefill($img, 0, 0, $alphacolor);
      imagecopyresampled($img, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
		
	  if($type=="radius"){
		  $radius = $param>1?$param * $q:$nw * $param;
		  imagearc($img, $radius-1, $radius-1, $radius*2, $radius*2, 180, 270, $alphacolor);
		  imagefilltoborder($img, 0, 0, $alphacolor, $alphacolor);
		  imagearc($img, $nw-$radius, $radius-1, $radius*2, $radius*2, 270, 0, $alphacolor);
		  imagefilltoborder($img, $nw-1, 0, $alphacolor, $alphacolor);
		  imagearc($img, $radius-1, $nh-$radius, $radius*2, $radius*2, 90, 180, $alphacolor);
		  imagefilltoborder($img, 0, $nh-1, $alphacolor, $alphacolor);
		  imagearc($img, $nw-$radius, $nh-$radius, $radius*2, $radius*2, 0, 90, $alphacolor);
		  imagefilltoborder($img, $nw-1, $nh-1, $alphacolor, $alphacolor); 
	  }else{
		  if(!is_array($param))$param = array($param, $param);
		  $cx = ($param[0]>1)?($param[0] * $q):($nw * $param[0]);
		  $cy = ($param[1]>1)?($param[1] * $q):($nh * $param[1]);
		  imageline($img, 0, $cy, $cx, 0, $alphacolor);
		  imagefilltoborder($img, 0, 0, $alphacolor, $alphacolor);
		  imageline($img, $nw - $cx, 0, $nw, $cy, $alphacolor);
		  imagefilltoborder($img, $nw, 0, $alphacolor, $alphacolor);
		  imageline($img, 0, $nh - $cy, $cx, $nh, $alphacolor);
		  imagefilltoborder($img, 0, $nh, $alphacolor, $alphacolor);
		  imageline($img, $nw - $cx, $nh, $nw, $nh - $cy, $alphacolor);
		  imagefilltoborder($img, $nw, $nh, $alphacolor, $alphacolor);
	  }
	  imagealphablending($img, true);
      imagecolortransparent($img, $alphacolor);

      # resize image down
      $res = imagecreatetruecolor($w, $h);
      imagealphablending($res, false);
      imagesavealpha($res, true);
      imagefilledrectangle($res, 0, 0, $w, $h, $alphacolor);
      imagecopyresampled($res, $img, 0, 0, 0, 0, $w, $h, $nw, $nh);

      # output image
      //$res = $dest;
      imagedestroy($src);
	  imagedestroy($img);
	}
	if(!$dest){
		header("Content-type: image/png");
		imagepng($res);
	}else imagepng($res,$dest);
	return;
}

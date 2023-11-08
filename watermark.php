<?php

	set_time_limit(999999999);
	ini_set('max_execution_time', 30000);  //sec
	
	
function thumbmaker($imgSrc, $imgSrcOut, $thumbnail_width, $thumbnail_height)
{
	//getting the image dimensions
	list($width_orig, $height_orig) = getimagesize($imgSrc);
	$myImage    = imagecreatefromjpeg($imgSrc);
	$ratio_orig = $width_orig / $height_orig;
	if ($thumbnail_width / $thumbnail_height > $ratio_orig)
	{
		$new_height = $thumbnail_width / $ratio_orig;
		$new_width  = $thumbnail_width;
	}
	else
	{
		$new_width  = $thumbnail_height * $ratio_orig;
		$new_height = $thumbnail_height;
	}
	$x_mid   = $new_width / 2; //horizontal middle
	$y_mid   = $new_height / 2; //vertical middle
	$process = imagecreatetruecolor(round($new_width), round($new_height));
	imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
	$thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
	imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
	imagedestroy($process);
	imagedestroy($myImage);
	imagejpeg($thumb, $imgSrcOut, 90);
}





function quality($imgSrc, $imgSrcOut, $thumbnail_width,$thumbnail_height,$quality)
{
		set_time_limit(9999999);
	ini_set('memory_limit', '2048M');
	// $thumbnail_width = 0;
	// $thumbnail_height = 0;
	//getting the image dimensions
	list($width_orig, $height_orig) = getimagesize($imgSrc);
	$myImage    = imagecreatefromjpeg($imgSrc);
	$ratio_orig = $width_orig / $height_orig;
	if ($thumbnail_width / $thumbnail_height > $ratio_orig)
	{
		$new_height = $thumbnail_width / $ratio_orig;
		$new_width  = $thumbnail_width;
	}
	else
	{
		$new_width  = $thumbnail_height * $ratio_orig;
		$new_height = $thumbnail_height;
	}
	//ha csak felezzuk a meretet
		$new_width  = $width_orig /2;
		$new_height = $height_orig/2;
	$x_mid   = $new_width / 2; //horizontal middle
	$y_mid   = $new_height / 2; //vertical middle
	$process = imagecreatetruecolor(round($new_width), round($new_height));
	imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
	$thumb = imagecreatetruecolor($width_orig, $height_orig);
	//imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
	imagedestroy($process);
	imagedestroy($myImage);
	imagejpeg($thumb, $imgSrcOut, $quality);
}


function resize($filename)
{
// File and new size
//$filename = 'test.jpg';
$percent = 0.5;

// Content type
//header('Content-Type: image/jpeg');

// Get new sizes
list($width, $height) = getimagesize($filename);
$newwidth = $width * $percent;
$newheight = $height * $percent;


// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
//imagejpeg($thumb);
if (($width>1200) or ($height>1200))
	imagejpeg($thumb, $filename, 90);
	imagedestroy($thumb);
}

function watermark($filename)
{
	set_time_limit(9999999);
	ini_set('memory_limit', '2048M');
	echo $filename . '<br>';
	$wm_file = 'onepixel.jph';
	//$filename = $_GET[ 'file' ];  // itt nem getbol jon a filenev
	//header('Content-type: image/jpeg');
	$watermark = imagecreatefrompng($wm_file);
	$image     = imagecreatefromjpeg($filename);
	//imagejpeg($image, NULL, 100);  ezzel lehet kiiratni a kepet, kell ele a header is vagy nem
	$img_width  = imagesx($image); //kep meret
	$img_height = imagesy($image);

	//echo $img_width .' - ' . $img_height;
	//exit;
	list($wm_width_e, $wm_height_e) = getimagesize($wm_file); // eredeti watermark meret kell az összenyomashoz
	$wm_width  = $img_width/2; // uj watermark meret   237  78
	$wm_height = $img_width/5;
	//$watermark_p = imagecreatetruecolor($wm_width, $wm_height);
	/*  egymas mellé rakja a vizjelet
	for ($h = 0; $h <= $img_height; $h += $wm_height)
	{
		for ($w = 0; $w <= $img_width; $w += $wm_width)
		{
			imagecopyresampled($image, $watermark, $w, $h, 0, 0, $wm_width, $wm_height, $wm_width, $wm_height);
		}
	}*/
	imagecopyresampled($image, $watermark, $img_width - $wm_width, $img_height - $wm_height, 0, 0, $wm_width, $wm_height, $wm_width_e, $wm_height_e);
	//imagejpeg($image, NULL, 100);  // ha csak megjelenitettni akarjuk akkor NULL
	imagejpeg($image, $filename, 90);
	imagedestroy($image);
}





function deldir($dirname)
{
	global $sz;
	global $tol;
	//watermark();
	if (is_dir($dirname)) // a megadott elérési út könyvtár-e
	{
		$dir_handle = opendir($dirname);
	}
	if (!$dir_handle)
	{
		return FALSE;
	}
	while ($file = readdir($dir_handle))
	{
		if ($file != "." && $file != ".." && $file[ 0 ] != ".")
		{
			if (!is_dir($dirname . "/" . $file)) // ha nem könyvtár akkor törli
			{
				//unlink($dirname . "/" . $file);
				//echo $dirname . "/" . $file . '<br>';
				$filenamei = $dirname . "/" . $file;
				$i         = strlen($filenamei);
				$fileext   = '';
				do
				{
					$i--;
					$fileext = $filenamei[ $i ] . $fileext;
				}
				while ($filenamei[ $i - 1 ] != '.');
				//echo $fileext;
				$fileext = strtolower($fileext);
				if (($fileext == 'jpg') or ($fileext == 'jpeg')or ($fileext == 'png'))
				{
					$sz++;
					if ($sz>$tol) {
					//	thumbmaker($filenamei,$filenamei,1080,1080);
						watermark_f17($filenamei);
					//	quality($filenamei,$filenamei,1080,1080,80);
					//	resize($filenamei);
					//thumbmaker_f17($filenamei,$dirname . "/thumb_" . $file,320, 400);
					}

					//echo $filenamei . '<br>';
					file_put_contents('log.txt', $sz);
				}
			}
			else
			{
				deldir($dirname . '/' . $file);
			} // ha könyvtár, meghívja saját magát és így tovább...
		}
	}
	closedir($dir_handle);

	//rmdir($dirname);
	return TRUE;
}

// a törlendő könyvtár neve/elérési útja
$tol = 0;  // erre azert van szukseg mert a serveren limitalva van a max script futas,
//es tobb ezer kepnel leall idokozonkent, ott emgnezzuk hol tartott a log alapjan es a tolt beallitjuk arra az ertekre, igy ott fojtatja ahol abba hagyta
$sz = 0;
deldir('.');

?>
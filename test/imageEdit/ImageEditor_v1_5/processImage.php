<?php
/*
processImage.php
Copyright (C) 2004-2006 Peter Frueh (http://www.ajaxprogrammer.com/)
Additional code contributions and modifications by David Fuller, Olli Jarva, and Simon Jensen

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

// required params: imageName

header("Content-Type: text/plain");

$originalDirectory = getcwd()."/original/";
$activeDirectory = getcwd()."/active/";
$editDirectory = getcwd()."/edit/";
$undoDirectory = getcwd()."/undo/";
$imageName = $_REQUEST["imageName"];
$action = $_REQUEST["action"];

if (empty($imageName) || !file_exists($originalDirectory.$imageName)) {
        echo "{imageFound:false}";
        exit;
}
if (!file_exists($editDirectory.$imageName)) {
        copy($originalDirectory.$imageName, $editDirectory.$imageName);
}
if (!file_exists($activeDirectory.$imageName)) {
        copy($originalDirectory.$imageName, $activeDirectory.$imageName);
}

if ($action == "undo") {
	if (file_exists($undoDirectory.$imageName)) {
		rename($editDirectory.$imageName, $undoDirectory.$imageName.".current");
		rename($undoDirectory.$imageName, $editDirectory.$imageName);
		rename($undoDirectory.$imageName.".current", $undoDirectory.$imageName);
	}
} else {
   	copy($editDirectory.$imageName, $undoDirectory.$imageName);
}

$fileInfo = pathinfo($editDirectory.$imageName);
$extension = $fileInfo['extension'];

switch($action){

	case "viewOriginal":
		copy($originalDirectory.$imageName, $editDirectory.$imageName);
		break;

	case "viewActive":
		copy($activeDirectory.$imageName, $editDirectory.$imageName);
		break;

	case "save":
		copy($editDirectory.$imageName, $activeDirectory.$imageName);
		break;

	case "resize": // additional required params: w, h
		$out_w = $_REQUEST["w"];
		$out_h = $_REQUEST["h"];
		if (!is_numeric($out_w) || $out_w < 1 || $out_w > 2000 || !is_numeric($out_h) || $out_h < 1 || $out_h > 2000) { exit; }
		list($in_w, $in_h) = getimagesize($editDirectory.$imageName);

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		$out = imagecreatetruecolor($out_w, $out_h);
		imagecopyresampled($out, $in, 0, 0, 0, 0, $out_w, $out_h, $in_w, $in_h);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($out, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($out,$editDirectory.$imageName);
		}
		if ($extension == "png") {
				imagepng($out,$editDirectory.$imageName);
		}
		imagedestroy($in);
		imagedestroy($out);
		break;

	case "rotate": // additional required params: degrees (90, 180 or 270)
		$degrees = $_REQUEST["degrees"];
		if (($degrees != 90 && $degrees != 180 && $degrees != 270)) { exit; }

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		if ($degrees == 180){
			$out = imagerotate($in, $degrees, 180);
		}else{ // 90 or 270
			$x = imagesx($in);
			$y = imagesy ($in);
			$max = max($x, $y);

			$square = imagecreatetruecolor($max, $max);
			imagecopy($square, $in, 0, 0, 0, 0, $x, $y);
			$square = imageRotate($square, $degrees, 0);

			$out = imagecreatetruecolor($y, $x);
			if ($degrees == 90) {
				imagecopy($out, $square, 0, 0, 0, $max - $x, $y, $x);
			} elseif ($degrees == 270) {
				imagecopy($out, $square, 0, 0, $max - $y, 0, $y, $x);
			}
			imagedestroy($square);
		}
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($out, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($out,$editDirectory.$imageName);
		}
		if ($extension == "png") {
				imagepng($out,$editDirectory.$imageName);
		}
		imagedestroy($in);
		imagedestroy($out);
		break;

	case "crop": // additional required params: x, y, w, h
		$x = $_REQUEST["x"];
		$y = $_REQUEST["y"];
		$w = $_REQUEST["w"];
		$h = $_REQUEST["h"];
		if (!is_numeric($x) || !is_numeric($y) || !is_numeric($w) || !is_numeric($h)) { exit; }

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		$out = imagecreatetruecolor($w, $h);
		imagecopyresampled($out, $in, 0, 0, $x, $y, $w, $h, $w, $h);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($out, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($out,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($out,$editDirectory.$imageName);
		}
		imagedestroy($in);
		imagedestroy($out);
		break;

	case "grayscale":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in,IMG_FILTER_GRAYSCALE);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "sepia":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_GRAYSCALE);
		imagefilter($in, IMG_FILTER_COLORIZE, 100, 50, 0);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "pencil":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_EDGEDETECT);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "emboss":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_EMBOSS);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "blur":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_GAUSSIAN_BLUR);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "smooth":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_SMOOTH, 5);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "invert":	// no additional params.

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_NEGATE);

		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

	case "brighten":	// param amt = amount to brighten (up or down)
		$amt = $_REQUEST['amt'];

		if ($extension == "jpg" || $extension == "jpeg") {
			$in = imagecreatefromjpeg($editDirectory.$imageName);
		}
		if ($extension == "gif") {
			$in = imagecreatefromgif($editDirectory.$imageName);
		}
		if ($extension == "png") {
			$in = imagecreatefrompng($editDirectory.$imageName);
		}
		imagefilter($in, IMG_FILTER_BRIGHTNESS, $amt);
		if ($extension == "jpg" || $extension == "jpeg") {
			imagejpeg($in, $editDirectory.$imageName, 100);
		}
		if ($extension == "gif") {
			imagegif($in,$editDirectory.$imageName);
		}
		if ($extension == "png") {
			imagepng($in,$editDirectory.$imageName);
		}
		imagedestroy($in);
		break;

}

list($w, $h) = getimagesize($editDirectory.$imageName);
echo '{imageFound:true,imageName:"'.$imageName.'",w:'.$w.',h:'.$h.'}';

?>
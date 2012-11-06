<?php
function create_thumb($img, $type, $maxsize, $output = false, $useexif = true) {
    set_time_limit ("60");
    ini_set('gd.jpeg_ignore_warning', 1);

    $maxsize = substr(ereg_replace("[^[:digit:]]", "", $maxsize),0,4); // fjern alt andet end tal og reducer til 4 tegn.

    $img_abs = $img;

    $filtype = getimagesize($img_abs);

    if ($filtype[2] == 2 && $useexif === true && function_exists('exif_thumbnail')) {
        $data = exif_thumbnail($img, $width, $height, $exifthumbtype);
        if ($data !== false && ($width >= $maxsize || $height >= $maxsize)) {
            if ($output !== false) {
                $fp = fopen($output, 'w');
                fputs($fp, $data);
                fclose($fp);
                $img_abs = $output;
                $img = $output;
                $filtype = getimagesize($img_abs);
                //return true;
            }
            else {
                print($data);
            }
        }
    }
 
    if ($filtype[2] == 3 && imagetypes() & IMG_PNG) { if (!$im = imagecreatefrompng($img_abs)) { return false; }}
    elseif ($filtype[2] == 2 && imagetypes() & IMG_JPEG) { if (!$im = imagecreatefromjpeg($img_abs)) { return false; }}
    elseif ($filtype[2] == 1 && imagetypes() & IMG_GIF) { if (!$im = imagecreatefromgif($img_abs)) { return false; }}
    else { return false; }


    $xsize=imagesx($im); // Find x- and y-size of image
    $ysize=imagesy($im);

    $forhold=$xsize/$ysize; // Find relation between the two.

    if ($ysize <= $maxsize && $xsize <= $maxsize) { $ynysize=$ysize; $xnysize=$xsize; }
    elseif ($ysize>$maxsize && $xsize<$maxsize+1) { $ynysize=$maxsize; $xnysize=$ynysize*$forhold; }
    elseif ($xsize>$maxsize && $ysize<$maxsize+1) { $xnysize=$maxsize; $ynysize=$xnysize/$forhold; }
    elseif ($ysize>$maxsize && $xsize>$maxsize) {
        if ($xsize>$ysize) { $xnysize=$maxsize; $ynysize=$xnysize/$forhold; }
        else { $ynysize=$maxsize; $xnysize=$ynysize*$forhold; }
    }

    $xnysize = floor($xnysize);
    $ynysize = floor($ynysize);

    if (in_array ("imagegd2",get_extension_funcs("gd"))) { // check if gd2 is loaded (the imagegd2 function exists)
        $thumb = ImageCreateTrueColor($xnysize, $ynysize);
        imagecopyresampled($thumb, $im, 0, 0, 0, 0, $xnysize, $ynysize, $xsize, $ysize);
    }
    else {
        $thumb = ImageCreate($xnysize, $ynysize);
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $xnysize, $ynysize, $xsize, $ysize);
    }

    ImageDestroy ($im);

    if ($type == "png") {
        if (isset($output)) {
            if (is_writable($output) || true) {
                if (!ImagePng($thumb, $output)) { return false; }
                else { return true; }
            }
            else { return false; }
        }
        else {
            ImagePng($thumb);
        }
    }

    elseif ($type == "gif") {
        if (isset($output)) {
            if (is_writable($output) || true) {
                if (!ImageGIF($thumb, $output)) { return false; }
                else { return true; }
            }
            else { return false; }
        }
        else {
            ImageGIF($thumb);
        }
    }

    elseif ($type == "jpg" || $type == "jpeg") {
        if (isset($output)) {
            if (is_writable($output) || true) {
                if (!ImageJPEG($thumb, $output)) { return false; }
                else { return true; }
            }
            else { return false; }
        }
        else {
            ImageJPEG($thumb);
        }
    }

    elseif ($type == "wbmp") {
        if (isset($output)) {
            if (is_writable($output) || true) {
                if (!ImageWBMP($thumb, $output)) { return false; }
                else { return true; }
            }
            else { return false; }
        }
        else {
            ImageWBMP($thumb);
        }
    }

    else {
        ImageDestroy ($thumb);
        return false;
    }
}
?>
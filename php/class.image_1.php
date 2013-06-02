
<?php
set_time_limit ("60");
ini_set('gd.jpeg_ignore_warning', 1);

class image
{

}
/*
	format
	quality
	resize_type
		max_w
		max_h
		max_wh
		strech
		percent
	out

*/



/*
header("Content-type: image/png");
imagePNG($imgh);
imageDestroy($img);
*/




?>



<?php
function create_thumb($img, $type, $maxsize, $output = false, $useexif = true)
{
    set_time_limit ("60");
    ini_set('gd.jpeg_ignore_warning', 1);

    $img_abs = $img;

    $filtype = getimagesize($img_abs);

    if ($filtype[2] == 2 && $useexif === true && function_exists('exif_thumbnail'))
    {
        $data = exif_thumbnail($img, $width, $height, $exifthumbtype);
        if ($data !== false && ($width >= $maxsize || $height >= $maxsize))
        {
            if ($output !== false)
            {
                $fp = fopen($output, 'w');
                fputs($fp, $data);
                fclose($fp);
                $img_abs = $output;
                $img = $output;
                $filtype = getimagesize($img_abs);
                //return true;
            }
            else
            {
                print($data);
            }
        }
    }

    if ($filtype[2] == 3 && imagetypes() & IMG_PNG)
    {
        if (!$im = imagecreatefrompng($img_abs))
            return false;
    }
    elseif ($filtype[2] == 2 && imagetypes() & IMG_JPEG)
    {
        if (!$im = imagecreatefromjpeg($img_abs))
            return false;
    }
    elseif ($filtype[2] == 1 && imagetypes() & IMG_GIF)
    {
        if (!$im = imagecreatefromgif($img_abs))
            return false;
    }
    else
    {
        return false;
    }


    $xsize = imagesx($im); // Find x- and y-size of image
    $ysize = imagesy($im);

    $forhold = $xsize / $ysize; // Find relation between the two.

    if ($ysize <= $maxsize && $xsize <= $maxsize)
    {
        $ynysize = $ysize;
        $xnysize = $xsize;
    }
    elseif ($ysize > $maxsize && $xsize < $maxsize+1)
    {
        $ynysize = $maxsize;
        $xnysize = $ynysize * $forhold;
    }
    elseif ($xsize > $maxsize && $ysize < $maxsize+1)
    {
        $xnysize = $maxsize;
        $ynysize = $xnysize / $forhold;
    }
    elseif ($ysize > $maxsize && $xsize > $maxsize)
    {
        if ($xsize > $ysize)
        {
            $xnysize = $maxsize;
            $ynysize = $xnysize / $forhold;
        }
        else
        {
            $ynysize = $maxsize;
            $xnysize = $ynysize * $forhold;
        }
    }

    $xnysize = floor($xnysize);
    $ynysize = floor($ynysize);

    if (in_array ("imagegd2", get_extension_funcs("gd")))// check if gd2 is loaded (the imagegd2 function exists)
    {
        $thumb = ImageCreateTrueColor($xnysize, $ynysize);
        imagecopyresampled($thumb, $im, 0, 0, 0, 0, $xnysize, $ynysize, $xsize, $ysize);
    }
    else {
        $thumb = ImageCreate($xnysize, $ynysize);
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $xnysize, $ynysize, $xsize, $ysize);
    }

    ImageDestroy ($im);

    if ($type == "png")
    {
        if (isset($output))
            return is_writable($output) AND ImagePng($thumb, $output);

        ImagePng($thumb);
    }

    elseif ($type == "gif")
    {
        if (isset($output))
            return is_writable($output) AND ImageGIF($thumb, $output);

        ImageGIF($thumb);
    }

    elseif ($type == "jpg" || $type == "jpeg")
    {
        if (isset($output))
            return is_writable($output) AND ImageJPEG($thumb, $output);

        ImageJPEG($thumb);
    }

    elseif ($type == "wbmp")
    {
        if (isset($output))
            return is_writable($output) AND ImageWBMP($thumb, $output);

        ImageWBMP($thumb);
    }

    else {
        ImageDestroy ($thumb);
        return false;
    }
}
?>



<?php

/*
Sample :
$thumb=new thumbnail("./shiegege.jpg");			// generate image_file, set filename to resize/resample
$thumb->size_width(100);						// set width for thumbnail, or
$thumb->size_height(300);						// set height for thumbnail, or
$thumb->size_auto(200);							// set the biggest width or height for thumbnail
$thumb->jpeg_quality(75);						// [OPTIONAL] set quality for jpeg only (0 - 100) (worst - best), default = 75
$thumb->show();									// show your thumbnail
$thumb->save("./huhu.jpg");						// save your thumbnail to file
----------------------------------------------
Note :
- GD must Enabled
- Autodetect file extension (.jpg/jpeg, .png, .gif, .wbmp)
  but some server can't generate .gif / .wbmp file types
- If your GD not support 'ImageCreateTrueColor' function,
  change one line from 'ImageCreateTrueColor' to 'ImageCreate'
  (the position in 'show' and 'save' function)
- If your GD not support 'ImageCopyResampled' function,
  change 'ImageCopyResampled' to 'ImageCopyResize'
*/############################################


class thumbnail
{
	var $img;

	function thumbnail($imgfile)
	{
		//detect image format
		$this->img["format"]=ereg_replace(".*\.(.*)$","\\1",$imgfile);
		$this->img["format"]=strtoupper($this->img["format"]);
		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			$this->img["format"]="JPEG";
			$this->img["src"] = ImageCreateFromJPEG ($imgfile);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			$this->img["format"]="PNG";
			$this->img["src"] = ImageCreateFromPNG ($imgfile);
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			$this->img["format"]="GIF";
			$this->img["src"] = ImageCreateFromGIF ($imgfile);
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			$this->img["format"]="WBMP";
			$this->img["src"] = ImageCreateFromWBMP ($imgfile);
		} else {
			//DEFAULT
			echo "Not Supported File";
			exit();
		}
		@$this->img["x"] = imagesx($this->img["src"]);
		@$this->img["y"] = imagesy($this->img["src"]);
		//default quality jpeg
		$this->img["quality"] = 75;
	}

	function size_height($size = 100)
	{
		//height
    	$this->img["y_thumb"] = $size;
    	$this->img["x_thumb"] = $this->img["y_thumb"] * $this->img["x"] / $this->img["y"];
	}

	function size_width($size = 100)
	{
		//width
		$this->img["x_thumb"] = $size;
    	$this->img["y_thumb"] = $this->img["x_thumb"] * $this->img["y"] / $this->img["x"];
	}

	function size_auto($size = 100)
	{
		//size
		if ($this->img["x"] >= $this->img["y"])
		{
    		$this->img["x_thumb"] = $size;
    		$this->img["y_thumb"] = $this->img["x_thumb"] * $this->img["y"] / $this->img["x"];
		} else {
	    	$this->img["y_thumb"] = $size;
    		$this->img["x_thumb"] = $this->img["y_thumb"] * $this->img["x"] / $this->img["y"];
 		}
	}

	function jpeg_quality($quality = 75)
	{
		$this->img["quality"] = $quality;
	}

	function show()
	{
		//show thumb
		Header("Content-Type: image/".$this->img["format"]);

		/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
		$this->img["des"] = ImageCreateTrueColor($this->img["x_thumb"],$this->img["y_thumb"]);

    	imagecopyresampled(
    		$this->img["des"],
    		$this->img["src"],
    		0, 0, 0, 0,
    		$this->img["x_thumb"],
    		$this->img["y_thumb"],
    		$this->img["x"],
    		$this->img["y"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imageJPEG($this->img["des"],"",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"]);
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"]);
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"]);
		}
	}

	function save($save = "")
	{
		//save thumb
		if (empty($save))
			$save=strtolower("./thumb.".$this->img["format"]);

		// change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function
		$this->img["des"] = ImageCreateTrueColor($this->img["x_thumb"],$this->img["y_thumb"]);
    		@imagecopyresampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["x_thumb"], $this->img["y_thumb"], $this->img["x"], $this->img["y"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imageJPEG($this->img["des"],"$save",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"],"$save");
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"],"$save");
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"],"$save");
		}
	}
}




// Comprobar los tipos de imagen soportados
// IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP | IMG_XPM.
// (imagetypes() & IMG_PNG)



//http://lineadecodigo.com/php/alto-y-ancho-de-una-imagen-en-php/
//http://www.scriptiny.com/2013/01/image-resize-using-php/

?>
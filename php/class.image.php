
<?php
set_time_limit ("60");
ini_set('gd.jpeg_ignore_warning', 1);

class Image
{
	var $img;

	function resize($imgfile)
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


?>
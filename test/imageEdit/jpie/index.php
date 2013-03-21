<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>JavaScript & PHP Image Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">
</head>

<body onload="loadJPIE('arizonaPhoto.jpg')">
<div id="JPIE_toolbar">
	<span id="statusBar">
		<span id="info1"></span>
		<span id="info2"></span>
	</span>	
	<span><a href="javascript:openImage();"><img src="icons/open.gif" alt="open a new image file"></a></span> 
	<span><a href="javascript:saveImage();"><img src="icons/save.gif" alt="save the current image"></a></span>
	<span><a href="javascript:crop();"><img src="icons/crop.gif" alt="Crop the current image"></a></span>
	<span><a href="javascript:undo();"><img src="icons/undo.gif" alt="Undo the previous modification"></a></span>
	<span><a href="javascript:redo();"><img src="icons/redo.gif" alt="Redo the previous undo action"></a></span>
	<span><a href="javascript:rotate();"><img src="icons/rotate.gif" alt="Rotate current image"></a></span>
	<span><a href="javascript:alert('Not implemented yet');"><img src="icons/brightness_contrast.gif" alt="Change the brightness and contrast value"></a></span><!--TODO-->
	<span><a href="javascript:resize();"><img src="icons/resize.gif" alt="change the size of the current image"></a></span>

</div>
<div id="canvas">
	<img id="mainImage">
</div>


<script src="includes/scripts/functions.js" language="javascript" type="text/javascript"></script>
</body>
</html>
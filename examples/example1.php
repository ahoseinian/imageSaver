<?php 
	require_once '../ImageSaver.php';
	define('APPLICATION_PATH', __DIR__);

	$imageSaver = new ImageSaver();
	$imageSaver->allowFileTypes = array("jpg","jpeg"); //defining allowed file types

	if(isset($_FILES['image'])){	
		$imageSaver->setImage($_FILES['image'])->setName('test')->setPath("\images\\")->resize('auto',300)->save();
	}
?>

<html>
<head>
	<title>image file post example</title>
</head>
<body>
	<form method="post" action="" enctype="multipart/form-data">
		Accepted file types is 
		( <?php foreach($imageSaver->allowFileTypes as $type) echo $type." ";?>)
		u can add other types in $imageSaver->allowFileTypes property
		<div>
			<lable>image file:<input name="image" type="file"></lable>
		</div>
		<div><input type="submit"></div>
	</form>
	<img src="images/test.jpg" alt="">
</body>
</html>
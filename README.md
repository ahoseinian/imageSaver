ImageSaver
==========

	version 0.0.1  Created by Amir Hoseinian


Introduction
-----------------

this small class is built for 

- saving post images from html form in file system
- resizing images before saving them


Resizing
--------

- By width     resize(xxx,'auto')
- By height	  resize('auto',xxx)
- By both	  resize(xxx,xxx)

Usage
-----

	<?php

	define('APPLICATION_PATH', __DIR__);

	$imageSaver = new ImageSaver();
	$imageSaver->allowFileTypes = array("jpg","jpeg"); //defining allowed file types

	if(isset($_FILES['image'])){	
		$imageSaver->setImage($_FILES['image'])->setName('test')->setPath("\images\\")->resize('auto',300)->save();
	}


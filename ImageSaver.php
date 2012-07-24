<?php 
/**
*Example usage
*		$imageMaker = New ImageMaker();
*		$imageMaker->setImage($_FILES['image'])->setName('sample name')->setPath("\data\images\brand\\")->resize(270,'auto')->save();             
*/
class ImageSaver{

	//class properties
	protected $image;
	protected $name;
	protected $path;
	protected $imageResized;
	public $allowFileTypes = array("jpg","jpeg","gif","png");

	/**
	*@param array $image posted file like $FILE['image']
	*/
	public function setImage($image){
		$this->image = $image;
		
		if(!in_array($this->getExtension() , $this->allowFileTypes)) //check if file is an image
			throw new \Exception("FileTypeNotSupported", 1);
			
		return $this;
	}

	/**
	*@param string $name new name for image to save
	*/
	public function setName($name){
		$this->name = $name;
		return $this;
	}

	/**
	*@param string $path store image place -- example \data\images\sample\
	*@constant APPLICATION_PATH u can define your application path in a global variable or simply change this 
	* 	with somting like || c:\wamp\www\sample\public
	*/
	public function setPath($path){

		if(APPLICATION_PATH)
			$this->path = APPLICATION_PATH.$path;
		else
			$this->path = $path;

		return $this;
	}

	/**
	*@return string $ext image extension eg jpg,png
	*/
	public function getExtension(){
	    $info = pathinfo($this->image['name']);
		$ext = strtolower($info['extension']); // get the extension of the file
		return $ext;
	}

	/**
	*@return resource  open image file for resizing
	*/
	public function getImageFile(){
		switch (strtolower($this->getExtension())) {
			case 'jpg':
			case 'jpeg':
				return imagecreatefromjpeg($this->image['tmp_name']);
				break;

			case 'png':
				return imagecreatefrompng($this->image['tmp_name']);
				break;
			
			case 'gif':
				return imagecreatefromgif($this->image['tmp_name']);
				break;
			
			default:
				throw new \Exception("image type not recognized");
				break;
		}
	}

	/**
	*@param string OR int $width eg 300,250,'auto'
	*@param string OR int $height eg 300,250,'auto'
	*/
	public function resize($width, $height){

		list($imageWidth, $imageHeight, $type, $attr) = getimagesize($this->image['tmp_name']);
		list($width, $height) = $this->getBestDimensions($width, $height, $imageWidth, $imageHeight);

		$this->imageResized = imagecreatetruecolor($width, $height);
		imagecopyresampled($this->imageResized, $this->getImageFile(), 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);

		return $this;
	}

	/**
	*@param string OR int $width eg 300,250,'auto'
	*@param string OR int $height eg 300,250,'auto'
	*@param int width of uploaded image
	*@param int height of uploaded image
	*@return array ($width, $height) optimal dimensions
	*/
	private function getBestDimensions($width, $height, $imageWidth, $imageHeight){
		if($width == 'auto' && is_numeric($height)){
			$width = round(($imageWidth * $height) / $imageHeight);
		}elseif($height == 'auto' && is_numeric($width)){
			$height = round(($imageHeight * $width) / $imageWidth);
		}
		else if(!is_numeric($height) && !is_numeric($width))
			throw new \Exception("error in d", 1);	

		return array($width,$height);
	}

	/**
	*@param int $imageQuality range between 1-100 define quality of saved image
	*/
	public function save($imageQuality=100){
			$newname = $this->name.".".$this->getExtension($this->image);
			$target = $this->path.$newname;

			if($this->imageResized){
				imagejpeg($this->imageResized, $target, $imageQuality);
				imagedestroy($this->imageResized);
				$this->imageResized = null;
			}else
    			move_uploaded_file($this->image['tmp_name'], $target);
	}
}

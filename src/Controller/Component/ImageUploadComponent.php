<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Routing\Router;
use Cake\Filesystem\Folder;

class ImageUploadComponent extends Component {

/**
* Upload the images, resize and send the response
*
* @param POST
* @return array
* @throws \Cake\Http\Exception\NotFoundException
*/
public function UploadResizedImages($imageData, $path) {

    try {
            if (!file_exists(WWW_ROOT . $path)) { // create the image folder if it does not exist
                $dir = new Folder(WWW_ROOT . $path, true, 0777);
            }
            $result = [];
            $saveData = $imageData;
			$imgPath = WWW_ROOT . $path;
            $file=explode(".", $imageData['name']);
            $filename=$file[0].time().uniqid(rand()).'.'.$file[1];
            
            $ext = substr(strtolower(strrchr($imageData['name'], '.')), 1); //get the extension
        	$arr_ext = array('jpg', 'jpeg', 'png', 'PNG'); //set allowed extensions
            
            if(in_array($ext, $arr_ext))
        	{
	            if (move_uploaded_file($imageData['tmp_name'], $imgPath.$filename)){
					$this->resize(100, 59, $imgPath.$filename, $imgPath.$filename);
	            }else{
	                $result['file_upload_error'][] = "Could Not Upload " . $imageData['name']; //send the error if any file did not get upload
	            }
	        }else{
	        	$result['status'] = false;
                $result['uploadData'] = ''; 
                $result['message'] = 'Invalid file extension, please select images only.';
                return $result;
	        }
            if (empty($result)) {
                $result['status'] = true;
                $result['uploadData'] =  $filename;;
                $result['message'] = 'uploaded successfully'; 
            }else if (!empty($result)) {
                $result['status'] = false;
                $result['uploadData'] = ''; 
                $result['message'] = 'Could Not Upload';
            }
            return $result;
        }
        catch (InternalErrorException $exception) {
            throw new NotFoundException();
        }
    }   

/**
*  Resize Images and send the response
*
* @param POST
* @return Array
*/
public function resize($newHeight, $newWidth, $targetFile, $originalFile) {
	$info = getimagesize($originalFile);
	$mime = $info['mime'];

	switch ($mime) {
		case 'image/jpeg':
			$image_create_func = 'imagecreatefromjpeg';
			$image_save_func = 'imagejpeg';
			$new_image_ext = 'jpeg';
			break;

		case 'image/jpg':
			$image_create_func = 'imagecreatefromjpg';
			$image_save_func = 'imagejpg';
			$new_image_ext = 'jpg';
			break;

		case 'image/png':
			$image_create_func = 'imagecreatefrompng';
			$image_save_func = 'imagepng';
			$new_image_ext = 'png';
			break;

		case 'image/gif':
			$image_create_func = 'imagecreatefromgif';
			$image_save_func = 'imagegif';
			$new_image_ext = 'gif';
			break;	

		default: 
		throw Exception('Unknown image type.');
	}
	$img = $image_create_func($originalFile);
	list($orig_width, $orig_height) = getimagesize($originalFile);

	$width = $orig_width;
	$height = $orig_height;
	$max_height = $newHeight;
	$max_width = $newWidth;

	# taller
	if ($height > $max_height) {
		$width = ($max_height / $height) * $width;
		$height = $max_height;
	}

	# wider
	if ($width > $max_width) {
		$height = ($max_width / $width) * $height;
		$width = $max_width;
	}
	$tmp = imagecreatetruecolor($width, $height);
	imagealphablending($tmp, false);
	imagesavealpha($tmp,true);
	imagecopyresampled($tmp, $img, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

	if (file_exists($targetFile)) {
		unlink($targetFile);
	}
	$image_save_func($tmp, "$targetFile");
}
}
?>

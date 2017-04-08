<?php
$temp_dir = "tmp/";
$profile_image_dir = "img_profile/";
$profile_image_width = 75;

function getUpdatedDeletedId(&$respuesta, $idRows, $paramsData){
    $aIdsData = array();
    if( is_array($idRows) && count($idRows) > 0 ){
        if($idRows[0]['updated'] != $paramsData['fecha']){
            $respuesta['updated'] = $idRows[0]['updated'];
        }
        foreach($idRows as $row){
            $aIdsData[] = $row['id'];
        }
    }
    $deviceId = explode(',', $paramsData['ids']);
    unset($deviceId[0]);
    $aIdsData = array_diff($deviceId, $aIdsData);
    if(count($aIdsData) > 0){
        $respuesta['deletedid'] = implode(',', $aIdsData);
    }
}

function createThumbnail($pathToImage, $thumbWidth = 180) {
    global $temp_dir;
    global $profile_image_dir;
    $result = 'Failed';
    if (is_file($pathToImage)) {
        $info = pathinfo($pathToImage);

        $extension = strtolower($info['extension']);
        $file_name = $info['filename'];
        if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {
            switch ($extension) {
                case 'png':
                    $img = imagecreatefrompng("{$pathToImage}");
                    break;
                case 'gif':
                    $img = imagecreatefromgif("{$pathToImage}");
                    break;
                default:
                    $img = imagecreatefromjpeg("{$pathToImage}");
            }
            // load image and get image size

            $width = imagesx($img);
            $height = imagesy($img);

            // calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($height * ( $thumbWidth / $width ));

            // create a new temporary image
            $tmp_img = imagecreatetruecolor($new_width, $new_height);

            // copy and resize old image into new image
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            //$pathToImage = $pathToImage . '.thumb.' . $extension;
            // save thumbnail into a file
            $aDir = array($temp_dir, $profile_image_dir);
            $pathToImage = "";
            foreach($aDir as $sDir){
                $pathToImage .= $sDir;
                if(!file_exists($pathToImage)){
                    @mkdir($pathToImage);
                    chmod($pathToImage, 0777);
                }
            }
            $pathToImage .= $file_name.".".$extension;
            switch ($extension) {
                case 'png':
                    imagepng($tmp_img, "{$pathToImage}");
                    break;
                case 'gif':
                    imagegif($tmp_img, "{$pathToImage}");
                    break;
                default:
                    imagejpeg($tmp_img, "{$pathToImage}");
            }
            imagedestroy($tmp_img);
            imagedestroy($img);
            return $pathToImage;
        } else {
            throw new Exception('Failed|Not an accepted image type (JPG, PNG, GIF).', 11);
        }
    } else {
        throw new Exception('Failed|Image file does not exist.', 10);
    }
}

function createBase64Thumbnail($pathToImage, $thumbWidth = 180){
    $filePath = createThumbnail($pathToImage, $thumbWidth);
    $base64 = base64_encode(file_get_contents($filePath));
    @unlink($filePath);
    return array(
        'base64' => $base64,
        'extension' => substr($filePath, strrpos($filePath, '.')+1)
    );
}

?>
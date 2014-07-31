<?php

include './function.php';

$ds          = DIRECTORY_SEPARATOR; 
$return_val = array();

if(isset($_REQUEST['album_json']) && $_REQUEST['album_json'] != ''){
    $album_json = $_REQUEST['album_json'];

    if(!is_dir('album')){
        mkdir('album',0775);
    }
    
    $storeFolder = 'album'.$ds.date('YmdHis').'.'.rand(1000,2000);
    if(isset($_REQUEST['dir']) && $_REQUEST['dir'] != ''){
        $storeFolder = 'album'.$ds.$_REQUEST['dir'];
    }
    
    $targetPath = dirname( __FILE__ ) .$ds. $storeFolder . $ds;
    if(!is_dir($storeFolder)){
        // 修改权限，貌似没有成功
        mkdir($storeFolder,0775);
    }
    $return_val['got json'] = $album_json;
    $album_json_array = json_decode($album_json,true);
    // 保存缩略图为 png 图片
    $thumb_name = $storeFolder.'.thumb.png';
    if(base64_to_image($album_json_array['thumb'],$thumb_name)){
        $album_json_array['thumb'] = $thumb_name;
    }else{
        $return_val['save thumbnail error'] = "Error create".$thumb_name;
    }
    
    if(file_exists($targetPath."album.json")){
        if (!unlink($targetPath."album.json")){
            $return_val['del file error'] = "Error deleting album.json";
        }
    }
    $json_file_handler = fopen($targetPath."album.json","w");
    fwrite($json_file_handler,json_encode($album_json_array));
    fclose($json_file_handler);

    if (!empty($_FILES)) {
        $len = count($_FILES['file']['name']);
        $return_val['len'] = $len;
        $return_val['files'] = $_FILES;
        for($i=0;$i<$len;$i++){
            $tempFile = $_FILES['file']['tmp_name'][$i];
            if (file_exists($targetPath) && is_writable($targetPath)) {
                // do upload logic here
                $name = iconv('utf-8','gb2312',$file['name'][$i]);
                //$targetFile =  $targetPath. $_FILES['file']['name'][$i];
                $targetFile =  $targetPath.$name;
                $return_val['tmpname'] .= $tempFile;
                $return_val['target'] .= $targetFile;
                if(move_uploaded_file($tempFile,$targetFile)){
                    $return_val['info'].= '文件上传成功！';
                }else{
                    $return_val['info'].= '=上传文件移动失败!'.$_FILES['file']['name'][$i];
                }
            }
            else {
                $return_val['info'].= 'Upload directory is not writable, or does not exist.';
            }
        }
        
    }
}
echo json_encode($return_val);

?>
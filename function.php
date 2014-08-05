<?php
/**
 * Created by PhpStorm.
 * User: kamalyu
 * Date: 14-5-14
 * Time: 下午4:09
 */

include './config.php';

function myUrl($url='',$fullurl=false) {
    
    $s=$fullurl ? WEB_DOMAIN : '';
    $s.=WEB_DOMAIN.$url;
    return $s;
}

function require_login() {
    if (!isset($_SESSION['auth'])){
        header('Location: '.myUrl('login.php'));
        exit;
    }
}
function is_login(){
    return isset($_SESSION['auth'])&&isset($_SESSION['auth']['super']);
}
function is_super(){
    return isset($_SESSION['auth'])&&isset($_SESSION['auth']['super'])&&$_SESSION['auth']['super']=='1';
}

function login($user,$pass){
    $return_val = false;
    if($user == ADMIN_NAME && $pass == ADMIN_PASS){
        $_SESSION['auth']=array(
            'name'=>'admin'
            ,'super'=>'0'
        );
        $return_val = true;
    }elseif($user == SUPER_ADMIN_NAME && $pass == SUPER_ADMIN_PASS){
        $_SESSION['auth']=array(
            'name'=>'admin'
            ,'super'=>'1'
        );
        $return_val = true;
    }
    return $return_val;
}


function get_album_list(){
    $dir = './album';
    $album_list=array();
    if(is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if((is_dir($dir."/".$file)) && $file!="." 
                    && $file!=".." && $file!=".idea" && $file!="lib"){
                    
                    $json_dir = $dir."/".$file."/album.json";
                    if(file_exists($json_dir)){
                        $json_con = file_get_contents($json_dir);
                        $album_list[$file] = $json_con;
                    }
                }
            }
            closedir($dh);
        }
    }
    krsort($album_list);
    return $album_list;
}
function listAlbum($dir){
    $json_dir = 'album/'.$dir."/album.json";

    if(file_exists($json_dir)){
        $json_con = file_get_contents($json_dir);
        return json_decode($json_con,true);
    }
}

function delete_album($dir){
    $ds          = DIRECTORY_SEPARATOR;
    $dir = dirname( __FILE__ ) .$ds. 'album' . $ds.$dir;
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file);
    }
    rmdir($dir);
    return true;
}

function path_info($filepath)
{
    $path_parts = array();
    $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";
    $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");
    $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
    $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");
    return $path_parts;
}

function album_date_format($dir_name){
    $str = '';
    $arr = str_split(substr($dir_name,0,14),2);
    for($i=0,$len=count($arr);$i<$len;$i++){
        $str .= $arr[$i];
        if($i==1){
            $str .='-';
        }elseif($i==2){
            $str .='-';
        }elseif($i==3){
            $str .=' ';
        }elseif($i==4){
            $str .=':';
        }elseif($i==5){
            $str .=':';
        }
    }
    return $str;
}

function base64_to_image($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);

    return $output_file;
}
?>
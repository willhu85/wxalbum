<?php
/**
 * Created by PhpStorm.
 * User: kamalyu
 * Date: 14-5-14
 * Time: 下午4:09
 */

include './function.php';
$album_list=get_album_list();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>微信DEMO</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    
    <style type="text/css">
        *{margin:0;padding:0;-webkit-tap-highlight-color: rgba(0,0,0,0);}
        body{
            font-size:14px;
            font-family:Helvetica,STHeiti STXihei, Microsoft JhengHei, Microsoft YaHei, Arial, sans-serif;
        }
        h1{text-align:center;padding:20px 0;}
        a{display:block;background-color:#e6e6e6;height:40px;line-height:40px;
            color:#000;text-decoration:none; text-align:center;margin:20px 10px; }
        a:active{background-color:rgba(0,0,0,.2);}
    </style>
</head>
<body>
<h1>DEMO列表</h1>

<?php
//var_dump($album_list);

//    foreach($album_list as $album_name=>$dir_name){
foreach($album_list as $dir_name=>$album_json_str){
        $url =  WEB_DOMAIN."album.php?dir=".$dir_name;
        $album_json = json_decode($album_json_str,true);
        $album_name = $album_json['album_name'];

        echo '<a href="'.$url.'" >'.$album_name.'</a>';
    }
?>
</body>

</html>
<?php
/**
 * Created by PhpStorm.
 * User: kamalyu
 * Date: 14-5-14
 * Time: 下午4:09
 */
include './function.php';
$dir = '';
$err_info = '';
if(isset($_REQUEST['dir']) && $_REQUEST['dir'] != ''){
    $dir = $_REQUEST['dir'];
    
    $url_base = WEB_DOMAIN."album/".$dir;
    //开始运行
    $album =  listAlbum($dir);
    $num = count($album['album_images']);
    for($i=0;$i<$num;++$i){
        $album['album_images'][$i] = $url_base."/".urlencode($album['album_images'][$i]);
    }
//    var_dump($album);
}else{
    $err_info = '参数错误，没有这个DEMO。';
} 

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title><?php echo $album['album_name']; ?></title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">

    <style type="text/css">
        *{margin:0;padding:0;-webkit-tap-highlight-color: rgba(0,0,0,0);}
        body{
            font-size:14px;
            font-family:Helvetica,STHeiti STXihei, Microsoft JhengHei, Microsoft YaHei, Arial, sans-serif;
        }
        a{display:block;background-color:#e6e6e6;height:80px;line-height:80px;
            color:#000;text-decoration:none; text-align:center;margin:20px 9%; }
        a:active{background-color:rgba(0,0,0,.2);}
        .wrapper{
            width:100%;
            position:absolute;
            top:50%;
            left:0;
            margin-top:-60px;
        }
    </style>
</head>
<body>
<?php
if($err_info != ''){
?>
    <div class="wrapper">
        <?php echo $err_info; ?>
    </div>
<?php
}else{
?>
    <div class="wrapper">
        <a href="javascript:activeAlbum();">开始演示</a>
    </div>
    <script type="text/javascript" src="lib/jquery-1.11.1.js"></script>
    <script type="text/javascript">
        var img_list = '<?php echo join('|||',$album['album_images']); ?>'.split('|||');
        var n_img_list =[];
        $.each(img_list, function(i) {
            var url = img_list[i].split("?t=")[0] + "?t=" + new Date().getTime();
            n_img_list.push(url);
        })
        //console.log(n_img_list);
        function activeAlbum(){
            WeixinJSBridge.invoke('imagePreview',{'current':n_img_list[0],'urls':n_img_list});
        }
        $(function(){
            document.addEventListener("WeixinJSBridgeReady", onWeixinReady, false);            
        });
        function onWeixinReady(){
            WeixinJSBridge.on("menu:share:appmessage", shareFriends);
        }
        function shareFriends(){
            var data={};
//            data.img='http://c.hiphotos.baidu.com/image/w%3D2048/sign=5a381851d62a283443a6310b6f8dc8ea/adaf2edda3cc7cd9b7713c953b01213fb80e9119.jpg';
            data.link=location.href;
            data.content='<?php echo $album['album_name']; ?>';
            data.title='微信DEMO预览';

            WeixinJSBridge.invoke("sendAppMessage", {
//                img_url: data.img,
//                img_width: "30",
//                img_height: "30",
                link: data.link,
                desc: data.content,
                title: data.title
            }, function(b) {
                alert("sendAppMessage done")
            });

        }
    </script>    
<?php
}
?>

</body>
</html>
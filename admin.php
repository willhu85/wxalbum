<?php
/**
 * Created by PhpStorm.
 * User: kamalyu
 * Date: 14-5-14
 * Time: 下午4:09
 */

include './function.php';
//require_login();
//
if(isset($_REQUEST['del']) && $_REQUEST['del'] != ''){
    $del_name = $_REQUEST['del'];
    delete_album($del_name);
    if(delete_album($del_name)){
        //重定向浏览器
        header("Location: ".WEB_DOMAIN.'admin.php');
        //确保重定向后，后续代码不会被执行
        exit;
    }else{
        $page_err = '删除失败';
    }
}

$album_list=get_album_list();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>管理界面 - 微信DEMO</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="lib/css/quick-layout.css"/>
    <link rel="stylesheet" href="lib/css/demo.css"/>
</head>
<body class="bgeb">
<!-- 内容部分 -->
<div class="bg-blue p5 fix">
    <h1 class="f18 fw white l">微信相册:</h1>
    <a href="admin.php" class="l pl10 p5 white">相册列表</a>
    <a href="create.php" class="l pl10 p5 white">创建相册</a>
</div>
<div class="p20" data-url="<?php echo WEB_DOMAIN; ?>">
    <ul class="bg-white bdc">
        <li class="bbc p10 f14 b">相册列表</li>
        <?php
        foreach($album_list as $dir_name=>$album_json_str){
            $url =  WEB_DOMAIN."album.php?dir=".$dir_name;
            $album_json = json_decode($album_json_str,true);
            $album_name = $album_json['album_name'];
            $album_thumb = $album_json['thumb'];
            $create_time = album_date_format($dir_name);
            ?>
            <li class="p10 bbc J_listLi" data-url="<?php echo $url; ?>">
                <i class="qr_code mr10"></i>
                <?php echo $album_name; ?>
                <a href="<?php echo $url; ?>" target="_blank">链接地址</a> &nbsp;|&nbsp;
                <a href="modify.php?dir=<?php echo $dir_name ?>">编辑相册</a>&nbsp;|&nbsp;
                <a href="javascript:;" class="delAlbum" data-dir="<?php echo $dir_name ?>" data-name="<?php echo $album_name; ?>" title="删除DEMO-<?php echo $album_name; ?>" >删除相册：-<?php echo $album_name; ?></a>
            </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript" src="lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="lib/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="lib/admin.js"></script>
<script type="text/javascript">

    $(function() {
        var eleList = $(".J_listLi");
        $.each(eleList, function() {
            var _this = $(this);
            $(_this.find(".qr_code")).qrcode({
                    width: 100,
                    height: 100,
                    text: _this.attr("data-url")}
            );
        })
    })
</script>
</body>
</html>
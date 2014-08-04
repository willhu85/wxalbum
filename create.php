<?php
/**
 * Created by PhpStorm.
 * User: kamalyu
 * Date: 14-5-14
 * Time: 下午4:09
 */

include './function.php';
//require_login();
if(isset($_REQUEST['del']) && $_REQUEST['del'] != ''){
    $del_name = $_REQUEST['del'];
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
    <title>创建新DEMO - 微信DEMO</title>
    <link rel="stylesheet" href="lib/css/quick-layout.css"/>
    <link rel="stylesheet" href="lib/css/dropzone.css"/>
    <link rel="stylesheet" href="lib/css/demo.css"/>
</head>
<body>
<div class="bg-blue p5 fix">
    <h1 class="f18 fw white l">微信相册:</h1>
    <a href="admin.php" class="l pl10 p5 white">相册列表</a>
    <a href="create.php" class="l pl10 p5 b yellow">创建相册</a>
</div>
<div class="p20">
    <h2 class="f16">创建新相册</h2>
    <div class="pt20">
        <input type="text" placeholder="DEMO名称" name="album_name" id="album_name" class="p5" />
        <p class="p5 mt10 bg-white bdc red b">请保持图片尺寸 ≤ 500K</p>
        <p class="p5 mt10 bg-white bdc yellow b">大于 200K 的图片移动端加载已经有困难了</p>
        <div class="pt20">
            <form action="upload.php" class="dropzone" id="my-dropzone"></form>

        </div>
        <p class="pt20">
            <input id="create_btn" type="button" value="创建DEMO" class="btn btn-blue"/>
        </p>
    </div>
</div>
<script type="text/javascript" src="lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="lib/dropzone.js"></script>
<script type="text/javascript" src="lib/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="lib/admin.js"></script>
<script type="text/javascript">

</script>
</body>
</html>
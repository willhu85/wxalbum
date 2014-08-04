<?php
/**
 * Created by PhpStorm.
 * User: kamalyu
 * Date: 14-5-14
 * Time: 下午4:09
 */

include './function.php';
//require_login();

$dir = '';
$err_info = '';
if(isset($_REQUEST['dir']) && $_REQUEST['dir'] != ''){
    $dir = $_REQUEST['dir'];
    
    $url_base = WEB_DOMAIN."album/".$dir;
    $dir_base = "album/".$dir;
    //开始运行
    $album =  listAlbum($dir);
    $num = count($album['album_images']);
    for($i=0;$i<$num;++$i){
        //$file_size = filesize($dir_base."/".$album['album_images'][$i]);
        $file_size = filesize($dir_base."/".iconv("UTF-8", "gb2312", $album['album_images'][$i]));
        $file_url = $url_base."/".$album['album_images'][$i];
        $album['album_images'][$i] = array(
            'size'=>$file_size
            ,'url'=>$file_url
            ,'name'=>$album['album_images'][$i]
        );
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
    <title>修改DEMO - 微信DEMO</title>
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
<?php
if($err_info != ''){
    ?>
    <div class="main_con">
        <h3>修改DEMO</h3>
        <?php echo $err_info; ?>
        <p>返回 <a href="admin.php">DEMO列表</a></p>
    </div>
<?php
}else{
?>
<div class="p20">
    <h2 class="f16">修改相册</h2>
    <div class="pt20">
        <input type="text" placeholder="DEMO名称" name="album_name" id="album_name" class="p5" value="<?php echo $album['album_name'];  ?>" />
        <p class="p5 mt10 bg-white bdc red b">请保持图片尺寸 ≤ 500K</p>
        <p class="p5 mt10 bg-white bdc yellow b">大于 200K 的图片移动端加载已经有困难了</p>
        <div class="pt20">
            <form action="upload.php" class="dropzone" id="my-dropzone"></form>

        </div>
        <p class="pt20">
            <input id="create_btn" type="button" value="修改DEMO" class="btn btn-blue"/>
            <a id="cancel_btn" class="btn btn-gray" href="admin.php?admin=admin">取消修改</a>
        </p>
    </div>
</div>
<script type="text/javascript" src="lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="lib/dropzone.js"></script>
<script type="text/javascript" src="lib/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="lib/admin.js"></script>
<script type="text/javascript">
    var global_album_img_arr = <?php echo json_encode($album['album_images']); ?>;
    var global_album_dir = '<?php echo $dir; ?>';
</script>
<?php
}
?>
</body>
</html>
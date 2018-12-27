<?php
/**
 * Created by PhpStorm.
 * User: LEO
 * Date: 2018/12/24
 * Time: 16:17
 */
//将数据存储在文件中
// 表单处理三部曲
// 1. 接收并校验
// 2. 持久化（将数据持久保存到磁盘）
// 3. 响应（服务端的反馈）
if($_SERVER['REQUEST_METHOD']==='POST'){
    getback();
}
function getback()
{
//input数据接受及验证
    if(!isset($_POST['title'])){
        $GLOBALS['error_message'] = '请正确输入内容1';
        return;
    }
    if(!isset($_POST['artist'])){
        $GLOBALS['error_message'] = '请正确输入内容2';
        return;
    }
    if(empty($_POST['title'])){
        $GLOBALS['error_message'] = '请输入标题';
        return;
    }
    if(empty($_POST['artist'])){
        $GLOBALS['error_message'] = '请输入歌手名字';
        return;
    }
    $data['id'] = uniqid();
    $data['title'] = $_POST['title'];
    $data['artist'] = $_POST['artist'];
//图片文件接受验证及存储<文件域多文件上传> => multiple
    if(empty($_FILES['images'])){
        $GLOBALS['error_message'] = '请正确上传图片';
        return;
    }

    if($_FILES['images']['name'][0] == ''){
        $GLOBALS['error_message'] = '请上传图片';
        return;
    }
    $images = $_FILES['images'];
    for ($i = 0; $i < count($images['name']); $i++) {
      //文件大小类型
        $allow_types = array('image/png','image/jpg','image/gif','image/jpeg');
        if(!in_array($images['type'][$i],$allow_types)){
            $GLOBALS['error_message'] = '不支持的图片格式';
            return;
        }
        if($images['size'][$i] > 6*1024*1024){
            $GLOBALS['error_message'] = '图片过大';
            return;
        }
        if($images['error'][$i] !== UPLOAD_ERR_OK){
            $GLOBALS['error_message'] = '上传失败1';
            return;
        }
        //存储图片
        $target = './uploads/'.uniqid().'-'.$images['name'][$i];

        if(!move_uploaded_file($images['tmp_name'][$i],$target)){
            $GLOBALS['error_message'] = '上传失败2';
            return;
        }
        $data['images'][] = $target;
    }

//音乐文件接受验证及存储
    $source = $_FILES['source'];
    if(empty($_FILES['source'])){
        $GLOBALS['error_message'] = '请正确上传音乐';
        return;
    };
    if($_FILES['source']['name'][0] == ''){
        $GLOBALS['error_message'] = '请上传音乐';
        return;
    }
    $allow_types = array('audio/mp3','audio/aac');
    if(!in_array($source['type'],$allow_types)){
        $GLOBALS['error_message'] = '不支持的音乐格式';
        return;
    }
    if($source['size'] > 8*1024*1024){
        $GLOBALS['error_message'] = '音乐过大';
        return;
    }
    if($source['size'] < 1*1024*1024){
        $GLOBALS['error_message'] = '音乐过小';
        return;
    }
    if($source['error'] !== UPLOAD_ERR_OK){
        $GLOBALS['error_message'] = '上传音乐失败1';
        return;
    }
    //存储图片
    $target = './uploads/'.uniqid().'-'.$source['name'];

    if(!move_uploaded_file($source['tmp_name'],$target)){
        $GLOBALS['error_message'] = '上传音乐失败2';
        return;
    }
    $data['source'] = $target;

    $json = file_get_contents('data.json');
    $old = json_decode($json,true);
    array_push($old,$data);
    $new = json_encode($old);
    file_put_contents('data.json',$new);
    header('Location: musiclist.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加音乐</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="display-3">添加音乐</h1>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif ?>
    <hr>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">标题</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="form-group">
            <label for="artist">歌手</label>
            <input type="text" class="form-control" id="artist" name="artist">
        </div>
        <div class="form-group">
            <label for="images">海报</label>
            <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
        </div>
        <div class="form-group">
            <label for="source">音乐</label>
            <!-- accept 可以限制文件域能够选择的文件种类，值是 MIME Type -->
            <input type="file" class="form-control" id="source" name="source" accept="audio/*">
        </div>
        <button class="btn btn-primary btn-block">保存</button>
    </form>
</div>
</body>
</html>
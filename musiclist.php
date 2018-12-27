<?php
/**
 * Created by PhpStorm.
 * User: LEO
 * Date: 2018/12/24
 * Time: 15:53
 */

//uniqid()生成唯一的id
$contents = file_get_contents('data.json');

$data = json_decode($contents,true);

//var_dump($data);
?>
<!--在音乐列表里添加或删除文件-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>音乐列表</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="display-3">音乐列表<a href="addmusic.php" class="btn btn-primary">添加</a></h1>
    <hr>
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-inverse">
        <tr>
            <th>标题</th>
            <th>歌手</th>
            <th>海报</th>
            <th>音乐</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $item):?>

            <tr>
                <td><?php echo $item["title"] ?></td>
                <td><?php echo $item["artist"] ?></td>
                <td><img src="<?php echo $item["images"][0] ?>" alt=""></td>
                <td><audio src="<?php echo $item["source"] ?>" controls></audio></td>
                <td><button class="btn btn-danger btn-sm"><a href="delete.php?id=<?php echo $item['id'] ?>">删除</a></button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

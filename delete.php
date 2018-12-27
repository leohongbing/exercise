<?php
/**
 * Created by PhpStorm.
 * User: LEO
 * Date: 2018/12/25
 * Time: 20:32
 */

//从文件中删除 保存 重新定向
$data = file_get_contents('data.json');
$check = json_decode($data,true);
foreach ($check as $item) {
    if($item['id'] !== $_GET['id']) continue;
    $index = array_search($item,$check);
    array_splice($check,$index,1);
    $new = json_encode($check);
    file_put_contents('data.json',$new);

  header('Location: musiclist.php');
}
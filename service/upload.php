<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4
 * Time: 10:00
 */
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
//echo '<pre>';
//print_r($_FILES);
if ($_FILES['file_image']['error'] > 0) {
    exit(json_encode(array('errno' => 1, 'data' => [])));
}
$fi = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $fi->file($_FILES['file_image']['tmp_name']);

//限制文件类型和大小
$allows = array('image/jpeg', 'image/png');
if (!in_array($mime_type, $allows)) {
    exit(json_encode(array('errno' => 1, 'data' => [])));
}

$res = move_uploaded_file($_FILES['file_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/myblog/upload_images/' . $_FILES['file_image']['name']);
exit(json_encode(array('errno' => 0, 'data' => ['/myblog/upload_images/' . $_FILES['file_image']['name']])));
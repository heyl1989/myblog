<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/3
 * Time: 18:57
 */
session_start();
$user = $_SESSION['user'];
if (!$user) {
    exit('你还没有登录，请先登录');
}
$data['uid'] = $user['uid'];
$data['title'] = trim($_POST['title']);
$data['cid'] = trim($_POST['cid']);
$data['keywords'] = trim($_POST['keywords']);
$data['article_desc'] = trim($_POST['describe']);
$data['contents'] = htmlspecialchars(trim($_POST['contents']));
$data['add_time'] = time();
//保存数据
require_once $_SERVER['DOCUMENT_ROOT'] . '/myblog/lib/db.php';
$db = new Db();
$id = $db->table('blog_article')->insert($data);
if (!$id) {
    exit(json_encode(array('code' => 1, 'msg' => '保存失败')));
}
exit(json_encode(array('code' => 0, 'msg' => '保存成功')));
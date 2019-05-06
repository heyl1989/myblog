<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 10:27
 */
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
//echo '<pre>';
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'] . '/myblog/lib/db.php';
$db = new Db();
//$result = $db->table('cates')
//    ->field('title,id')->where("id>5")
//    ->limit(2)->order('id desc')->list();
//$result = $db->table('cates')->item();
//$result = $db->table('cates')
//    ->delete(array('title' => '安卓开发'));
//$result = $db->table('cates')->where('id=15')
//    ->delete();
//$result = $db->table('cates')->where('id=14')
//    ->update(array('title'=>'新加分类'));

//$result = $db->table('cates')->where('id>2')
//    ->count('*');
$page = $_GET['page'];
$pageSize = 3;
$result = $db->table('cates')->field('id,title')->where('id>2')
    ->pages($page, $pageSize,'test.php');
//echo '<pre>';
//print_r($result);
//echo json_encode($result);

?>
<!DOCTYPE html>
<html>
<head>
    <title>分页</title>
    <link rel="stylesheet" type="text/css" href="static/plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 50px">
    <p>共查询出<?php echo $result['total'] ?>条数据</p>
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th>ID</th>
            <th>分类</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result['data'] as $cate) {
            ?>
            <tr>
                <td><?php echo $cate['id'] ?></td>
                <td><?php echo $cate['title'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <!--    分页-->
    <div><?php echo $result['pages'] ?></div>

</div>
</body>
</html>

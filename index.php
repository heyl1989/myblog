<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
session_start();
$user = $_SESSION['user'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/myblog/lib/db.php';
$db = new Db();
$path = 'index.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pageSize = 5;

$cid = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;
$where = [];
if ($cid) {
    $where['cid'] = $cid;
    $path .= "?cid={$cid}";
}
$data = $db->table('blog_article')->field('id,title,pv,add_time')->where($where)->pages($page, $pageSize, $path);
//echo '<pre>';
//print_r($data);
$articles = $data['data'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢迎访问我的博客</title>
    <link rel="stylesheet" type="text/css" href="static/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="static/css/site.css">
    <script type="text/javascript" src="static/js/jquery.min.js"></script>
    <script type="text/javascript" src="static/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="static/js/UI.js"></script>
</head>
<body>
<div class="header">
    <div class="container">
        <span class="title"><?php echo $user['username'] ?>大官人的博客</span>
        <div class="search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="输入标题搜索">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">搜索</button>
                </span>
            </div>
        </div>
        <div class="login-reg">
            <?php if ($user) { ?>
                <span><?php echo $user['username'] ?></span><a href="javascript:;" onclick="logout()">退出</a>
            <?php } else { ?>
                <button type="button" class="btn btn-success" onclick="login()">登录</button>
            <?php } ?>
            <button type="button" class="btn btn-warning" onclick="add_article()">发表博客</button>
        </div>
    </div>
</div>
<div class="main container">
    <div class="col-lg-3 left-container">
        <p class="cates">博客分类</p>
        <div class="cate-list">
            <?php
            $cate_db = new Db();
            $cates = $cate_db->table('cates')->list();
            foreach ($cates as $cate) {
                ?>
                <div class="cate-item"><a
                            href="index.php?cid=<?php echo $cate['id'] ?>"><?php echo $cate['title'] ?></a></div>
            <?php } ?>
        </div>
    </div>
    <div class="col-lg-9 right-container">
        <div class="nav">
            <a>热门</a>
            <a class="active">最新</a>
        </div>

        <div class="content-list">
            <?php foreach ($articles as $article) { ?>
                <div class="content-item">
                    <img src="static/image/avatar.png">
                    <div class="title">
                        <p><a href="detail.php?aid=<?php echo $article['id'] ?>"><?php echo $article['title'] ?></a></p>
                        <div><span><?php echo $article['pv'] == "" ? '0' : $article['pv'] ?>
                                次浏览</span><span><?php echo date("Y/m/d", $article['add_time']) ?></span></div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!--    分页-->
        <div><?php echo $data['pages'] ?></div>
    </div>
</div>

</body>
</html>

<script type="text/javascript">
    //登录
    function login() {
        // UI.alert({title:'系统消息提示',message:'请输入用户名',icon:'ok'});
        UI.open({title: '登录', url: 'login.php', width: 450, height: 300});
    }

    //退出登录
    function logout() {
        if (!confirm('确定要退出吗？')) {
            return;
        }
        $.get('service/logout.php', {}, function (res) {
            if (res.code > 0) {
                UI.alert({message: res.msg, icon: 'error'});
            } else {
                UI.alert({message: res.msg, icon: 'ok'});
                setTimeout(function () {
                    parent.window.location.reload();
                }, 1000)
            }
        }, 'json');
    }

    //发表博客
    function add_article() {
        UI.open({title: '发表博客', url: 'add_article.php', width: 750, height: 650});

    }

</script>
<?php
/**文章详情
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4
 * Time: 15:45
 */
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
session_start();
$user = $_SESSION['user'];

$aid = (int)$_GET['aid'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/myblog/lib/db.php';
$db = new Db();

$article = $db->table('blog_article')->where(array('id' => $aid))->item();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $article['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="static/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="static/css/site.css">
    <script type="text/javascript" src="static/js/jquery.min.js"></script>
    <script type="text/javascript" src="static/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="static/js/UI.js"></script>
    <style type="text/css">
        .content-list .title {
            text-align: center;
            font-size: 18px;
            margin: 20px 0px;
            color: #666666;
        }

        .content-list .time {
            float: right;
            font-size: 12px;
            color: #666666;
        }

    </style>
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
            <p class="title"><?php echo $article['title'] ?></p>
            <P><span class="time">发布时间：<?php echo date("Y/m/d", $article['add_time']) ?></span></P>
            <div style="clear: both"></div>
            <hr>
            <div class="content">
                <?php echo htmlspecialchars_decode($article['contents']) ?>
            </div>
        </div>
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

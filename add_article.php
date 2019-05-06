<?php

session_start();
$user = $_SESSION['user'];
if (!$user) {
    exit('你还没有登录，请先登录');
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/myblog/lib/db.php';
$db = new Db();
$cates = $db->table('cates')->list();
?>

<!DOCTYPE html>
<html>
<head>
    <title>发表博客</title>
    <link rel="stylesheet" type="text/css" href="static/plugins/bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="static/js/jquery.min.js"></script>
    <script type="text/javascript" src="static/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="static/js/UI.js"></script>
    <script type="text/javascript" src="static/plugins/wangEditor/release/wangEditor.min.js"></script>
    <style type="text/css">
        .form {
            margin: 10px 0px;
        }

        .form .input-group-sm {
            margin: 20px;
        }
    </style>
</head>
<body>
<div class="form">
    <div class="input-group input-group-sm">
        <span class="input-group-addon">博客标题</span>
        <input type="text" class="form-control" name="title" placeholder="请输入博客标题">
    </div>
    <div class="input-group input-group-sm">
        <span class="input-group-addon">博客分类</span>
        <select class="form-control" name="cid">
            <?php foreach ($cates as $cate) { ?>
                <option value="<?php echo $cate['id'] ?>"><?php echo $cate['title'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="input-group input-group-sm">
        <span class="input-group-addon">关键字</span>
        <input type="text" class="form-control" name="keywords" placeholder="请输入博客关键字">
    </div>
    <div class="input-group input-group-sm">
        <span class="input-group-addon">博客描述</span>
        <input type="text" class="form-control" name="describe" placeholder="请输入博客描述">
    </div>
    <div class="input-group input-group-sm">
        <span class="input-group-addon">博客内容</span>
        <div id="editor"></div>
    </div>
    <button type="button" class="btn btn-primary" style="float: right" onclick="save()">保存</button>
</div>
</body>
</html>

<script type="text/javascript">
    var editor;

    function initEditor() {
        var E = window.wangEditor;
        editor = new E('#editor');
        editor.customConfig.zIndex = 1;
        // 或者 var editor = new E( document.getElementById('editor') )
        editor.customConfig.uploadImgServer = 'service/upload.php';  // 上传图片到服务器
        editor.customConfig.uploadFileName = 'file_image';
        editor.customConfig.customAlert = function (info) {
            // info 是需要提示的内容
            UI.alert({message: info, icon: 'error'});
        };
        editor.create();
    }

    initEditor();

    //保存
    function save() {
        var data = {};
        data.title = $.trim($('input[name="title"]').val());
        data.cid = $.trim($('select[name="cid"]').val());
        data.keywords = $.trim($('input[name="keywords"]').val());
        data.describe = $.trim($('input[name="describe"]').val());
        data.contents = editor.txt.html();
        // alert(data.contents);
        if (data.title == '') {
            UI.alert({message: '请输入博客标题', icon: 'error'});
            return;
        }
        if (data.contents == '<p><br></p>') {
            UI.alert({message: '请输入博客内容', icon: 'error'});
            return;
        }
        // alert(JSON.stringify(data));
        $.post('service/save_article.php', data, function (res) {
            if (res.code > 0) {
                UI.alert({message: res.msg, icon: 'error'});
            }else{
                UI.alert({message: res.msg, icon: 'ok'});
                setTimeout(function () {
                    parent.window.location.reload();
                },1000)
            }
        }, 'json');
    }

</script>
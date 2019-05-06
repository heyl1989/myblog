<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/3
 * Time: 9:32
 */

//退出登录
session_start();
$_SESSION['user'] = null;
exit(json_encode(array('code' => 0, 'msg' => '退出成功')));
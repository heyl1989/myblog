<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/26
 * Time: 22:20
 */
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

//header("content-type:text/html;charset=utf-8");
$dsn = 'mysql:host=localhost;dbname=myblog;charset=utf8';
$username = 'root';
$pwd = 'heyl1989715';
$pdo = new PDO($dsn,$username,$pwd);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//捕获pdo错误日志
//$sql = 'select * from cates where id = :id';
//$sql = 'update cates set title="其他分类" where id = :id';
$sql = 'INSERT INTO `cates`(`title`) VALUES (:title)';
$stmt = $pdo->prepare($sql);
//$stmt->bindValue(':id',10);
$stmt->bindValue(':title','php分类');
$stmt->execute();
//$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$id = $pdo->lastInsertId();
var_dump($id);
echo '<pre>';
//print_r($rows);
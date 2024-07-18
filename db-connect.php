<?php

require __DIR__ .'/connect-setting.php';#這裡include=require 但require語氣較強烈

// echo $db_name;

$dsn ="mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";



$pdo_options =[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo =new PDO($dsn,$db_user,$db_pass,$pdo_options);

if(! isset($_SESSION)) {
    //如果尚未啟動 SESSION 的功能 就啟動
session_start();

}
/*
#執行一個sql語法 取得PDOStatement
$stmt = $pdo->query("SELECT * FROM address_book LIMIT 3");
#呼叫fetch() 取得一筆資料
$row = $stmt->fetch();
#輸出json格式
echo json_encode($row);
*/

<?php

if(!isset($_SESSION)){
  //如果尚未啟動session的功能就啟動
  session_start();
}

if (!isset($_SESSION["admin"])) {
  #如果沒有登入就會跳轉登入介面
  header('Location: login.php');
  exit;
}
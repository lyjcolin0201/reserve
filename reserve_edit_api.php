<?php
require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/db-connect.php';
header('Content-Type: application/json');

$output = [
  'success' => false,
  'bodyData' => $_POST, # 除錯用
  'code' => 0, # 除錯用
];


// TODO: 表單欄位的資料檢查
$reserve_id = isset($_POST['reserve_id']) ? intval($_POST['reserve_id']) : 0;
if (empty($reserve_id)) {
  $output['code'] = 400;
  echo json_encode($output);
  exit;
}
$name = $_POST['customer_name'] ?? ''; # ?? 如果 ?? 的左邊為 undefined, 就使用右邊的值
if (mb_strlen($name) < 2) {
  $output['code'] = 405;
  echo json_encode($output);
  exit;
}


$rday = $_POST['date'];
$ts = strtotime($rday); # 轉換成 timestamp
if ($ts === false) {
  $rday = null; # 如果不是日期的格式, 就使用 null
  # 下列兩行測試用
  // echo json_encode($output);
  // exit;
} else {
  $rday = date('Y-m-d', $ts);
}

/*
// 錯誤的作法: SQL injection 的問題
$sql = "INSERT INTO `address_book`( 
  `name`, `email`, `mobile`,
  `birthday`, `address`, `created_at`
  ) VALUES (
    '{$_POST['name']}', '{$_POST['email']}', '{$_POST['mobile']}',
    '{$_POST['birthday']}', '{$_POST['address']}', NOW()
  )";
$stmt = $pdo->query($sql);
*/
$sql = "UPDATE `reservations` SET 
    `customer_name`=?,
    `contact_number`=?,
    `store`=?,
    `date`=?,
    `time`=?,
    `count`=?
    WHERE `reserve_id`=? ";


// $sql = "INSERT INTO `reservations`( 
//   `customer_name`, `contact_number`,
//   `store`, `date`, `time`, `count`,
//   `created_at`
//   ) VALUES (
//     ?, ?, ?, 
//     ?, ?, ?,
//     NOW()
//   )";

$stmt = $pdo->prepare($sql); # 準備 sql 語法, 除了 "值" 語法要合法
$stmt->execute([
  $name,
  $_POST['contact_number'],
  $_POST['store'],
  $rday,
  $_POST['time'],
  $_POST['count'],
  $reserve_id,

]);

$output['success'] = !!$stmt->rowCount();
echo json_encode($output);

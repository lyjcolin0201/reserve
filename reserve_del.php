<?php
require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/db-connect.php';

$reserve_id = isset($_GET['reserve_id']) ? intval($_GET['reserve_id']) : 0;
if (!empty($reserve_id)) {
  $sql = "DELETE FROM reservations WHERE reserve_id=$reserve_id";
  $pdo->query($sql);
}
$come_from = "index_.php";
# 如果有 referer 的 url, 就使用 referer url
if (isset($_SERVER['HTTP_REFERER'])) {
  $come_from = $_SERVER['HTTP_REFERER'];
}

header('Location: '. $come_from);


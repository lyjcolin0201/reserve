<?php

session_start();
if (isset($_SESSION["admin"])) {
  include __DIR__ . '/list_reserve_admin.php';
} else {
  include __DIR__ . '/list_reserve_no_admin.php';
}

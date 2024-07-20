<?php

session_start();
if (isset($_SESSION["admin"])) {
  include __DIR__ . '/reserve_list_admin.php';
} else {
  include __DIR__ . '/reserve_list_no_admin.php';
}

<?php
if (!isset($pageName)) {
  $pageName = "";
}
?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=LXGW+WenKai+TC&display=swap');

  * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    font-family: "LXGW WenKai TC", cursive;
    font-weight: 400;
    font-style: normal;
  }

  body {
    /* min-height: 100vh; */
    background-color: #000000;
    background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
    /* This is mostly intended for prototyping; please download the pattern and re-host for production environments. Thank you! */
    /* background-size: cover;
    background-position: center; */
    
  }

  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 80px;
    height: 100%;
    background: transparent;
    backdrop-filter: blur(40px);
    padding: 6px 14px;
    transition: .5s;
    border-right: 2px solid rgba(255, 255, 255, .2);
    box-shadow: 0 0 10px rgba(0, 0, 0, .2);
  }

  .sidebar.active {
    width: 260px;

  }

  .sidebar .logo-menu {
    display: flex;
    align-items: center;
    width: 100%;
    height: 70px;
  }

  .sidebar .logo-menu .logo {
    font-size: 25px;
    color: #ffffff;
    pointer-events: none;
    opacity: 0;
    transition: .3s;
  }

  .sidebar.active .logo-menu .logo {
    opacity: 1;
    transition-delay: .2s;
  }

  .sidebar .logo-menu .fa-solid {
    position: absolute;
    left: 50%;
    transform: translate(-50%);
    width: 40px;
    height: 40px;
    font-size: 22px;
    color: #f4772f;
    text-align: center;
    line-height: 40px;
    cursor: pointer;
    transform: .5s;
  }

  .sidebar.active .logo-menu .fa-solid {
    left: 90%;
  }

  .sidebar .list {
    margin-top: 10px;
    padding: 0;

  }

  .list .list-item {
    list-style: none;
    width: 100%;
    height: 50px;
    margin: 5px 0;
    line-height: 50px;
  }

  .list .list-item a {
    display: flex;
    align-items: center;
    font-style: 18px;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    white-space: nowrap;
    transition: .5s;

  }

  .list .list-item.active a,
  .list .list-item a:hover,
  a.nav_link.active {
    background: rgba(255, 255, 255, .2);
  }



  .list .list-item a i {
    min-width: 50px;
    height: 50px;
    text-align: center;
    line-height: 50px;
    color: #f4772f;
  }

  .sidebar .link-name {
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s;
  }

  .sidebar.active .link-name {
    opacity: 1;
    pointer-events: auto;
    transition-delay: calc(.1s * var(--i));
  }
</style>

<nav class="sidebar">
  <div class="logo-menu">
    <h2 class="logo">Coffee</h2>
    <i class="fa-solid fa-bars"></i>

  </div>

  <ul class="list">
    <li class="list-item">
      <a href="#">
        <i class="fa-solid fa-user-pen"></i>
        <span class="link-name" style="--i:1">會員管理</span>
      </a>
    </li>
    <li class="list-item">
      <a href="#">
        <i class="fa-solid fa-mug-saucer"></i>
        <span class="link-name" style="--i:2">訂單管理</span>
      </a>
    </li>
    <li class="list-item">
      <a href="#">
        <i class="fa-solid fa-cart-flatbed"></i>
        <span class="link-name" style="--i:3">購物車管理</span>
      </a>
    </li>
    <li class="list-item">
      <a href="#">
        <i class="fa-solid fa-house-circle-check"></i>
        <span class="link-name" style="--i:4">門市管理</span>
      </a>
    </li>
    <li class="list-item">
      <a href="./index_.php">
        <i class="fa-solid fa-address-card"></i>
        <span class="link-name" style="--i:5">預約管理</span>
      </a>
    </li>
  </ul>

  <ul class="list mt-5">
    <li class="list-item">
      <a class="nav_link <?= $pageName == 'ab_list' ? 'active' : '' ?>" href="index_.php"> <i class="fa-regular fa-rectangle-list"></i><span class="link-name" style="--i:6">列表頁</span></a>
    </li>
    <li class="list-item">
      <a class="nav_link <?= $pageName == 'ab_add' ? 'active' : '' ?>" href="add_reserve.php"><i class="fa-solid fa-plus"></i><span class="link-name" style="--i:7">新增通訊錄</span></a>
    </li>
  </ul>

  <ul class="list mt-5">
    <?php if (isset($_SESSION["admin"])) : ?>
      <li class="list-item">
        <a class="nav_link"><i class="fa-regular fa-face-smile"></i><span class="link-name" style="--i:8"><?= $_SESSION["admin"]['nickname'] ?></span></a>
      </li>
      <li class="list-item">
        <a class="nav_link" href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="link-name" style="--i:9">登出</span></a>
      </li>
    <?php else : ?>
      <li class="list-item">
        <a class="nav_link <?= $pageName == 'login' ? 'active' : '' ?>" href="login.php"><i class="fa-solid fa-gears"></i><span class="link-name" style="--i:9">登入</span></a>
      </li>
    <?php endif; ?>
  </ul>
</nav>
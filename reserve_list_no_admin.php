<?php
$title = "訂位名單";
$pageName = "r_list";

$perPage = 20; # 表示一頁最多有 20 筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ./'); # 跳轉頁面
  exit; # 結束程式, die()
}

require __DIR__ . '/db-connect.php';
$t_sql = "SELECT COUNT(1) FROM reservations";
# 取得總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = 0;
$rows = [];
if ($totalRows) {
  # 計算總頁數
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages); # 跳轉頁面到最後一頁
    exit; # 結束程式
  }

  # 取得該頁的資料
  $sql = sprintf(
    "SELECT * FROM reservations ORDER BY reserve_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );

  $rows = $pdo->query($sql)->fetchAll();
}

?>
<?php include __DIR__ . "/parts/html-head.php"; ?>
<?php include __DIR__ . "/parts/navbar.php"; ?>
<div class="container">
  <div class="row">
    <div class="col">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
            if ($i >= 1 && $i <= $totalPages) :
          ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
          <?php
            endif;
          endfor; ?>
        </ul>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>reserve_id</th>
            <th>customer_name</th>
            <th>mobile</th>
            <th>store</th>
            <th>date</th>
            <th>time</th>
            <th>count</th>
            <th>created_at</th>
           
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><?= $r['reserve_id'] ?></td>
              <td><?= $r['customer_name'] ?></td>
              <td><?= $r['contact_number'] ?></td>
              <td><?= $r['store'] ?></td>
              <td><?= $r['date'] ?></td>
              <td><?= $r['time'] ?></td>
              <td><?= $r['count'] ?></td>
              <td><?= $r['created_at'] ?></td>
             
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/parts/script.php"; ?>
<?php include __DIR__ . "/parts/html-foot.php"; ?>
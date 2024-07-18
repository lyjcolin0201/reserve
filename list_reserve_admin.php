<?php
require __DIR__ . './parts/admin-required.php';
$title = "訂位名單";
$pageName = "r_list";

$perPage = 20; # 表示一頁最多有 20 筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ./'); # 跳轉頁面
  exit; # 結束程式, die()
}

require __DIR__ . '/db-connect.php';

// 獲取查詢條件
$reserve_id = isset($_GET['reserve_id']) ? $_GET['reserve_id'] : '';
$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : '';
$number = isset($_GET['contact_number']) ? $_GET['contact_number'] : '';
$d_from = isset($_GET['date']) ? $_GET['date'] : '';
$d_to = isset($_GET['date']) ? $_GET['date'] : '';

$conditions = [];
$params = [];

// 建構條件
if (!empty($reserve_id)) {
  $conditions[] = "reserve_id = ?";
  $params[] = $reserve_id;
}

if (!empty($customer_name)) {
  $conditions[] = "customer_name LIKE ?";
  $params[] = '%' . $customer_name . '%';
}

// 預約日期範圍
// if (!empty($d_from)) {
//   $conditions[] = "date >= ?";
//   $params[] = $d_from;
// }

// if (!empty($d_to)) {
//   $conditions[] = "date <= ?";
//   $params[] = $d_to;
// }

// 建構sql查詢
$sql = "SELECT * FROM reservations";
if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY reserve_id DESC LIMIT " . (($page - 1) * $perPage) . ", " . $perPage;

// 查數據
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();


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
<?php include __DIR__ . "./parts/html-head.php"; ?>
<style>
        .search-results {
            margin-top: 20px;
        }
        .search-results ul {
            list-style-type: none;
            padding: 0;
        }
        .search-results li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-results li hr {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
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
  <!-- search start -->
  <div class="container">
  <div class="row mt-5">
    <div class="mt-2">
      <form action="list_reserve_admin.php" method="get">
        <label for="reserve_id" style="color:#ffffff;">預約編號:</label>
        <input class="text-dark" type="text" name="reserve_id" id="reserve_id" placeholder="請輸入編號" class="me-2" value="<?= htmlspecialchars($reserve_id) ?>">
        <label for="customer_name" style="color:#ffffff;">姓名:</label>
        <input class="text-dark" type="text" name="customer_name" id="customer_name" placeholder="請輸入姓名" class="me-2" value="<?= htmlspecialchars($customer_name) ?>">
        <!-- <label for="name" style="color:#ffffff;">生日:</label>
        <input class="text-dark" type="date" name="date"> <span style="color:#ffffff;">~</span>
        <input class="text-dark" type="date" name="date"> -->
        <button type="submit" class="btn btn-dark ms-2 p-1 px-3">查詢</button>
      </form>
    </div>
  </div>
</div>


  <!-- search end -->
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
              <td><a href="javascript: deleteOne(<?= $r['reserve_id'] ?>)">
                  <i class="fa-solid fa-trash"></i>
                </a></td>
                <td><a href="edit_reserve.php?reserve_id=<?= $r['reserve_id'] ?>">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/parts/scripts.php"; ?>
<script>
  const data = <?= json_encode($rows)  ?>;
  const deleteOne = (reserve_id) => {
    if (confirm(`是否要刪除編號為 ${reserve_id} 的資料??`)) {
      location.href = `del_reserve.php?reserve_id=${reserve_id}`;
    }
  };
  //searh
  // $(document).ready(function() {
  //           $('#search-form').submit(function(event) {
  //               event.preventDefault(); // 阻止表单提交的默认行为

  //               var keyword = $('#keyword').val();

  //               // 发送 AJAX 请求
  //               $.ajax({
  //                   type: 'GET',
  //                   url: 'search.php',
  //                   data: {
  //                       keyword: keyword
  //                   },
  //                   dataType: 'html',
  //                   success: function(response) {
  //                       $('#search-results').html(response); // 更新结果显示区域的内容
  //                   },
  //                   error: function(xhr, status, error) {
  //                       console.error('Error:', error);
  //                   }
  //               });
  //           });
  //       });
</script>

<?php include __DIR__ . "/parts/html-foot.php"; ?>
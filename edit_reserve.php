<?php
require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/db-connect.php';

$reserve_id = isset($_GET['reserve_id']) ? intval($_GET['reserve_id']) : 0;
if (empty($reserve_id)) {
  header('Location: index_.php'); exit;
}
$sql = "SELECT * FROM reservations WHERE reserve_id=$reserve_id";


$r = $pdo->query($sql)->fetch();
if (empty($r)) {
  header('Location: index_.php'); exit;
}

# header('Content-Type: application/json'); # 告訴瀏覽器內容為 JSON
# echo json_encode($r);
?>
<?php include __DIR__ . "/parts/html-head.php"; ?>
<style>
  .container {
    color: black;
  }
  form .mb-3 .form-text {
    color: red;
  }
</style>
<?php include __DIR__ . "/parts/navbar.php"; ?>
<div class="container">
  <div class="row">
    <div class="col-6">
      <div class="card">

        <div class="card-body">
          <h5 class="card-title">編輯訂位資訊</h5>

          <form name="form1" onsubmit="sendData(event)" novalidate>
            <input type="hidden" name="reserve_id" value="<?= $r['reserve_id'] ?>">
            <div class="mb-3">
              <label for="customer_name" class="form-label text-dark">姓名</label>
              <input type="text" class="form-control" name="customer_name" 
              value="<?= htmlentities($r['customer_name']) ?>" id="customer_name" required>
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="contact_number" class="form-label text-dark">手機</label>
              <input type="text" class="form-control" name="contact_number"
              value="<?= $r['contact_number'] ?>" id="contact_number">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="store" class="form-label text-dark">分店</label>
              <input type="text" class="form-control" name="store"
              value="<?= $r['store'] ?>" id="store">
            </div>
            <div class="mb-3">
              <label for="date" class="form-label text-dark">預約日期</label>
              <input type="date" class="form-control" name="date"
              value="<?= $r['date'] ?>" id="date">
            </div>
            <div class="mb-3">
              <label for="time" class="form-label text-dark">用餐時間</label>
              <input type="time" class="form-control" name="time"
              value="<?= $r['time'] ?>" id="time">

              <div class="mb-3">
              <label for="count" class="text-dark">用餐人數：</label>
    <select class="text-dark" id="count" name="count" required>
        <option value="1">1人</option>
        <option value="2">2人</option>
        <option value="3">3人</option>
        <option value="4">4人</option>
        <option value="5">5人</option>
        <option value="6">6人</option>
    </select>
    </div>
            </div>
            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">新增結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          編輯成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
        <a href="index_.php" class="btn btn-primary">到列表頁</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/parts/scripts.php"; ?>
<script>
  const nameField = document.form1.customer_name;
  const mobileField = document.form1.contact_number;
  const modal = new bootstrap.Modal('#exampleModal');
  const modalBody = document.querySelector('#exampleModal .modal-body');

  function validateMobile(contact_number) {
    const re =/^[0][1-9]{1,3}[0-9]{6,8}$/;
    return re.test(contact_number);
  }

  const sendData = e => {
    e.preventDefault(); // 不要使用傳統的表單送出, 使用 AJAX
    // 重置錯誤訊息
    nameField.nextElementSibling.innerHTML = '';
    nameField.style.border = '1px solid #CCC';
    mobileField.nextElementSibling.innerHTML = '';
    mobileField.style.border = '1px solid #CCC';

    let isPass = true; // 表單有沒有通過檢查

    // TODO: 表單欄位的資料檢查
    if (nameField.value.length < 2) {
      isPass = false;
      nameField.nextElementSibling.innerHTML = '請填寫正確的姓名';
      nameField.style.border = '1px solid red';
    }
    if (!validateMobile(mobileField.value)) {
      isPass = false;
      mobileField.nextElementSibling.innerHTML = '請填寫正確的 手機號碼';
      mobileField.style.border = '1px solid red';
    }

    if (isPass) {
      // FormData 的個體看成沒有外觀的表單
      const fd = new FormData(document.form1);

      fetch('edit_reserve_api.php', {
          method: 'POST',
          body: fd, // enctype: multipart/form-data
        }).then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            modalBody.innerHTML = `
            <div class="alert alert-success" role="alert">
              編輯成功
            </div>`;
          } else {
            modalBody.innerHTML = `
            <div class="alert alert-danger" role="alert">
              沒有編輯
            </div>`;
          }
          modal.show();
        })
        .catch(ex => console.log(ex))
    }
  };
</script>
<?php include __DIR__ . "/parts/html-foot.php"; ?>
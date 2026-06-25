<!DOCTYPE html>
<html lang="en">
<head>
  <title>Đăng nhập</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h3>Đăng nhập hệ thống</h3>
      
  <form action="xuly_dangnhap.php" class="was-validated" method = "post">
    <div class="mb-3 mt-3">
      <label for="uname" class="form-label">Username:</label>
      <input type="text" class="form-control" id="uname" placeholder="Nhập username" name="uname" required>
      <div class="valid-feedback">Hợp lệ.</div>
      <div class="invalid-feedback">Vui lòng điền đủ thông tin.</div>
    </div>
    <div class="mb-3">
      <label for="pwd" class="form-label">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required>
      <div class="valid-feedback">Hợp lệ.</div>
      <div class="invalid-feedback">Vui lòng điền đủ thông tin.</div>
    </div>
    <div class="form-check mb-3">
      <a " href = "registry.php">Đăng ký</a>
    </div>
  <button type="submit" class="btn btn-primary" name = "sbDangNhap">Đăng nhập</button>
  </form>
</div>

</body>
</html>

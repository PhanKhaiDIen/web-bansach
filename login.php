<!DOCTYPE html>
<html lang="vi">
<head>
  <title>Đăng nhập hệ thống</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    .login-box {
      max-width: 450px;
      margin: 60px auto;
      background: #ffffff;
      padding: 35px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="login-box">
    <h3 class="text-center fw-bold text-primary mb-4">ĐĂNG NHẬP HỆ THỐNG</h3>
        
    <form action="xuly_dangnhap.php" class="was-validated" method="post">
      <div class="mb-3 mt-3">
        <label for="uname" class="form-label fw-medium">Username:</label>
        <input type="text" class="form-control" id="uname" placeholder="Nhập username" name="uname" required>
        <div class="valid-feedback">Hợp lệ.</div>
        <div class="invalid-feedback">Vui lòng điền đủ thông tin.</div>
      </div>
      <div class="mb-3">
        <label for="pwd" class="form-label fw-medium">Password:</label>
        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required>
        <div class="valid-feedback">Hợp lệ.</div>
        <div class="invalid-feedback">Vui lòng điền đủ thông tin.</div>
      </div>
      <div class="mb-4">
        Chưa có tài khoản? <a href="registry.php" class="text-decoration-none">Đăng ký ngay</a>
      </div>
      
      <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg fs-6" name="sbDangNhap">Đăng nhập</button>
      </div>
    </form>

    <div class="d-flex align-items-center my-4">
      <div class="flex-grow-1" style="height: 1px; background: #e0e0e0;"></div>
      <span class="px-3 text-muted small text-uppercase">Hoặc</span>
      <div class="flex-grow-1" style="height: 1px; background: #e0e0e0;"></div>
    </div>

    <div class="d-grid">
      <button type="button" id="btn-google-login" class="btn btn-outline-danger d-flex align-items-center justify-content-center py-2 fw-medium">
        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="18" height="18" class="me-2" alt="Google">
        Tiếp tục với Google
      </button>
    </div>
  </div>
</div>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
  import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-auth.js";

  // XÓA ĐOẠN MẪU NÀY ĐI VÀ DÁN ĐOẠN THỰC TẾ CỦA BẠN LẤY Ở BƯỚC 1.5 VÀO ĐÂY:
  const firebaseConfig = {
  apiKey: "AIzaSyADr0HogKC8h0n7ze2laYxcQjdriG3Jz54",
  authDomain: "bookstore-auth-ba606.firebaseapp.com",
  projectId: "bookstore-auth-ba606",
  storageBucket: "bookstore-auth-ba606.firebasestorage.app",
  messagingSenderId: "894165049941",
  appId: "1:894165049941:web:0df13c406595d2e05daadc",
  measurementId: "G-VLKHP53NRE"
  };

  // Khởi chạy Firebase tại trình duyệt
  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const provider = new GoogleAuthProvider();

  // Bắt sự kiện khi bấm nút Google
  document.getElementById('btn-google-login').addEventListener('click', () => {
      // Mở Pop-up đăng nhập của Google
      signInWithPopup(auth, provider)
      .then((result) => {
          const user = result.user;
          
          // Lấy chuỗi Token bảo mật do Google cấp phát sau khi User đăng nhập thành công
          user.getIdToken().then((idToken) => {
              
              // Tạo một Form ẩn bằng Javascript để tự động chuyển Token này về Server xử lý qua PHP
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = 'xuly_firebase.php';

              const inputToken = document.createElement('input');
              inputToken.type = 'hidden';
              inputToken.name = 'idToken';
              inputToken.value = idToken;

              form.appendChild(inputToken);
              document.body.appendChild(form);
              form.submit(); // Tự động click submit chuyển trang ngầm sang file xuly_firebase.php
          });
      })
      .catch((error) => {
          console.error("Lỗi xác thực Google:", error);
          alert("Có lỗi xảy ra hoặc bạn đã hủy thao tác đăng nhập!");
      });
  });
</script>

</body>
</html>
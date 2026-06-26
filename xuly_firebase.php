<?php
session_start();
// Kết nối tới database MySQL của bạn
include_once("connect.php");

// Kiểm tra xem có nhận được mã idToken từ file login.php gửi sang không
if (isset($_POST['idToken'])) {
    $idToken = $_POST['idToken'];

    // 1. Dán API Key của bạn lấy ở Bước 1.5 vào đây để xác thực với Google
    $apiKey = getenv('FIREBASE_API_KEY');
    $url = "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=" . $apiKey;
    
    // Đóng gói dữ liệu gửi lên máy chủ Google xác thực ngầm
    $data = json_encode(['idToken' => $idToken]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    curl_close($ch);

    // Nhận kết quả giải mã từ Google
    $result = json_decode($response, true);

    // Nếu thông tin trả về hợp lệ và tồn tại tài khoản Google
    if (isset($result['users'][0])) {
        $googleUser = $result['users'][0];
        
        $uid = $conn->real_escape_string($googleUser['localId']); // Mã UID định danh duy nhất của Firebase
        $email = $conn->real_escape_string($googleUser['email']); // Địa chỉ Gmail của khách
        $name = isset($googleUser['displayName']) ? $conn->real_escape_string($googleUser['displayName']) : $email; // Tên hiển thị

        // 2. Kiểm tra xem tài khoản Google này đã từng đăng nhập vào web của bạn chưa
        $sql = "SELECT * FROM accounts WHERE firebase_uid = '$uid' OR Username = '$email'";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            // NẾU ĐÃ TỒN TẠI: Lấy dữ liệu cũ ra để gán vào Session đăng nhập
            $userRow = $res->fetch_assoc();
            
            // Cập nhật lại cột firebase_uid phòng trường hợp tài khoản cũ đăng nhập bằng Google lần đầu
            if (empty($userRow['firebase_uid'])) {
                $conn->query("UPDATE accounts SET firebase_uid = '$uid' WHERE Username = '$email'");
            }

            $_SESSION["Name"] = $userRow["Name"];
            $_SESSION["Role"] = $userRow["Role"]; // Giữ nguyên phân quyền cũ của tài khoản
        } else {
            // NẾU LÀ TÀI KHOẢN MỚI TOANH: Tự động đăng ký thêm thành viên mới vào bảng MySQL
            // Mặc định cài mật khẩu ẩn, Role = 0 (khách hàng bình thường)
            $insertSql = "INSERT INTO accounts (Username, Password, Name, Role, firebase_uid) 
                          VALUES ('$email', 'firebase_auth_protected', '$name', 0, '$uid')";
            
            if ($conn->query($insertSql) === TRUE) {
                $_SESSION["Name"] = $name;
                $_SESSION["Role"] = 0; // Tài khoản mới luôn là Khách hàng
            } else {
                echo "<div style='color:red; font-family:sans-serif; text-align:center; margin-top:50px;'>";
                echo "<h3>Lỗi hệ thống database khi tạo tài khoản tự động!</h3>";
                echo "<p>" . $conn->error . "</p>";
                echo "</div>";
                exit();
            }
        }

        // 3. ĐĂNG NHẬP THÀNH CÔNG -> Chuyển hướng người dùng thẳng về trang chủ xem sách
        header("Location: trangchu.php");
        exit();

    } else {
        echo "<script>alert('Mã xác thực từ Google không hợp lệ hoặc đã hết hạn! Vui lòng thử lại.'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Không nhận được dữ liệu token bảo mật!'); window.location.href='login.php';</script>";
}
?>
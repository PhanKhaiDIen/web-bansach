<?php
    include_once("connect.php");

    if (isset($_POST["sbDangKy"])) 
    {
        // Chỉ lấy dữ liệu và chạy SQL khi người dùng thực sự bấm nút Submit Đăng ký
        $ten = $_POST["uname"];
        $mk = md5($_POST["pswd"]);
        $q = "2"; // Gán mặc định quyền khách hàng là 2

        // Đưa câu lệnh INSERT vào bên trong khối IF
        $sql = "INSERT INTO accounts (Username, Pass, RoleID) VALUES ('$ten', '$mk', '$q')";
        
        if ($conn->query($sql) === TRUE) 
        {
            // Đăng ký thành công thì chuyển hướng sang trang đăng nhập
            header("Location: login.php");
            exit(); // Thêm exit để dừng hẳn script sau khi redirect
        } 
        else 
        {
            echo "Lỗi truy vấn: " . $sql . "<br>" . $conn->error;
        }
    } 
    else 
    {
        // Nếu người dùng truy cập lén vào file này mà không qua form, đẩy họ về trang đăng ký
        header("Location: registry.php");
        exit();
    }
?>
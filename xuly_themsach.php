<?php
    //1-Kết nối cơ sở dữ liệu
    include_once("connect.php");
    
    $target_dir = "images/";
    $file = "no-image.png"; // Thiết lập một tên ảnh mặc định nếu người dùng không upload ảnh
    $uploadOk = 1; // Mặc định là cho phép chạy tiếp

    // Kiểm tra xem người dùng CÓ CHỌN FILE và FILE TẢI LÊN THÀNH CÔNG hay không
    if (isset($_FILES["fileHinh"]) && $_FILES["fileHinh"]["error"] == UPLOAD_ERR_OK) {
        $target_file = $target_dir . basename($_FILES["fileHinh"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Kiểm tra xem file có phải ảnh thật không
        $check = getimagesize($_FILES["fileHinh"]["tmp_name"]);
        if($check !== false) {
            // Kiểm tra định dạng file ảnh
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
                // Nếu mọi thứ ổn, tiến hành di chuyển file vào thư mục images
                if (move_uploaded_file($_FILES["fileHinh"]["tmp_name"], $target_file)) {
                    $file = $_FILES["fileHinh"]["name"]; // Đổi tên file lưu vào DB thành tên ảnh vừa upload
                } else {
                    echo "Sorry, there was an error uploading your file.<br>";
                    $uploadOk = 0;
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
                $uploadOk = 0;
            }
        } else {
            echo "File tải lên không phải là hình ảnh thực sự.<br>";
            $uploadOk = 0;
        }
    } 
    // Nếu người dùng không chọn ảnh (hoặc ảnh quá nặng bị hủy), 
    // code sẽ bỏ qua khối lệnh IF trên, giữ nguyên $file = "no-image.png" và $uploadOk = 1 để chạy tiếp xuống dưới.

    //2-Lấy dữ liệu từ form
    $dm = $mota = $gia = $tacgia = $isbn = $ten = $sl = "";
    if(!empty($_POST["txtISBN"])
        && !empty($_POST["slDanhMuc"])
        && !empty($_POST["txtTen"])
        && !empty($_POST["txtTacGia"])
        && !empty($_POST["txtGia"])
        && !empty($_POST["txtMoTa"])
    )
    {
        $dm = $_POST["slDanhMuc"];
        $mota = $_POST["txtMoTa"];
        $gia = $_POST["txtGia"];
        $tacgia = $_POST["txtTacGia"];
        $isbn = $_POST["txtISBN"];
        $ten = $_POST["txtTen"];
        $sl = $_POST["txtSoluong"];
    }
    
    // Chỉ thực hiện INSERT khi kiểm tra dữ liệu form đầy đủ và không dính lỗi upload ảnh thực sự
    if ($uploadOk == 1 && !empty($isbn) && !empty($ten)) {
        //3-Viết câu truy vấn (Biến $file lúc này sẽ là tên ảnh thực tế HOẶC "no-image.png")
        $sql = "INSERT INTO books(ISBN, Author, Title, Price, Description, CategoryID, Picture, Soluong) VALUES ('$isbn','$tacgia','$ten','$gia','$mota','$dm','$file','$sl')";
        
        //4-Thực thi câu truy vấn và kiểm tra kết quả
        if($conn->query($sql) === TRUE)
        {
            header("Location: sach.php");
            exit();
        }
        else
        {
            echo "Lỗi câu truy vấn: " . $conn->error;
        }
    } else {
        echo "Không thể thêm sách do điền thiếu thông tin form hoặc lỗi xử lý ảnh!";
    }
?>
<?php
session_start();
//1-Kết nối cơ sở dữ liệu
include_once("connect.php");?>

<!DOCTYPE html>
<html lang="vi">
<head>
	<title>Website cửa hàng sách online</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    /* Thanh điều hướng trên cùng */
    .top-nav {
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 5px 0;
        margin-bottom: 20px;
    }
    .top-nav .nav-link {
        color: #495057;
        font-weight: 500;
        transition: color 0.2s;
    }
    .top-nav .nav-link:hover {
        color: #0066cc;
    }
    /* Khu vực logo & search */
    .header-main {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
    /* Menu bên trái */
    .sidebar-menu {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .sidebar-menu a.nav-tabs-custom {
        display: block;
        padding: 12px 15px;
        color: #495057;
        text-decoration: none;
        font-weight: 500;
        border-radius: 8px;
        margin-bottom: 6px;
        transition: all 0.2s;
    }
    .sidebar-menu a.nav-tabs-custom:hover, .sidebar-menu a.nav-tabs-custom.active {
        background-color: #e6f0fa;
        color: #0066cc;
    }
    /* Khung chứa sách bên phải */
    .content-area {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        min-height: 400px;
    }
    /* Khối bọc từng cuốn sách */
    .book-card {
        text-align: center;
        padding: 10px;
        margin-bottom: 15px;
        transition: transform 0.2s;
    }
    .book-card:hover {
        transform: translateY(-5px);
    }
    .book-card a {
        text-decoration: none;
    }
    .book-card img {
        border-radius: 6px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        margin-bottom: 10px;
    }
    .book-card .price {
        color: #dc3545;
        font-weight: 600;
        margin-top: 5px;
        font-size: 0.95rem;
    }
    /* Carousel custom */
    .custom-indicators {
        justify-content: center;
        gap: 8px;
        margin-top: 15px;
    }
    .custom-indicators a {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #ced4da;
        display: inline-block;
        cursor: pointer;
    }
    .custom-indicators a.active {
        background-color: #0066cc;
    }
    /* Section Sách Hay */
    .section-title {
        font-weight: 700;
        color: #222;
        margin: 35px 0 20px 0;
        position: relative;
        padding-left: 15px;
    }
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        bottom: 4px;
        width: 4px;
        background-color: #0066cc;
        border-radius: 2px;
    }
    /* Footer */
    .footer-custom {
        background-color: #ffffff;
        padding: 30px 0;
        margin-top: 50px;
        border-top: 1px solid #e9ecef;
        color: #6c757d;
    }
    .footer-custom h6 {
        color: #333;
        font-weight: 600;
    }
    .footer-custom ul {
        padding-left: 0;
        list-style: none;
    }
    .footer-custom ul li a {
        color: #6c757d;
        text-decoration: none;
    }
    .footer-custom ul li a:hover {
        color: #0066cc;
    }
</style>
</head>
<body>

<nav class="top-nav">
    <div class="container d-flex justify-content-end align-items-center">
        <ul class="nav">
            <?php if(isset($_SESSION["Role"]) && $_SESSION["Role"] == 1) { ?>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-danger" href="quantri.php">Đến trang quản trị</a>
                </li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" href="trangchu.php">Trang chủ</a></li>
            <li class="nav-item"><a class="nav-link" href="donhang.php">Đơn hàng</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Sản Phẩm</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="trangchu.php?category=5">Văn Học Việt Nam</a></li>
                    <li><a class="dropdown-item" href="trangchu.php?category=6">Văn Học Nước Ngoài</a></li>
                    <li><a class="dropdown-item" href="trangchu.php?category=7">Cổ Tích - Thần Thoại</a></li>
                    <li><a class="dropdown-item" href="trangchu.php?category=8">Ngôn Ngữ Lập Trình</a></li>
                    <li><a class="dropdown-item" href="trangchu.php?category=9">Sách Giáo Khoa - Giảng Dạy</a></li>
                    <li><a class="dropdown-item" href="trangchu.php?category=10">Sách Y Khoa</a></li>
                </ul>
            </li>
            <?php if(!isset($_SESSION["Name"])) { ?>
                <li class="nav-item"><a class="nav-link" href="registry.php">Đăng ký</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
            <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="logout.php?flag=1">Đăng xuất</a></li>
                <li class="nav-item"><span class="nav-link text-primary fw-medium">Xin chào, <?php echo $_SESSION["Name"]; ?>!</span></li>
            <?php } ?>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="header-main row align-items-center">
        <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
            <a href="https://giaothongvantaitphcm.edu.vn/">
                <img width="80" height="80" src="images/logo1.jpg" alt="TRƯỜNG ĐH GTVT" class="img-fluid rounded">
            </a>
        </div>
        <div class="col-md-9">
            <form action="trangchu.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Nhập tên sách cần tìm.." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-primary px-4">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if(isset($_GET['search'])) {
        if(!empty($_GET['search'])) {
            echo "<div class='alert alert-info text-center'>Kết quả tìm kiếm cho: <strong>" . htmlspecialchars($_GET['search']) . "</strong></div>";
        } else {
            echo "<div class='alert alert-warning text-center'>Vui lòng nhập từ khóa tìm kiếm.</div>";
        }
    }
    ?>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="sidebar-menu">
                <h5 class="fw-bold mb-3 px-2 text-secondary" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Danh mục sách</h5>
                <a class="nav-tabs-custom <?php echo !isset($_GET['category']) ? 'active' : ''; ?>" href="trangchu.php">Tất Cả Sản Phẩm</a>
                <a class="nav-tabs-custom <?php echo (isset($_GET['category']) && $_GET['category']==5) ? 'active' : ''; ?>" href="trangchu.php?category=5">Sách Văn Học Việt Nam</a>
                <a class="nav-tabs-custom <?php echo (isset($_GET['category']) && $_GET['category']==6) ? 'active' : ''; ?>" href="trangchu.php?category=6">Sách Văn Học Nước Ngoài</a>
                <a class="nav-tabs-custom <?php echo (isset($_GET['category']) && $_GET['category']==7) ? 'active' : ''; ?>" href="trangchu.php?category=7">Truyện Cổ Tích - Thần Thoại</a>
                <a class="nav-tabs-custom <?php echo (isset($_GET['category']) && $_GET['category']==8) ? 'active' : ''; ?>" href="trangchu.php?category=8">Sách Ngôn Ngữ Lập Trình</a>
                <a class="nav-tabs-custom <?php echo (isset($_GET['category']) && $_GET['category']==9) ? 'active' : ''; ?>" href="trangchu.php?category=9">Sách Giáo Khoa - Giảng Dạy</a>
                <a class="nav-tabs-custom <?php echo (isset($_GET['category']) && $_GET['category']==10) ? 'active' : ''; ?>" href="trangchu.php?category=10">Sách Y Khoa</a>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="content-area">
                <div id="bookCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        include_once("connect.php");
                        
                        if(isset($_GET['search']) && !empty($_GET['search'])) {
                            $search = $conn->real_escape_string($_GET['search']);
                            $sql = "SELECT * FROM books WHERE Title LIKE '%$search%'";
                        } else {
                            if(isset($_GET["category"])) {
                                $category = $conn->real_escape_string($_GET["category"]);
                                $sql = "SELECT * FROM books WHERE CategoryID='$category'";
                            } else {
                                $sql = "SELECT * FROM books";
                            }
                        }
                        
                        $result = $conn->query($sql);
                        
                        if ($result && $result->num_rows > 0) {
                            $count = 0;
                            $slideCount = 0;
                            $active = "active";
                            echo '<div class="carousel-item '.$active.'"><div class="row row-cols-2 row-cols-md-5 g-3">';
                            
                            while ($row = $result->fetch_assoc()) {
                                if ($count % 10 == 0 && $count != 0) {
                                    echo '</div></div>';
                                    $slideCount++;
                                    $active = "";
                                    echo '<div class="carousel-item '.$active.'"><div class="row row-cols-2 row-cols-md-5 g-3">';
                                }
                                
                                echo "<div class='col'>";
                                echo "<div class='book-card'>";
                                echo "<a href='chitiet_sach.php?txtISBN=".$row["ISBN"]."'>";
                                $imgSrc = !empty($row["Picture"]) ? "images/".$row["Picture"] : "images/no-image.png";
                                echo "<img width='120' height='150' src='".$imgSrc."' alt='".htmlspecialchars($row["Title"])."'>";   
                                echo "<div class='text-dark text-truncate fw-medium small'>".$row["Title"]."</div>";
                                echo "<div class='price'>".number_format($row["Price"], 0, ',', '.')." đ</div>"; 
                                echo "</a>";
                                echo "</div>";
                                echo "</div>";
                                $count++;
                            }
                            echo '</div></div>';
                        } else {
                            echo "<div class='text-center my-5 text-muted'><p>Không tìm thấy quyển sách nào phù hợp.</p></div>";
                        }
                        ?>
                    </div>
                    
                    <div class="d-flex custom-indicators">
                        <?php
                        if(!empty($slideCount) && $slideCount > 0){
                            for ($i = 0; $i <= $slideCount; $i++) {
                                $activeClass = ($i == 0) ? "active" : "";
                                echo '<a data-bs-target="#bookCarousel" data-bs-slide-to="'.$i.'" class="'.$activeClass.'"></a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="section-title">SÁCH MỚI CẬP NHẬT</h4>
    <div class="bg-white p-4 rounded-4 shadow-sm">
        <div class="row row-cols-2 row-cols-md-5 g-3">
            <?php
            if(isset($_GET["category"])) {
                $category = $conn->real_escape_string($_GET["category"]);
                $sql = "SELECT * FROM books WHERE CategoryID='$category' ORDER BY ISBN DESC LIMIT 5";
            } else {   
                $sql = "SELECT * FROM books ORDER BY ISBN DESC LIMIT 5";
            }
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col">';
                    echo '<div class="book-card">';
                    echo '<a href="chitiet_sach.php?txtISBN='. $row["ISBN"].'">';
                    $imgSrc = !empty($row["Picture"]) ? "images/".$row["Picture"] : "images/no-image.png";
                    echo '<img width="120" height="150" src="' . $imgSrc . '" alt="">';
                    echo '<div class="text-dark text-truncate fw-medium small">'.$row["Title"].'</div>';
                    echo '<div class="price">'.number_format($row["Price"], 0, ',', '.').' đ</div>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p class='text-muted ps-3'>Không có sản phẩm nào nổi bật.</p>";
            }
            ?>
        </div>
    </div>
</div>

<footer class="footer-custom">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h6>📍 ĐỊA CHỈ CỬA HÀNG</h6>
                <p class="small mt-2">Biên Hòa,Đồng Nai </p>
            </div>
            <div class="col-md-4">
                <h6>📞 HOTLINE HỖ TRỢ</h6>
                <p class="small mt-2 mb-1">Điện thoại: 0332.605.243</p>
                <p class="small">Di động: 0332.605.243</p>
            </div>
            <div class="col-md-4">
                <h6>🌐 KẾT NỐI VỚI CHÚNG TÔI</h6>
                <ul class="small mt-2">
                    <li class="mb-1"><a target="_blank" href="https://www.youtube.com/">Youtube Channel</a></li>
                    <li class="mb-1"><a target="_blank" href="https://www.facebook.com/khaidien.phan">Facebook Page</a></li>
                    <li><a target="_blank" href="https://twitter.com/">Twitter</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center small text-muted">&copy; 2026 Cửa hàng sách Online. Bảo lưu mọi quyền.</div>
    </div>
</footer>

</body>
</html>
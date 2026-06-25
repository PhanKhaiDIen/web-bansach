<?php
session_start();
//echo $_SESSION["Role"];
//1-Kết nối cơ sở dữ liệu
include_once("connect.php");?>
<!DOCTYPE html>
<html>
<head>
	<title>Website giới thiệu sản phẩm ...</title>
	<meta charset = "utf-8">
	<!-- Latest compiled and minified CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Latest compiled JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class = "container ">
<?php
	//1 - Nhận mã ISBN được gửi từ liên kết
	$isbn = "";
	if(isset($_GET["ma"]))
	{
			$isbn = $_GET["ma"];
	}
	
	//2- Select dữ liệu từ bảng books với điều kiện ISBN = $isbn
	$isbn ="";
	$isbn=$_GET["txtISBN"];
	$sql = "SELECT books.ISBN,books.Title,books.Author,books.Price,books.Description,books.Picture,books.Soluong,categories.Name FROM books,categories
	WHERE ISBN = '$isbn' AND books.CategoryID=categories.CategoryID";
	$result = $conn->query($sql);
	
	$isbn_val = $cate_val = $title_val = $author_val = $price_val = $des_val = $picture_val=$souong_val="";
	//echo $sql;
	$row = $result->fetch_assoc();
	$isbn_val = $row["ISBN"];
	$cate_val = $row["Name"];
	$title_val = $row["Title"];
	$author_val = $row["Author"];
	$price_val = $row["Price"];
	$des_val = $row["Description"];
	$picture_val = $row["Picture"];
	$soluong_val = $row["Soluong"];
	
	$sql2 = "SELECT *FROM Photos WHERE ISBN = '$isbn'";
	$result = $conn->query($sql2);	
?>
	
<!--Thiết kế form chi tiết mua một quyển sách - books-->
<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <fieldset>
            <legend>Thông tin sản phẩm:</legend>
            <div class="row">
                <div class="col-md-6">
                    <img src="images/<?php echo $picture_val; ?>" alt="Ảnh sách" class="img-fluid" width="60%" height="60%">
					<br><br><p>
		<?php
					while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><img width='50' height='70' src='images/".$row["Names"]."'></td>";
        echo "</tr>";
        echo "<tr><td>&nbsp&nbsp&nbsp;</td></tr>"; // Thêm khoảng cách sau mỗi ảnh
    }
		?>
					</p>
                </div>
                <div class="col-md-6">
                    <h3><?php echo "Tên sách: ".$title_val; ?></h3>
                    <p><b>Thông tin tác giả:</b> <?php echo $author_val; ?></p>
                    <p><b>Thể loại:</b> <?php echo $cate_val; ?></p>
                    <p><b>Số lượng tồn:</b> <?php echo $soluong_val; ?></p>
                    <p><b>Giá bán:</b> <?php echo $price_val; ?> VND</p>
                    <p><b>Mô tả sản phẩm:</b></p>
                    <p><?php echo $des_val; ?></p>
                    <form name="frmThemhang" action="giohang.php" method="post">
						<div class="mb-3 mt-3">
							<label for="txtSoLuongMua" class="form-label"><b>Nhập số lượng sách cần mua:</b></label>
							<input type="text" class="form-control" id="txtSoLuongMua" 
								placeholder="Nhập số lượng sách cần mua" name="txtSoLuongMua" required>
						</div>
                        <input type="hidden" name="txtISBN" value="<?php echo $isbn_val; ?>">
                        <input type="hidden" name="slDanhMuc" value="<?php echo $cate_val; ?>">
                        <!-- Các trường thông tin sách .... -->
                      <div class="mb-3 mt-3">
						<input class="btn btn-danger me-5" type="submit" name="sbThemhang" value="Mua Hàng"> 
						<a class="btn btn-primary" href="trangchu.php">Quay lại trang chủ</a> 
					  </div>
                    </form>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-sm-2"></div>
</div>


<?php
	include_once("phanchan.php");
?>
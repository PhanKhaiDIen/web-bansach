<h2>Thông Tin Các Đơn Đặt Hàng:</h2>
<?php
session_start();
//1-Kết nối cơ sở dữ liệu
include_once("connect.php");

// Truy vấn để lấy thông tin đơn hàng của mọi khách hàng kèm theo tên khách hàng và tiêu đề sách
$sql = "SELECT o.OrderID, a.Username, o.Amount, o.DateTran, i.ISBN, b.Title, b.Price, i.Prices, i.Quantity
        FROM orders o
        INNER JOIN order_items i ON o.OrderID = i.OrderID
        INNER JOIN accounts a ON o.AccountID = a.AccountID
        INNER JOIN books b ON i.ISBN = b.ISBN";

$result = $conn->query($sql);




if ($result->num_rows > 0) {
    // Hiển thị dữ liệu
	
    echo "<table border='2'>
            <tr>
                <th>OrderID</th>
                <th>Tên tài khoản</th>
                <th>Ngày đặt</th>
                <th>ISBN</th>
                <th>Tên Sách</th>                
				<th>Giá</th> 
				<th>Số lượng</th>
				<th>Tổng tiền</th>
				<th>Xoá</th>
            </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["OrderID"] . "</td>";
        echo "<td>" . $row["Username"] . "</td>";
        echo "<td>" . $row["DateTran"] . "</td>";
        echo "<td>" . $row["ISBN"] . "</td>";
        echo "<td>" . $row["Title"] . "</td>";  
		echo "<td>" . $row["Price"] . "</td>";		
        echo "<td>" . $row["Quantity"] . "</td>";
		echo "<td>" . $row["Prices"] . "</td>";
		echo "<td>";
		?>
		<?php
		if($_SESSION["Role"]==1)//Quản trị
	{
	?>
	
	<a onclick = "return confirm('Bạn có chắc xóa không?');" 
							href = "xoa_donhang.php?ma=<?php echo $row["OrderID"];?>"><img src = "images/icons8-delete-24.png"></a>
	  <?php
	}
	?>
	<?php	
		
			
		echo "</td>";		
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Không có đơn hàng nào.";
}
?>
<h3><a class="nav-link"  href="trangchu.php">Quay Lại Trang chủ</a></h3>
<?php
// Đóng kết nối
$conn->close();
?>

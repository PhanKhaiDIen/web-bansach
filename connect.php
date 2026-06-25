<?php
	//Khai báo các biến
	$servername = "bookstore-db.mysql.database.azure.com";
	$username = "bookadmin";
	$password = "Dien2522005@";
	$dbname = "db_bookstore";
	
	//Tạo kết nối
	$conn = new mysqli($servername,$username,$password,$dbname);
	
	//Kiểm tra kết nối thành công
	if(!$conn)
	{
		die("Lỗi kết nối: ".mysqli_connect_error());
	}
?>
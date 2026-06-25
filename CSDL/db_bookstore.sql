-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 20, 2024 lúc 10:55 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_bookstore`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `accounts`
--

CREATE TABLE `accounts` (
  `AccountID` INT NOT NULL,
  `Username` char(50) DEFAULT NULL,
  `Pass` text NOT NULL,
  `RoleID` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `accounts`
--

INSERT INTO `accounts` (`AccountID`, `Username`, `Pass`, `RoleID`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 1),
(2, 'kiep', '202cb962ac59075b964b07152d234b70', 2),
(5, 'khachhang', '202cb962ac59075b964b07152d234b70', 2),
(6, 'admin2', '202cb962ac59075b964b07152d234b70', 1),
(7, 'quantri', '202cb962ac59075b964b07152d234b70', 1),
(10, 'khachhang2', '202cb962ac59075b964b07152d234b70', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

CREATE TABLE `books` (
  `ISBN` char(13) NOT NULL,
  `Author` char(255) DEFAULT NULL,
  `Title` char(100) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `CategoryID` INT DEFAULT NULL,
  `Picture` text DEFAULT NULL,
  `Soluong` INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`ISBN`, `Author`, `Title`, `Price`, `Description`, `CategoryID`, `Picture`, `Soluong`) VALUES
('100', 'Hiromi Shinya', 'Nhân Tố Enzyme – Hiromi Shinya', 169000, 'Từ kết quả lâm sàng khi tiến hành kiểm tra dạ dày của hơn 300.000 người, bác sĩ Hiromi Shinya đã rút ra kết luận: “Người có sức khỏe tốt là người có dạ dày đẹp, ngược lại, người có sức khỏe kém là người có dạ dày không đẹp.”', 10, '100.jpg', 400),
('101', 'Ngô Đức Vượng', 'Minh Triết Trong Ăn Uống Của Phương Đông', 99000, 'Với thiện chí đem lại sức khỏe và trị bệnh không dùng thuốc, ít tốn kém, dễ thực hành…cho cộng đồng, tác giả đã viết cuốn sách này, nhằm cống hiến cho bạn đọc những lời khuyên quý giá và đáp ứng những nhu cầu cần thiết cho độc giả.', 10, '101-1.jpg', 62),
('102', 'Satoshi Tsubota', 'Ngủ Ơi Là Ngủ 5 Tiếng Là Đủ', 86000, 'Có một điều bạn có thể biết được chính là những người thành công đều ngủ ít hơn bình thương nhưng năng suất công việc của họ vẫn luôn hiệu quả. Mức độ tốt và xấu của giấc ngủ không thể chỉ đo bằng “thời gian”.', 10, '102-1.jpg', 120),
('103', 'George Ohsawa', 'Phòng Bệnh Và Trị Bệnh Theo Phương Pháp Thực Dưỡng Ohsawa', 60000, 'Phương pháp thực dưỡng hiểu theo từ chuyên ngành là Macrobiotics theo tiếng Hy Lạp Makrobiotos hoặc theo ngữ nghĩa Hán Việt là phương pháp hoặc nghệ thuật dưỡng sinh sống vui sống khỏe dựa vào một tri thức sâu xa về sự sống trong vũ trụ', 10, '103-1.jpg', 89),
('104', 'Wallace Delois Wattles', 'Khoa Học Chăm Sóc Sức Khỏe', 174000, 'Khoa Học Chăm Sóc Sức Khỏe là cuốn sách hay về y học sức khỏe mà bạn đọc có thể xem nó là một người hỗ trợ bạn trong việc áp dụng những nguyên tắc sống phổ quát, những điều đã được cả thế giới thừa nhận để bạn có thể áp dụng vào đời sống thường nhật cho việc chăm sóc sức khỏe của mình.', 10, '104-1.jpg', 146),
('105', 'Nguyễn Khánh Dư', 'Phẫu Thuật Mạch Máu Lớn', 60000, 'Trong quyển sách hay về y học này, tác giả muốn chia sẽ một phần nhỏ kinh nghiệm của mình qua hơn 50 năm làm công tác mổ xẻ điều trị các bệnh và tổn thương ở mạch máu.', 10, '105-1.jpg', 49),
('106', 'Thomas M. Campbell II - T. Colin Campbell', 'Bí Mật Dinh Dưỡng Cho Sức Khỏe Toàn Diện', 270000, 'Quyển sách hay về y học này được viết dựa trên một công trình nghiên cứu khoa học lớn nhất, kéo dài trong suốt 20 năm về mối liên hệ giữa dinh dưỡng và bệnh tật của con người. ', 10, '106-1.jpg', 200),
('107', 'Ray D. Strand', 'Y Học Dinh Dưỡng - Những Điều Bác Sĩ Không Nói Với Bạn', 141000, '“Với kinh nghiệm cá nhân, bác sĩ Strand đã đem lại món quà vô giá để cải thiện cuộc sống, cả về chất lượng lẫn số lượng” – Tiến sĩ Denis Waitley, tác giả The Psychology of Winning.', 10, '107-1.jpg', 200),
('108', 'NXB Dân Trí', 'Cẩm Nang Sơ Cấp Cứu Thường Thức', 233000, 'Cẩm nang sơ cấp cứu thường thức là cuốn sách cung cấp những kiến thức cơ bản nhất về sơ cấp cứu. Những lời khuyên trong sách đều được các cố vấn y khoa của hội các Tổ chức sơ cứu tại Anh đưa ra dự trên những nghiên cứu cập nhật nhất, đồng thời được chuyên gia đào tạo sơ cứu minh họa, dẫn giải theo cách vừa dễ hiểu vừa dễ nhớ.', 10, '108-1.jpg', 148),
('109', 'Đào Thị Yến Phi', 'Dinh Dưỡng Học', 300000, 'Sách Dinh Dưỡng Học (Tái bản lần thứ 5). Dinh dưỡng là ngành học mang sứ mệnh hướng dẫn cho các cá nhân nhận thức được vai trò cũng như chức năng của yếu tố dinh dưỡng đối với sức khỏe và cơ thể con người nói chung.', 10, '109-1.jpg', 99),
('50', 'Nguyễn Ngọc Tư', 'Cánh Đồng Bất Tận', 430000, 'Tác phẩm là câu chuyện về cuộc sống lênh đênh của ba cha con ông Tư, cùng người đàn bà tên Sương. Họ là những mảnh đời bất hạnh, hẩm hiu tưởng chừng may mắn nắm lấy được nhau nhưng rồi lại bị chia rẽ đến mãi mãi.', 5, 'canhdongbattan1.jpg', 800),
('51', 'Ngô Tất Tố', 'Tắt Đèn', 151000, 'Đây được xem là cuốn tiểu thuyết thành công nhất của ông và được lưu truyền như một vật báu của văn học Việt qua nhiều thời đại.', 5, 'tatden1.jpg', 150),
('52', 'Nguyễn Du', 'Truyện Kiều', 152000, 'ruyện Kiều hay Đoạn Trường Tân Thanh, tập thơ Nôm được mệnh danh là kiệt tác thi ca của văn học dân tộc xuất sắc nhất mọi thời đại. Tập thơ là sáng tác thành công của vị Đại thi hào dân tộc - Nguyễn Du với 3254 câu thơ.', 5, 'truyenkieu1.jpg', 200),
('53', 'Vũ Trọng Phụng', 'Số Đỏ', 230000, 'Số Đỏ là một trong ba tác phẩm văn học làm nên tiếng tăm của Vũ Trọng Phụng. Cuốn tiểu thuyết có 20 chương, mỗi chương là một lời mỉa mai sâu sắc, châm biếm của tác giả với cái thói đời thuở ấy.', 5, 'sodo1.jpg', 200),
('54', 'Nguyên Hồng', 'Những Ngày Thơ Ấu', 250000, 'Được viết ở ngôi thứ nhất, Những Ngày Thơ Ấu chính là dòng hồi ức chân thực về tuổi thơ hồn nhiên, thơ mộng cũng không khỏi những tủi hờn, thống khổ của bản thân tác giả.', 5, 'nhungngayautho1.jpg', 100),
('55', 'Nguyễn Tuân', 'Vang Bóng Một Thời', 400000, 'Tập truyện ngắn “Vang Bóng Một Thời” của Nguyễn Tuân không còn đơn thuần là những câu chuyện, đó là kí ức, là hoài niệm, là sợi dây gắn kết con người với những thứ xưa cũ đang dần mờ nhạt.', 5, 'vangbongmotthoi1.jpg', 320),
('56', 'Kim Lân', 'Vợ Nhặt', 360000, 'Tác phẩm có lẽ sẽ là cuốn phóng sự chân thật nhất để người đọc có thể cảm nhận rõ cái được gọi là “nạn đói năm 45”, thời điểm mà cái đói, cái khổ đày đọa người nông dân đến nỗi đâu đâu cũng thấy xác người chết vì đói.', 5, 'vonhat1.jpg', 500),
('57', 'Nam Cao', 'Chí Phèo', 600000, 'Là một nhà văn hiện thực xuất sắc những năm 1940, đồng thời là người “khai hoang” cho nền văn học mới tại Việt Nam, Nam Cao đã để lại cho đời kho báu văn học mang đầy giá trị nhân văn, ý nghĩa và sâu sắc.', 5, 'chipheo1.jpg', 392),
('58', 'Thạch Lam', 'Gió lạnh đầu mùa', 300000, 'Gió lạnh đầu mùa là một trong những truyện ngắn xuất sắc của nhà văn Thạch Lam, được in trong tập truyện ngắn cùng tên, xuất bản năm 1937.', 5, 'giolanhdaumua1.jpg', 100),
('59', 'Nam Cao', 'Đời Thừa', 440000, 'Một tác phẩm văn học Việt Nam nữa cũng thành công không kém của đời văn Nam Cao chính là Đời Thừa. Ở tác phẩm này, ông đã cho người yêu văn biết đến thế nào gọi là đỉnh cao của miêu tả tâm lí nhân vật, thế nào là chất sâu sắc của văn chương.', 5, 'doithua1.jpg', 200),
('60', 'Gabriel Garcia Márquez', 'Trăm năm cô đơn', 1000000, 'Trăm năm cô đơn là một trong những tiểu thuyết nổi tiếng nhất của nhà văn người Columbia Gabriel Garcia Márquez. Xuất bản lần đầu tiên vào năm 1967 bằng tiếng Tây Ban Nha và trong vòng ba năm đã bán được hơn nửa triệu bản.', 6, 'tramnamcodon1.jpg', 100),
('61', 'Lev Tolstoy', 'Chiến tranh và hoà bình', 1400000, 'Chiến tranh và Hòa bình đã trở thành kiệt tác của nhân loại về những triết lý sâu sắc giữa cái thiện và cái ác trong cuộc đời.\r\nChiến tranh và hòa bình mở đầu vào đêm trước chiến tranh giữa Pháp và Nga.', 6, 'chientranhvahoabinh1.jpg', 1500),
('62', 'Franz Kafka', 'Vụ án', 800000, 'Nằm trong một phần nhỏ đã thoát ngọn lửa vùi 90% tác phẩm của Franz Kafka vào vĩnh viễn hư vô, Vụ án, cũng như những tiểu thuyết may mắn còn lại của ông, ở dạng bản thảo chưa hoàn chỉnh.', 6, 'vuan1.jpg', 400),
('63', 'Virginia Woolf', 'Đến ngọn hải đăng', 400000, 'Đến ngọn hải đăng là một bản phân tích đầy tham vọng, xuất sắc về tâm lý gia đình...Đến ngọn hải đăng không có sự hoàn hảo về hình thức, sự chặt chẽ, sự khắc họa nhân vật sống động mạnh mẽ như cuốn bà Dalloway.', 6, 'denngonhaidang1.jpg', 300),
('64', 'Vladimir Nabokov', 'Lolita', 230000, 'Lolita được xem là hiện tượng bất thường bậc nhất của văn chương thế kỷ 20, được xuất bản lần đầu vào năm 1955 tại Paris, mặc dù viết bằng tiếng Anh.', 6, 'Lolita1.jpg', 150),
('65', 'Paulo Coelho', 'Nhà giả kim', 250000, 'Nhà giả kim là tiểu thuyết được xuất bản lần đầu vào năm 1988 bởi nhà văn Paulo Coelho. Được coi là một trong những đầu sách nổi tiếng nhất thế giới.', 6, 'nhagiakim1.jpg', 500),
('66', 'Mario Puzo', 'Bố già', 115000, 'Bố già- Mario PuzoVốn được biết đến là Mafia có tiếng, ông là mục tiêu của rất nhiều bên thù địch lần lượt tìm cách hãm hại ông, tuy nhiên kết cục của bọn chúng không có gì khác ngoài sự thất bại và bị trừng phạt bởi Bố Già một cách đầy đau đớn. ', 6, 'bogia1.jpg', 200),
('67', 'Ernest Hemingway', 'Ông già và biển cả', 60000, 'Tác phẩm này được viết theo thể loại truyện viễn tưởng và được nhiều người đọc biết đến với nguyên lý “tảng băng trôi” với lý thuyết là một phần nổi - bảy phần chìm. ', 6, 'onggiavabienca1.jpg', 300),
('68', 'J.R.R Tolkien', 'Chúa tể của những chiếc nhẫn', 310000, 'Qua 7 cuốn sách giả tưởng của J.R.R Tolkien bạn sẽ có cảm giác như được sống trong một thế giới huyền bí, chiến đấu chống lại Chúa tể bóng tối.', 6, 'chuatecuanhungchiecnhan1.jpg', 2000),
('69', 'Authur Conan Doyle', 'Sherlock Holmes', 2000000, 'Sherlock Holmes là tiểu thuyết trinh thám kinh điển xoay quanh nhân vật cùng tên. Sherlock Holmes trong truyện là một thám tử nổi tiếng với biệt tài phá án thần kỳ, nhạy bén.', 6, 'SherlockHolmes1.jpg', 498),
('70', 'Dân gian, truyền khẩu', 'Sơn Tinh - Thủy Tinh', 65000, 'Sơn Tinh – Thủy Tinh hay Sơn Thần – Thủy Quái là tên gọi của một truyền thuyết của văn hóa Việt Nam. Câu chuyện kể về cuộc chiến giữa Sơn Tinh và Thủy Tinh khi tranh giành nàng Mỵ Nương, con gái của Vua Hùng thứ 18.', 7, 'sontinhthuytinh1.jpg', 118),
('71', 'Dân gian, truyền khẩu', 'Tấm Cám', 50000, 'Câu chuyện kể về người thiếu nữ xinh đẹp, thùy mị, nết na là nàng Tấm và phải sống chung với dì ghẻ cùng con gái của bà là cô Cám. ', 7, 'tamcam1.jpg', 95),
('72', 'Nghìn lẻ một đêm', 'Aladdin và cây đèn thần', 230000, 'Tại vương quốc Ba Tư, có một chàng thanh niên trẻ sống lang thang khắp nơi và vô cùng tốt bụng. Trong một lần vô tình, anh tìm thấy chiếc đèn thần và được thần đèn giúp đỡ trong nhiều sự. Đến cuối, nhờ sự dũng cảm và trí thông minh, anh đã chiến thắng kẻ ác, cưới được công chúa và trở thành hoàng tử nước Ba Tư.', 7, 'aladdinvacaydenthan1.jpg', 350000),
('73', 'Dân gian, truyền khẩu', 'Cô bé Lọ Lem', 250000, 'Cô bé Lọ Lem là câu chuyện kể về người con gái cùng tên, có vẻ ngoài xinh đẹp, luôn dịu dàng, đảm đang nhưng lại bị hiếp đáp bởi người mẹ kế và 2 cô em cùng cha khác mẹ.', 7, 'cobelolem1.jpg', 500),
('74', 'Dân gian, truyền khẩu', 'Nàng tiên cá', 250000, 'Nàng tiên cá còn gọi là mỹ nhân ngư hoặc ngư nữ là sinh vật sống dưới nước có phần đầu và phần thân trên giống phụ nữ, còn phần dưới thì là đuôi cá trong văn hóa dân gian.', 7, 'nangtienca1.jpg', 300),
('75', 'Dân gian, truyền khẩu', 'Thạch Sanh Lý Thông', 40000, 'Câu chuyện kể về 2 anh em kết nghĩa là Thạch Sanh và Lý Thông. Lợi dụng sự hiền lành của em mình, Lý Thông đã cướp công giết chằn tinh và cứu công chúa của Thạch Sanh. Thế nhưng về sau, nhờ tấm lòng nhân hậu, Thạch Sanh chẳng những giải được oan cho bản thân mà đến cuối còn đã cưới được công chúa.', 7, 'thachsanhlythong1.jpg', 350),
('76', 'Dân gian, truyền khẩu', 'Sọ Dừa', 500000, 'Sọ Dừa là một câu chuyện rất hay và cảm động, dạy cho ta nhiều bài học nhân sinh trong cuộc sống.', 7, 'sodua1.jpg', 200),
('77', 'Dân gian, truyền khẩu', 'Cây tre trăm đốt', 150000, 'Cây tre trăm đốt là một truyện cổ tích dân gian Việt Nam và là một phần của văn học truyền khẩu truyền thống Việt Nam. Đây được xem là một trong số những câu chuyện cổ tích nổi bật nhất.', 7, 'caytretramdot4.jpg', 900),
('78', 'Dân gian, truyền khẩu', 'Thánh Gióng', 600000, 'Thánh Gióng nói về người hùng Phù Đổng Thiên Vương giúp dân đánh đuổi giặc Ân cứu nước. ', 7, 'thanhgiong1.jpg', 800),
('79', 'Dân gian, truyền khẩu', 'Sự Tích Bánh Chưng - Bánh Giầy', 45000, 'Qua câu chuyện \"Sự tích bánh chưng, bánh giầy\" chúng mình đã được hiểu thêm về tục lệ làm bánh chưng, bánh giầy trong ngày Tết Nguyên đán.', 7, 'sutichbanhchungbanhgiay1.jpg', 100),
('80', 'Dương Thiên Tử', 'Kỹ Thuật Lập Trình C - Dương Thiên Tử', 59000, 'Cuốn sách chọn lọc 230 bài tập kỹ thuật thực hành môn Ngôn ngữ lập trình C và lập trình cấu trúc dữ liệu bằng ngôn ngữ C cho sinh viên Đại học và Cao đẳng chuyên ngành Công nghệ Thông tin.', 8, 'kythuatlaptrinh1.jpg', 50),
('81', 'Phạm Văn Ất', 'Kỹ Thuật Lập Trình C – Phạm Văn Ất', 130000, 'Giáo trình kỹ thuật lập trình C căn bản gồm 18 chương của Gs. Phạm Văn Ất.', 8, 'duongthientu1.jpg', 80),
('82', 'NXB Thanh Niên', 'Kỹ Thuật Lập Trình LabVIEW Từ Cơ Bản Đến Nâng Cao', 180000, 'Bộ sách hướng dẫn sử dụng LabVIEW gồm 3 tập với các nội dung chính sau: Tìm hiểu Môi trường LabVIEW, bao gồm cách cài đặt, các chức năng chung trong môi trường ...', 8, 'labview1.jpg', 200),
('83', 'Nguyễn Văn Thư', 'Lập trình nâng cao C++', 137000, 'NXB Đại Học Kinh Tế Quốc Dân. Giáo trình “Lập trình nâng cao” sẽ giới thiệu cho bạn đọc các tính năng mới trong ngôn ngữ lập trình C++ so với ngôn ngữ lập trình C.', 8, 'laptrinhnangcao1.jpg', 118),
('84', 'Nguyễn Ngọc Tân', 'Sách - lập trình Python cho người mới bắt đầu (Moon)', 150000, 'Python là một ngôn ngữ lập trinh bậc cao do Guido van Rossum, nhà lập trình nổi tiếng Hà Lan, tạo ra và công bố lần đầu tiên vào năm 199. Gồm 15 bài học và 2 dự án thực hành.', 8, 'python1.jpg', 90),
('85', 'Steve Martin', 'STEM – Học Viện Lập Trình Viên', 45000, 'Sau khi đọc xong cuốn sách này, bạn sẽ học được cách tạo ra âm nhạc, trò chơi, trang Web và hoạt hình. Ngoài ra, bạn sẽ học về cách mà máy tính suy nghĩ, cách để ra lệnh cho chúng, cách sử dụng Scratch, viết mã HTML cùng nhiều thứ khác.', 8, 'stem1.jpg', 80),
('86', 'Lê Hoàng Sơn', 'Giáo Trình Lập Trình Android', 61000, 'Cuốn sách sẽ giúp bạn hiểu biết về các thành phần cốt yếu trong Android và có thể lập trình được các ứng dụng cơ bản một cách hiệu quả. Cấu trúc sách được chia thành 6 chương.', 8, 'android1.jpg', 150),
('87', 'Phạm Văn Trung, Phạm Văn Tho, Bùi Công Thành, Phạm Thị Minh Phương', 'Lập trình Java căn bản', 95000, 'Lập trình Java căn bản sẽ cung cấp các kiến thức cơ bản về ngôn ngữ lập trình Java; kiến thức về lập trình hướng đối tượng, xử lý biệt lệ, lập trình đa luồng, các luồng vào/ra, lập trình Form với Swing và kết nối cơ sở dữ liệu (CSDL) với Java.', 8, 'java1.jpg', 110),
('88', 'Nguyễn Hữu Hưng', 'Lập Trình Ứng Dụng Di Động Với App Inventor', 239000, 'Sau khi học xong cuốn sách này, bạn sẽ được hướng dẫn chi tiết các bước để hoàn thiện một số ứng dụng đơn giản như dự án lái xe, dự án vẽ đồ thị. Nội dung chính của cuốn sách này được gói gọn trong 8 chương.', 8, 'inventor1.jpg', 180),
('89', 'Nguyễn Văn Hùng - Phan Quan Việt', 'Giáo Trình Thương Mại Điện Tử', 115000, 'Giáo trình Thương mại điện tử được biên soạn với mục đích để hỗ trợ cho đào tạo chuyên ngành Quản trị kinh doanh – thương mại – marketing…', 8, 'thuongmaidientu1.jpg', 200),
('90', 'Nhà xuất bản giáo dục', 'Sách tiếng anh 12', 89000, 'Với giải bài tập Tiếng Anh 12 sách mới Tập 1, Tập 2 [chương trình thí điểm - có kèm video bài giải] hay nhất, chi tiết sẽ giúp học sinh lớp 12 dễ dàng soạn.', 9, 'giaitichlop12-1.jpg', 110),
('91', 'Nhà xuất bản giáo dục', 'Trọn Bộ Sách Giáo Khoa Lớp 1', 580000, 'Trọn bộ 20 cuốn sách giáo khoa lớp 1 (Chân trời sáng tạo)', 9, 'sachgiaokhoalop1-1.jpg', 1000),
('92', 'Nhà xuất bản giáo dục', 'Trọn Bộ Sách Giáo Khoa Lớp 2', 590000, 'Mua Sách - Trọn bộ 21 cuốn sách giáo khoa lớp 2 (Chân trời sáng tạo) giá tốt. Mua hàng qua mạng uy tín, tiện lợi.', 9, 'sachgiaokhoalop2-1.jpg', 1000),
('93', 'Nhà xuất bản giáo dục', 'Trọn Bộ Sách Giáo Khoa Lớp 3', 600000, 'Bộ Sách giáo khoa lớp 3 - Chân trời sáng tạo (13 cuốn) giá tốt. Mua hàng qua mạng uy tín, tiện lợi.', 9, 'sachgiaokhoalop3-1.jpg', 1000),
('94', 'Trần Văn Hạo, Vũ Tuấn...', 'Bộ Sách Giải Tích Lớp 12', 250000, 'SGK Giải tích 12 cơ bản được biên soạn bởi các tác giả: Trần Văn Hạo, Vũ Tuấn, Lê Thị Thiên Hương, Nguyễn Tiến Tài, Cấn Văn Tuất', 9, 'sachgiaokhoalop4-1.jpg', 292),
('95', 'Dale Carnegie', 'Đắc nhân tâm', 1500000, 'Đắc nhân tâm là cuốn sách bán chạy hàng đầu với khoảng 58 bản dịch trên toàn cầu. Bằng những bài học đơn giản nhưng thực tế và giá trị, cuốn sách truyền tải nghệ thuật kiểm soát cảm xúc của bản thân và điều khiển cảm xúc của đối phương một cách tài tình.', 9, 'dacnhantam1.jpg', 500),
('96', 'Nhà xuất bản giáo dục', 'Bộ sách giáo khoa tiếng anh lớp 6', 83500, 'Phát triển năng lực giao tiếp bằng Tiếng Anh thông qua bốn kỹ năng nghe, nói, đọc và viết. 8 đầu sách giáo khoa tiếng Anh cho lớp 6', 9, 'tienganhlop6-1.jpg', 98),
('97', 'Nhà xuất bản giáo dục', 'Sách tin học lớp 7', 96500, 'Sách Tin Học lớp 7 Kết Nối Tri Thức Với Cuộc Sống. Hướng dẫn thực hành tin học lớp 7', 9, 'tinhoclop7-1.jpg', 100),
('98', 'Nhà xuất bản giáo dục', 'Sách toán lớp 8', 56400, 'Sách Giáo Khoa Toán Lớp 8: Bài 1. Phân thức đại số · Bài 2. Tính chất cơ bản của phân thức · Bài 3. Rút gọn phân thức · Bài 4. Quy đồng mẫu thức nhiều phân thức.', 9, 'toanlop8-1.jpg', 100),
('99', 'Nhà xuất bản giáo dục', 'Bộ sách giáo khoa lớp 9', 645000, 'Bộ sách lớp 9 năm học 2023 – 2024 là bộ sách giáo khoa được biên soạn bởi nhóm tác giả Nhà xuất bản giáo dục Việt Nam. Bao gồm đầy đủ 12 môn học.', 9, 'lop9-1.jpg', 96);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `CategoryID` INT NOT NULL,
  `Name` char(50) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`CategoryID`, `Name`, `Description`) VALUES
(5, 'Sách Văn Học Việt Nam', 'Là tác phẩm sáng tác ngữ văn của người Việt Nam, không kể quốc tịch và thời đại.'),
(6, 'Sách Văn Học Nước Ngoài', 'Là các dòng sách nguyên bản được viết và xuất bản tại nước ngoài, kể cả Trung Quốc, Nhật Bản, Ấn Độ.'),
(7, 'Truyện Cổ Tích - Thần Thoại', 'Là một trong những thể loại văn học dân gian Việt Nam.'),
(8, 'Sách Ngôn Ngữ Lập Trình', 'Các sách về ngôn ngữ lập trình JavaScript, PHP, C#, C++, C, Python...'),
(9, 'Sách Giáo Khoa - Giảng Dạy', 'Là loại sách cung cấp kiến thức, được biên soạn với mục đích dạy và học tại trường học hoặc tự học.'),
(10, 'Sách Y Khoa', 'Là những cuốn sách cần thiết với các bạn sinh viên Y khoa, đặc biệt là những bạn đang học môn nội cơ sở hay ngoại cơ sở.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `OrderID` INT NOT NULL,
  `AccountID` INT NOT NULL,
  `Amount` float NOT NULL,
  `DateTran` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`OrderID`, `AccountID`, `Amount`, `DateTran`) VALUES
(29, 1, 1, '2024-05-20'),
(30, 1, 1, '2024-05-20'),
(31, 1, 1, '2024-05-20'),
(32, 5, 1, '2024-05-20'),
(33, 2, 1, '2024-05-20'),
(34, 10, 1, '2024-05-20'),
(35, 6, 1, '2024-05-20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `OrderID` INT NOT NULL,
  `ISBN` char(13) NOT NULL,
  `Quantity` tinyint(3) NOT NULL,
  `Prices` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`OrderID`, `ISBN`, `Quantity`, `Prices`) VALUES
(31, '57', 5, 3000000),
(32, '57', 3, 1800000),
(33, '83', 2, 274000),
(35, '96', 2, 167000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `photos`
--

CREATE TABLE `photos` (
  `PhotoID` INT NOT NULL,
  `Names` text NOT NULL,
  `ISBN` char(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `photos`
--

INSERT INTO `photos` (`PhotoID`, `Names`, `ISBN`) VALUES
(27, 'tatden2.jpg', '51'),
(28, 'tatden3.jpg', '51'),
(29, 'tatden4.jpg', '51'),
(30, 'truyenkieu2.jpg', '52'),
(31, 'truyenkieu1.jpg', '52'),
(32, 'truyenkieu3.jpg', '52'),
(33, 'truyenkieu4.jpg', '52'),
(34, 'sodo1.jpg', '53'),
(35, 'sodo2.jpg', '53'),
(36, 'sodo3.jpg', '53'),
(37, 'sodo4.jpg', '53'),
(39, 'tatden1.jpg', '51'),
(40, 'nhungngayautho1.jpg', '54'),
(41, 'nhungngayautho2.jpg', '54'),
(42, 'nhungngayautho3.jpg', '54'),
(43, 'nhungngayautho4.jpg', '54'),
(44, 'vangbongmotthoi1.jpg', '55'),
(45, 'vangbongmotthoi2.jpg', '55'),
(46, 'vangbongmotthoi3.jpg', '55'),
(47, 'vangbongmotthoi4.jpg', '55'),
(48, 'vonhat1.jpg', '56'),
(49, 'vonhat2.jpg', '56'),
(50, 'vonhat3.jpg', '56'),
(51, 'vonhat4.jpg', '56'),
(52, 'chipheo5.jpg', '57'),
(53, 'chipheo2.jpg', '57'),
(54, 'chipheo3.jpg', '57'),
(55, 'chipheo4.jpg', '57'),
(56, 'giolanhdaumua1.jpg', '58'),
(57, 'giolanhdaumua2.jpg', '58'),
(58, 'giolanhdaumua3.jpg', '58'),
(59, 'giolanhdaumua4.jpg', '58'),
(60, 'doithua1.jpg', '59'),
(61, 'doithua2.jpg', '59'),
(62, 'doithua3.jpg', '59'),
(64, 'canhdongbattan1.jpg', '50'),
(65, 'canhdongbattan2.jpg', '50'),
(66, 'canhdongbattan3.jpg', '50'),
(67, 'canhdongbattan4.jpg', '50'),
(68, 'doithua5.jpg', '59'),
(69, 'tramnamcodon1.jpg', '60'),
(70, 'tramnamcodon2.jpg', '60'),
(71, 'tramnamcodon3.jpg', '60'),
(72, 'tramnamcodon4.jpg', '60'),
(73, 'chientranhvahoabinh1.jpg', '61'),
(74, 'chientranhvahoabinh2.jpg', '61'),
(75, 'chientranhvahoabinh3.jpg', '61'),
(76, 'chientranhvahoabinh4.jpg', '61'),
(77, 'vuan1.jpg', '62'),
(78, 'vuan2.jpg', '62'),
(79, 'vuan3.jpg', '62'),
(80, 'vuan4.jpg', '62'),
(81, 'denngonhaidang1.jpg', '63'),
(82, 'denngonhaidang2.jpg', '63'),
(83, 'denngonhaidang3.jpg', '63'),
(84, 'denngonhaidang4.jpg', '63'),
(85, 'Lolita1.jpg', '64'),
(86, 'Lolita2.jpg', '64'),
(87, 'Lolita3.jpg', '64'),
(88, 'Lolita4.jpg', '64'),
(89, 'nhagiakim1.jpg', '65'),
(90, 'nhagiakim2.jpg', '65'),
(91, 'nhagiakim3.jpg', '65'),
(92, 'nhagiakim4.jpg', '65'),
(93, 'bogia1.jpg', '66'),
(94, 'bogia2.jpg', '66'),
(95, 'bogia3.jpg', '66'),
(96, 'bogia4.jpg', '66'),
(97, 'onggiavabienca1.jpg', '67'),
(98, 'onggiavabienca2.jpg', '67'),
(99, 'onggiavabienca3.jpg', '67'),
(100, 'onggiavabienca4.jpg', '67'),
(101, 'chuatecuanhungchiecnhan1.jpg', '68'),
(102, 'chuatecuanhungchiecnhan2.jpg', '68'),
(103, 'chuatecuanhungchiecnhan3.jpg', '68'),
(104, 'chuatecuanhungchiecnhan4.jpg', '68'),
(105, 'SherlockHolmes1.jpg', '69'),
(106, 'SherlockHolmes2.jpg', '69'),
(107, 'SherlockHolmes3.jpg', '69'),
(108, 'SherlockHolmes4.jpg', '69'),
(109, 'tamcam1.jpg', '71'),
(110, 'tamcam2.jpg', '71'),
(111, 'tamcam3.jpg', '71'),
(112, 'tamcam4.jpg', '71'),
(113, 'aladdinvacaydenthan1.jpg', '72'),
(114, 'aladdinvacaydenthan2.jpg', '72'),
(115, 'aladdinvacaydenthan3.jpg', '72'),
(116, 'aladdinvacaydenthan4.jpg', '72'),
(117, 'cobelolem1.jpg', '73'),
(118, 'cobelolem2.jpg', '73'),
(119, 'cobelolem3.jpg', '73'),
(120, 'cobelolem4.jpg', '73'),
(121, 'nangtienca1.jpg', '74'),
(122, 'nangtienca2.jpg', '74'),
(123, 'nangtienca3.jpg', '74'),
(124, 'nangtienca4.jpg', '74'),
(125, 'thachsanhlythong1.jpg', '75'),
(126, 'thachsanhlythong2.jpg', '75'),
(127, 'thachsanhlythong3.jpg', '75'),
(128, 'thachsanhlythong4.jpg', '75'),
(129, 'sodua1.jpg', '76'),
(130, 'sodua2.jpg', '76'),
(131, 'sodua3.jpg', '76'),
(132, 'sodua4.jpg', '76'),
(133, 'caytretramdot1.jpg', '77'),
(134, 'caytretramdot2.jpg', '77'),
(135, 'caytretramdot3.jpg', '77'),
(136, 'caytretramdot4.jpg', '77'),
(137, 'thanhgiong1.jpg', '78'),
(138, 'thanhgiong2.jpg', '78'),
(139, 'thanhgiong3.jpg', '78'),
(140, 'thanhgiong4.jpg', '78'),
(141, 'sutichbanhchungbanhgiay1.jpg', '79'),
(142, 'sutichbanhchungbanhgiay2.jpg', '79'),
(143, 'thachsanhlythong3.jpg', '79'),
(144, 'sutichbanhchungbanhgiay4.jpg', '79'),
(145, 'sontinhthuytinh1.jpg', '70'),
(146, 'sontinhthuytinh2.jpg', '70'),
(147, 'sontinhthuytinh3.jpg', '70'),
(148, 'sontinhthuytinh4.jpg', '70'),
(149, 'sachgiaokhoalop1-1.jpg', '91'),
(150, 'sachgiaokhoalop1-2.jpg', '91'),
(151, 'sachgiaokhoalop1-3.jpg', '91'),
(152, 'sachgiaokhoalop1-4.jpg', '91'),
(153, 'sachgiaokhoalop2-1.jpg', '92'),
(154, 'sachgiaokhoalop2-2.jpg', '92'),
(155, 'sachgiaokhoalop2-3.jpg', '92'),
(156, 'sachgiaokhoalop2-4.jpg', '92'),
(157, 'sachgiaokhoalop3-1.jpg', '93'),
(158, 'sachgiaokhoalop3-2.jpg', '93'),
(159, 'sachgiaokhoalop3-3.jpg', '93'),
(160, 'sachgiaokhoalop3-4.jpg', '93'),
(161, 'sachgiaokhoalop4-1.jpg', '94'),
(162, 'sachgiaokhoalop4-2.jpg', '94'),
(163, 'sachgiaokhoalop4-3.jpg', '94'),
(166, 'sachgiaokhoalop4-4.jpg', '94'),
(167, 'dacnhantam1.jpg', '95'),
(168, 'dacnhantam2.jpg', '95'),
(169, 'dacnhantam3.jpg', '95'),
(170, 'dacnhantam4.jpg', '95'),
(171, 'tienganhlop6-1.jpg', '96'),
(172, 'tienganhlop6-2.jpg', '96'),
(173, 'tienganhlop6-3.jpg', '96'),
(174, 'tienganhlop6-4.jpg', '96'),
(175, 'tinhoclop7-2.jpg', '97'),
(176, 'tinhoclop7-3.jpg', '97'),
(177, 'tinhoclop7-4.jpg', '97'),
(178, 'toanlop8-1.jpg', '98'),
(179, 'toanlop8-2.jpg', '98'),
(180, 'toanlop8-3.jpg', '98'),
(181, 'toanlop8-4.jpg', '98'),
(182, 'lop9-1.jpg', '99'),
(183, 'lop9-2.jpg', '99'),
(184, 'lop9-3.jpg', '99'),
(185, 'lop9-4.jpg', '99'),
(186, 'giaitichlop12-1.jpg', '90'),
(187, 'giaitichlop12-2.jpg', '90'),
(188, 'giaitichlop12-3.jpg', '90'),
(189, 'giaitichlop12-4.jpg', '90'),
(190, 'kythuatlaptrinh1.jpg', '80'),
(191, 'kythuatlaptrinh2.jpg', '80'),
(192, 'kythuatlaptrinh3.jpg', '80'),
(193, 'kythuatlaptrinh4.jpg', '80'),
(194, 'duongthientu2.jpg', '81'),
(195, 'duongthientu3.jpg', '81'),
(196, 'duongthientu4.jpg', '81'),
(197, 'labview1.jpg', '82'),
(198, 'labview3.jpg', '82'),
(199, 'labview2.jpg', '82'),
(200, 'labview4.jpg', '82'),
(201, 'laptrinhnangcao1.jpg', '83'),
(202, 'laptrinhnangcao2.jpg', '83'),
(203, 'laptrinhnangcao3.jpg', '83'),
(204, 'laptrinhnangcao4.jpg', '83'),
(205, 'python1.jpg', '84'),
(206, 'python2.jpg', '84'),
(207, 'python3.jpg', '84'),
(208, 'python4.jpg', '84'),
(209, 'stem2.jpg', '85'),
(210, 'stem4.jpg', '85'),
(211, 'stem3.jpg', '85'),
(212, 'android1.jpg', '86'),
(213, 'android2.jpg', '86'),
(214, 'android3.jpg', '86'),
(215, 'android4.jpg', '86'),
(216, 'java1.jpg', '87'),
(217, 'java2.jpg', '87'),
(218, 'java3.jpg', '87'),
(219, 'java4.jpg', '87'),
(220, 'inventor3.jpg', '88'),
(221, 'inventor2.jpg', '88'),
(222, 'inventor1.jpg', '88'),
(223, 'inventor4.jpg', '88'),
(224, 'thuongmaidientu1.jpg', '89'),
(225, 'thuongmaidientu3.jpg', '89'),
(226, 'thuongmaidientu2.jpg', '89'),
(227, 'thuongmaidientu4.jpg', '89'),
(228, '100.jpg', '100'),
(229, '100-2.jpg', '100'),
(230, '100-3.jpg', '100'),
(231, '100-4.jpg', '100'),
(232, '101-1.jpg', '101'),
(233, '101-2.jpg', '101'),
(234, '101-3.jpg', '101'),
(235, '101-4.jpg', '101'),
(236, '102-1.jpg', '102'),
(237, '102-2.jpg', '102'),
(238, '102-3.jpg', '102'),
(239, '102-4.jpg', '102'),
(240, '103-1.jpg', '103'),
(241, '103-2.jpg', '103'),
(242, '103-3.jpg', '103'),
(243, '103-4.jpg', '103'),
(244, '104-1.jpg', '104'),
(245, '104-2.jpg', '104'),
(246, '104-3.jpg', '104'),
(247, '104-4.jpg', '104'),
(248, '105-1.jpg', '105'),
(249, '105-2.jpg', '105'),
(250, '105-3.jpg', '105'),
(251, '105-4.jpg', '105'),
(252, '106-1.jpg', '106'),
(253, '106-2.jpg', '106'),
(254, '106-3.jpg', '106'),
(255, '106-4.jpg', '106'),
(256, '107-1.jpg', '107'),
(257, '107-2.jpg', '107'),
(258, '107-3.jpg', '107'),
(259, '107-4.jpg', '107'),
(260, '108-1.jpg', '108'),
(261, '108-2.jpg', '108'),
(262, '108-3.jpg', '108'),
(263, '108-4.jpg', '108'),
(264, '109-1.jpg', '109'),
(265, '109-2.jpg', '109'),
(266, '109-3.jpg', '109'),
(267, '109-4.jpg', '109');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `RoleID` INT NOT NULL,
  `Name` char(50) NOT NULL,
  `Description` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`RoleID`, `Name`, `Description`) VALUES
(1, 'Quản trị', 'Có toàn quyền sử dụng'),
(2, 'Khách hàng', 'Mua hàng, đặt hàng, xem vận đơn...');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`AccountID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Chỉ mục cho bảng `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ISBN`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `AccountID` (`AccountID`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ISBN` (`ISBN`);

--
-- Chỉ mục cho bảng `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`PhotoID`),
  ADD KEY `photos_ibfk_1` (`ISBN`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`RoleID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `accounts`
--
ALTER TABLE `accounts`
  MODIFY `AccountID` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `photos`
--
ALTER TABLE `photos`
  MODIFY `PhotoID` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `RoleID` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `roles` (`RoleID`);

--
-- Các ràng buộc cho bảng `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `accounts` (`AccountID`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`ISBN`) REFERENCES `books` (`ISBN`);

--
-- Các ràng buộc cho bảng `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`ISBN`) REFERENCES `books` (`ISBN`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

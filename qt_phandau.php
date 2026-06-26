<?php
session_start();

if(!isset($_SESSION["Name"])) {
    header("Location:login.php");
    exit();
}
if($_SESSION["Role"] == 2) {
    header("Location:trangchu.php");
    exit();
}

include_once("connect.php");

// --- Truy vấn dữ liệu thật ---

// Tổng số sách
$r = mysqli_query($conn, "SELECT COUNT(*) as total FROM books");
$total_books = mysqli_fetch_assoc($r)['total'];

// Tổng số danh mục
$r = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
$total_categories = mysqli_fetch_assoc($r)['total'];

// Tổng số đơn hàng
$r = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$total_orders = mysqli_fetch_assoc($r)['total'];

// Doanh thu tháng này
$r = mysqli_query($conn, "SELECT SUM(Amount) as revenue FROM orders WHERE MONTH(DateTran) = MONTH(CURDATE()) AND YEAR(DateTran) = YEAR(CURDATE())");
$monthly_revenue = mysqli_fetch_assoc($r)['revenue'] ?? 0;

// Doanh thu tháng trước (để tính % tăng trưởng)
$r = mysqli_query($conn, "SELECT SUM(Amount) as revenue FROM orders WHERE MONTH(DateTran) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(DateTran) = YEAR(CURDATE() - INTERVAL 1 MONTH)");
$last_revenue = mysqli_fetch_assoc($r)['revenue'] ?? 0;
$growth = ($last_revenue > 0) ? round((($monthly_revenue - $last_revenue) / $last_revenue) * 100) : 0;

// Tổng số sách thêm tháng này (dùng Soluong thay vì không có created_at)
// -> dùng tổng sluong sách trong tháng qua order_items thay thế
$r = mysqli_query($conn, "SELECT SUM(oi.Quantity) as sold FROM order_items oi JOIN orders o ON oi.OrderID = o.OrderID WHERE MONTH(o.DateTran) = MONTH(CURDATE()) AND YEAR(o.DateTran) = YEAR(CURDATE())");
$sold_this_month = mysqli_fetch_assoc($r)['sold'] ?? 0;

// 10 đơn hàng gần nhất
$recent_orders = mysqli_query($conn, "
    SELECT o.OrderID, a.Name as CustomerName, o.Amount, o.DateTran
    FROM orders o
    LEFT JOIN accounts a ON o.AccountID = a.AccountID
    ORDER BY o.DateTran DESC, o.OrderID DESC
    LIMIT 10
");

// Lấy tên admin từ session
$admin_name = $_SESSION["Name"];
$avatar = strtoupper(mb_substr($admin_name, 0, 1, 'UTF-8'));

// Format tiền VND
function formatVND($amount) {
    if ($amount >= 1000000) return number_format($amount/1000000, 1) . 'M';
    if ($amount >= 1000) return number_format($amount/1000, 0) . 'K';
    return number_format($amount, 0);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nhà Sách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        :root {
            --sidebar-width: 240px;
            --accent: #3b82f6;
            --accent-light: #eff6ff;
            --accent-text: #1d4ed8;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --success: #22c55e;
            --success-light: #f0fdf4;
            --success-text: #15803d;
            --warning-light: #fffbeb;
            --warning-text: #b45309;
            --sidebar-bg: #f8f9fa;
            --border: #e5e7eb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; display: flex; min-height: 100vh; background: #f1f5f9; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
        .logo-area {
            padding: 20px 16px 16px;
            border-bottom: 1px solid var(--border);
        }
        .logo-text {
            font-size: 15px;
            font-weight: 600;
            color: #111;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logo-text i { color: var(--accent); font-size: 20px; }
        .logo-sub { font-size: 11px; color: #9ca3af; margin-top: 2px; padding-left: 28px; }

        .user-card {
            margin: 12px;
            padding: 10px 12px;
            background: var(--accent-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: var(--accent-text); }
        .user-role { font-size: 11px; color: #93c5fd; }

        .nav-section {
            padding: 12px 16px 4px;
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            margin: 1px 8px;
            border-radius: 6px;
            color: #4b5563;
            font-size: 13.5px;
            text-decoration: none;
            transition: background .12s, color .12s;
        }
        .nav-item:hover { background: #e5e7eb; color: #111; }
        .nav-item.active { background: var(--accent-light); color: var(--accent-text); font-weight: 500; }
        .nav-item i { font-size: 17px; flex-shrink: 0; }

        .sidebar-divider { height: 1px; background: var(--border); margin: 8px 12px; }
        .spacer { flex: 1; }

        .logout-btn {
            margin: 8px;
            padding: 9px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--danger);
            font-size: 13.5px;
            border: 1px solid #fecaca;
            background: transparent;
            width: calc(100% - 16px);
            cursor: pointer;
            text-decoration: none;
            transition: background .12s;
        }
        .logout-btn:hover { background: var(--danger-light); color: var(--danger); }
        .logout-btn i { font-size: 17px; }

        /* --- MAIN --- */
        .main {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 28px 32px;
        }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-title { font-size: 20px; font-weight: 600; color: #111; }
        .page-sub { font-size: 13px; color: #6b7280; margin-top: 3px; }

        .btn-home {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: #fff;
            color: #4b5563;
            font-size: 13px;
            text-decoration: none;
            transition: border-color .12s, color .12s;
        }
        .btn-home:hover { border-color: var(--accent); color: var(--accent-text); }

        /* --- STAT CARDS --- */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 24px; }
        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px 16px;
        }
        .stat-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }
        .stat-label { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
        .stat-val { font-size: 24px; font-weight: 600; color: #111; }
        .stat-sub { font-size: 11px; color: #9ca3af; margin-top: 4px; }
        .stat-sub.up { color: var(--success-text); }
        .stat-sub.down { color: var(--danger); }

        /* --- TABLE CARD --- */
        .table-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }
        .table-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-card-title { font-size: 14px; font-weight: 600; color: #111; }
        .table-card-sub { font-size: 12px; color: #9ca3af; margin-top: 2px; }
        .table-card table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table-card th {
            text-align: left;
            padding: 10px 20px;
            font-weight: 500;
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            background: #f9fafb;
            border-bottom: 1px solid var(--border);
        }
        .table-card td { padding: 12px 20px; border-bottom: 1px solid #f3f4f6; color: #374151; }
        .table-card tr:last-child td { border-bottom: none; }
        .table-card tr:hover td { background: #f9fafb; }
        .order-id { font-family: monospace; color: #6b7280; }
        .amount { font-weight: 500; color: #111; }
        .date { color: #9ca3af; font-size: 12px; }
        .empty-row td { text-align: center; padding: 32px; color: #9ca3af; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<nav class="sidebar">
    <div class="logo-area">
        <div class="logo-text"><i class="ti ti-book-2"></i>Nhà sách Admin</div>
        <div class="logo-sub">Quản lý hệ thống</div>
    </div>

    <div class="user-card">
        <div class="avatar"><?= $avatar ?></div>
        <div>
            <div class="user-name"><?= htmlspecialchars($admin_name) ?></div>
            <div class="user-role">Quản trị viên</div>
        </div>
    </div>

    <div class="nav-section">Tổng quan</div>
    <a class="nav-item active" href="admin.php"><i class="ti ti-layout-dashboard"></i>Dashboard</a>

    <div class="nav-section">Quản lý dữ liệu</div>
    <a class="nav-item" href="danhmuc.php"><i class="ti ti-category"></i>Danh mục</a>
    <a class="nav-item" href="sach.php"><i class="ti ti-books"></i>Sản phẩm (sách)</a>
    <a class="nav-item" href="photos.php"><i class="ti ti-photo"></i>Hình ảnh</a>
    <a class="nav-item" href="donhang.php"><i class="ti ti-shopping-cart"></i>Đơn hàng</a>

    <div class="nav-section">Thống kê</div>
    <a class="nav-item" href="thongke_anpham.php"><i class="ti ti-chart-bar"></i>Ấn phẩm theo danh mục</a>
    <a class="nav-item" href="thongke_doanhthu.php"><i class="ti ti-report-money"></i>Doanh thu</a>

    <div class="sidebar-divider"></div>
    <a class="nav-item" href="trangchu.php"><i class="ti ti-home"></i>Về trang chủ</a>

    <div class="spacer"></div>
    <a class="logout-btn" href="logout.php?flag=1"><i class="ti ti-logout"></i>Đăng xuất</a>
</nav>

<!-- MAIN CONTENT -->
<main class="main">
    <div class="topbar">
        <div>
            <div class="page-title">Dashboard</div>
            <div class="page-sub">Chào mừng trở lại, <?= htmlspecialchars($admin_name) ?>!</div>
        </div>
        <a class="btn-home" href="trangchu.php"><i class="ti ti-home" style="font-size:15px"></i>Trang chủ</a>
    </div>

    <!-- STAT CARDS -->
    <div class="cards">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff; color:#3b82f6"><i class="ti ti-books"></i></div>
            <div class="stat-label">Tổng sách</div>
            <div class="stat-val"><?= number_format($total_books) ?></div>
            <?php if($sold_this_month > 0): ?>
            <div class="stat-sub up"><i class="ti ti-trending-up" style="font-size:11px"></i> <?= $sold_this_month ?> cuốn bán tháng này</div>
            <?php else: ?>
            <div class="stat-sub">Chưa có đơn tháng này</div>
            <?php endif; ?>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4; color:#22c55e"><i class="ti ti-category"></i></div>
            <div class="stat-label">Danh mục</div>
            <div class="stat-val"><?= number_format($total_categories) ?></div>
            <div class="stat-sub">Loại sách</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed; color:#f97316"><i class="ti ti-shopping-cart"></i></div>
            <div class="stat-label">Tổng đơn hàng</div>
            <div class="stat-val"><?= number_format($total_orders) ?></div>
            <div class="stat-sub">Tất cả thời gian</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff; color:#a855f7"><i class="ti ti-report-money"></i></div>
            <div class="stat-label">Doanh thu tháng</div>
            <div class="stat-val" style="font-size:20px"><?= formatVND($monthly_revenue) ?>đ</div>
            <?php if($growth > 0): ?>
            <div class="stat-sub up"><i class="ti ti-trending-up" style="font-size:11px"></i> +<?= $growth ?>% so với tháng trước</div>
            <?php elseif($growth < 0): ?>
            <div class="stat-sub down"><i class="ti ti-trending-down" style="font-size:11px"></i> <?= $growth ?>% so với tháng trước</div>
            <?php else: ?>
            <div class="stat-sub">Tháng <?= date('m/Y') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="table-card">
        <div class="table-card-header">
            <div>
                <div class="table-card-title">Đơn hàng gần đây</div>
                <div class="table-card-sub">10 đơn hàng mới nhất</div>
            </div>
            <a href="donhang.php" class="btn-home" style="font-size:12px">Xem tất cả <i class="ti ti-arrow-right" style="font-size:14px"></i></a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($recent_orders) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($recent_orders)): ?>
                    <tr>
                        <td class="order-id">#<?= $row['OrderID'] ?></td>
                        <td><?= htmlspecialchars($row['CustomerName'] ?? 'Khách vãng lai') ?></td>
                        <td class="amount"><?= number_format($row['Amount'], 0, ',', '.') ?>đ</td>
                        <td class="date"><?= date('d/m/Y', strtotime($row['DateTran'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr class="empty-row"><td colspan="4">Chưa có đơn hàng nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
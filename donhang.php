<?php
session_start();
include_once("connect.php");

$sql = "SELECT o.OrderID, a.Username, o.Amount, o.DateTran, i.ISBN, b.Title, b.Price, i.Prices, i.Quantity
        FROM orders o
        INNER JOIN order_items i ON o.OrderID = i.OrderID
        INNER JOIN accounts a ON o.AccountID = a.AccountID
        INNER JOIN books b ON i.ISBN = b.ISBN
        ORDER BY o.DateTran DESC, o.OrderID DESC";

$result = $conn->query($sql);
$orders = [];
$total_revenue = 0;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
        $total_revenue += floatval($row["Prices"]);
    }
}
$conn->close();

$is_admin = isset($_SESSION["Role"]) && $_SESSION["Role"] == 1;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        :root {
            --cream: #FAF8F4;
            --ink: #1C1A16;
            --muted: #7A7570;
            --accent: #C0392B;
            --accent-soft: #F9EEEC;
            --border-line: #E8E4DE;
            --surface: #fff;
            --thumb-bg: #F0EDE8;
            --green: #1a7a4a;
            --green-soft: #E6F5EE;
        }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
        }

        /* ── Page header ── */
        .page-header {
            padding: 22px 0 18px;
            border-bottom: 1px solid var(--border-line);
            margin-bottom: 28px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-header h1 {
            font-size: 22px; font-weight: 700; margin: 0;
            display: flex; align-items: center; gap: 10px;
        }
        .page-header h1 i { color: var(--accent); }
        .breadcrumb-bar {
            font-size: 13px; color: var(--muted); margin-top: 4px;
        }
        .breadcrumb-bar a { color: var(--muted); text-decoration: none; }
        .breadcrumb-bar a:hover { color: var(--ink); }

        /* ── Stat cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 12px;
            padding: 18px 20px;
        }
        .stat-card .label {
            font-size: 11px; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 6px;
        }
        .stat-card .value {
            font-size: 24px; font-weight: 800; color: var(--ink);
        }
        .stat-card .value.accent { color: var(--accent); }
        .stat-card .value.green  { color: var(--green); }

        /* ── Search/filter bar ── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 180px;
        }
        .search-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 16px; pointer-events: none;
        }
        .search-wrap input {
            width: 100%; height: 38px;
            padding: 0 12px 0 36px;
            border: 1px solid var(--border-line);
            border-radius: 8px;
            font-size: 14px; color: var(--ink);
            background: var(--cream);
            outline: none;
        }
        .search-wrap input:focus { border-color: #aaa; background: #fff; }
        .filter-count {
            font-size: 13px; color: var(--muted); white-space: nowrap;
        }
        #filterCount { font-weight: 700; color: var(--ink); }

        /* ── Table ── */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 16px;
            overflow: hidden;
        }
        .order-table {
            width: 100%; border-collapse: collapse;
            font-size: 14px;
        }
        .order-table thead {
            background: var(--thumb-bg);
            border-bottom: 1px solid var(--border-line);
        }
        .order-table th {
            padding: 12px 16px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .06em; text-transform: uppercase;
            color: var(--muted); text-align: left;
            white-space: nowrap;
        }
        .order-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-line);
            vertical-align: middle;
        }
        .order-table tbody tr:last-child td { border-bottom: none; }
        .order-table tbody tr:hover { background: #FDFCFB; }

        /* order id */
        .order-id {
            font-weight: 700; font-size: 13px;
            color: var(--accent);
        }

        /* user chip */
        .user-chip {
            display: inline-flex; align-items: center; gap: 6px;
        }
        .user-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--accent-soft); color: var(--accent);
            font-size: 11px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* date */
        .date-cell { color: var(--muted); font-size: 13px; }

        /* isbn */
        .isbn-cell {
            font-family: monospace; font-size: 12px;
            background: var(--thumb-bg); border-radius: 4px;
            padding: 2px 7px; color: var(--muted);
        }

        /* title */
        .title-cell { font-weight: 500; max-width: 180px; }

        /* price */
        .price-cell { font-weight: 500; white-space: nowrap; }
        .total-cell { font-weight: 700; color: var(--green); white-space: nowrap; }

        /* qty badge */
        .qty-badge {
            display: inline-block;
            background: #EEF3FB; color: #1a4a8a;
            border: 1px solid #C6D8F5;
            border-radius: 99px; padding: 2px 10px;
            font-size: 12px; font-weight: 600;
        }

        /* delete btn */
        .btn-delete {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px;
            background: transparent; border: 1px solid var(--border-line);
            border-radius: 8px; color: var(--muted);
            font-size: 16px; cursor: pointer;
            text-decoration: none;
            transition: background .12s, color .12s, border-color .12s;
        }
        .btn-delete:hover {
            background: var(--accent-soft);
            border-color: #F5BDB6;
            color: var(--accent);
        }

        /* ── Empty state ── */
        .empty-state {
            padding: 64px 32px; text-align: center;
        }
        .empty-icon { font-size: 48px; color: var(--border-line); margin-bottom: 14px; }
        .empty-state h3 { font-size: 17px; font-weight: 600; margin-bottom: 6px; }
        .empty-state p  { font-size: 14px; color: var(--muted); }

        /* ── Back button ── */
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            height: 38px; padding: 0 18px;
            background: transparent; color: var(--ink);
            border: 1.5px solid var(--border-line); border-radius: 8px;
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            transition: border-color .15s, background .15s;
        }
        .btn-back:hover { border-color: var(--ink); background: var(--thumb-bg); color: var(--ink); }

        /* ── hidden rows ── */
        .order-row.hidden { display: none; }

        /* ── responsive ── */
        @media (max-width: 768px) {
            .order-table th:nth-child(4),
            .order-table td:nth-child(4),
            .order-table th:nth-child(6),
            .order-table td:nth-child(6) { display: none; }
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 1100px;">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="ti ti-list-check" aria-hidden="true"></i> Quản lý đơn hàng</h1>
            <div class="breadcrumb-bar">
                <a href="trangchu.php">Trang chủ</a>
                <span style="margin:0 6px;">›</span>
                <span>Đơn hàng</span>
            </div>
        </div>
        <a href="trangchu.php" class="btn-back">
            <i class="ti ti-arrow-left" aria-hidden="true"></i> Trang chủ
        </a>
    </div>

    <?php if (!empty($orders)): ?>

    <!-- Stat cards -->
    <?php
        $unique_orders = count(array_unique(array_column($orders, 'OrderID')));
        $unique_users  = count(array_unique(array_column($orders, 'Username')));
        $total_qty     = array_sum(array_column($orders, 'Quantity'));
    ?>
    <div class="stat-grid">
        <div class="stat-card">
            <div class="label">Tổng đơn hàng</div>
            <div class="value accent"><?php echo $unique_orders; ?></div>
        </div>
        <div class="stat-card">
            <div class="label">Khách hàng</div>
            <div class="value"><?php echo $unique_users; ?></div>
        </div>
        <div class="stat-card">
            <div class="label">Sách đã bán</div>
            <div class="value"><?php echo $total_qty; ?></div>
        </div>
        <div class="stat-card">
            <div class="label">Tổng doanh thu</div>
            <div class="value green"><?php echo number_format($total_revenue, 0, ',', '.'); ?> ₫</div>
        </div>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar">
        <div class="search-wrap">
            <i class="ti ti-search" aria-hidden="true"></i>
            <input type="text" id="searchInput"
                   placeholder="Tìm theo tên tài khoản, tên sách, ISBN, mã đơn..."
                   oninput="filterTable()">
        </div>
        <div class="filter-count">Hiển thị <span id="filterCount"><?php echo count($orders); ?></span> / <?php echo count($orders); ?> dòng</div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <table class="order-table" id="orderTable">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Tài khoản</th>
                    <th>Ngày đặt</th>
                    <th>ISBN</th>
                    <th>Tên sách</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <?php if ($is_admin): ?><th></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $row):
                $initials = "";
                $parts = explode(" ", trim($row["Username"]));
                foreach (array_slice($parts, -2) as $p)
                    $initials .= mb_strtoupper(mb_substr($p, 0, 1));
                if (!$initials) $initials = mb_strtoupper(mb_substr($row["Username"], 0, 2));
            ?>
            <tr class="order-row"
                data-search="<?php echo strtolower(htmlspecialchars(
                    $row['OrderID'].' '.$row['Username'].' '.$row['ISBN'].' '.$row['Title']
                )); ?>">
                <td><span class="order-id">#<?php echo htmlspecialchars($row["OrderID"]); ?></span></td>
                <td>
                    <div class="user-chip">
                        <div class="user-avatar"><?php echo htmlspecialchars($initials); ?></div>
                        <?php echo htmlspecialchars($row["Username"]); ?>
                    </div>
                </td>
                <td class="date-cell">
                    <i class="ti ti-calendar" style="font-size:13px;vertical-align:-1px;" aria-hidden="true"></i>
                    <?php echo htmlspecialchars(date("d/m/Y", strtotime($row["DateTran"]))); ?>
                </td>
                <td><span class="isbn-cell"><?php echo htmlspecialchars($row["ISBN"]); ?></span></td>
                <td class="title-cell"><?php echo htmlspecialchars($row["Title"]); ?></td>
                <td class="price-cell"><?php echo number_format(floatval($row["Price"]), 0, ',', '.'); ?> ₫</td>
                <td><span class="qty-badge"><?php echo htmlspecialchars($row["Quantity"]); ?></span></td>
                <td class="total-cell"><?php echo number_format(floatval($row["Prices"]), 0, ',', '.'); ?> ₫</td>
                <?php if ($is_admin): ?>
                <td>
                    <a class="btn-delete"
                       href="xoa_donhang.php?ma=<?php echo urlencode($row["OrderID"]); ?>"
                       title="Xóa đơn hàng #<?php echo $row["OrderID"]; ?>"
                       onclick="return confirm('Bạn có chắc muốn xóa đơn hàng #<?php echo $row["OrderID"]; ?> không?');">
                        <i class="ti ti-trash" aria-hidden="true"></i>
                    </a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($orders)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="ti ti-clipboard-x" aria-hidden="true"></i></div>
            <h3>Chưa có đơn hàng</h3>
            <p>Hiện chưa có đơn hàng nào trong hệ thống.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php else: ?>

    <div class="table-card">
        <div class="empty-state">
            <div class="empty-icon"><i class="ti ti-clipboard-x" aria-hidden="true"></i></div>
            <h3>Chưa có đơn hàng</h3>
            <p>Hiện chưa có đơn hàng nào trong hệ thống.</p>
        </div>
    </div>

    <?php endif; ?>

</div>

<script>
function filterTable() {
    const q     = document.getElementById('searchInput').value.toLowerCase().trim();
    const rows  = document.querySelectorAll('.order-row');
    let visible = 0;
    rows.forEach(row => {
        const match = !q || row.dataset.search.includes(q);
        row.classList.toggle('hidden', !match);
        if (match) visible++;
    });
    document.getElementById('filterCount').textContent = visible;
}
</script>

</body>
</html>
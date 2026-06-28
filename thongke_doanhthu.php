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
$doanhthu = 0;
$total_qty = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
        $doanhthu  += floatval($row["Prices"]);
        $total_qty += intval($row["Quantity"]);
    }
}
$conn->close();

$unique_orders = count(array_unique(array_column($orders, 'OrderID')));
$unique_users  = count(array_unique(array_column($orders, 'Username')));
$avg_order     = $unique_orders > 0 ? $doanhthu / $unique_orders : 0;

// Group revenue by date for sparkline data
$by_date = [];
foreach ($orders as $r) {
    $d = $r['DateTran'];
    $by_date[$d] = ($by_date[$d] ?? 0) + floatval($r['Prices']);
}
ksort($by_date);
$spark_vals  = array_values($by_date);
$spark_dates = array_keys($by_date);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê doanh thu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
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
            --blue: #185FA5;
            --blue-soft: #E6F1FB;
            --amber: #854F0B;
            --amber-soft: #FAEEDA;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            padding-bottom: 48px;
        }

        /* ── Header ── */
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
        .breadcrumb-bar { font-size: 13px; color: var(--muted); margin-top: 4px; }
        .breadcrumb-bar a { color: var(--muted); text-decoration: none; }
        .breadcrumb-bar a:hover { color: var(--ink); }

        /* ── KPI cards ── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .kpi-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 14px;
            padding: 20px 22px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .kpi-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .kpi-icon.red    { background: var(--accent-soft); color: var(--accent); }
        .kpi-icon.green  { background: var(--green-soft);  color: var(--green); }
        .kpi-icon.blue   { background: var(--blue-soft);   color: var(--blue); }
        .kpi-icon.amber  { background: var(--amber-soft);  color: var(--amber); }
        .kpi-label {
            font-size: 11px; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 4px;
        }
        .kpi-value {
            font-size: 22px; font-weight: 800; color: var(--ink);
            line-height: 1.1;
        }
        .kpi-value.red   { color: var(--accent); }
        .kpi-value.green { color: var(--green); }

        /* ── Chart card ── */
        .chart-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
        }
        .chart-card .card-title {
            font-size: 13px; font-weight: 700;
            letter-spacing: .06em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
        }
        .chart-wrap { position: relative; height: 220px; }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .search-wrap {
            position: relative; flex: 1; min-width: 180px;
        }
        .search-wrap i {
            position: absolute; left: 11px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 15px; pointer-events: none;
        }
        .search-wrap input {
            width: 100%; height: 36px;
            padding: 0 12px 0 34px;
            border: 1px solid var(--border-line); border-radius: 8px;
            font-size: 14px; color: var(--ink);
            background: var(--cream); outline: none;
        }
        .search-wrap input:focus { border-color: #aaa; background: #fff; }
        .row-count { font-size: 13px; color: var(--muted); white-space: nowrap; }
        #visCount { font-weight: 700; color: var(--ink); }

        /* ── Table ── */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .order-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .order-table thead {
            background: var(--thumb-bg);
            border-bottom: 1px solid var(--border-line);
        }
        .order-table th {
            padding: 11px 14px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .06em; text-transform: uppercase;
            color: var(--muted); text-align: left; white-space: nowrap;
        }
        .order-table td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--border-line);
            vertical-align: middle;
        }
        .order-table tbody tr:last-child td { border-bottom: none; }
        .order-table tbody tr:hover { background: #FDFCFB; }
        .order-table tbody tr.hidden { display: none; }

        .oid   { font-weight: 700; color: var(--accent); font-size: 13px; }
        .user-chip { display: inline-flex; align-items: center; gap: 6px; }
        .uavatar {
            width: 26px; height: 26px; border-radius: 50%;
            background: var(--accent-soft); color: var(--accent);
            font-size: 10px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
        }
        .date-cell { color: var(--muted); font-size: 13px; }
        .isbn-pill {
            font-family: monospace; font-size: 12px;
            background: var(--thumb-bg); border-radius: 4px;
            padding: 2px 7px; color: var(--muted);
        }
        .qty-badge {
            display: inline-block;
            background: var(--blue-soft); color: var(--blue);
            border-radius: 99px; padding: 2px 10px;
            font-size: 12px; font-weight: 600;
        }
        .price-cell { font-weight: 500; white-space: nowrap; }
        .total-cell { font-weight: 700; color: var(--green); white-space: nowrap; }

        /* ── Revenue total banner ── */
        .revenue-banner {
            background: linear-gradient(135deg, #1a7a4a 0%, #145c38 100%);
            border-radius: 14px;
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            color: #fff;
        }
        .revenue-banner .label {
            font-size: 13px; opacity: .8; margin-bottom: 4px;
        }
        .revenue-banner .amount {
            font-size: 30px; font-weight: 800; letter-spacing: -.5px;
        }
        .revenue-banner .sub {
            font-size: 13px; opacity: .7; margin-top: 2px;
        }
        .revenue-banner i { font-size: 48px; opacity: .2; }

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

        /* ── Empty ── */
        .empty-state { padding: 64px 32px; text-align: center; }
        .empty-state .eicon { font-size: 48px; color: var(--border-line); margin-bottom: 14px; }
        .empty-state h3 { font-size: 17px; font-weight: 600; margin-bottom: 6px; }
        .empty-state p  { font-size: 14px; color: var(--muted); }

        @media (max-width: 768px) {
            .order-table th:nth-child(4),
            .order-table td:nth-child(4) { display: none; }
            .revenue-banner i { display: none; }
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 1100px;">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="ti ti-chart-bar" aria-hidden="true"></i> Thống kê doanh thu</h1>
            <div class="breadcrumb-bar">
                <a href="trangchu.php">Trang chủ</a>
                <span style="margin:0 6px;">›</span>
                <span>Thống kê doanh thu</span>
            </div>
        </div>
        <a href="trangchu.php" class="btn-back">
            <i class="ti ti-arrow-left" aria-hidden="true"></i> Trang chủ
        </a>
    </div>

    <?php if (!empty($orders)): ?>

    <!-- KPI cards -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon red"><i class="ti ti-receipt" aria-hidden="true"></i></div>
            <div>
                <div class="kpi-label">Tổng đơn hàng</div>
                <div class="kpi-value red"><?php echo $unique_orders; ?></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="ti ti-users" aria-hidden="true"></i></div>
            <div>
                <div class="kpi-label">Khách hàng</div>
                <div class="kpi-value"><?php echo $unique_users; ?></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber"><i class="ti ti-books" aria-hidden="true"></i></div>
            <div>
                <div class="kpi-label">Sách đã bán</div>
                <div class="kpi-value"><?php echo $total_qty; ?></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="ti ti-trending-up" aria-hidden="true"></i></div>
            <div>
                <div class="kpi-label">TB / đơn hàng</div>
                <div class="kpi-value green"><?php echo number_format($avg_order, 0, ',', '.'); ?> ₫</div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <?php if (count($spark_vals) > 1): ?>
    <div class="chart-card">
        <div class="card-title">
            <i class="ti ti-chart-line" aria-hidden="true"></i>
            Doanh thu theo ngày
        </div>
        <div class="chart-wrap">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <!-- Revenue banner -->
    <div class="revenue-banner" style="margin-bottom: 24px;">
        <div>
            <div class="label">Tổng doanh thu</div>
            <div class="amount"><?php echo number_format($doanhthu, 0, ',', '.'); ?> ₫</div>
            <div class="sub">Từ <?php echo $unique_orders; ?> đơn hàng · <?php echo $total_qty; ?> sách</div>
        </div>
        <i class="ti ti-cash" aria-hidden="true"></i>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar">
        <div class="search-wrap">
            <i class="ti ti-search" aria-hidden="true"></i>
            <input type="text" id="searchInput"
                   placeholder="Tìm theo tên tài khoản, tên sách, ISBN, mã đơn..."
                   oninput="filterRows()">
        </div>
        <div class="row-count">Hiển thị <span id="visCount"><?php echo count($orders); ?></span> / <?php echo count($orders); ?> dòng</div>
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
            <tr data-search="<?php echo strtolower(htmlspecialchars(
                $row['OrderID'].' '.$row['Username'].' '.$row['ISBN'].' '.$row['Title']
            )); ?>">
                <td><span class="oid">#<?php echo htmlspecialchars($row["OrderID"]); ?></span></td>
                <td>
                    <div class="user-chip">
                        <div class="uavatar"><?php echo htmlspecialchars($initials); ?></div>
                        <?php echo htmlspecialchars($row["Username"]); ?>
                    </div>
                </td>
                <td class="date-cell">
                    <i class="ti ti-calendar" style="font-size:12px;vertical-align:-1px;" aria-hidden="true"></i>
                    <?php echo date("d/m/Y", strtotime($row["DateTran"])); ?>
                </td>
                <td><span class="isbn-pill"><?php echo htmlspecialchars($row["ISBN"]); ?></span></td>
                <td style="font-weight:500;"><?php echo htmlspecialchars($row["Title"]); ?></td>
                <td class="price-cell"><?php echo number_format(floatval($row["Price"]), 0, ',', '.'); ?> ₫</td>
                <td><span class="qty-badge"><?php echo intval($row["Quantity"]); ?></span></td>
                <td class="total-cell"><?php echo number_format(floatval($row["Prices"]), 0, ',', '.'); ?> ₫</td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>
    <div class="table-card">
        <div class="empty-state">
            <div class="eicon"><i class="ti ti-chart-bar-off" aria-hidden="true"></i></div>
            <h3>Chưa có dữ liệu</h3>
            <p>Chưa có đơn hàng nào để thống kê.</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php if (!empty($spark_vals) && count($spark_vals) > 1): ?>
<script>
const labels = <?php echo json_encode($spark_dates); ?>;
const data   = <?php echo json_encode($spark_vals); ?>;

const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels.map(d => {
            const p = d.split('-');
            return p[2] + '/' + p[1];
        }),
        datasets: [{
            label: 'Doanh thu (₫)',
            data: data,
            backgroundColor: 'rgba(192,57,43,0.12)',
            borderColor: '#C0392B',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' ₫'
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 12 }, color: '#7A7570' }
            },
            y: {
                grid: { color: '#F0EDE8' },
                ticks: {
                    font: { size: 12 }, color: '#7A7570',
                    callback: v => new Intl.NumberFormat('vi-VN', {notation:'compact'}).format(v) + '₫'
                }
            }
        }
    }
});
</script>
<?php endif; ?>

<script>
function filterRows() {
    const q    = document.getElementById('searchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#orderTable tbody tr');
    let vis    = 0;
    rows.forEach(r => {
        const match = !q || r.dataset.search.includes(q);
        r.classList.toggle('hidden', !match);
        if (match) vis++;
    });
    document.getElementById('visCount').textContent = vis;
}
</script>

</body>
</html>
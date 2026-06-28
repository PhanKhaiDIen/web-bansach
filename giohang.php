<?php
session_start();
include_once("connect.php");

$isbn = $soLuongMua = $dateTran = $amount = $accountId = $name = "";

if (isset($_POST['sbThemhang'])) {
    $isbn        = $_POST['txtISBN'];
    $soLuongMua  = intval($_POST['txtSoLuongMua']);
    $dateTran    = date("Y-m-d");
    $amount      = "1";
    $accountId   = $_SESSION["Account"];
    $name        = $_SESSION["Name"];
}

// Fetch book data
$book = null;
if ($isbn !== "") {
    $sql    = "SELECT * FROM books WHERE ISBN = '$isbn'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $book = $result->fetch_assoc();
    }
}

$total = $book ? floatval($book["Price"]) * $soLuongMua : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
        }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
        }

        /* ── Header bar ── */
        .page-header {
            padding: 22px 0 18px;
            border-bottom: 1px solid var(--border-line);
            margin-bottom: 36px;
        }
        .page-header h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .page-header h1 i { color: var(--accent); }

        /* Breadcrumb */
        .breadcrumb-bar {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }
        .breadcrumb-bar a { color: var(--muted); text-decoration: none; }
        .breadcrumb-bar a:hover { color: var(--ink); }
        .breadcrumb-bar .sep { margin: 0 6px; }

        /* ── Cart item card ── */
        .cart-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 16px;
            padding: 28px 32px;
            margin-bottom: 20px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-line);
        }

        /* Customer row */
        .customer-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border-line);
            margin-bottom: 20px;
        }
        .avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 700;
            font-size: 16px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .customer-name { font-weight: 600; font-size: 15px; }
        .customer-sub  { font-size: 13px; color: var(--muted); }

        /* Book row */
        .book-row {
            display: grid;
            grid-template-columns: 56px 1fr auto;
            gap: 16px;
            align-items: center;
        }
        .book-thumb {
            width: 56px; height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-line);
        }
        .book-isbn  { font-size: 12px; color: var(--muted); margin-bottom: 4px; }
        .book-title { font-size: 16px; font-weight: 600; margin-bottom: 2px; }

        /* Meta chips */
        .chip-row   { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
        .chip {
            font-size: 12px; font-weight: 500;
            padding: 3px 10px; border-radius: 99px;
            border: 1px solid var(--border-line);
            color: var(--muted);
            background: var(--thumb-bg);
        }
        .chip.qty   { background: #EEF3FB; border-color: #C6D8F5; color: #1a4a8a; }
        .chip.stock { background: #E6F5EE; border-color: #9ADAB9; color: #1a7a4a; }
        .chip.low   { background: #FEF3C7; border-color: #FCD34D; color: #92400e; }

        /* Unit price */
        .unit-price {
            text-align: right;
            white-space: nowrap;
        }
        .unit-price .label { font-size: 11px; color: var(--muted); margin-bottom: 2px; }
        .unit-price .val   { font-size: 18px; font-weight: 700; color: var(--ink); }
        .unit-price .curr  { font-size: 12px; color: var(--muted); }

        /* ── Order summary ── */
        .summary-card {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 16px;
            padding: 28px 32px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 14px;
        }
        .summary-row .key   { color: var(--muted); }
        .summary-row .val   { font-weight: 500; }
        .summary-divider    { border-top: 1px solid var(--border-line); margin: 6px 0; }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 14px 0 0;
        }
        .total-row .key     { font-size: 15px; font-weight: 600; }
        .total-row .val     { font-size: 24px; font-weight: 800; color: var(--accent); }

        /* ── Buttons ── */
        .btn-checkout {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; height: 48px;
            background: var(--accent); color: #fff;
            border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700;
            cursor: pointer; text-decoration: none;
            margin-top: 20px;
            transition: background .15s, transform .1s;
        }
        .btn-checkout:hover  { background: #a93226; color: #fff; }
        .btn-checkout:active { transform: scale(.98); }

        .btn-back {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; height: 42px;
            background: transparent; color: var(--ink);
            border: 1.5px solid var(--border-line); border-radius: 10px;
            font-size: 14px; font-weight: 500;
            cursor: pointer; text-decoration: none;
            margin-top: 10px;
            transition: border-color .15s, background .15s;
        }
        .btn-back:hover { border-color: var(--ink); background: var(--thumb-bg); color: var(--ink); }

        /* ── Empty state ── */
        .empty-state {
            background: var(--surface);
            border: 1px solid var(--border-line);
            border-radius: 16px;
            padding: 64px 32px;
            text-align: center;
        }
        .empty-icon { font-size: 56px; color: var(--border-line); margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
        .empty-state p  { font-size: 14px; color: var(--muted); }

        @media (max-width: 767px) {
            .cart-card, .summary-card { padding: 20px 16px; }
            .book-row { grid-template-columns: 48px 1fr; }
            .unit-price { grid-column: 1 / -1; text-align: left; }
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 960px;">

    <!-- Header -->
    <div class="page-header">
        <h1><i class="ti ti-shopping-cart" aria-hidden="true"></i> Giỏ hàng</h1>
        <div class="breadcrumb-bar">
            <a href="trangchu.php">Trang chủ</a>
            <span class="sep">›</span>
            <span>Giỏ hàng</span>
        </div>
    </div>

    <?php if ($book): ?>

    <div class="row g-4">

        <!-- Left: cart item -->
        <div class="col-lg-8">
            <div class="cart-card">
                <div class="section-label">Thông tin đơn hàng</div>

                <!-- Customer -->
                <?php
                    $initials = "";
                    $parts = explode(" ", trim($name));
                    foreach (array_slice($parts, -2) as $p) $initials .= mb_strtoupper(mb_substr($p, 0, 1));
                ?>
                <div class="customer-row">
                    <div class="avatar"><?php echo htmlspecialchars($initials ?: "?"); ?></div>
                    <div>
                        <div class="customer-name"><?php echo htmlspecialchars($name); ?></div>
                        <div class="customer-sub">Tài khoản: <?php echo htmlspecialchars($accountId); ?></div>
                    </div>
                </div>

                <!-- Book -->
                <div class="book-row">
                    <img class="book-thumb"
                         src="images/<?php echo htmlspecialchars($book['Picture']); ?>"
                         alt="Bìa sách">

                    <div>
                        <div class="book-isbn">ISBN: <?php echo htmlspecialchars($book['ISBN']); ?></div>
                        <div class="book-title"><?php echo htmlspecialchars($book['Title']); ?></div>
                        <div style="font-size:13px; color:var(--muted);"><?php echo htmlspecialchars($book['Author']); ?></div>
                        <div class="chip-row">
                            <span class="chip qty">Số lượng đặt: <?php echo $soLuongMua; ?></span>
                            <?php
                                $soluong = intval($book['Soluong']);
                                $sc = $soluong > 5 ? "stock" : "low";
                                $st = $soluong > 5 ? "Còn " . $soluong . " cuốn" : "Còn " . $soluong . " cuốn (sắp hết)";
                            ?>
                            <span class="chip <?php echo $sc; ?>"><?php echo $st; ?></span>
                        </div>
                    </div>

                    <div class="unit-price">
                        <div class="label">Đơn giá</div>
                        <div class="val"><?php echo number_format(floatval($book['Price']), 0, ',', '.'); ?></div>
                        <div class="curr">VND</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right: summary -->
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="section-label">Tóm tắt thanh toán</div>

                <div class="summary-row">
                    <span class="key">Đơn giá</span>
                    <span class="val"><?php echo number_format(floatval($book['Price']), 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="summary-row">
                    <span class="key">Số lượng</span>
                    <span class="val">× <?php echo $soLuongMua; ?></span>
                </div>
                <div class="summary-row">
                    <span class="key">Phí vận chuyển</span>
                    <span class="val" style="color:#1a7a4a;">Miễn phí</span>
                </div>

                <div class="summary-divider"></div>

                <div class="total-row">
                    <span class="key">Tổng cộng</span>
                    <span class="val"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>

                <a class="btn-checkout"
                   href="hoadon_thanhtoan.php?ma=<?php echo urlencode($book['ISBN']); ?>&slm=<?php echo $soLuongMua; ?>&gia=<?php echo urlencode($book['Price']); ?>">
                    <i class="ti ti-credit-card" aria-hidden="true"></i>
                    Tiến hành thanh toán
                </a>

                <a class="btn-back" href="trangchu.php">
                    <i class="ti ti-arrow-left" aria-hidden="true"></i>
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>

    </div><!-- /row -->

    <?php else: ?>

    <!-- Empty state -->
    <div class="empty-state">
        <div class="empty-icon"><i class="ti ti-shopping-cart-off" aria-hidden="true"></i></div>
        <h3>Giỏ hàng trống</h3>
        <p>Không tìm thấy sản phẩm nào trong giỏ hàng của bạn.</p>
        <a href="trangchu.php" class="btn-checkout" style="max-width:220px; margin:24px auto 0;">
            <i class="ti ti-arrow-left" aria-hidden="true"></i>
            Quay lại trang chủ
        </a>
    </div>

    <?php endif; ?>

</div><!-- /container -->

<?php include_once("phanchan.php"); ?>
</body>
</html>
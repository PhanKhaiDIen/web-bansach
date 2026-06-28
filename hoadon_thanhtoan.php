<?php
session_start();
include_once("connect.php");

$status  = "error";
$message = "";
$orderid = "";
$amonut  = "1";

if (isset($_GET['ma'])) {
    $isbn       = $_GET['ma'];
    $soLuongMua = intval($_GET['slm']);
    $gia        = floatval($_GET['gia']);
    $tongtien   = $soLuongMua * $gia;
    $dateTran   = date("Y-m-d");
    $accountId  = $_SESSION["Account"];
    $name       = $_SESSION["Name"];

    // Insert order
    $sql_dathang = "INSERT INTO orders(AccountID, Amount, DateTran) VALUES('$accountId','$amonut','$dateTran')";
    if ($conn->query($sql_dathang) === TRUE) {
        $orderid = $conn->insert_id;

        // Insert order_items
        $sql_hoadon = "INSERT INTO order_items (ISBN, OrderID, Prices, Quantity) VALUES ('$isbn','$orderid','$tongtien','$soLuongMua')";
        if ($conn->query($sql_hoadon) === TRUE) {

            // Update stock
            $sql_soluong = "UPDATE books SET Soluong = Soluong - '$soLuongMua' WHERE ISBN = '$isbn'";
            if ($conn->query($sql_soluong) === TRUE) {
                $status  = "success";
                $message = "Đơn hàng #" . $orderid . " đã được đặt thành công.";
            } else {
                $message = "Lỗi cập nhật tồn kho: " . $conn->error;
            }
        } else {
            $message = "Lỗi thêm chi tiết đơn hàng: " . $conn->error;
        }
    } else {
        $message = "Lỗi tạo đơn hàng: " . $conn->error;
    }

    $conn->close();
} else {
    $message = "Không có thông tin đơn hàng được gửi.";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $status === "success" ? "Đặt hàng thành công" : "Lỗi đặt hàng"; ?></title>
    <?php if ($status === "success"): ?>
    <meta http-equiv="refresh" content="4;url=donhang.php">
    <?php endif; ?>
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
            --green: #1a7a4a;
            --green-soft: #E6F5EE;
            --green-border: #9ADAB9;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: #fff;
            border: 1px solid var(--border-line);
            border-radius: 20px;
            padding: 48px 44px;
            width: 100%;
            max-width: 480px;
            text-align: center;
        }

        /* ── Icon circle ── */
        .icon-circle {
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
            font-size: 38px;
        }
        .icon-circle.success {
            background: var(--green-soft);
            color: var(--green);
            border: 1.5px solid var(--green-border);
        }
        .icon-circle.error {
            background: var(--accent-soft);
            color: var(--accent);
            border: 1.5px solid #F5BDB6;
        }

        /* ── Text ── */
        .status-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .status-msg {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 28px;
        }

        /* ── Order ID badge ── */
        .order-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--cream);
            border: 1px solid var(--border-line);
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 32px;
            font-size: 14px;
            color: var(--muted);
        }
        .order-badge strong {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
        }

        /* ── Progress bar (auto-redirect) ── */
        .redirect-bar-wrap {
            margin-bottom: 28px;
        }
        .redirect-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .redirect-track {
            height: 4px;
            background: var(--border-line);
            border-radius: 99px;
            overflow: hidden;
        }
        .redirect-fill {
            height: 100%;
            background: var(--green);
            border-radius: 99px;
            animation: fillBar 4s linear forwards;
        }
        @keyframes fillBar {
            from { width: 0%; }
            to   { width: 100%; }
        }

        /* ── Buttons ── */
        .btn-primary-custom {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; height: 46px;
            background: var(--accent); color: #fff;
            border: none; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: background .15s, transform .1s;
            margin-bottom: 10px;
        }
        .btn-primary-custom:hover  { background: #a93226; color: #fff; }
        .btn-primary-custom:active { transform: scale(.98); }
        .btn-primary-custom.green  { background: var(--green); }
        .btn-primary-custom.green:hover { background: #145c38; }

        .btn-ghost {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; height: 42px;
            background: transparent; color: var(--ink);
            border: 1.5px solid var(--border-line); border-radius: 10px;
            font-size: 14px; font-weight: 500;
            cursor: pointer; text-decoration: none;
            transition: border-color .15s, background .15s;
        }
        .btn-ghost:hover { border-color: var(--ink); background: #F0EDE8; color: var(--ink); }

        /* ── Divider ── */
        .divider {
            border-top: 1px solid var(--border-line);
            margin: 24px 0;
        }

        /* ── Date line ── */
        .date-line {
            font-size: 12px;
            color: var(--muted);
            margin-top: 24px;
        }

        @media (max-width: 480px) {
            .card { padding: 36px 20px; }
        }
    </style>
</head>
<body>

<div class="card">

    <?php if ($status === "success"): ?>

        <!-- Success icon -->
        <div class="icon-circle success">
            <i class="ti ti-circle-check" aria-hidden="true"></i>
        </div>

        <div class="status-title">Đặt hàng thành công!</div>
        <p class="status-msg">
            Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận
            và đang được xử lý.
        </p>

        <!-- Order ID -->
        <div class="order-badge">
            <i class="ti ti-receipt" style="font-size:18px;" aria-hidden="true"></i>
            Mã đơn hàng: <strong>#<?php echo htmlspecialchars($orderid); ?></strong>
        </div>

        <!-- Auto-redirect progress -->
        <div class="redirect-bar-wrap">
            <div class="redirect-label">Tự động chuyển đến trang đơn hàng sau 4 giây...</div>
            <div class="redirect-track">
                <div class="redirect-fill"></div>
            </div>
        </div>

        <!-- Buttons -->
        <a href="donhang.php" class="btn-primary-custom green">
            <i class="ti ti-list-check" aria-hidden="true"></i>
            Xem đơn hàng của tôi
        </a>
        <a href="trangchu.php" class="btn-ghost">
            <i class="ti ti-home" aria-hidden="true"></i>
            Về trang chủ
        </a>

        <div class="date-line">
            <i class="ti ti-calendar" style="vertical-align:-2px" aria-hidden="true"></i>
            Ngày đặt: <?php echo date("d/m/Y"); ?>
        </div>

    <?php else: ?>

        <!-- Error icon -->
        <div class="icon-circle error">
            <i class="ti ti-circle-x" aria-hidden="true"></i>
        </div>

        <div class="status-title">Đặt hàng thất bại</div>
        <p class="status-msg">
            Đã xảy ra lỗi trong quá trình xử lý đơn hàng của bạn.<br>
            Vui lòng thử lại hoặc liên hệ hỗ trợ.
        </p>

        <?php if (!empty($message)): ?>
        <div style="background:#FEF3F2; border:1px solid #FCA5A5; border-radius:8px;
                    padding:12px 16px; font-size:13px; color:#991B1B;
                    text-align:left; margin-bottom:28px; word-break:break-all;">
            <i class="ti ti-alert-circle" style="vertical-align:-2px" aria-hidden="true"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <a href="javascript:history.back()" class="btn-primary-custom">
            <i class="ti ti-arrow-left" aria-hidden="true"></i>
            Quay lại giỏ hàng
        </a>
        <a href="trangchu.php" class="btn-ghost">
            <i class="ti ti-home" aria-hidden="true"></i>
            Về trang chủ
        </a>

    <?php endif; ?>

</div>

</body>
</html>
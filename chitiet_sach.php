<?php
session_start();
include_once("connect.php");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
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
            --thumb-bg: #F0EDE8;
        }

        body {
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
        }

        /* ── Breadcrumb ── */
        .breadcrumb-bar {
            padding: 14px 0;
            border-bottom: 1px solid var(--border-line);
            font-size: 13px;
            color: var(--muted);
        }
        .breadcrumb-bar a {
            color: var(--muted);
            text-decoration: none;
        }
        .breadcrumb-bar a:hover { color: var(--ink); }
        .breadcrumb-bar .sep { margin: 0 6px; }

        /* ── Main card ── */
        .product-card {
            background: #fff;
            border: 1px solid var(--border-line);
            border-radius: 16px;
            overflow: hidden;
            margin: 32px 0;
        }

        /* ── Left: image panel ── */
        .image-panel {
            background: var(--thumb-bg);
            padding: 40px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .main-cover {
            width: 100%;
            max-width: 260px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.14);
        }

        .thumb-strip {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .thumb-strip img {
            width: 52px;
            height: 72px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color .15s;
        }
        .thumb-strip img:hover { border-color: var(--accent); }

        /* ── Right: info panel ── */
        .info-panel {
            padding: 40px 40px 40px 36px;
        }

        .category-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--accent);
            background: var(--accent-soft);
            border-radius: 4px;
            padding: 3px 10px;
            margin-bottom: 14px;
        }

        .book-title {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .book-author {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 28px;
        }
        .book-author strong { color: var(--ink); }

        /* ── Divider ── */
        .divider { border-top: 1px solid var(--border-line); margin: 24px 0; }

        /* ── Meta row ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 24px;
            margin-bottom: 24px;
        }
        .meta-item label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 3px;
        }
        .meta-item span {
            font-size: 15px;
            font-weight: 500;
            color: var(--ink);
        }

        /* ── Price ── */
        .price-block {
            background: var(--accent-soft);
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: baseline;
            gap: 8px;
        }
        .price-label { font-size: 13px; color: var(--muted); }
        .price-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent);
        }
        .price-currency { font-size: 15px; color: var(--muted); }

        /* ── Description ── */
        .desc-section h6 {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .desc-section p {
            font-size: 14px;
            line-height: 1.75;
            color: #3D3A34;
        }

        /* ── Purchase row ── */
        .purchase-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
        }

        .qty-wrapper {
            display: flex;
            align-items: center;
            border: 1.5px solid var(--border-line);
            border-radius: 8px;
            overflow: hidden;
            height: 44px;
        }
        .qty-btn {
            width: 38px;
            height: 44px;
            background: var(--thumb-bg);
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: var(--ink);
            line-height: 1;
            transition: background .1s;
        }
        .qty-btn:hover { background: var(--border-line); }
        .qty-input {
            width: 52px;
            height: 44px;
            border: none;
            border-left: 1.5px solid var(--border-line);
            border-right: 1.5px solid var(--border-line);
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            outline: none;
            color: var(--ink);
            background: #fff;
        }
        /* remove arrows from number input */
        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }
        .qty-input[type=number] { -moz-appearance: textfield; }

        .btn-buy {
            flex: 1;
            height: 44px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-buy:hover { background: #a93226; }
        .btn-buy:active { transform: scale(.98); }

        .btn-back {
            height: 44px;
            padding: 0 20px;
            background: transparent;
            color: var(--ink);
            border: 1.5px solid var(--border-line);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: border-color .15s, background .15s;
        }
        .btn-back:hover {
            border-color: var(--ink);
            background: var(--thumb-bg);
            color: var(--ink);
        }

        /* ── Stock badge ── */
        .stock-ok  { color: #1a7a4a; background: #e6f5ee; padding: 2px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; }
        .stock-low { color: #b45309; background: #fef3c7; padding: 2px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; }

        @media (max-width: 767px) {
            .info-panel { padding: 28px 20px; }
            .meta-grid  { grid-template-columns: 1fr; }
            .purchase-row { flex-direction: column; align-items: stretch; }
            .btn-buy, .btn-back { justify-content: center; }
        }
    </style>
</head>
<body>

<?php
    $isbn = "";
    if (isset($_GET["txtISBN"])) {
        $isbn = $_GET["txtISBN"];
    } elseif (isset($_GET["ma"])) {
        $isbn = $_GET["ma"];
    }

    $sql = "SELECT books.ISBN, books.Title, books.Author, books.Price, books.Description,
                   books.Picture, books.Soluong, categories.Name
            FROM books, categories
            WHERE books.ISBN = '$isbn' AND books.CategoryID = categories.CategoryID";
    $result = $conn->query($sql);

    $isbn_val = $cate_val = $title_val = $author_val = $price_val = $des_val = $picture_val = $soluong_val = "";
    $row = $result->fetch_assoc();
    if ($row) {
        $isbn_val    = $row["ISBN"];
        $cate_val    = $row["Name"];
        $title_val   = $row["Title"];
        $author_val  = $row["Author"];
        $price_val   = $row["Price"];
        $des_val     = $row["Description"];
        $picture_val = $row["Picture"];
        $soluong_val = $row["Soluong"];
    }

    $sql2   = "SELECT * FROM Photos WHERE ISBN = '$isbn'";
    $photos = $conn->query($sql2);
    $photo_rows = [];
    while ($p = $photos->fetch_assoc()) {
        $photo_rows[] = $p;
    }

    $stock_class = (intval($soluong_val) > 5) ? "stock-ok" : "stock-low";
    $stock_text  = (intval($soluong_val) > 5) ? "Còn hàng" : "Sắp hết";
    $price_fmt   = number_format(floatval($price_val), 0, ',', '.');
?>

<div class="container" style="max-width: 980px;">

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="trangchu.php"><i class="ti ti-home" style="vertical-align:-2px"></i> Trang chủ</a>
        <span class="sep">›</span>
        <a href="#"><?php echo htmlspecialchars($cate_val); ?></a>
        <span class="sep">›</span>
        <span><?php echo htmlspecialchars($title_val); ?></span>
    </div>

    <!-- Product card -->
    <div class="product-card">
        <div class="row g-0">

            <!-- Left: images -->
            <div class="col-md-4 image-panel">
                <img class="main-cover"
                     id="mainCover"
                     src="images/<?php echo htmlspecialchars($picture_val); ?>"
                     alt="Bìa sách <?php echo htmlspecialchars($title_val); ?>">

                <?php if (!empty($photo_rows)): ?>
                <div class="thumb-strip">
                    <img src="images/<?php echo htmlspecialchars($picture_val); ?>"
                         alt="Ảnh chính"
                         onclick="document.getElementById('mainCover').src=this.src">
                    <?php foreach ($photo_rows as $p): ?>
                    <img src="images/<?php echo htmlspecialchars($p['Names']); ?>"
                         alt="Ảnh phụ"
                         onclick="document.getElementById('mainCover').src=this.src">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right: info -->
            <div class="col-md-8 info-panel">
                <span class="category-badge"><?php echo htmlspecialchars($cate_val); ?></span>
                <h1 class="book-title"><?php echo htmlspecialchars($title_val); ?></h1>
                <p class="book-author">Tác giả: <strong><?php echo htmlspecialchars($author_val); ?></strong></p>

                <!-- Meta -->
                <div class="meta-grid">
                    <div class="meta-item">
                        <label>Mã ISBN</label>
                        <span><?php echo htmlspecialchars($isbn_val); ?></span>
                    </div>
                    <div class="meta-item">
                        <label>Tồn kho</label>
                        <span>
                            <?php echo htmlspecialchars($soluong_val); ?> cuốn
                            <span class="<?php echo $stock_class; ?> ms-2"><?php echo $stock_text; ?></span>
                        </span>
                    </div>
                </div>

                <!-- Price -->
                <div class="price-block">
                    <span class="price-label">Giá bán</span>
                    <span class="price-value"><?php echo $price_fmt; ?></span>
                    <span class="price-currency">VND</span>
                </div>

                <!-- Description -->
                <div class="desc-section">
                    <h6>Mô tả sản phẩm</h6>
                    <p><?php echo nl2br(htmlspecialchars($des_val)); ?></p>
                </div>

                <div class="divider"></div>

                <!-- Purchase form -->
                <form name="frmThemhang" action="giohang.php" method="post">
                    <input type="hidden" name="txtISBN"    value="<?php echo htmlspecialchars($isbn_val); ?>">
                    <input type="hidden" name="slDanhMuc"  value="<?php echo htmlspecialchars($cate_val); ?>">

                    <div class="purchase-row">
                        <!-- Qty stepper -->
                        <div class="qty-wrapper">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                            <input  type="number" class="qty-input" id="txtSoLuongMua"
                                    name="txtSoLuongMua" value="1" min="1"
                                    max="<?php echo intval($soluong_val); ?>" required>
                            <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>

                        <!-- Buy -->
                        <button type="submit" name="sbThemhang" class="btn-buy">
                            <i class="ti ti-shopping-cart" aria-hidden="true"></i>
                            Mua ngay
                        </button>

                        <!-- Back -->
                        <a href="trangchu.php" class="btn-back">
                            <i class="ti ti-arrow-left" aria-hidden="true"></i>
                            Quay lại
                        </a>
                    </div>
                </form>

            </div><!-- /info-panel -->
        </div><!-- /row -->
    </div><!-- /product-card -->

</div><!-- /container -->

<script>
function changeQty(delta) {
    const input = document.getElementById('txtSoLuongMua');
    const max   = parseInt(input.max) || 9999;
    let val     = parseInt(input.value) || 1;
    val = Math.min(max, Math.max(1, val + delta));
    input.value = val;
}
</script>

<?php include_once("phanchan.php"); ?>
</body>
</html>
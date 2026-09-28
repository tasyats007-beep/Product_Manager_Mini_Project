<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$q = trim($_GET["q"] ?? "");
$status = $_GET["status"] ?? "";

if ($q !== "") {
    $stmt = $pdo->prepare(
        "SELECT id, name, category, price, stock, created_at
         FROM products
WHERE name LIKE :name_q OR category LIKE :category_q
ORDER BY id DESC"
);
$stmt->execute([
    "name_q" => "%$q%",
    "category_q" => "%$q%"
]);
} else {
    $stmt = $pdo->query(
        "SELECT id, name, category, price, stock, created_at
         FROM products ORDER BY id DESC"
    );
}
$products = $stmt->fetchAll();

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div>
        <h1>Product Manager</h1>
        <p>Kelola data produk dengan mudah</p>
    </div>
    <a class="btn primary" href="create.php">+ Tambah Produk</a>
</header>

<main class="container">
    <?php if ($status === "created"): ?>
        <div class="alert success">Produk berhasil ditambahkan.</div>
    <?php elseif ($status === "updated"): ?>
        <div class="alert success">Produk berhasil diubah.</div>
    <?php elseif ($status === "deleted"): ?>
        <div class="alert success">Produk berhasil dihapus.</div>
    <?php endif; ?>

    <section class="toolbar">
        <form method="GET" class="search">
            <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari nama atau kategori...">
            <button class="btn" type="submit">Cari</button>
            <?php if ($q !== ""): ?>
                <a class="btn light" href="index.php">Reset</a>
            <?php endif; ?>
        </form>
    </section>

    <?php if (!$products): ?>
        <div class="empty">
            <h2>Belum ada produk</h2>
            <p>Tambahkan produk pertama untuk memulai.</p>
        </div>
    <?php else: ?>
        <section class="products">
            <?php foreach ($products as $p): ?>
                <article class="card">
                    <div class="card-head">
                        <span class="category"><?= e($p["category"]) ?></span>
                        <span class="stock <?= $p["stock"] == 0 ? "out" : "" ?>">
                            Stok: <?= e($p["stock"]) ?>
                        </span>
                    </div>

                    <h2><?= e($p["name"]) ?></h2>
                    <p class="price">Rp <?= number_format($p["price"], 0, ",", ".") ?></p>

                    <div class="actions">
                        <a class="btn edit" href="edit.php?id=<?= (int)$p["id"] ?>">Edit</a>

                        <form method="POST" action="delete.php"
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                            <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
                            <input type="hidden" name="csrf" value="<?= e($_SESSION["csrf"] ?? "") ?>">
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
</body>
</html>

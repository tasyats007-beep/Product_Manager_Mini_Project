<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(["id" => $id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit("Produk tidak ditemukan.");
}

$errors = [];
$name = $product["name"];
$category = $product["category"];
$price = $product["price"];
$stock = $product["stock"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "Umum");
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, "stock", FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) {
        $errors["name"] = "Nama minimal 3 karakter.";
    }
    if ($category === "") {
        $errors["category"] = "Kategori wajib diisi.";
    }
    if ($price === false || $price === null || $price <= 0) {
        $errors["price"] = "Harga harus lebih dari 0.";
    }
    if ($stock === false || $stock === null || $stock < 0) {
        $errors["stock"] = "Stok tidak boleh negatif.";
    }

    if (!$errors) {
        $check = $pdo->prepare(
            "SELECT id FROM products WHERE name = :name AND id != :id"
        );
        $check->execute(["name" => $name, "id" => $id]);

        if ($check->fetch()) {
            $errors["name"] = "Nama produk sudah digunakan.";
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            "UPDATE products
             SET name = :name, category = :category, price = :price, stock = :stock
             WHERE id = :id"
        );
        $stmt->execute([
            "name" => $name,
            "category" => $category,
            "price" => $price,
            "stock" => $stock,
            "id" => $id
        ]);

        header("Location: index.php?status=updated");
        exit;
    }
}

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="form-container">
    <a class="back" href="index.php">← Kembali</a>
    <div class="form-card">
        <h1>Edit Produk</h1>
        <p>Perbarui informasi produk.</p>

        <?php if ($errors): ?>
            <div class="alert error">
                <strong>Periksa kembali:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="name">Nama Produk</label>
            <input id="name" name="name" minlength="3" required value="<?= e($name) ?>">

            <label for="category">Kategori</label>
            <input id="category" name="category" required value="<?= e($category) ?>">

            <label for="price">Harga</label>
            <input id="price" name="price" type="number" min="1" step="0.01" required
                   value="<?= e($price) ?>">

            <label for="stock">Stok</label>
            <input id="stock" name="stock" type="number" min="0" required
                   value="<?= e($stock) ?>">

            <button class="btn primary full" type="submit">Simpan Perubahan</button>
        </form>
    </div>
</main>
</body>
</html>

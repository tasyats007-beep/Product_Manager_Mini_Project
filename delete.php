<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$csrf = $_POST["csrf"] ?? "";

if (!$id || !hash_equals($_SESSION["csrf"] ?? "", $csrf)) {
    http_response_code(403);
    exit("Request tidak valid.");
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
$stmt->execute(["id" => $id]);

header("Location: index.php?status=deleted");
exit;

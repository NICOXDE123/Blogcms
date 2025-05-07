<?php
require 'db.php';

$title = $_POST['title'];
$content = $_POST['content'];
$imag = $_POST['image']; // ← punto y coma

$stmt = $conn->prepare("INSERT INTO posts (title, content, imag) VALUES (?, ?, ?)"); // ← corregido
$stmt->bind_param("sss", $title, $content, $imag); // ← tres valores
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: posts.php");
exit();
?>
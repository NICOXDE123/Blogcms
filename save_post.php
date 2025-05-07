<?php
require 'db.php';

$title = $_POST['title'];
$content = $_POST['content'];

$imag = null;

// Verificar si se subió una imagen
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads';
    
    // Crear la carpeta si no existe
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $original_name = basename($_FILES['image']['name']);
    $unique_name = time() . '_' . $original_name;
    $target_path = $upload_dir . $unique_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $imag = $target_path;
    }
}

// Insertar en la base de datos
$stmt = $conn->prepare("INSERT INTO posts (title, content, imag) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $imag);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: posts.php");
exit();
?>
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar datos del formulario
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $imag = ''; // Nombre del archivo

    // Procesar imagen si fue subida
    if (isset($_FILES['imag']) && $_FILES['imag']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Crear carpeta si no existe
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                die("Error: No se pudo crear la carpeta uploads");
            }
        }

        // Validar tipo
        $fileType = $_FILES['imag']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Solo se permiten imágenes JPG, PNG o GIF.");
        }

        // Validar tamaño
        if ($_FILES['imag']['size'] > $maxSize) {
            die("Error: La imagen es demasiado grande. Máximo 5MB.");
        }

        // Guardar imagen
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaCompleta = $uploadDir . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $imagen = $nombreArchivo; // Solo el nombre, no toda la ruta
        } else {
            die("Error al mover la imagen subida.");
        }
    }

    // Insertar en base de datos
    $stmt = $conn->prepare("INSERT INTO posts (title, content, imag) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imagen);

    if ($stmt->execute()) {
        header("Location: posts.php");
        exit();
    } else {
        echo "Error al guardar el post: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

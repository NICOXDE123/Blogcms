<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar datos del formulario
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $imagen = '';

    // Procesar la imagen si fue subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Configuraciones
        $uploadDir = 'uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Verificar si la carpeta uploads existe, si no, crearla
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validar tipo de archivo
        $fileType = $_FILES['imagen']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Solo se permiten imágenes JPG, PNG o GIF.");
        }

        // Validar tamaño del archivo
        if ($_FILES['imagen']['size'] > $maxSize) {
            die("Error: La imagen es demasiado grande. Máximo 5MB.");
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaCompleta = $uploadDir . $nombreArchivo;

        // Mover el archivo subido
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $imagen = $nombreArchivo;
        } else {
            die("Error al subir la imagen.");
        }
    }

    // Insertar en la base de datos usando consultas preparadas (más seguro)
    $stmt = $conn->prepare("INSERT INTO posts (title, content, imag) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imagen);
    
    if ($stmt->execute()) {
        header("Location: posts.php"); // Redirigir al listado de posts
        exit();
    } else {
        echo "Error al guardar el post: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar datos del formulario
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $imagen = '';

    // Procesar la imagen si fue subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Configuraciones
        $uploadDir = 'uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Verificar y crear carpeta si no existe
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                die("Error: No se pudo crear la carpeta uploads");
            }
        }

        // Validar tipo de archivo
        $fileType = $_FILES['imagen']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Solo se permiten imágenes JPG, PNG o GIF.");
        }

        // Validar tamaño
        if ($_FILES['imagen']['size'] > $maxSize) {
            die("Error: La imagen es demasiado grande. Máximo 5MB.");
        }

        // Generar nombre único
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaCompleta = $uploadDir . $nombreArchivo;

        // Mover el archivo
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $imagen = $nombreArchivo;
            echo "<div class='alert alert-success'>Imagen subida correctamente: $nombreArchivo</div>";
        } else {
            die("Error al mover la imagen subida.");
        }
    }

    // Insertar en la base de datos
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
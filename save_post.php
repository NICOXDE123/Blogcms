<?php
require 'db.php';

// Verifica si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $imagen = '';

    // Procesar imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp = $_FILES['imagen']['tmp_name'];
        $nombreOriginal = basename($_FILES['imagen']['name']);
        $rutaDestino = 'uploads/' . $nombreOriginal;

        // Mover el archivo a la carpeta /uploads
        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $imagen = $nombreOriginal;  // Solo guardamos el nombre del archivo
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    // Insertar datos en la base
    $stmt = $conn->prepare("INSERT INTO posts (title, content, imag) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imagen);

    if ($stmt->execute()) {
        header("Location: post.php");
        exit;
    } else {
        echo "Error al guardar el post: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: posts.php");
    exit();
}

?>
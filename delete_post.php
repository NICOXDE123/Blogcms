<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
  $id = intval($_POST['id']);

  // Primero obtenemos el nombre de la imagen (si existe) para borrarla también
  $res = $conn->query("SELECT imag FROM posts WHERE id = $id");
  if ($res && $res->num_rows > 0) {
    $row = $resfetch_assoc();
    $imagen = $row['imag'];

    // Eliminamos imagen del servidor si existe
    if (!empty($imagen) && file_exists("uploads/$imagen")) {
      unlink("uploads/$imagen");
    }

    // Eliminamos el post de la base de datos
    $conn->query("DELETE FROM posts WHERE id = $id");
  }
}

$conn->close();
header("Location: post.php");
exit;
?>

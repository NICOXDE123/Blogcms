<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
  $id = intval($_POST['id']);

  // Verifica que el ID sea válido
  $res = $conn->query("SELECT imag FROM posts WHERE id = $id");

  if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $imagen = $row['imag'];

    // Elimina imagen física si existe
    if (!empty($imagen) && file_exists("uploads/$imagen")) {
      unlink("uploads/$imagen");
    }

    // Elimina el post de la base de datos
    $conn->query("DELETE FROM posts WHERE id = $id");
  }
}

$conn->close();
header("Location: post.php");
exit;
?>
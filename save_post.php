<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $imagen = '';

  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreTmp = $_FILES['imagen']['tmp_name'];
    $nombreArchivo = basename($_FILES['imagen']['name']);
    $ruta = 'uploads/' . $nombreArchivo;

    if (move_uploaded_file($nombreTmp, $ruta)) {
      $imagen = $nombreArchivo;
    }
  }

  $sql = "INSERT INTO posts (title, content, imag) VALUES ('$title', '$content', '$imagen')";

  if ($conn->query($sql) === TRUE) {
    echo "Post guardado correctamente.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>

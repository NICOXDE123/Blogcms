<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Publicaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.html">Inicio</a>
    <a class="btn btn-outline-light" href="create.php">Crear Post</a>
  </div>
</nav>

<div class="container">
  <h1 class="mb-4">Ver post</h1>

  <?php
  $result = $conn->query("SELECT * FROM posts ORDER BY id DESC");
  
  while ($row = $result->fetch_assoc()):
  ?>
    <div class="card mb-3">
    <div class="card mb-3">
  <?php if (!empty($row['imag'])): ?>
    <img src="uploads/<?= htmlspecialchars($row['imag']) ?>"
         class="card-img-top"
         style="height: 200px; object-fit: cover; width: 100%;"
         alt="Imagen del post">
  <?php endif; ?>
      <div class="card-body">
        <h3 class="card-title"><?= htmlspecialchars($row['title']) ?></h3>
        <p class="card-text"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
        <!-- 🔴 Botón para eliminar el post -->
    <form action="delete_post.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este post?');">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <button type="submit" class="btn btn-danger btn-sm mt-2">Eliminar</button>
    </form>
      </div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
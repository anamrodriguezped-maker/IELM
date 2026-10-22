<?php
$pageTitle = $pageTitle ?? 'Lechugas — frescura de origen';
$activePage = $activePage ?? '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f5f4e9"><title><?= e($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg site-nav sticky-top"><div class="container">
  <a class="navbar-brand" href="index.php"><span class="brand-mark">L</span><span>lechugas<span class="brand-dot">.</span><small>frescura de origen</small></span></a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Abrir navegación"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="mainNav"><ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
    <li class="nav-item"><a class="nav-link <?= $activePage==='inicio'?'active':'' ?>" href="index.php">Inicio</a></li>
    <li class="nav-item"><a class="nav-link <?= $activePage==='productos'?'active':'' ?>" href="productos.php">Productos</a></li>
    <li class="nav-item"><a class="nav-link <?= $activePage==='nosotros'?'active':'' ?>" href="acercadenosotros.php">Nosotros</a></li>
    <?php if (current_user()): ?>
      <?php if (current_user()['rol']==='admin'): ?><li class="nav-item"><a class="nav-link" href="admin_productos.php">Administrar</a></li><li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li><?php endif; ?>
      <li class="nav-item"><span class="nav-greeting">Hola, <?= e(current_user()['nombre']) ?></span></li>
      <li class="nav-item"><a class="btn btn-outline-dark btn-sm rounded-pill px-3" href="cerrar_sesion.php">Salir</a></li>
    <?php else: ?>
      <li class="nav-item"><a class="nav-link" href="iniciar_sesion.php">Iniciar sesión</a></li>
      <li class="nav-item"><a class="btn btn-leaf btn-sm rounded-pill px-3" href="registro.php">Crear cuenta</a></li>
    <?php endif; ?>
  </ul></div>
</div></nav>

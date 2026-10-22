<?php
require_once __DIR__ . '/config.php'; $pageTitle='Iniciar sesión — Lechugas'; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check(); $correo=trim($_POST['correo']??''); $password=$_POST['password']??'';
    try { $stmt=db()->prepare('SELECT id,nombre,correo,password,rol FROM usuarios WHERE correo=?'); $stmt->execute([$correo]); $user=$stmt->fetch(); }
    catch(PDOException $ex) { $user=false; $error='No se pudo conectar con la base de datos. Importa database.sql en phpMyAdmin.'; }
    if (!$error && $user && password_verify($password,$user['password'])) { session_regenerate_id(true); unset($user['password']); $_SESSION['user']=$user; header('Location: index.php'); exit; }
    elseif (!$error) $error='Correo o contraseña incorrectos.';
}
require __DIR__ . '/includes/header.php'; ?>
<main class="auth-wrap"><div class="auth-card"><span class="eyebrow">BIENVENIDO DE VUELTA</span><h1>Qué gusto<br><em>verte aquí.</em></h1><p>Entra para descubrir lo más fresco de la cosecha.</p><?php if($error): ?><div class="alert alert-warning"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label class="form-label" for="correo">Correo electrónico</label><input class="form-control" type="email" id="correo" name="correo" required value="<?= e($_POST['correo']??'') ?>"><label class="form-label mt-3" for="password">Contraseña</label><input class="form-control" type="password" id="password" name="password" required><button class="btn btn-leaf w-100 rounded-pill mt-4">Iniciar sesión ↗</button></form><div class="auth-switch">¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a></div></div></main><?php require __DIR__ . '/includes/footer.php'; ?>

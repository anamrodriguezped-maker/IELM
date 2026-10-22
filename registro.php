<?php
require_once __DIR__ . '/config.php';
$pageTitle='Crear cuenta — Lechugas'; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check(); $nombre=trim($_POST['nombre']??''); $correo=trim($_POST['correo']??''); $password=$_POST['password']??'';
    if ($nombre==='' || mb_strlen($nombre)>100 || !filter_var($correo,FILTER_VALIDATE_EMAIL) || strlen($password)<8) $error='Ingresa un nombre y correo válidos. La contraseña debe tener al menos 8 caracteres.';
    else try { $stmt=db()->prepare('INSERT INTO usuarios (nombre,correo,password) VALUES (?,?,?)'); $stmt->execute([$nombre,$correo,password_hash($password,PASSWORD_DEFAULT)]); $_SESSION['user']=['id'=>(int)db()->lastInsertId(),'nombre'=>$nombre,'correo'=>$correo,'rol'=>'cliente']; header('Location: productos.php'); exit; }
    catch (PDOException $ex) { $error=$ex->getCode()==='23000'?'Ese correo ya tiene una cuenta.':'No fue posible crear tu cuenta. Intenta de nuevo.'; }
}
require __DIR__ . '/includes/header.php'; ?>
<main class="auth-wrap"><div class="auth-card"><span class="eyebrow">QUÉ BUENO TENERTE</span><h1>Únete a la<br><em>mesa fresca.</em></h1><p>Crea tu cuenta y recibe la cosecha directo del campo.</p><?php if($error): ?><div class="alert alert-warning"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label class="form-label" for="nombre">Nombre completo</label><input class="form-control" id="nombre" name="nombre" maxlength="100" required value="<?= e($_POST['nombre']??'') ?>"><label class="form-label mt-3" for="correo">Correo electrónico</label><input class="form-control" type="email" id="correo" name="correo" required value="<?= e($_POST['correo']??'') ?>"><label class="form-label mt-3" for="password">Contraseña</label><input class="form-control" type="password" id="password" name="password" minlength="8" required><button class="btn btn-leaf w-100 rounded-pill mt-4">Crear mi cuenta ↗</button></form><div class="auth-switch">¿Ya tienes cuenta? <a href="iniciar_sesion.php">Inicia sesión</a></div></div></main><?php require __DIR__ . '/includes/footer.php'; ?>

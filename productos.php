<?php
require_once __DIR__ . '/config.php';
$pageTitle='Productos frescos — Lechugas'; $activePage='productos'; $error=''; $notice='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['comprar'])) {
    csrf_check(); require_login();
    $id=filter_input(INPUT_POST,'producto_id',FILTER_VALIDATE_INT); $cantidad=filter_input(INPUT_POST,'cantidad',FILTER_VALIDATE_INT);
    if (!$id || !$cantidad || $cantidad<1 || $cantidad>50) $error='Selecciona una cantidad válida.';
    else {
        try {
            $pdo=db(); $pdo->beginTransaction();
            $stmt=$pdo->prepare('SELECT * FROM productos WHERE id=? AND activo=1 FOR UPDATE'); $stmt->execute([$id]); $p=$stmt->fetch();
            if (!$p || $p['stock']<$cantidad) { $pdo->rollBack(); $error='No hay suficiente inventario para esa cantidad.'; }
            else { $pdo->prepare('INSERT INTO ventas (usuario_id,producto_id,cantidad,precio_unitario,total) VALUES (?,?,?,?,?)')->execute([current_user()['id'],$id,$cantidad,$p['precio'],$p['precio']*$cantidad]); $pdo->prepare('UPDATE productos SET stock=stock-? WHERE id=?')->execute([$cantidad,$id]); $pdo->commit(); $notice='¡Pedido registrado! Gracias por elegir productos frescos.'; }
        } catch (PDOException $ex) { if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack(); $error='No fue posible registrar tu compra. Intenta de nuevo.'; }
    }
}
try { $products=db()->query('SELECT * FROM productos WHERE activo=1 ORDER BY nombre')->fetchAll(); } catch (PDOException $ex) { $products=[]; $error='No se pudo conectar con la base de datos. Importa database.sql en phpMyAdmin.'; }
require __DIR__ . '/includes/header.php'; ?>
<main><section class="page-hero"><div class="container"><span class="eyebrow">FRESCO Y DE TEMPORADA</span><h1>La cosecha <em>de hoy.</em></h1><p>Hojas seleccionadas con cuidado, para que lo bueno empiece en tu cocina.</p></div></section>
<section class="section-pad"><div class="container"><div class="alert-stack"><?php if($error): ?><div class="alert alert-warning"><?= e($error) ?></div><?php endif; ?><?php if($notice): ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?></div>
<div class="row g-4"><?php foreach ($products as $i=>$product): ?><div class="col-sm-6 col-lg-4"><article class="product-card product-card-large"><div class="product-photo photo-<?= ($i%3)+1 ?>"><?php if($product['imagen']): ?><img src="<?= e($product['imagen']) ?>" alt="<?= e($product['nombre']) ?>"><?php else: ?><span class="leaf-illustration">🥬</span><?php endif; ?><span class="photo-tag"><?= (int)$product['stock']>0?'DISPONIBLE':'AGOTADO' ?></span></div><div class="product-info"><div><span class="product-kicker">RECIÉN COSECHADA</span><h3><?= e($product['nombre']) ?></h3><p><?= e($product['descripcion']) ?></p></div><strong><?= money($product['precio']) ?></strong></div><div class="purchase-row"><span class="stock-label"><?= (int)$product['stock'] ?> unidades</span><?php if((int)$product['stock']>0): ?><form method="post" class="purchase-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="producto_id" value="<?= (int)$product['id'] ?>"><input class="form-control form-control-sm" type="number" name="cantidad" value="1" min="1" max="50" aria-label="Cantidad"><button class="btn btn-leaf btn-sm rounded-pill" name="comprar" value="1">Comprar ↗</button></form><?php endif; ?></div></article></div><?php endforeach; ?></div><?php if(!$products): ?><div class="notice-card">No hay productos disponibles por el momento.</div><?php endif; ?></div></section></main>
<?php require __DIR__ . '/includes/footer.php'; ?>

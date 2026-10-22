# Lechugas

Tienda virtual PHP para XAMPP con Bootstrap 5, MySQL, registro e inicio de sesión, catálogo, registro de ventas y mantenimiento de productos.

## Instalación en XAMPP

1. Copia esta carpeta a `C:\xampp\htdocs\LECHUGAS` y en el panel de XAMPP inicia Apache y MySQL.
2. Abre `http://localhost/phpmyadmin`, selecciona **Importar** y carga `database.sql`. El esquema se llama `lechuga_bd` (MySQL no usa la extensión `.bd` para nombrar una base de datos).
3. Si tus credenciales de MySQL son distintas a las predeterminadas de XAMPP, edita `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` en `config.php`.
4. Abre `http://localhost/LECHUGAS/`.
5. Registra una cuenta desde **Crear cuenta**. Para habilitar el panel de productos y ventas, cambia esa cuenta a administrador en phpMyAdmin:

   ```sql
   USE lechuga_bd;
   UPDATE usuarios SET rol = 'admin' WHERE correo = 'tu-correo@ejemplo.com';
   ```

   Cierra sesión y vuelve a entrar para que se actualice el rol en la sesión.

La portada es `index.php`: PHP debe procesarla para mostrar datos reales del catálogo, por lo que no se usa `index.html`. Las compras descuentan existencias y guardan una fila por producto comprado en `ventas`. El enlace **Ventas** aparece para administradores.

Puedes colocar imágenes en `img/` y asignar su ruta relativa (por ejemplo `img/lechuga1.jpg`) al producto desde el panel. Si no hay imágenes locales, se muestra la ilustración o fotografía de respaldo.

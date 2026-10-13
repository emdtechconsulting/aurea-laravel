# Validación de esta entrega

- Entorno verificado: Linux, PHP 8.3.6, Laravel instalado mediante Composer y SQLite.
- Instalación de dependencias y archivo composer.lock generado.
- Migraciones y carga inicial ejecutadas correctamente.
- Composer validate --strict: correcto.
- Pruebas automatizadas: 6 aprobadas, 24 comprobaciones.
- JavaScript del panel renderizado y adaptador: sintaxis válida.
- Prueba HTTP real con sesión y protección CSRF: pedido de dos petipanes + una chicha = S/8.50.
- Cierre de sesión: API privada devuelve 401.
- Flujo de sesión con origen separado localhost:5173: login, cookies, CORS y consulta de productos correctos.

No se ejecutó la instalación en Windows ni el contenedor Docker en este entorno. No se completó una comprobación visual automatizada en navegador. La guía incluye la prueba manual para tu PC y celular. Se excluyen del ZIP las credenciales y datos temporales empleados para comprobar el proyecto.

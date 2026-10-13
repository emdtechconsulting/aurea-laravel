# AUREA · Minilaboratorio Laravel
Proyecto inicial de EMD Tech Consulting para AUREA, Sanguchitos y más.

## 1. Qué vas a construir
Una aplicación web adaptable a PC y celular con acceso privado, catálogo editable, registro de pedidos con varios productos, recojo/delivery, estados y resumen diario. Incluye una API versionada para conectar posteriormente React o un cliente móvil.

Es un proyecto nuevo y separado. No modifica ni importa tu aplicación React/Firebase. La integración real requiere revisar esa aplicación, decidir qué datos pasarán a Laravel y adaptar su inicio de sesión. No incluye cobros Yape/Plin, facturación electrónica, inventario, comprobación de pagos ni funcionamiento sin internet. Los valores del resumen son pedidos registrados/entregados, no pagos verificados.

## 2. Conceptos, sin asumir conocimientos previos
| Término | Significado / para qué sirve | Ejemplo AUREA |
|---|---|---|
| Laravel | Conjunto de herramientas en PHP para construir aplicaciones | Valida y guarda un pedido |
| PHP | Lenguaje que ejecuta el servidor | Calcula el total real |
| Composer | Administrador que descarga dependencias PHP | Instala Laravel con composer install |
| Backend | Parte del sistema que procesa y guarda datos | Laravel y su base de datos |
| Frontend | Pantallas que usa la persona | Panel incluido; luego tu interfaz React |
| API (Application Programming Interface) | Interfaz de programación de aplicaciones: permite comunicar programas | React envía un pedido a /api/v1/orders |
| JSON (JavaScript Object Notation) | Formato de intercambio de datos | {"quantity":2} |
| Localhost | Dirección que apunta al mismo equipo | localhost en tu PC apunta a tu PC; en el celular apunta al celular |
| SQLite | Base de datos almacenada en un archivo | database/database.sqlite |
| Migración | Código que crea o cambia tablas de forma controlada | php artisan migrate |
| Seeder | Carga inicial de datos de ejemplo | Tres productos de prueba |
| Sanctum | Herramienta de Laravel que controla el acceso desde web/móvil | Sesión web o credencial temporal móvil |
| Token | Credencial secreta para autorizar solicitudes | La app móvil lo envía en Authorization |
| CSRF (Cross-Site Request Forgery) | Falsificación de solicitudes entre sitios; su protección comprueba solicitudes de sesión | La web envía X-CSRF-TOKEN |
| CORS (Cross-Origin Resource Sharing) | Intercambio de recursos entre orígenes; define qué web puede llamar al servidor | Permite localhost:5173 |
| UUID (Universally Unique Identifier) | Identificador universalmente único | Identifica un intento de pedido para no duplicarlo al reintentar |
| HTTPS (Hypertext Transfer Protocol Secure) | Protocolo web con cifrado | Necesario para un servidor publicado |
| Pruebas automatizadas | Verificaciones ejecutadas por el programa | Comprueban totales y acceso privado |

## 3. Primera instalación en Windows (ruta recomendada)
1. Instala Laravel Herd para Windows desde https://herd.laravel.com/windows . Usa PHP 8.3 o superior, extensiones SQLite/PDO SQLite, mbstring, DOM/XML, curl y zip. Herd incluye PHP y Composer; confirma los comandos después de abrir una terminal nueva.
2. Descomprime este proyecto, por ejemplo en C:\EMD_Labs\AUREA-Laravel-Lab. Evita trabajar en OneDrive mientras corre SQLite.
3. Abre PowerShell en esa carpeta y verifica:

```powershell
php -v
composer --version
php -m
```

4. Ejecuta el instalador. Descarga las dependencias por internet; no están incluidas en el ZIP.

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\install.ps1
```

El Bypass solo aplica a ese proceso. Si tu equipo impide scripts, usa la ruta manual siguiente.
5. Crea tu cuenta. El correo y la contraseña los eliges tú; no hay una contraseña pública de fábrica.

```powershell
php artisan aurea:admin
```

6. Inicia el servidor y mantén abierta esa terminal:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

7. Abre http://localhost:8000 e ingresa. Detén el servidor con Ctrl+C.
8. Para las próximas sesiones solo ejecuta el paso 6.

### Instalación manual, sin script
Ejecuta una línea cada vez, en la carpeta del proyecto:
```powershell
composer install
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan aurea:admin
php artisan serve --host=127.0.0.1 --port=8000
```
No vuelvas a copiar `.env` ni regenerar la clave en una instalación ya configurada. El instalador automático conserva ambas. `db:seed` no sobrescribe precios existentes.

No necesitas Node ni compilar React para ejecutar este panel: usa Blade, el sistema de plantillas de Laravel. React entra en una etapa posterior.

## 4. Primera prueba guiada (20–30 minutos)
1. Ingresa. Verás petipán S/2.50, mini triple S/3.00 y chicha S/3.50, todos de ejemplo.
2. Crea o edita un producto en el panel.
3. Registra dos petipanes y una chicha: total esperado S/8.50 si conservas los precios iniciales.
4. Cambia el pedido a preparando y luego entregado. El tablero se actualizará.
5. Cambia el precio del petipán: el pedido anterior debe conservar el precio original.
6. Inactiva un producto: no aparecerá para nuevos pedidos tras recargar.
7. Prueba delivery sin dirección: debe rechazarse.
8. Sal de la sesión: no debe permitirse acceder al panel ni a la API privada.

Los estados entregado/cancelado son finales. La lista del panel muestra los 30 pedidos más recientes; la API tiene paginación para consultar más. No hay eliminación física de productos para conservar la historia. Todos los usuarios creados son operadores con acceso completo: no crear cuentas de clientes en esta versión.

## 5. Acceso desde tu celular en la misma red
1. PC y celular conectados al mismo Wi-Fi privado.
2. En otra terminal ejecuta `ipconfig` y ubica la dirección IPv4 de la tarjeta Wi-Fi, por ejemplo 192.168.1.25.
3. Detén el servidor anterior y ejecuta:
```powershell
php artisan serve --host=0.0.0.0 --port=8000
```
4. Permite PHP en el firewall de Windows únicamente para la red privada si lo solicita.
5. En el navegador del celular abre http://192.168.1.25:8000 (sustituye por la IP real).
6. Agrega esa IP con puerto a `SANCTUM_STATEFUL_DOMAINS` en `.env`, por ejemplo `192.168.1.25:8000`, manteniendo los valores anteriores. Ejecuta `php artisan optimize:clear` y reinicia el servidor. Esto permite que las llamadas del panel móvil reconozcan la sesión.

La IP puede cambiar. No uses localhost en el celular para buscar la PC. Si falla, revisa la red, firewall y aislamiento de clientes del router. Este acceso HTTP es para datos ficticios en un laboratorio privado. No configures redirección de puertos en el router. Una web pública HTTPS no puede llamar de forma general al localhost privado de tu PC: para acceso remoto necesitas un backend alojado con HTTPS.

## 6. Separar desarrollo y pruebas
Una cosa es probar datos en localhost, otra ejecutar las pruebas automáticas. Para pruebas manuales separadas:
- Conserva este proyecto como desarrollo y haz una copia `AUREA-Laravel-Pruebas`.
- Cada carpeta debe tener su propia `.env`, `APP_KEY` y `database/database.sqlite`. No compartas archivos de base de datos.
- En la copia, pon APP_ENV=staging, APP_DEBUG=false, APP_URL=http://localhost:8001 y SESSION_COOKIE=aurea_pruebas_session. Agrega localhost:8001 a SANCTUM_STATEFUL_DOMAINS.
- Borra el archivo SQLite de la copia antes de iniciar SOLO si aún no contiene datos que necesites; créalo vacío, ejecuta migrate, db:seed y aurea:admin. No copies datos reales a pruebas.
- Genera una clave nueva en esa copia con `php artisan key:generate`, limpia configuración con `php artisan optimize:clear` y sirve con puerto 8001.
- Abre http://localhost:8001. Desarrollo corre en 8000.

Pruebas automáticas:
```powershell
php artisan test
```
Usan SQLite en memoria y no tu base del laboratorio. Cubren acceso privado, totales calculados en servidor, repetición de pedido, rechazo de productos inactivos/delivery incompleto, precio histórico, estados finales, acceso móvil y renderizado del panel.

## 7. Alternativa Docker
Docker ejecuta el entorno en un contenedor, un entorno aislado con PHP. Instala Docker Desktop y activa su motor. En la carpeta:
```powershell
docker compose build
docker compose run --rm app sh scripts/install.sh
docker compose run --rm app php artisan aurea:admin
docker compose up
```
Abre http://localhost:8000. Las dependencias y SQLite quedan en la carpeta montada. Ejecuta pruebas con `docker compose run --rm app php artisan test`. Detén con Ctrl+C o `docker compose down`. Esta configuración expone solo la PC (127.0.0.1). Para acceso por Wi-Fi usa la instalación nativa de la sección 5; no cambies puertos sin revisar exposición.

## 8. Dónde modificar
| Carpeta / archivo | Responsabilidad |
|---|---|
| routes/web.php | Acceso y panel |
| routes/api.php | Direcciones que usa otra app |
| app/Models | Representación de las tablas |
| app/Http/Controllers | Recibir solicitudes y devolver resultados |
| app/Http/Requests/StoreOrderRequest.php | Reglas del pedido |
| app/Services/CreateOrder.php | Cálculo y guardado del pedido |
| database/migrations | Estructura de las tablas |
| database/seeders | Productos de ejemplo |
| resources/views | Diseño de pantallas |
| examples/aurea-client.js | Adaptador de ejemplo para React web y móvil |
| tests/Feature/AureaTest.php | Casos de prueba |

Un pedido se guarda en una transacción: si falla un detalle, no queda un pedido incompleto. Importes en céntimos enteros: 250 significa S/2.50. En una sola cuenta se reusa el mismo client_request_id al reintentar un pedido; si los datos cambian se necesita uno nuevo. La restricción única evita duplicados concurrentes. Si dos solicitudes chocan, el servicio recupera el pedido existente; un bloqueo temporal de SQLite aún puede requerir reintentar con el mismo identificador. Este laboratorio no pretende resolver sincronización sin conexión ni conflictos entre servidores.

## 9. Integración con React/Firebase: ruta concreta
Lee `docs/API.md` y el adaptador `examples/aurea-client.js`.

1. Mantén tu aplicación actual funcionando. Usa una rama/copia para el experimento.
2. Primero consulta productos de Laravel con el adaptador. Cambia únicamente la fuente de datos del catálogo.
3. Luego envía pedidos a Laravel y consulta estados. Define Laravel como fuente única para esos módulos; evita guardar el mismo pedido por separado en Firebase y Laravel.
4. Decide si conservarás Firebase para otros módulos. El inicio de sesión Firebase NO es reconocido automáticamente por Sanctum. Este ejemplo usa cuentas Laravel. Si decides mantener Firebase Authentication necesitarás validar sus credenciales en Laravel y agregar autorización; todavía no está implementado.
5. Antes de migrar datos reales, crea un mapa entre identificadores Firebase y Laravel, conserva respaldos y comprueba importes y cantidad de registros.
6. Para React web propio usa sesión/cookies con Sanctum; para una app móvil nativa, tokens temporales guardados en el almacén seguro del sistema. La web adaptable del panel ya funciona en el navegador móvil sin crear una app nativa.

Para una futura aplicación web progresiva (PWA, Progressive Web App), el guardado sin internet necesita una cola local, identificadores estables y resolución de conflictos. No queda resuelto solo por tener una API.

## 10. Ruta de trabajo de cinco sesiones
| Sesión | Trabajo | Evidencia para tu portafolio |
|---|---|---|
| 1 | Instalar, ingresar y registrar pedido | Captura con datos ficticios + README |
| 2 | Seguir el recorrido pantalla → ruta → controlador → servicio → tabla | Explicación de un pedido de S/8.50 |
| 3 | Cambiar estilos/nombres y ejecutar pruebas | Comparación y resultado de pruebas |
| 4 | Conectar catálogo React con API | Demostración de catálogo único |
| 5 | Preparar entorno separado de pruebas | Lista de cambios, pruebas y criterios de aceptación |

## 11. Respaldo, errores y próxima publicación
Detén el servidor antes de copiar `database/database.sqlite`. Respalda `.env` de manera privada; conserva APP_KEY. No subas `.env`, vendor, tokens ni SQLite a GitHub. `.gitignore` ya excluye esos archivos. Sí conserva `composer.lock` para reproducir dependencias. SQLite está pensado para este laboratorio; una base MySQL/PostgreSQL para mayor concurrencia será una etapa que debe probarse, no un cambio de texto sin validación.

- `php no se reconoce`: abre una terminal nueva y revisa instalación/PATH de Herd.
- `could not find driver`: habilita pdo_sqlite y sqlite3 en el PHP que muestra `php --ini`.
- Puerto ocupado: usa otro puerto y actualiza APP_URL y SANCTUM_STATEFUL_DOMAINS.
- Error 419: sesión/CSRF; evita mezclar localhost y 127.0.0.1 en el navegador, revisa orígenes y reinicia tras optimize:clear.
- Error 401: no hay sesión/token válido; vuelve a ingresar.
- Error 422: datos inválidos; lee el mensaje.
- Error 409: se reusó un identificador con otro pedido o se intentó reabrir un pedido final.
- Error 500: revisa storage/logs/laravel.log sin publicarlo; puede contener información privada.

Antes de publicar: servidor real con HTTPS y raíz web `public/`, APP_DEBUG=false, credenciales nuevas, roles/autorización por usuario, respaldo probado, registro de cambios, pruebas de concurrencia/idempotencia, protección de datos y pruebas en el ambiente separado. El servidor `artisan serve` es para desarrollo, no hosting de producción.

## Fuentes oficiales
- https://laravel.com/docs/13.x/installation
- https://laravel.com/docs/13.x/sanctum
- https://laravel.com/docs/13.x/database
- https://laravel.com/docs/13.x/deployment
- https://herd.laravel.com/windows

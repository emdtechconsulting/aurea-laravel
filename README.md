# AUREA · Laboratorio Laravel

Sistema de pedidos de AUREA, Sanguchitos y más.
Proyecto de aprendizaje y portafolio de EMD Tech Consulting.

## Versión actual: 1.1.1

- Diseño adaptable con identidad visual de AUREA.
- Inicio de sesión privado.
- Creación, edición y activación de productos.
- Registro de pedidos para recojo o delivery.
- Precios calculados por el servidor y conservados en cada pedido.
- Estados de pedidos y resumen diario.
- API para futuras integraciones web y móviles.

API significa interfaz de programación de aplicaciones:
permite que otros programas se comuniquen con Laravel.

## Entornos comprobados

| Ambiente | Carpeta | Dirección |
|---|---|---|
| Principal con Nginx | /var/www/aurea | http://localhost:8080 |
| Validación independiente | ~/proyectos/aurea-validacion | http://localhost:8001 |

Tecnologías: Ubuntu 24.04 en WSL2, PHP 8.3, Laravel 13,
Composer, SQLite, Nginx y PHP-FPM.

WSL2 permite ejecutar Linux dentro de Windows.
PHP-FPM procesa el código PHP que solicita Nginx.
Composer instala las dependencias del proyecto.

## Evidencia de funcionamiento

- 7 pruebas automatizadas aprobadas y 41 verificaciones.
- Respaldo y recuperación de SQLite comprobados.
- Instalación independiente desde GitHub completada.
- Administrador, inicio de sesión y primer pedido comprobados.

Consulta docs/VALIDACION.md para conocer el alcance de la validación.

## Alcance

Todos los usuarios actuales son operadores con acceso completo.
Los importes del resumen representan pedidos, no pagos verificados.

Quedan pendientes pedidos programados, calendario, categorías,
precios por paquetes, costo de delivery y roles diferenciados.

La integración con React/Firebase o una aplicación móvil nativa
todavía no está implementada.

El código está publicado en GitHub; la aplicación se ejecuta localmente.

## Instalación desde GitHub en Ubuntu

Requisitos: Git, Composer, PHP 8.3 y extensiones curl, DOM/XML,
mbstring, fileinfo, PDO, pdo_sqlite, sqlite3 y zip.

Ejecuta en una carpeta nueva, separada de cualquier instalación existente:

```bash
mkdir -p ~/proyectos
cd ~/proyectos
git clone https://github.com/emdtechconsulting/aurea-laravel.git aurea-validacion
cd aurea-validacion
composer install
cp .env.example .env
touch database/database.sqlite
nano .env
```

En .env configura estos valores. Agrega SESSION_COOKIE si no existe:

```dotenv
APP_URL=http://localhost:8001
SESSION_COOKIE=aurea_validacion_session
SANCTUM_STATEFUL_DOMAINS=localhost:8001,127.0.0.1:8001,localhost:5173
```

Guarda .env y continúa:

```bash
php artisan key:generate
php artisan config:clear
php artisan migrate
php artisan db:seed
php artisan aurea:admin
php artisan test
php artisan serve --host=127.0.0.1 --port=8001
```

Elige un correo y una contraseña de al menos 12 caracteres.
No hay credenciales predeterminadas.

Abre http://localhost:8001. Mantén la terminal abierta mientras usas
el servidor; detenlo con Ctrl+C.

Esta instalación tiene su propia base de datos y comienza sin pedidos.
No necesita Node para ejecutar las pantallas Blade incluidas.

artisan serve es para desarrollo. El ambiente principal utiliza
Nginx y PHP-FPM en el puerto 8080.

Nunca publiques .env, contraseñas, tokens ni bases de datos con clientes.
Conserva composer.lock para reproducir las dependencias.

## Documentación adicional

- [Validación del laboratorio](docs/VALIDACION.md)
- [API e integración](docs/API.md)
- [Plan multiplataforma](docs/PLAN-MULTIPLATAFORMA.md)

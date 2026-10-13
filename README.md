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

# Validación de AUREA 1.1.1

## Ambiente principal
- Ubuntu 24.04 LTS en WSL2.
- Nginx y PHP-FPM, acceso local en el puerto 8080.
- Actualización de diseño aplicada y pedidos anteriores conservados.
- 7 pruebas aprobadas y 41 verificaciones.
- Respaldo SQLite íntegro y recuperación comprobada en una base independiente.

## Instalación independiente desde GitHub
- Repositorio clonado en /home/emdlab/proyectos/aurea-validacion.
- Dependencias instaladas mediante composer install.
- Configuración, clave y base SQLite propias.
- Administrador creado correctamente.
- 7 pruebas aprobadas y 41 verificaciones.
- Inicio de sesión y primer pedido comprobados en el puerto 8001.
- La instalación comenzó sin los pedidos del ambiente principal.

## Pendientes
- Actualizar el README con las instrucciones actuales de Ubuntu.
- Ejecutar automáticamente las pruebas mediante GitHub Actions.
- Preparar una demostración con datos ficticios para el portafolio.
- Configurar y validar un alojamiento público si se publica la aplicación.

Esta validación corresponde al laboratorio local.

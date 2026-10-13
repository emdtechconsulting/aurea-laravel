# AUREA: laboratorio con varias tecnologías

Objetivo: implementar el mismo negocio en varias tecnologías para aprender, comparar y crear evidencias para el portafolio EMD. Esta entrega ejecutable corresponde a Laravel; las demás son etapas propuestas.

## Requisitos comunes
Productos con precio en céntimos y disponibilidad; pedidos con cliente, cantidades y entrega; estados; resumen diario. Usar datos ficticios y los mismos casos de prueba: dos petipanes a S/2.50 y una chicha a S/3.50 suman S/8.50.

## Orden de construcción
1. Laravel con el panel incluido y SQLite: instalar, probar y entender el recorrido de un pedido.
2. Laravel conectado a una copia de React: consultar catálogo y registrar pedidos mediante la API.
3. WordPress: sitio comercial AUREA. Para pedidos centralizados crear un plugin con conexión servidor a servidor a Laravel y permisos limitados; no insertar credenciales de administrador en páginas públicas. Para comparar un sistema independiente de comercio, evaluar WooCommerce con su propia base de pruebas, sin sincronización automática asumida.
4. Joomla: sitio comercial y extensión de pedidos; la conexión a Laravel requiere desarrollo, no sucede por instalar Joomla.
5. Flutter + Dart: una aplicación multiplataforma. Dart es el lenguaje usado para programar Flutter, no otra aplicación obligatoria.
6. Kotlin: cliente Android nativo sobre la misma API.
7. .NET MAUI con C#: alternativa actual a Xamarin, cuyo soporte terminó el 1 de mayo de 2024. MAUI significa Multi-platform App UI, interfaz de aplicación multiplataforma.
8. Otros laboratorios opcionales: aplicación web progresiva React, Angular, Drupal o backend .NET. Agregar uno solo cuando la etapa anterior cumpla sus pruebas.

## Dos formas de trabajar
Para aprender: cada implementación independiente con datos ficticios y su propia base, para comparar tiempo, complejidad y funcionalidades.
Para operar AUREA: Laravel puede ser el centro de productos/pedidos; web y móvil consultan ese centro. Los gestores de contenido mantienen aparte sus páginas y publicaciones. Evitar dos fuentes maestras de pedidos.

## Antes de conectar aplicaciones públicas
La API actual es de operador privado. Crear catálogo público filtrado, permisos por usuario, identidad de clientes y reglas de acceso por pedido antes de usarla para clientes finales. No distribuir tokens de operador en WordPress público o aplicaciones móviles de clientes. Revisar autenticación Firebase si se conserva; no se reconoce automáticamente en Laravel.

## Criterio de avance de cada etapa
Ingresar, consultar productos, crear pedido S/8.50, cambiar estado, verificar resumen, rechazar datos inválidos y comprobar permisos. Documentar capturas ficticias, tiempo invertido, decisiones y resultados. Las etapas no tienen duración garantizada: estimar después de instalar y medir la primera implementación.

Fuentes oficiales: https://docs.flutter.dev/learn ; https://dart.dev/overview ; https://developer.android.com/kotlin ; https://developer.wordpress.org/rest-api/ ; https://www.joomla.org/ ; https://dotnet.microsoft.com/en-us/platform/support/policy/xamarin

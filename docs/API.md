# API AUREA v1
Base local: http://localhost:8000/api/v1 . Todas las operaciones comerciales requieren autenticación. Es una API de operador, no de clientes finales.

## Contrato
| Método | Ruta | Entrada / resultado |
|---|---|---|
| POST | /mobile/token | email, password, device_name → token de 24 horas |
| DELETE | /mobile/token | Revoca token actual; sin cuerpo de respuesta |
| GET | /products?page=1 | Página de productos, incluidos inactivos (filtrar active para venta) |
| POST | /products | name, price_cents (entero 1–1000000), active (booleano) |
| PUT | /products/{id} | Mismos campos; actualiza producto |
| GET | /orders?page=1 | Página de pedidos con detalles |
| POST | /orders | Pedido del ejemplo siguiente |
| PATCH | /orders/{id}/status | status: pendiente, preparando, entregado o cancelado |
| GET | /summary | Fecha de Lima, número de pedidos, total registrado sin cancelados y entregados |

Ejemplo de pedido, reusar UUID solo para reintentos con el mismo contenido:
```json
{
 "client_request_id":"32721d6c-22d5-457b-905e-c39b445476a1",
 "customer_name":"Cliente de prueba",
 "phone":"999000111",
 "delivery_type":"delivery",
 "address":"Dirección ficticia 123",
 "items":[{"product_id":1,"quantity":2},{"product_id":3,"quantity":1}]
}
```
No envíes precios ni total: Laravel los calcula. Máximo 50 productos distintos por pedido, cantidad 1–100 por producto. Dirección obligatoria para delivery. Idempotencia compara la entrada validada exacta; conserva el mismo cuerpo en cada reintento. El primer y los sucesivos envíos exitosos devuelven 201 con el mismo pedido. Un bloqueo temporal de SQLite puede requerir reintento. Cada detalle incluye nombre y precio histórico en céntimos.

## React web propio: sesiones
Para desarrollo usa http://localhost:5173 en React y http://localhost:8000 en Laravel. Mantén el mismo nombre de host. En `.env` de Laravel:
```
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8000
```
Tras cambios ejecuta `php artisan optimize:clear`. CORS habilita solo los orígenes configurados; CORS no sustituye autenticación.
1. GET /sanctum/csrf-cookie con credentials:include.
2. POST /login con email/password, cookie y cabecera X-XSRF-TOKEN.
3. Solicitudes privadas /api/v1/... con credentials:include y, para escritura, X-XSRF-TOKEN actualizado.
4. POST /logout para salir de la sesión.
El adaptador incluido resuelve estos pasos. Usa import en tu proyecto React y elige `createWebClient`. No almacenes token de administrador en código React ni en localStorage.

## Cliente móvil nativo: tokens
POST /api/v1/mobile/token con correo, contraseña y device_name. En las siguientes solicitudes envía `Authorization: Bearer TOKEN`. Revoca al salir con DELETE /api/v1/mobile/token. Caduca en 24 horas; renovar requiere ingresar de nuevo. El ejemplo no almacena la credencial; implementa almacén seguro Android/iOS en el proyecto móvil. Para navegador móvil usa sesión web, no esta ruta. Nunca incrustes la contraseña de operador en un cliente público.

## Comprobación rápida en PowerShell (token móvil de prueba)
```powershell
$base = 'http://localhost:8000'
$credential = Get-Credential
$body = @{email=$credential.UserName;password=$credential.GetNetworkCredential().Password;device_name='PruebaPC'} | ConvertTo-Json
$auth = Invoke-RestMethod -Method Post -Uri "$base/api/v1/mobile/token" -ContentType 'application/json' -Body $body
$headers = @{Authorization="Bearer $($auth.token)";Accept='application/json'}
Invoke-RestMethod -Uri "$base/api/v1/products" -Headers $headers
Invoke-RestMethod -Method Delete -Uri "$base/api/v1/mobile/token" -Headers $headers
Remove-Variable auth,body,credential,headers
```
Esto solo es una herramienta de prueba privada. La interfaz React propia debe usar sesiones. HTTPS es necesario fuera de un laboratorio privado.

## Respuestas
401 acceso no autorizado; 419 sesión/CSRF inválido; 422 validación; 409 conflicto; 429 límite de solicitudes; 500 error de servidor. La API devuelve JSON. Las listas paginadas entregan `data`, `current_page`, `last_page`, `next_page_url` y otros campos del paginador estándar. Sigue `next_page_url` o incrementa page hasta last_page. No confundas el contenido de una página con todo el catálogo.

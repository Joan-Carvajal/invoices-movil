# Plan · invoices

Plan de implementación a partir de `docs/spec.md`. Cada tarea termina con `composer qa` en verde.

- [x] Modelos `Client` e `Invoice` con sus migraciones, factories y relaciones con `User`.
- [x] Login con sesión: `LoginController`, vista de login y límite de intentos.
- [x] Clientes: listado, alta con `StoreClientRequest` y edición.
- [x] Facturas: listado, detalle, alta y edición con form requests, borrado, e `InvoicePolicy`.
- [x] Adjunto de la factura en el disco `local` y descarga con `InvoiceAttachmentController`.
- [x] Búsqueda de facturas por número, sin distinguir mayúsculas.
- [x] Seeder con 2 usuarios de prueba, sus clientes y sus facturas.
- [x] Tests de Pest de clientes y facturas.
- [x] Configuración del servicio de PDF en `config/services.php` y `.env.example`.
- [ ] PDF de la factura: instalar `laravel/invoice-pdf` con `composer require laravel/invoice-pdf` y generar el PDF con la clave de `services.pdf`.
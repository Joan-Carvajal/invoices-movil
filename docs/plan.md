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
- [x] Correcciones de seguridad: búsqueda sin SQL crudo, notas escapadas y actualización de clientes con datos validados.
- [x] Interfaz adaptada a móvil, con modo oscuro, mensajes en español y filtros por estado.
- [x] App móvil con NativePHP: primer arranque, cámara y galería, compartir adjuntos y puente nativo bloqueado en la web. Guía en `docs/mobile.md`.
- [ ] Probar la app en un Android y un iPhone reales.
- [ ] PDF de la factura: llamar al servicio de PDF con el cliente HTTP de Laravel (`Http`), con la URL y la clave de `services.pdf`. Sin paquetes nuevos. En móvil, a través de un backend propio.
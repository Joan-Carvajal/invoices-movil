# Especificación · invoices

Aplicación para gestionar las facturas de los clientes de cada usuario, con un adjunto por factura.

## Usuarios

- Acceso con email y contraseña, con sesión. Sin registro: los usuarios se crean con el seeder.
- Cada usuario solo ve y gestiona sus clientes y las facturas de sus clientes.

## Clientes

- Datos: nombre, email y NIF. El nombre es obligatorio.
- Listado con el número de facturas de cada cliente, alta y edición.

## Facturas

- Cada factura pertenece a un cliente del usuario.
- Datos: número (único), importe, estado (borrador, enviada o pagada), fecha de emisión y notas.
- Las notas conservan los saltos de línea al mostrarse.
- Un adjunto opcional por factura (PDF, JPG o PNG, hasta 5 MB), guardado en el disco privado y descargable desde el detalle.
- Listado paginado con búsqueda por número, sin distinguir mayúsculas.
- Detalle, alta, edición y borrado. Al borrar una factura se borra su adjunto.

## Calidad

- Tests de Pest para cada funcionalidad.
- `composer qa` en verde: Pint, Larastan y Pest.

## Más adelante

- Generar el PDF de cada factura con un servicio externo de PDF. La clave del servicio ya la tenemos.
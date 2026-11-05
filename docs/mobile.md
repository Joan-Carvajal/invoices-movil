# App móvil · invoices

La app móvil es esta misma aplicación Laravel dentro de [NativePHP for Mobile](https://nativephp.com/docs/mobile/4). PHP y Laravel se ejecutan en el propio dispositivo y las vistas Blade se muestran en una WebView.

## Cómo funciona en el dispositivo

- **Sin conexión y sin servidor.** Cada dispositivo tiene su propia base de datos SQLite. No se sincroniza con el MySQL de la versión web y se pierde al desinstalar la app.
- **Primer arranque.** No hay seeder: la pantalla `/setup` crea la cuenta local. Esa ruta solo existe en el dispositivo y mientras no haya usuarios.
- **Contraseña.** No hay recuperación. Si se olvida, hay que reinstalar la app y se pierden los datos.
- **Adjuntos.** Descargar se convierte en compartir, porque la WebView no gestiona descargas.

| Acción | Web | iOS | Android |
| --- | --- | --- | --- |
| Adjuntar PDF, JPG o PNG desde el formulario | Sí | Sí | No: la WebView no abre el selector de archivos |
| Adjuntar foto de la cámara o la galería | No | Sí | Sí |
| Sacar el adjunto | Descarga | Menú de compartir | Menú de compartir |

## Requisitos

NativePHP no funciona en WSL y se niega a arrancar allí, así que hay que compilar desde **Windows nativo**, con una copia del proyecto fuera de WSL.

- PHP 8.4 o superior con las extensiones `gd`, `zip`, `sqlite3` y `pdo_sqlite`, Composer y Node.
- Android: Android Studio con el SDK, un JDK y 7-Zip. Conviene excluir `C:\temp` y la carpeta del proyecto en Windows Defender.
- iOS: un Mac con Xcode.
- Un móvil real con depuración USB o un emulador ARM64: el proyecto Android solo compila para `arm64-v8a`.

## Probar en Android

```shell
composer install
copy .env.example .env
php artisan key:generate
npm install
npm run build -- --mode=android
php artisan native:install android
php artisan native:run android --build=debug
```

`native:install` pregunta por los binarios con ICU. La app no los necesita, así que se puede responder que no.

En iOS, lo mismo con `npm run build -- --mode=ios` y `php artisan native:run ios`.

Tras compilar para móvil, vuelve a ejecutar `npm run build` antes de usar la versión web, porque las rutas de los assets son distintas.

## Publicar

Antes de una versión de tienda, en `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
NATIVEPHP_APP_VERSION=1.0.0
NATIVEPHP_APP_VERSION_CODE=1
```

- El código de versión debe aumentar en cada build que instales. Con la misma versión y el mismo código, el dispositivo sigue ejecutando el código anterior.
- `NATIVEPHP_APP_ID` identifica la app en las tiendas y no se puede cambiar después de publicar.

## Seguridad

- **Todo lo que va en la app es público:** código, vistas, configuración y `.env`. Ningún secreto debe quedar dentro.
- `cleanup_env_keys` en `config/nativephp.php` quita del `.env` del bundle las claves de base de datos, correo, Redis y PDF. Añade ahí cualquier clave nueva.
- **El PDF no se puede llamar desde el dispositivo**, porque exigiría incluir la clave. Cuando se implemente, la app tendrá que pedirlo a un backend propio.
- `BlockNativeBridgeOffDevice` responde 404 a `_native/*` fuera del dispositivo, porque NativePHP registra esas rutas sin autenticación ni CSRF.

## Permisos

El plugin de cámara declara también micrófono y ubicación, aunque la app no los usa. Revísalo antes de publicar: las tiendas pueden pedir que lo justifiques.

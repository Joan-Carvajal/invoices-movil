import { Camera, Events, On } from '../../vendor/nativephp/mobile/resources/dist/native.js';

// Diálogos de confirmación: window.confirm() no funciona en la WebView de iOS.
document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-open-dialog]');

    if (opener) {
        document.getElementById(opener.dataset.openDialog)?.showModal();
    }
});

// Evita enviar dos veces un formulario en conexiones o dispositivos lentos.
document.addEventListener('submit', (event) => {
    if (event.target.method === 'dialog') {
        return;
    }

    const buttons = event.target.querySelectorAll('button[type="submit"]');
    setTimeout(() => buttons.forEach((button) => (button.disabled = true)));
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('button[type="submit"]:disabled').forEach((button) => (button.disabled = false));
});

// Cámara y galería en la app móvil.
const photoForm = document.querySelector('[data-device-photo]');

if (photoForm) {
    const errorMessage = document.querySelector('[data-capture-error]');

    const showError = (message) => {
        errorMessage.textContent = message;
        errorMessage.hidden = false;
    };

    const submitPhoto = (path) => {
        photoForm.elements.path.value = path;
        photoForm.requestSubmit();
    };

    On(Events.Camera.PhotoTaken, ({ path }) => submitPhoto(path));
    On(Events.Gallery.MediaSelected, ({ files }) => {
        if (files?.length) {
            submitPhoto(files[0].path);
        }
    });
    On(Events.Camera.PermissionDenied, () => showError('Activa el permiso de cámara en los ajustes del dispositivo.'));

    document.querySelectorAll('[data-capture]').forEach((button) => {
        button.addEventListener('click', async () => {
            errorMessage.hidden = true;

            try {
                await (button.dataset.capture === 'camera' ? Camera.getPhoto() : Camera.pickImages().images());
            } catch {
                showError('No se ha podido abrir. Inténtalo de nuevo.');
            }
        });
    });
}

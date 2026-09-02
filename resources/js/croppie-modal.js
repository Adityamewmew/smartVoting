import Croppie from 'croppie';
import 'croppie/croppie.css';

const MAX_FILE_BYTES = 5 * 1024 * 1024;
let activeCroppie = null;

function blobToObjectUrl(blob) {
    return URL.createObjectURL(blob);
}

function ensureCropModal() {
    // Remove any leftover old modal elements
    const oldModals = document.querySelectorAll('#app-crop-modal');
    oldModals.forEach((el) => el.remove());

    let modal = document.getElementById('tui-crop-modal');

    if (modal) {
        return modal;
    }

    modal = document.createElement('div');
    modal.id = 'tui-crop-modal';
    modal.className = 'tui-crop-modal hidden';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.innerHTML = `
        <div class="tui-crop-modal__backdrop" data-tui-crop-dismiss></div>
        <div class="tui-crop-modal__panel">
            <div class="tui-crop-modal__header">
                <div>
                    <p class="tui-crop-modal__title" data-tui-crop-title>Sesuaikan Gambar</p>
                    <p class="tui-crop-modal__subtitle" data-tui-crop-subtitle>
                        Geser & zoom untuk memotong area penting.
                    </p>
                </div>
            </div>
            <div class="tui-crop-modal__body">
                <div id="tui-crop-container" class="tui-crop-modal__container"></div>
            </div>
            <div class="tui-crop-modal__footer">
                <button type="button" class="tui-crop-modal__btn tui-crop-modal__btn--ghost" data-tui-crop-cancel>
                    Batal
                </button>
                <button type="button" class="tui-crop-modal__btn tui-crop-modal__btn--primary" data-tui-crop-confirm>
                    Gunakan Foto
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    return modal;
}

/**
 * Open Croppie on a blob/file and resolve with cropped JPEG blob.
 *
 * @param {Blob|File} blob
 * @param {{ title?: string, confirmLabel?: string, subtitle?: string, outputWidth?: number, outputHeight?: number, viewportWidth?: number, viewportHeight?: number, boundaryWidth?: number, boundaryHeight?: number }} [options]
 * @returns {Promise<Blob>}
 */
export function cropImageWithCroppie(blob, options = {}) {
    const title = options.title || 'Sesuaikan Gambar';
    const confirmLabel = options.confirmLabel || 'Gunakan Foto';
    const subtitle = options.subtitle || 'Geser & zoom untuk memotong area penting.';
    const outputWidth = options.outputWidth || 1400;
    const outputHeight = options.outputHeight || 933;
    const viewportWidth = options.viewportWidth || 360;
    const viewportHeight = options.viewportHeight || 240;
    const boundaryWidth = options.boundaryWidth || 420;
    const boundaryHeight = options.boundaryHeight || 300;

    return new Promise((resolve, reject) => {
        const modal = ensureCropModal();
        const container = modal.querySelector('#tui-crop-container');
        const titleEl = modal.querySelector('[data-tui-crop-title]');
        const cancelBtn = modal.querySelector('[data-tui-crop-cancel]');
        const confirmBtn = modal.querySelector('[data-tui-crop-confirm]');
        const subtitleEl = modal.querySelector('[data-tui-crop-subtitle]');
        const dismissEls = modal.querySelectorAll('[data-tui-crop-dismiss]');

        // Clean up previous instance if any
        if (activeCroppie) {
            try {
                activeCroppie.destroy();
            } catch (e) {
                // ignore
            }
            activeCroppie = null;
        }

        let settled = false;
        const objectUrl = blobToObjectUrl(blob);

        if (titleEl) {
            titleEl.textContent = title;
        }

        if (subtitleEl) {
            subtitleEl.textContent = subtitle;
        }

        confirmBtn.textContent = confirmLabel;

        const cleanup = () => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if (activeCroppie) {
                try {
                    activeCroppie.destroy();
                } catch (e) {
                    // ignore
                }
                activeCroppie = null;
            }
            container.innerHTML = '';
            URL.revokeObjectURL(objectUrl);
            cancelBtn.onclick = null;
            confirmBtn.onclick = null;
            dismissEls.forEach((el) => {
                el.onclick = null;
            });
        };

        const fail = (err) => {
            if (settled) {
                return;
            }
            settled = true;
            cleanup();
            reject(err || new Error('cancelled'));
        };

        const succeed = (resultBlob) => {
            if (settled) {
                return;
            }
            settled = true;
            cleanup();
            resolve(resultBlob);
        };

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        container.innerHTML = '';

        // Create fresh mount element to prevent Croppie multiple init on same element
        const mountEl = document.createElement('div');
        mountEl.className = 'w-full flex flex-col items-center justify-center';
        container.appendChild(mountEl);

        activeCroppie = new Croppie(mountEl, {
            viewport: { width: viewportWidth, height: viewportHeight, type: 'square' },
            boundary: { width: boundaryWidth, height: boundaryHeight },
            showZoomer: true,
            enableOrientation: true,
            enableZoom: true,
            mouseWheelZoom: true,
        });

        activeCroppie
            .bind({ url: objectUrl })
            .then(() => {
                const minZoom = parseFloat(activeCroppie?.elements?.zoomer?.min ?? 0);
                if (!Number.isNaN(minZoom) && activeCroppie) {
                    activeCroppie.setZoom(minZoom);
                }
            })
            .catch(() => fail(new Error('Gagal memuat gambar untuk crop.')));

        cancelBtn.onclick = () => fail(new Error('cancelled'));
        dismissEls.forEach((el) => {
            el.onclick = () => fail(new Error('cancelled'));
        });

        confirmBtn.onclick = async () => {
            confirmBtn.disabled = true;
            const previousLabel = confirmBtn.textContent;
            confirmBtn.textContent = 'Memproses...';

            try {
                if (!activeCroppie) {
                    throw new Error('Croppie tidak aktif');
                }

                const resultBlob = await activeCroppie.result({
                    type: 'blob',
                    format: 'jpeg',
                    quality: 0.9,
                    size: { width: outputWidth, height: outputHeight },
                });

                if (resultBlob.size > MAX_FILE_BYTES) {
                    window.alert('Hasil crop melebihi 5 MB. Perkecil area crop atau zoom out.');

                    return;
                }

                succeed(resultBlob);
            } catch {
                window.alert('Gagal memproses crop. Silakan coba lagi.');
            } finally {
                confirmBtn.disabled = false;
                confirmBtn.textContent = previousLabel || confirmLabel;
            }
        };
    });
}

window.cropImageWithCroppie = cropImageWithCroppie;
export { MAX_FILE_BYTES };

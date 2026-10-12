

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { Html5Qrcode } from 'html5-qrcode';
import QRCode from 'qrcode';

window.Alpine = Alpine;
window.Swal = Swal;
window.Html5Qrcode = Html5Qrcode;
window.QRCodeGenerator = QRCode;

// Warna tombol konfirmasi mengikuti peran aktif (variabel --c-primary pada <html>).
const brandColor = () => {
    const rgb = getComputedStyle(document.documentElement).getPropertyValue('--c-primary').trim();
    return `rgb(${rgb.split(/\s+/).join(', ')})`;
};

window.confirmAction = function (event, message, formElement = null) {
    event.preventDefault();
    Swal.fire({
        title: 'Konfirmasi',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: brandColor(),
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            container: 'font-sans'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            if (formElement) {
                formElement.submit();
            } else if (event.target.closest('form')) {
                event.target.closest('form').submit();
            } else if (event.target.closest('a')) {
                window.location.href = event.target.closest('a').href;
            }
        }
    });
};

Alpine.start();

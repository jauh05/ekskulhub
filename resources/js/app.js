

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { Html5Qrcode } from 'html5-qrcode';
import QRCode from 'qrcode';

window.Alpine = Alpine;
window.Swal = Swal;
window.Html5Qrcode = Html5Qrcode;
window.QRCodeGenerator = QRCode;

Alpine.start();

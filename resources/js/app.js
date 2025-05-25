import './bootstrap';
import { Html5QrcodeScanner } from "html5-qrcode";

if (document.getElementById("qr-reader")) {
    const scanner = new Html5QrcodeScanner("qr-reader", {
        fps: 10,
        qrbox: 250
    });

    scanner.render(
        (decodedText, decodedResult) => {
            const rutInput = document.getElementById("rut-input");
            if (rutInput) {
                rutInput.value = decodedText;
                document.getElementById("asistencia-form").submit();
            }
        },
        (error) => {
            // Puedes ignorar los errores de escaneo frecuentes
        }
    );
}

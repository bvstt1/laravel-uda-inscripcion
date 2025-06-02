<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ConfirmacionEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $evento;
    public $fechaHora;
    public $lugar;
    public $qrPath;

    public function __construct($nombre, $evento, $fechaHora, $lugar, $rut, $eventoId)
    {
        $this->nombre = $nombre;
        $this->evento = $evento;
        $this->fechaHora = $fechaHora;
        $this->lugar = $lugar;

        // Ruta del archivo QR
        $fileName = 'qr_' . $rut . '_' . $eventoId . '.svg';
        $relativePath = 'public/qr/' . $fileName;

        // Generar QR y guardarlo
        QrCode::format('svg')
            ->size(200)
            ->generate($rut, storage_path('app/' . $relativePath));

        // Ruta pública para mostrar en el correo
        $this->qrPath = asset('storage/qr/' . $fileName);

    }

    public function build()
    {
        return $this->subject('Confirmación de inscripción al evento')
                    ->view('emails.confirmacionEvento');
    }
}

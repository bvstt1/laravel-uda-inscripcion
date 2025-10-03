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
    
        // Nombre archivo QR en SVG
        $fileName = 'qr_' . $rut . '_' . $eventoId . '.svg';
        $this->qrPath = storage_path('app/public/qr/' . $fileName);
    
        // Generar QR en SVG y guardarlo
        QrCode::format('svg')
            ->size(200)
            ->generate($rut, $this->qrPath);
    }
    
    public function build()
    {
        return $this->subject('Confirmación de inscripción al evento')
            ->view('emails.confirmacionEvento')
            ->with([
                'qrPath' => $this->qrPath
            ]);
    }
    
}

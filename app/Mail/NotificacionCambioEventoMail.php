<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionCambioEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $evento;
    public $cambios;

    public function __construct($nombre, $evento, $cambios)
    {
        $this->nombre = $nombre;
        $this->evento = $evento;
        $this->cambios = $cambios;
    }

    public function build()
    {
        return $this->subject('Actualización en el evento al que estás inscrito')
                    ->view('emails.cambioEvento');
    }
}

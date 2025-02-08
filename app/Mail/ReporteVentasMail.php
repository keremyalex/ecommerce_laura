<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ReporteVentasMail extends Mailable
{
    public $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function build()
    {
        return $this->subject('Reporte de Ventas')
                    ->view('emails.reporte_ventas')
                    ->with(['mensaje' => $this->mensaje]);
    }
}

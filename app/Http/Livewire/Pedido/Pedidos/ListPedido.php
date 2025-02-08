<?php

namespace App\Http\Livewire\Pedido\Pedidos;
use Illuminate\Support\Facades\Mail;
use App\Models\Pagina;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;
use App\Mail\ReporteVentasMail;


class ListPedido extends Component
{
    use WithPagination;

    public $search = '';
    public $notificacion = false;
    public $type = 'success';
    public $message = 'Creado correctamente';
    public $layout;
    public $modalOpen = false;
    public $email;
    public $subject;
    public $fechaInicial;
    public $fechaFinal;
    public $totalAmount;

    public function mount()
    {
        Pagina::UpdateVisita('pedido.list');
        $this->layout = auth()->user()->tema;
    }

    public function toggleNotificacion()
    {
        $this->notificacion = !$this->notificacion;
        $this->emit('notificacion');
    }

    public function updatingAttribute()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $pedido = Pedido::find($id);

        if ($pedido) {
            PedidoDetalle::where('pedido_id', $pedido->id)->delete();
            $pedido->delete();

            $this->message = 'Eliminado correctamente';
            $this->type = 'success';
        } else {
            $this->message = 'Error al eliminar';
            $this->type = 'error';
        }

        $this->notificacion = true;
    }

    public function render()
    {
        $pedidos = Pedido::GetPedidos($this->search, 'ASC', 20);
        $visitas = Pagina::GetPagina('pedido.list') ?? 0;
        return view('livewire.pedido.pedidos.list-pedido', compact('pedidos', 'visitas'))->layout($this->layout);
    }

    public function generateReport()
    {
        $this->totalAmount = Pedido::whereBetween('fecha', [$this->fechaInicial, $this->fechaFinal])->sum('monto_total');
        $this->modalOpen = true;
    }
    

  /*  public function sendMail()
{
    $mensaje = "Estimado/a, <br><br>";
    $mensaje .= "El monto total de los pedidos entre las fechas {$this->fechaInicial} y {$this->fechaFinal} es: <strong>{$this->totalAmount} Bs.</strong><br><br>";
    $mensaje .= "Gracias por su atención.<br><br>";
    $mensaje .= "Saludos, <br> El equipo de grupo00sa.";
  //  dd($this->email);
    try {
     Mail::to($this->email)->send(new ReporteVentasMail($mensaje));
dd("prueba");
        $this->message = 'El correo ha sido enviado correctamente.';
        $this->type = 'success';
    } catch (\Exception $e) {
        $this->message = 'Error al enviar el correo: ' . $e->getMessage();
        $this->type = 'error';
    }

    $this->notificacion = true;
    $this->modalOpen = false;
}*/
    public function sendMail()
    {
        $mensaje = "Estimado/a, <br><br>";
        $mensaje .= "El monto total de los pedidos entre las fechas {$this->fechaInicial} y {$this->fechaFinal} es: <strong>{$this->totalAmount} Bs.</strong><br><br>";
        $mensaje .= "Gracias por su atención.<br><br>";
        $mensaje .= "Saludos, <br> El equipo de grupo00sa.";

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'AKIA4MTWJNPEDSAURHNF'; // Tu nombre de usuario SMTP
            $mail->Password = 'BN2OU0gfXTdUBfpLrr01Cjok2PoKTVRCKcdXfVxDvV2A'; // Tu contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Datos del remitente
            $mail->setFrom('henry@sagolab.online', 'grupo00sa');

            // Destinatarios
            $mail->addAddress($this->email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body = $mensaje;
            $mail->AltBody = strip_tags($mensaje);

            // Enviar el correo
            $mail->send();

            $this->message = 'El correo ha sido enviado correctamente.';
            $this->type = 'success';
        } catch (MailerException $e) {
            $this->message = 'Error al enviar el correo: ' . $e->getMessage();
            $this->type = 'error';
        }

        $this->notificacion = true;
        $this->modalOpen = false;
    }
   
}

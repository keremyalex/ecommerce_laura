<?php

namespace App\Http\Livewire\Producto;

use App\Models\Pagina;
use Livewire\Component;
use App\Models\Producto;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage; // Importar la clase Storage

class NewProducto extends Component
{
    use WithFileUploads; // Activar la carga de archivos

    public $productoArray = [];
    public $imagen; // Propiedad para almacenar la imagen cargada
    public $type = 'success';
    public $message = 'Creado correctamente';
    public $listeners = ['store' => 'save'];
    public $layout;
    public $notificacion = false;

    public function mount()
    {
        Pagina::UpdateVisita('producto.new');
    }

    public function save()
    {
        // Verificar si se cargó una imagen y almacenarla
        if ($this->imagen) {
            // Almacena la imagen en la carpeta 'productos' y en el disco 'public'
            $imagePath = $this->imagen->store('public/imagenes', 'public');
              $imageUrl = url($imagePath);
//dd($imagePath);
            // Generar la URL completa de la imagen
           // $imageUrl = Storage::url($imagePath);

            // Agregar la URL de la imagen al array de producto
            $this->productoArray['imagen'] = $imageUrl;
        }

        // Crear el nuevo producto
        $new = Producto::CreateProducto($this->productoArray);
//dd($new);
        // Si ocurre un error al crear el producto
        if (!$new) {
            $this->message = 'Error al crear el producto';
            $this->type = 'error';
            $this->notificacion = true;
        }

        // Redirigir después de guardar
        return redirect()->route('producto.list');
    }

    public function render()
    {
        $visitas = Pagina::GetPagina('producto.new') ?? 0;
        return view('livewire.producto.new-producto', compact('visitas'))->layout(auth()->user()->tema);
    }
}

<div class="mt-5">
    <nav class="flex py-3 mb-5">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/dashboard"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-500 dark:hover:text-gray-600">
                    <x-iconos.home />
                    Home
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <x-iconos.flecha />
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-500">Ventas</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="p-4 bg-white flex flex-center justify-between dark:bg-gray-900">
            <div class="flex space-x-4">
                <!-- Fecha Inicial -->
                <div>
                    <label for="fecha-inicial" class="block text-sm font-medium text-gray-700">Fecha Inicial</label>
                    <input type="date" id="fecha-inicial" wire:model="fechaInicial"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Fecha Final -->
                <div>
                    <label for="fecha-final" class="block text-sm font-medium text-gray-700">Fecha Final</label>
                    <input type="date" id="fecha-final" wire:model="fechaFinal"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <button type="button" wire:click="generateReport"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <x-iconos.book />
                Generar Reporte
            </button>
        </div>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Cliente</th>
                    <th scope="col" class="px-6 py-3">Fecha</th>
                    <th scope="col" class="px-6 py-3">Hora</th>
                    <th scope="col" class="px-6 py-3">Monto</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $pedido->usuario }}</td>
                        <td class="px-6 py-4">{{ $pedido->fecha }}</td>
                        <td class="px-6 py-4">{{ $pedido->hora }}</td>
                        <td class="px-6 py-4">{{ $pedido->monto_total }} Bs.</td>
                        <td class="px-6 py-4">
                            @if ($pedido->estado == 'pendiente')
                                <span
                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200 last:mr-0 mr-1">
                                    {{ $pedido->estado }}
                                </span>
                            @elseif($pedido->estado == 'cancelado')
                                <span
                                    class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-red-600 bg-red-200 uppercase last:mr-0 mr-1">
                                    {{ $pedido->estado }}
                                </span>
                            @else
                                <span
                                    class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-green-600 bg-green-200 uppercase last:mr-0 mr-1">
                                    {{ $pedido->estado }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('pedido.show', $pedido->id) }}"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <x-iconos.view />
                            </a>
                            <button type="button" wire:click="delete({{ $pedido->id }})"
                                onclick="confirm('¿Está seguro?') || event.stopImmediatePropagation()"
                                class="text-white bg-red-700 hover:bg-red-800 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                <x-iconos.delete />
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-shared.pagination :modelo='$pedidos' />
    </div>

    <!-- Modal -->
    <x-shared.modal id="modalReporte" wire:model="modalOpen">
        <x-slot name="title">Enviar Reporte</x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="sendEmail">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Destinatario</label>
                    <input type="email" id="email" wire:model.defer="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Asunto</label>
                    <input type="text" id="subject" wire:model.defer="subject"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Monto Total</label>
                    <p>{{ $totalAmount }} Bs.</p>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
            <button type="button" wire:click="sendMail"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Enviar
            </button>
        </x-slot>
    </x-shared.modal>

</div>

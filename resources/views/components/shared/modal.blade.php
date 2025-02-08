@props(['id' => null, 'title' => '', 'content' => '', 'footer' => ''])

<div x-data="{ isOpen: @entangle($attributes->wire('model')).defer }" x-show="isOpen"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="min-h-screen px-4 text-center">


        <span class="inline-block h-screen align-middle">&#8203;</span>

        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
               <!-- Modal Header -->
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ $title }}
                </h3>
                <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a1 1 0 01-1-1V4a1 1 0 112 0v4a1 1 0 01-1 1zm0 3a1 1 0 011-1h4a1 1 0 110 2h-4a1 1 0 01-1-1zm0 0a1 1 0 00-1 1v4a1 1 0 002 0v-4a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                {{ $content }}
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 flex justify-end space-x-4">
                {{ $footer }}
            </div>
        </div>

</div>

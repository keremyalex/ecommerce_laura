<?php

return [
    'paths' => ['*'],  // Permite cualquier ruta
    'allowed_methods' => ['*'],  // Permite todos los métodos (GET, POST, PUT, DELETE, etc.)
    'allowed_origins' => ['*'],  // Permite todos los orígenes
    'allowed_headers' => ['*'],  // Permite todos los encabezados
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,  // Permite cookies si es necesario
];



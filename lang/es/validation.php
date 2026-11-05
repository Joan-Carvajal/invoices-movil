<?php

declare(strict_types=1);

return [
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => ':Attribute no es una fecha válida.',
    'email' => ':Attribute debe ser una dirección de correo válida.',
    'exists' => ':Attribute no es válido.',
    'file' => ':Attribute debe ser un archivo.',
    'in' => ':Attribute no es válido.',
    'integer' => ':Attribute debe ser un número entero.',
    'max' => [
        'file' => ':Attribute no puede superar los :max KB.',
        'numeric' => ':Attribute no puede ser mayor que :max.',
        'string' => ':Attribute no puede tener más de :max caracteres.',
    ],
    'mimes' => ':Attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => ':Attribute debe ser al menos :min.',
        'string' => ':Attribute debe tener al menos :min caracteres.',
    ],
    'numeric' => ':Attribute debe ser un número.',
    'required' => 'El campo :attribute es obligatorio.',
    'string' => ':Attribute debe ser un texto.',
    'unique' => 'Ya existe una factura con este :attribute.',
    'uploaded' => 'No se ha podido subir :attribute.',

    'attributes' => [
        'amount' => 'importe',
        'attachment' => 'adjunto',
        'client_id' => 'cliente',
        'email' => 'email',
        'issued_at' => 'fecha de emisión',
        'name' => 'nombre',
        'notes' => 'notas',
        'number' => 'número',
        'password' => 'contraseña',
        'status' => 'estado',
        'tax_id' => 'NIF',
    ],
];

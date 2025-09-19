<?php

return [
    'custom' => [
        'name' => [
            'regex' => 'Le nom contient des caractères non autorisés.',
            'max' => 'Le nom ne doit pas dépasser :max caractères.',
        ],
        'user_other' => [
            'regex' => 'Le champ "Précisez" contient des caractères non autorisés.',
            'max' => 'Le champ "Précisez" ne doit pas dépasser :max caractères.',
        ],
        'phone' => [
            'regex' => 'Le téléphone ne doit contenir que chiffres, espaces, +, -, ( ).',
            'max' => 'Le téléphone ne doit pas dépasser :max caractères.',
        ],
        'user_type' => [
            'in' => 'Veuillez sélectionner une option valide.',
        ],
        'message' => [
            'max' => 'Le message ne doit pas dépasser :max caractères.',
            'required' => 'Le message est obligatoire.',
        ],
        'email' => [
            'email' => 'Veuillez renseigner une adresse email valide.',
            'required' => "L'email est obligatoire.",
        ],
    ],
];

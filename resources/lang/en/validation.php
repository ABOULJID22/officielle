<?php

return [
    'custom' => [
        'name' => [
            'regex' => 'The name contains invalid characters.',
            'max' => 'The name may not be greater than :max characters.',
        ],
        'user_other' => [
            'regex' => 'The "Specify" field contains invalid characters.',
            'max' => 'The "Specify" field may not be greater than :max characters.',
        ],
        'phone' => [
            'regex' => 'The phone may contain only digits, spaces, +, -, ( ).',
            'max' => 'The phone may not be greater than :max characters.',
        ],
        'user_type' => [
            'in' => 'Please select a valid option.',
        ],
        'message' => [
            'max' => 'The message may not be greater than :max characters.',
            'required' => 'The message field is required.',
        ],
        'email' => [
            'email' => 'Please provide a valid email address.',
            'required' => 'The email field is required.',
        ],
    ],
];

<?php

return [

    'label' => 'Navigation de pagination',

    'overview' => '{1} Affichage de 1 résultat|[2,*] Affichage de :first à :last sur :total résultats',

    'fields' => [

        'records_per_page' => [

            'label' => 'Par page',

            'options' => [
                'all' => 'Tous',
            ],

        ],

    ],

    'actions' => [

        'first' => [
            'label' => 'Premier',
        ],

        'go_to_page' => [
            'label' => 'Aller à la page :page',
        ],

        'last' => [
            'label' => 'Dernier',
        ],

        'next' => [
            'label' => 'Suivant',
        ],

        'previous' => [
            'label' => 'Précédent',
        ],

    ],

];

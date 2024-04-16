<?php

return [

    'label' => 'Πλοήγηση σελίδων',

    'overview' => '{1} Εμφάνιση 1 αποτελέσματος|[2,*] Εμφάνιση :first έως :last από :total αποτελέσματα',

    'fields' => [

        'records_per_page' => [

            'label' => 'Ανά σελίδα',

            'options' => [
                'all' => 'Όλα',
            ],

        ],

    ],

    'actions' => [

        'first' => [
            'label' => 'Πρώτη',
        ],

        'go_to_page' => [
            'label' => 'Μετάβαση στη σελίδα :page',
        ],

        'last' => [
            'label' => 'Τελευταία',
        ],

        'next' => [
            'label' => 'Επόμενη',
        ],

        'previous' => [
            'label' => 'Προηγούμενη',
        ],

    ],

];

<?php

/**
 * Components configuration file.
 * (This file is part of verbena template system).
 */
$components = [

    /**
     * Namespace aliases.
     */
    'aliases'       => [

        // You must declare Environment component if you
        // want call it with aliasing inside a view.
        'Environment'   => 'Bootstrap\\Environment\\Environment',
        
        // Other base components.
        'Language'      => 'Bootstrap\\Components\\Language',
        'Session'       => 'Bootstrap\\Components\\Session',
        'Cache'         => 'Bootstrap\\Components\\Cache',
        'Security'      => 'Bootstrap\\Components\\Security',
        'HTTP'          => 'Bootstrap\\Components\\HTTP',
        'Mail'          => 'Bootstrap\\Components\\Mail',
        'Encrypt'       => 'Bootstrap\\Components\\Encrypt',
        'HTML'          => 'Bootstrap\\Components\\HTML'
    ]

];
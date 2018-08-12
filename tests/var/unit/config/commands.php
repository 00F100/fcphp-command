<?php

return [
    'package' => [
        [
            'command' => 'example',
            'action' => 'Controller@method',
            'rule' => 'example-permission',
        ],
        [
            'command' => 'exampleNonPermission',
            'action' => 'Controller@method',
            'rule' => 'example-permission-no-permission',
        ],
        [
            'command' => 'help',
            'action' => 'Controller@help',
            'rule' => null,
        ],
        [
            'command' => 'datasource',
            'action' => ':datasource',
            'rule' => 'connect-datasource',
        ],
    ],
    'datasource' => [
        [
            'command' => 'connect',
            'action' => 'ControllerDatasource@method',
            'rule' => 'connect-rule',
        ],
    ]
];

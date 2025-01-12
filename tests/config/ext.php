<?php

return [
    'app' => [
        'dialogs' => [
            'default' => [
                'confirm' => TestDialogLibrary::NAME,
            ],
            'lib' => [
                'ext' => [
                    TestDialogLibrary::NAME => TestDialogLibrary::class,
                ],
            ],
        ],
    ],
    'lib' => [
    ],
];

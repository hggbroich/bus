<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/js/app.js',
        'entrypoint' => true,
    ],
    'collection' => [
        'path' => './assets/js/collection.js',
        'entrypoint' => true,
    ],
    'maps-button' => [
        'path' => './assets/js/maps-button.js',
        'entrypoint' => true,
    ],
    'choice' => [
        'path' => './assets/js/choice.js',
        'entrypoint' => true,
    ],
    'bootstrap' => [
        'version' => '5.3.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.8',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free/css/all.css' => [
        'version' => '7.2.0',
        'type' => 'css',
    ],
    'axios' => [
        'version' => '1.15.0',
    ],
    'choices.js' => [
        'version' => '11.2.1',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'tom-select' => [
        'version' => '2.6.0',
    ],
    '@orchidjs/sifter' => [
        'version' => '1.1.0',
    ],
    '@orchidjs/unicode-variants' => [
        'version' => '1.1.2',
    ],
    'tom-select/dist/css/tom-select.default.min.css' => [
        'version' => '2.6.0',
        'type' => 'css',
    ],
    'tom-select/dist/css/tom-select.bootstrap5.min.css' => [
        'version' => '2.6.0',
        'type' => 'css',
    ],
];

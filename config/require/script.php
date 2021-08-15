<?php

return [
    'lib' => [
        "jquery-1.11.0.min",
        "/jquery-ui-1.12.1/jquery-ui.min",
        "bootstrap.min",
        "validator",
        "typeahead.bundle",
        "imagezoom",
        "/megamenu/js/megamenu",
        "jquery.easydropdown",
        "accordion",
        "responsiveslides.min",
        "slider",
        "jquery.flexslider",
        "flexslider", // defer
        "/adminlte/bower_components/ckeditor/ckeditor",
        "/adminlte/bower_components/ckeditor/adapters/jquery",
    ],
    'init' => [
        "_base_functions",
        "@".CONF."/require/consts.php",
        "_functions",
        "_variables",
    ],
    'main' => [
        "editor",
        "main",
    ]
];

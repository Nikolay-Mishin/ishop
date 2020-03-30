<?php

return [
    'lib' => [
        "js/jquery-1.11.0.min",
        "/jquery-ui-1.12.1/jquery-ui.min",
        "js/bootstrap.min",
        "js/validator",
        "js/typeahead.bundle",
        "js/imagezoom",
        "megamenu/js/megamenu",
        "js/jquery.easydropdown",
        "js/accordion",
        "js/responsiveslides.min",
        "js/slider",
        "js/jquery.flexslider",
        "js/flexslider", // defer
        "adminlte/bower_components/ckeditor/ckeditor",
        "adminlte/bower_components/ckeditor/adapters/jquery",
    ],
    'init' => [
        "js/_base_functions",
        "@ " . CONF . "/require/scripts_consts.php",
        "js/_functions",
        "js/_variables",
    ],
    'main' => [
        "js/editor",
        "js/main",
    ]
];

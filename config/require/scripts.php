<?php
// конфигурации БД - ORM RedBeanPHP

$base = "js";

return [
    'lib' => [
        "$base/jquery-1.11.0.min",
        "/jquery-ui-1.12.1/jquery-ui.min",
        "$base/bootstrap.min",
        "$base/validator",
        "$base/typeahead.bundle",
        "$base/imagezoom",
        "megamenu/js/megamenu",
        "$base/jquery.easydropdown",
        "$base/accordion",
        "$base/responsiveslides.min",
        "$base/slider",
        "$base/jquery.flexslider",
        "$base/flexslider", // defer
        "adminlte/bower_components/ckeditor/ckeditor",
        "adminlte/bower_components/ckeditor/adapters/jquery"
    ],
    'init' => [
        "@ " . CONF . "/require/scripts_consts.php",
        "$base/_base_functions",
        "$base/_functions",
        "$base/_variables",
    ],
    'main' => [
        "$base/editor",
        "$base/main"
    ]
];
